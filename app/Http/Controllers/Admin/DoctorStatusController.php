<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorStatus;
use Illuminate\Http\Request;

class DoctorStatusController extends Controller
{
    public function index()
    {
        $doctors = Doctor::where('is_active', true)
            ->with('status')
            ->get();

        return view('admin.doctor-status.index', compact('doctors'));
    }

    public function update(Request $request, int $doctorId)
    {
        $request->validate([
            'current_status' => 'required|in:AVAILABLE,IN_EXAMINATION,NEXT_AVAILABLE,UNAVAILABLE',
            'current_queue_number' => 'nullable|integer|min:1',
        ]);

        DoctorStatus::updateOrCreate(
            ['doctor_id' => $doctorId],
            [
                'current_status' => $request->current_status,
                'current_queue_number' => $request->current_status === 'IN_EXAMINATION'
                    ? $request->current_queue_number
                    : null,
                'updated_at' => now(),
            ]
        );

        return response()->json(['success' => true]);
    }
}
