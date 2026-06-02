<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Services\ProfanityService;
use Illuminate\Http\Request;

class ReviewModerationController extends Controller
{
    public function __construct(protected ProfanityService $profanityService) {}

    public function index(Request $request)
    {
        $query = Review::with(['user', 'doctor', 'booking'])->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('flagged')) {
            $query->where('is_flagged', true);
        }

        $reviews = $query->paginate(10);
        
        $stats = [
            'total' => Review::count(),
            'pending' => Review::where('status', 'pending')->count(),
            'flagged' => Review::where('is_flagged', true)->count(),
            'published' => Review::where('status', 'published')->count(),
        ];

        return view('admin.reviews.index', compact('reviews', 'stats'));
    }

    public function update(Request $request, Review $review)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,published,hidden',
            'moderation_note' => 'nullable|string|max:500',
            'comment' => 'nullable|string|max:1000', // Admin can edit slightly to sanitize
        ]);

        $review->update($validated);

        return back()->with('success', 'Status review berhasil diuraikan.');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Review berhasil dihapus dari sistem.');
    }
}
