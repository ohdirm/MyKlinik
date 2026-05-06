<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index()
    {
        // Semua user (admin & patient) bisa melihat semua review
        $reviews = Review::with(['booking.user', 'booking.schedule.doctor.clinic', 'booking.schedule.doctor.specialization'])
            ->latest()
            ->paginate(12);

        return view('reviews.index', compact('reviews'));
    }

    public function create(Request $request)
    {
        $booking_id = $request->query('booking_id');
        $booking = Booking::with('schedule.doctor')->findOrFail($booking_id);

        // Validasi: hanya pasien ybs, status completed, dan belum direview
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }
        if ($booking->status !== 'completed') {
            return redirect()->route('bookings.index')->with('error', 'Hanya booking yang telah selesai yang dapat direview.');
        }
        if ($booking->review()->exists()) {
            return redirect()->route('reviews.index')->with('error', 'Anda sudah memberikan review untuk booking ini.');
        }

        return view('reviews.create', compact('booking'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $booking = Booking::findOrFail($validated['booking_id']);

        if ($booking->user_id !== Auth::id() || $booking->status !== 'completed' || $booking->review()->exists()) {
            abort(403);
        }

        Review::create($validated);

        return redirect()->route('reviews.index')->with('success', 'Review berhasil dikirimkan. Terima kasih atas penilaian Anda!');
    }

    // Admin can delete review
    public function destroy(Review $review)
    {
        if (! Auth::user()->isAdmin()) {
            abort(403);
        }

        $review->delete();

        return redirect()->route('reviews.index')->with('success', 'Review berhasil dihapus.');
    }
}
