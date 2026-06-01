<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Booking;
use App\Models\DoctorStatus;

class DashboardController extends Controller
{
    public function index()
    {
        $metrics = [
            'pending' => Booking::where('status', 'PENDING')->count(),
            'confirmed' => Booking::where('status', 'CONFIRMED')->count(),
            'rejected' => Booking::where('status', 'REJECTED')->count(),
            'done' => Booking::where('status', 'DONE')->count(),
        ];

        $recentBookings = Booking::with('doctor', 'schedule')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard.index', compact('metrics', 'recentBookings'));
    }

    /**
     * Reset daily: close clinic, reset all doctor statuses and cancel hanging bookings.
     */
    public function resetDaily()
    {
        $today = now()->toDateString();

        // Set all doctor statuses to UNAVAILABLE and queue to 0
        DoctorStatus::query()->update([
            'current_status' => 'UNAVAILABLE',
            'current_queue_number' => 0,
        ]);

        // Cancel all PENDING/CONFIRMED bookings for today
        Booking::whereDate('exam_date', $today)
            ->whereIn('status', ['PENDING', 'CONFIRMED'])
            ->update(['status' => 'CANCELLED']);

        // Log Activity
        ActivityLog::log('Reset Harian', 'Menutup klinik, mereset status dokter, dan membatalkan booking gantung hari ini.');

        return response()->json([
            'success' => true,
            'message' => 'Klinik ditutup. Semua status dokter direset dan booking hari ini dibatalkan.',
        ]);
    }
}
