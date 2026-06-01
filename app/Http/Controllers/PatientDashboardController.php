<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\DoctorStatus;
use Illuminate\Support\Facades\Auth;

class PatientDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = now()->toDateString();

        // Booking aktif pasien (PENDING / CONFIRMED)
        $activeBookings = Booking::with('doctor', 'schedule')
            ->where('user_id', $user->id)
            ->whereIn('status', ['PENDING', 'CONFIRMED'])
            ->orderBy('exam_date')
            ->get();

        // Daftar antrean publik hari ini (tanpa nama pasien — hanya nomor & kode anonim)
        $todayQueue = Booking::with('doctor')
            ->where('exam_date', $today)
            ->whereIn('status', ['CONFIRMED', 'EXAMINING'])
            ->orderBy('queue_number')
            ->get()
            ->map(function ($b) {
                return [
                    'queue_number' => $b->queue_number,
                    'booking_code' => '***'.substr($b->booking_code, -3),
                    'doctor_name' => $b->doctor->name ?? '-',
                    'status' => $b->status,
                ];
            });

        // Status dokter real-time
        $doctorStatuses = DoctorStatus::with('doctor')->get();

        // Riwayat booking selesai (untuk tombol review)
        $completedBookings = Booking::with('doctor', 'schedule')
            ->where('user_id', $user->id)
            ->where('status', 'DONE')
            ->latest()
            ->paginate(5);

        // Booking IDs yang sudah di-review
        $reviewedBookingIds = $user->reviews()
            ->whereNotNull('booking_id')
            ->pluck('booking_id')
            ->toArray();

        return view('patient.dashboard', compact(
            'activeBookings',
            'todayQueue',
            'doctorStatuses',
            'completedBookings',
            'reviewedBookingIds'
        ));
    }
}
