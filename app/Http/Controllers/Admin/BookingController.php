<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\BookingConfirmed;
use App\Models\ActivityLog;
use App\Models\Booking;
use App\Models\Doctor;
use App\Models\DoctorStatus;
use App\Models\Schedule;
use App\Notifications\BookingStatusNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with('doctor', 'schedule');

        if ($request->filled('date')) {
            $query->whereDate('exam_date', $request->date);
        }

        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->latest()->paginate(15);
        $doctors = Doctor::where('is_active', true)->get();
        $busyDoctorIds = Booking::where('status', 'EXAMINING')->pluck('doctor_id')->unique()->toArray();

        return view('admin.bookings.index', compact('bookings', 'doctors', 'busyDoctorIds'));
    }

    /**
     * Store a walk-in booking registered by admin/receptionist.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_name' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'digits:16'],
            'phone' => ['required', 'string', 'regex:/^\+?[0-9]{5,16}$/'],
            'birth_date' => ['required', 'date'],
            'gender' => ['required', 'in:L,P'],
            'doctor_id' => ['required', 'exists:doctors,id'],
            'schedule_id' => ['required', 'exists:schedules,id'],
            'exam_date' => ['required', 'date', 'after_or_equal:today'],
            'address' => ['required', 'string'],
            'province' => ['required', 'string'],
            'district' => ['required', 'string'],
            'sub_district' => ['required', 'string'],
            'village' => ['required', 'string'],
            'complaint' => ['nullable', 'string', 'max:1000'],
        ]);

        return DB::transaction(function () use ($validated) {
            // Check duplicate: same NIK + doctor + exam_date with active status
            $duplicate = Booking::where('nik', $validated['nik'])
                ->where('doctor_id', $validated['doctor_id'])
                ->where('exam_date', $validated['exam_date'])
                ->whereIn('status', ['PENDING', 'CONFIRMED'])
                ->lockForUpdate()
                ->exists();

            if ($duplicate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pasien sudah memiliki booking aktif dengan dokter ini pada tanggal tersebut.',
                ], 422);
            }

            // Check capacity
            $schedule = Schedule::findOrFail($validated['schedule_id']);
            $currentCount = Booking::where('schedule_id', $validated['schedule_id'])
                ->where('exam_date', $validated['exam_date'])
                ->whereIn('status', ['PENDING', 'CONFIRMED'])
                ->lockForUpdate()
                ->count();

            if ($currentCount >= $schedule->max_patients) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kuota jadwal ini sudah penuh untuk tanggal tersebut.',
                ], 422);
            }

            // Generate unique booking code
            do {
                $code = 'MK-'.strtoupper(Str::random(6));
            } while (Booking::where('booking_code', $code)->exists());

            // Calculate queue number
            $queueNumber = Booking::where('doctor_id', $validated['doctor_id'])
                ->where('exam_date', $validated['exam_date'])
                ->max('queue_number');
            $queueNumber = ($queueNumber ?? 0) + 1;

            $booking = Booking::create(array_merge($validated, [
                'booking_code' => $code,
                'queue_number' => $queueNumber,
                'status' => 'CONFIRMED',
                'booking_source' => 'WALK_IN',
                'user_id' => null,
            ]));

            $booking->load('doctor', 'schedule');

            // Log Activity
            ActivityLog::log('Pendaftaran Walk-in', "Mendaftarkan pasien {$booking->patient_name} ({$booking->booking_code}) secara langsung.");

            // Send confirmation email automatically
            try {
                Mail::to($booking->email ?? 'no-reply@myklinik.com')
                    ->send(new BookingConfirmed($booking));
            } catch (\Exception $e) {
                // Log error but continue
            }

            return response()->json([
                'success' => true,
                'booking' => $booking,
                'wa_link' => $booking->whatsapp_link,
            ]);
        });
    }

    public function confirm(int $id)
    {
        $booking = Booking::with('doctor', 'schedule')->findOrFail($id);
        $booking->update(['status' => 'CONFIRMED']);

        // Log Activity
        ActivityLog::log('Konfirmasi Booking', "Mengonfirmasi pesanan pasien {$booking->patient_name} ({$booking->booking_code}).");

        // Send confirmation email automatically
        $emailSent = false;
        try {
            Mail::to($booking->user->email ?? $booking->email)->send(new BookingConfirmed($booking));
            $emailSent = true;
        } catch (\Exception $e) {
            // Log error but continue
        }

        // Send in-app notification to patient
        if ($booking->user) {
            $booking->user->notify(new BookingStatusNotification($booking, 'confirmed'));
        }

        return response()->json([
            'success' => true,
            'wa_link' => $booking->whatsapp_link,
            'email_sent' => $emailSent,
        ]);
    }

    public function reject(Request $request, int $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:10',
        ]);

        $booking = Booking::with('user')->findOrFail($id);
        $booking->update([
            'status' => 'REJECTED',
            'rejection_reason' => $request->rejection_reason,
        ]);

        // Log Activity
        ActivityLog::log('Tolak Booking', "Menolak pesanan pasien {$booking->patient_name} ({$booking->booking_code}) dengan alasan: {$request->rejection_reason}.");

        // Send in-app notification to patient
        if ($booking->user) {
            $booking->user->notify(new BookingStatusNotification($booking, 'rejected'));
        }

        return response()->json(['success' => true]);
    }

    public function examine(int $id)
    {
        $booking = Booking::with('user', 'doctor.status')->findOrFail($id);

        // Check if doctor is already examining someone else
        $isBusy = Booking::where('doctor_id', $booking->doctor_id)
            ->where('status', 'EXAMINING')
            ->where('id', '!=', $id)
            ->exists();

        if ($isBusy) {
            return response()->json([
                'success' => false,
                'message' => 'Dokter ini sedang melayani pasien lain. Selesaikan pemeriksaan sebelumnya terlebih dahulu.',
            ], 422);
        }

        $booking->update(['status' => 'EXAMINING']);

        // Log Activity
        ActivityLog::log('Mulai Periksa', "Memulai pemeriksaan untuk pasien {$booking->patient_name} ({$booking->booking_code}) oleh dr. {$booking->doctor->name}.");

        // Automatically update doctor status
        DoctorStatus::updateOrCreate(
            ['doctor_id' => $booking->doctor_id],
            [
                'current_status' => 'IN_EXAMINATION',
                'current_queue_number' => $booking->queue_number,
                'updated_at' => now(),
            ]
        );

        // Send in-app notification to patient
        if ($booking->user) {
            $booking->user->notify(new BookingStatusNotification($booking, 'examining'));
        }

        return response()->json([
            'success' => true,
            'start_time' => $booking->updated_at->toIso8601String(),
        ]);
    }

    public function done(int $id)
    {
        $booking = Booking::with('user', 'doctor.status')->findOrFail($id);
        $booking->update(['status' => 'DONE']);

        // Log Activity
        ActivityLog::log('Selesai Periksa', "Menyelesaikan pemeriksaan untuk pasien {$booking->patient_name} ({$booking->booking_code}).");

        // Automatically update doctor status back to AVAILABLE
        // ONLY if no other patients are currently being examined by this doctor
        $otherExamining = Booking::where('doctor_id', $booking->doctor_id)
            ->where('status', 'EXAMINING')
            ->where('id', '!=', $id)
            ->exists();

        if ($booking->doctor->status && ! $otherExamining) {
            $booking->doctor->status->update([
                'current_status' => 'AVAILABLE',
                'current_queue_number' => null,
                'updated_at' => now(),
            ]);
        }

        // Send in-app notification to patient
        if ($booking->user) {
            $booking->user->notify(new BookingStatusNotification($booking, 'done'));
        }

        return response()->json(['success' => true]);
    }
}
