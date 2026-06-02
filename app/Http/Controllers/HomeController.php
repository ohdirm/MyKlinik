<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Doctor;
use App\Models\Review;

class HomeController extends Controller
{
    public function index()
    {
        $doctors = Doctor::where('is_active', true)
            ->with('status')
            ->get();

        $reviews = Review::where('status', 'published')
            ->with(['user', 'doctor'])
            ->latest()
            ->take(12)
            ->get();

        $ratingSummary = Review::getSummary();

        $todayBookings = Booking::whereDate('exam_date', now())
            ->whereIn('status', ['PENDING', 'CONFIRMED', 'EXAMINING'])
            ->orderBy('queue_number')
            ->get();

        return view('home.index', compact('doctors', 'reviews', 'todayBookings', 'ratingSummary'));
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }
}
