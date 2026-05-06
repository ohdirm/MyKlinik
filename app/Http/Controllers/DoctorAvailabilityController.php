<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DoctorAvailabilityController extends Controller
{
    /**
     * Halaman ketersediaan dokter — bisa dilihat semua pasien setelah login.
     */
    public function index(Request $request)
    {
        $selectedDate = $request->query('date', Carbon::today()->toDateString());
        $dayOfWeek = strtolower(Carbon::parse($selectedDate)->format('l'));

        // Ambil semua jadwal yang aktif pada hari tersebut
        $schedules = Schedule::with(['doctor.clinic', 'doctor.specialization'])
            ->where('is_active', true)
            ->where('day_of_week', $dayOfWeek)
            ->whereHas('doctor', function ($q) {
                $q->where('is_active', true);
            })
            ->get();

        // Hitung booking yang sudah ada per jadwal pada tanggal tersebut
        $schedules = $schedules->map(function ($schedule) use ($selectedDate) {
            $bookedCount = Booking::where('schedule_id', $schedule->id)
                ->where('booking_date', $selectedDate)
                ->whereIn('status', ['pending', 'confirmed', 'in_progress'])
                ->count();

            $schedule->booked_count = $bookedCount;
            $schedule->available_slots = $schedule->max_patients - $bookedCount;
            $schedule->is_full = $bookedCount >= $schedule->max_patients;

            return $schedule;
        });

        return view('availability.index', compact('schedules', 'selectedDate', 'dayOfWeek'));
    }

    /**
     * API: Cek ketersediaan jadwal pada tanggal tertentu (untuk form booking AJAX).
     */
    public function checkAvailability(Request $request)
    {
        $date = $request->query('date');

        if (! $date) {
            return response()->json([]);
        }

        $dayOfWeek = strtolower(Carbon::parse($date)->format('l'));

        $schedules = Schedule::with(['doctor.clinic', 'doctor.specialization'])
            ->where('is_active', true)
            ->where('day_of_week', $dayOfWeek)
            ->whereHas('doctor', function ($q) {
                $q->where('is_active', true);
            })
            ->get();

        $result = $schedules->map(function ($schedule) use ($date) {
            $bookedCount = Booking::where('schedule_id', $schedule->id)
                ->where('booking_date', $date)
                ->whereIn('status', ['pending', 'confirmed', 'in_progress'])
                ->count();

            return [
                'id' => $schedule->id,
                'doctor_name' => $schedule->doctor->name,
                'specialization' => $schedule->doctor->specialization->name,
                'clinic_name' => $schedule->doctor->clinic->name,
                'day_of_week' => $schedule->day_of_week,
                'start_time' => Carbon::parse($schedule->start_time)->format('H:i'),
                'end_time' => Carbon::parse($schedule->end_time)->format('H:i'),
                'max_patients' => $schedule->max_patients,
                'booked_count' => $bookedCount,
                'available_slots' => $schedule->max_patients - $bookedCount,
                'is_full' => $bookedCount >= $schedule->max_patients,
            ];
        });

        return response()->json($result);
    }
}
