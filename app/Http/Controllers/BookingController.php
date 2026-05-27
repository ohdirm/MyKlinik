<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use App\Mail\BookingSubmitted;
use App\Models\Booking;
use App\Models\Doctor;
use App\Models\Schedule;
use App\Notifications\BookingStatusNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function index()
    {
        // Ensure profile is complete before booking
        if (! auth()->user()->hasCompleteProfile()) {
            return redirect()->route('profile.index')
                ->with('warning', 'Lengkapi data profile terlebih dahulu untuk melakukan konsultasi.');
        }

        $doctors = Doctor::where('is_active', true)->get();
        $profile = auth()->user()->patientProfile;
        $familyProfiles = auth()->user()->familyProfiles()->get();

        // Pre-format family data for JS (avoids arrow functions in Blade @json)
        $familyProfilesJson = $familyProfiles->keyBy('id')->mapWithKeys(function ($fp, $id) {
            return [$id => [
                'full_name' => $fp->full_name,
                'nik' => $fp->nik,
                'birth_date' => $fp->birth_date?->format('Y-m-d'),
                'gender' => $fp->gender,
                'phone_number' => $fp->phone_number,
            ]];
        });

        return view('booking.index', compact('doctors', 'profile', 'familyProfiles', 'familyProfilesJson'));
    }

    public function store(BookingRequest $request)
    {
        $validated = $request->validated();

        // Check duplicate: same NIK + doctor + exam_date with active status
        $duplicate = Booking::where('nik', $validated['nik'])
            ->where('doctor_id', $validated['doctor_id'])
            ->where('exam_date', $validated['exam_date'])
            ->whereIn('status', ['PENDING', 'CONFIRMED'])
            ->exists();

        if ($duplicate) {
            return back()->withErrors(['nik' => 'Anda sudah memiliki booking aktif dengan dokter ini pada tanggal tersebut.'])->withInput();
        }

        // Check capacity
        $schedule = Schedule::findOrFail($validated['schedule_id']);
        $currentCount = Booking::where('schedule_id', $validated['schedule_id'])
            ->where('exam_date', $validated['exam_date'])
            ->whereIn('status', ['PENDING', 'CONFIRMED'])
            ->count();

        if ($currentCount >= $schedule->max_patients) {
            return back()->withErrors(['schedule_id' => 'Kuota jadwal ini sudah penuh untuk tanggal tersebut.'])->withInput();
        }

        // Generate unique booking code
        do {
            $code = 'MK-'.strtoupper(Str::random(6));
        } while (Booking::where('booking_code', $code)->exists());

        // Calculate queue number
        $queueNumber = Booking::where('doctor_id', $validated['doctor_id'])
            ->where('exam_date', $validated['exam_date'])
            ->max('queue_number');
        $queueNumber = ($queueNumber ?? 0) + 1;

        $booking = Booking::create(array_merge($validated, [
            'booking_code' => $code,
            'queue_number' => $queueNumber,
            'status' => 'PENDING',
            'user_id' => auth()->id(),
        ]));

        $booking->load('doctor', 'schedule');

        // Send submission email automatically
        try {
            Mail::to(auth()->user()->email)->send(new BookingSubmitted($booking));
        } catch (\Exception $e) {
            // Log error but continue
        }

        // Send in-app notification
        auth()->user()->notify(new BookingStatusNotification($booking, 'submitted'));

        return redirect()->back()->with('booking', $booking);
    }
}
