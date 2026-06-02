<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Support\Facades\Artisan;

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
        Artisan::call('clinic:reset');

        return response()->json([
            'success' => true,
            'message' => 'Klinik ditutup. Semua status dokter direset dan booking hari ini dibatalkan.',
        ]);
    }
}
