<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Schedule;

class ApiController extends Controller
{
    public function doctors()
    {
        $doctors = Doctor::where('is_active', true)
            ->select('id', 'name', 'specialization')
            ->get();

        return response()->json($doctors);
    }

    public function schedules(int $id)
    {
        $schedules = Schedule::where('doctor_id', $id)
            ->select('id', 'day_of_week', 'start_time', 'end_time', 'max_patients')
            ->orderBy('day_of_week')
            ->get()
            ->map(function (Schedule $schedule) {
                return [
                    'id' => $schedule->id,
                    'day_of_week' => $schedule->day_of_week,
                    'day_name' => $schedule->day_name,
                    'start_time' => substr($schedule->start_time, 0, 5),
                    'end_time' => substr($schedule->end_time, 0, 5),
                    'max_patients' => $schedule->max_patients,
                ];
            });

        return response()->json($schedules);
    }

    public function doctorStatus()
    {
        $doctors = Doctor::where('is_active', true)
            ->with('status')
            ->get()
            ->map(function (Doctor $doctor) {
                return [
                    'id' => $doctor->id,
                    'name' => $doctor->name,
                    'specialization' => $doctor->specialization,
                    'specialization_label' => $doctor->specialization_label,
                    'initials' => $doctor->initials,
                    'current_status' => $doctor->status->current_status ?? 'AVAILABLE',
                    'current_queue_number' => $doctor->status->current_queue_number ?? null,
                    'status_label' => $doctor->status->status_label ?? 'Tersedia',
                    'status_badge_class' => $doctor->status->status_badge_class ?? 'bg-green-100 text-green-800',
                ];
            });

        return response()->json($doctors);
    }
}
