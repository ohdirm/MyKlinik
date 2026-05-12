<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Review;

class HomeController extends Controller
{
    public function index()
    {
        $doctors = Doctor::where('is_active', true)
            ->with('status')
            ->get();

        $reviews = Review::with('user', 'doctor')
            ->latest()
            ->take(12)
            ->get();

        return view('home.index', compact('doctors', 'reviews'));
    }
}
