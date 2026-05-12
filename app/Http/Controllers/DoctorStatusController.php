<?php

namespace App\Http\Controllers;

use App\Models\Doctor;

class DoctorStatusController extends Controller
{
    public function index()
    {
        $doctors = Doctor::where('is_active', true)
            ->with('status')
            ->get();

        return view('status.index', compact('doctors'));
    }
}
