<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Schedule;
use App\Models\Specialization;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

    /**
     * Suggest the best doctor based on patient complaint keywords.
     */
    public function suggestDoctor(Request $request)
    {
        $request->validate([
            'complaint' => ['required', 'string', 'min:3'],
            'date' => ['nullable', 'date'],
        ]);

        $complaint = $request->complaint;
        $dateStr = $request->date ?? now()->toDateString();
        $date = Carbon::parse($dateStr);
        $dayOfWeek = $date->dayOfWeek;

        // Match complaint to specialization
        $match = Specialization::matchComplaint($complaint);

        $suggestedDoctor = null;
        $matchedSpec = $match['specialization'];
        $fallback = false;

        if ($matchedSpec && $matchedSpec->value !== 'UMUM') {
            // Find an available specialist doctor with a schedule on that day
            $suggestedDoctor = Doctor::where('is_active', true)
                ->where('specialization', $matchedSpec->value)
                ->whereHas('schedules', fn ($q) => $q->where('day_of_week', $dayOfWeek))
                ->first();

            // If specialist not available, fallback to general practitioner
            if (! $suggestedDoctor) {
                $fallback = true;
            }
        }

        // Fallback: find available general practitioner
        if (! $suggestedDoctor) {
            $suggestedDoctor = Doctor::where('is_active', true)
                ->where('specialization', 'UMUM')
                ->whereHas('schedules', fn ($q) => $q->where('day_of_week', $dayOfWeek))
                ->first();
        }

        // Build schedules for the suggested doctor (for that day)
        $schedules = [];
        if ($suggestedDoctor) {
            $schedules = Schedule::where('doctor_id', $suggestedDoctor->id)
                ->where('day_of_week', $dayOfWeek)
                ->get()
                ->map(fn (Schedule $s) => [
                    'id' => $s->id,
                    'day_name' => $s->day_name,
                    'start_time' => substr($s->start_time, 0, 5),
                    'end_time' => substr($s->end_time, 0, 5),
                    'max_patients' => $s->max_patients,
                ]);
        }

        return response()->json([
            'matched_specialization' => $matchedSpec ? [
                'value' => $matchedSpec->value,
                'label' => $matchedSpec->label,
            ] : null,
            'score' => $match['score'],
            'suggested_doctor' => $suggestedDoctor ? [
                'id' => $suggestedDoctor->id,
                'name' => $suggestedDoctor->name,
                'specialization' => $suggestedDoctor->specialization,
                'specialization_label' => $suggestedDoctor->specialization_label,
            ] : null,
            'schedules' => $schedules,
            'fallback' => $fallback,
            'fallback_reason' => $fallback
                ? ($matchedSpec ? "Dokter {$matchedSpec->label} tidak tersedia pada tanggal tersebut, dialihkan ke Dokter Umum." : 'Dialihkan ke Dokter Umum.')
                : null,
        ]);
    }
}
