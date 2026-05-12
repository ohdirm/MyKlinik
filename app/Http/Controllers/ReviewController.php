<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function create(Booking $booking)
    {
        // Ensure the booking belongs to the logged-in user and is DONE
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->status !== 'DONE') {
            return redirect()->route('patient.dashboard')
                ->with('error', 'Review hanya bisa diberikan setelah pemeriksaan selesai.');
        }

        // Check if already reviewed
        $existingReview = Review::where('user_id', Auth::id())
            ->where('booking_id', $booking->id)
            ->first();

        if ($existingReview) {
            return redirect()->route('patient.dashboard')
                ->with('error', 'Anda sudah memberikan review untuk kunjungan ini.');
        }

        $booking->load('doctor', 'schedule');

        return view('patient.review-form', compact('booking'));
    }

    public function store(Request $request, Booking $booking)
    {
        if ($booking->user_id !== Auth::id() || $booking->status !== 'DONE') {
            abort(403);
        }

        $validated = $request->validate([
            'type' => 'required|in:clinic,doctor',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        Review::create([
            'user_id' => Auth::id(),
            'doctor_id' => $validated['type'] === 'doctor' ? $booking->doctor_id : null,
            'booking_id' => $booking->id,
            'type' => $validated['type'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return redirect()->route('patient.dashboard')
            ->with('success', 'Terima kasih atas review Anda!');
    }
}
