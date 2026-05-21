<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\BookingConfirmed;
use App\Models\Booking;
use App\Models\Doctor;
use App\Models\Schedule;
use Illuminate\Http\Request;
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

        return view('admin.bookings.index', compact('bookings', 'doctors'));
    }

    /**
     * Store a walk-in booking registered by admin/receptionist.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_name' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'digits:16'],
            'phone' => ['required', 'string', 'max:15'],
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

        // Check duplicate: same NIK + doctor + exam_date with active status
        $duplicate = Booking::where('nik', $validated['nik'])
            ->where('doctor_id', $validated['doctor_id'])
            ->where('exam_date', $validated['exam_date'])
            ->whereIn('status', ['PENDING', 'CONFIRMED'])
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

        // Send confirmation email automatically
        try {
            Mail::to($booking->phone && str_contains($booking->phone, '@') ? $booking->phone : $booking->email ?? 'no-reply@myklinik.com')
                ->send(new BookingConfirmed($booking));
        } catch (\Exception $e) {
            // Log error but continue
        }

        // Build WhatsApp link
        $msg = urlencode(
            "*KONFIRMASI PENDAFTARAN - MyKlinik911*\n\n"
            ."Halo *{$booking->patient_name}*, Anda telah terdaftar (Walk-in).\n\n"
            ."📌 *Detail Booking:*\n"
            ."- Kode: {$booking->booking_code}\n"
            ."- No. Antrean: *{$booking->queue_number}*\n\n"
            ."🩺 *Jadwal Periksa:*\n"
            ."- Dokter: {$booking->doctor->name}\n"
            ."- Tanggal: {$booking->exam_date->format('d/m/Y')}\n"
            ."- Jam: {$booking->schedule->time_range}\n\n"
            ."💡 *Penting:*\n"
            ."- Datanglah *15 menit* sebelum jadwal untuk verifikasi.\n"
            ."- Tunjukkan pesan ini ke petugas pendaftaran.\n\n"
            .'Terima kasih.'
        );

        $phone = ltrim($booking->phone, '0');
        $waLink = "https://wa.me/62{$phone}?text={$msg}";

        return response()->json([
            'success' => true,
            'booking' => $booking,
            'wa_link' => $waLink,
        ]);
    }

    public function confirm(int $id)
    {
        $booking = Booking::with('doctor', 'schedule')->findOrFail($id);
        $booking->update(['status' => 'CONFIRMED']);

        // Send confirmation email automatically
        try {
            Mail::to($booking->user->email ?? $booking->email)->send(new BookingConfirmed($booking));
        } catch (\Exception $e) {
            // Log error but continue
        }

        $msg = urlencode(
            "*KONFIRMASI PENDAFTARAN - MyKlinik911*\n\n"
            ."Halo *{$booking->patient_name}*, booking Anda telah *DIKONFIRMASI*.\n\n"
            ."📌 *Detail Booking:*\n"
            ."- Kode: {$booking->booking_code}\n"
            ."- No. Antrean: *{$booking->queue_number}*\n\n"
            ."🩺 *Jadwal Periksa:*\n"
            ."- Dokter: {$booking->doctor->name}\n"
            ."- Tanggal: {$booking->exam_date->format('d/m/Y')}\n"
            ."- Jam: {$booking->schedule->time_range}\n\n"
            ."💡 *Penting:*\n"
            ."- Datanglah *15 menit* sebelum jadwal untuk verifikasi.\n"
            ."- Tunjukkan pesan ini ke petugas pendaftaran.\n\n"
            .'Terima kasih.'
        );

        $phone = ltrim($booking->phone, '0');
        $waLink = "https://wa.me/62{$phone}?text={$msg}";

        return response()->json(['success' => true, 'wa_link' => $waLink]);
    }

    public function reject(Request $request, int $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:10',
        ]);

        $booking = Booking::findOrFail($id);
        $booking->update([
            'status' => 'REJECTED',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return response()->json(['success' => true]);
    }

    public function done(int $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'DONE']);

        return response()->json(['success' => true]);
    }
}
