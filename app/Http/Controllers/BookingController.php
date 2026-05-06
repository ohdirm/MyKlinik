<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        $query = Booking::with(['user', 'schedule.doctor.clinic', 'schedule.doctor.specialization', 'review'])->latest();

        // Jika bukan admin, hanya tampilkan booking milik pasien itu sendiri
        if (!Auth::user()->isAdmin()) {
            $query->where('user_id', Auth::id());
        }

        $bookings = $query->paginate(10);
        return view('bookings.index', compact('bookings'));
    }

    public function create()
    {
        // Ambil jadwal yang aktif saja
        $schedules = Schedule::with(['doctor.clinic', 'doctor.specialization'])
            ->where('is_active', true)
            ->whereHas('doctor', function ($query) {
                $query->where('is_active', true);
            })
            ->get();
            
        return view('bookings.create', compact('schedules'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'preferred_start' => 'nullable|date_format:H:i',
            'preferred_end' => 'nullable|date_format:H:i',
            'notes' => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending';

        // Cek apakah pasien sudah punya booking di jadwal dan tanggal yang sama
        $exists = Booking::where('user_id', Auth::id())
            ->where('schedule_id', $validated['schedule_id'])
            ->where('booking_date', $validated['booking_date'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['booking_date' => 'Anda sudah memiliki booking untuk jadwal ini pada tanggal yang dipilih.'])->withInput();
        }

        // Cek kuota
        $schedule = Schedule::findOrFail($validated['schedule_id']);
        $currentBookings = Booking::where('schedule_id', $schedule->id)
            ->where('booking_date', $validated['booking_date'])
            ->whereIn('status', ['pending', 'confirmed', 'in_progress'])
            ->count();

        if ($currentBookings >= $schedule->max_patients) {
            return back()->withErrors(['schedule_id' => 'Kuota pasien untuk jadwal ini sudah penuh.'])->withInput();
        }

        Booking::create($validated);

        return redirect()->route('bookings.index')->with('success', 'Booking berhasil dibuat.');
    }

    public function edit(Booking $booking)
    {
        // Hanya admin yang bisa mengedit booking status dll. Pasien tidak bisa edit (opsional bisa dibatalkan)
        if (!Auth::user()->isAdmin() && $booking->user_id !== Auth::id()) {
            abort(403);
        }

        return view('bookings.edit', compact('booking'));
    }

    public function update(Request $request, Booking $booking)
    {
        if (!Auth::user()->isAdmin() && $booking->user_id !== Auth::id()) {
            abort(403);
        }

        $rules = [
            'notes' => 'nullable|string',
        ];

        // Hanya admin yang bisa update status
        if (Auth::user()->isAdmin()) {
            $rules['status'] = 'required|in:pending,confirmed,in_progress,completed,cancelled';
        } else {
            // Pasien hanya bisa cancel jika status masih pending/confirmed
            if ($request->has('cancel_booking')) {
                if (in_array($booking->status, ['pending', 'confirmed'])) {
                    $booking->update(['status' => 'cancelled']);
                    return redirect()->route('bookings.index')->with('success', 'Booking berhasil dibatalkan.');
                }
                return back()->with('error', 'Booking ini tidak dapat dibatalkan.');
            }
        }

        $validated = $request->validate($rules);
        $booking->update($validated);

        return redirect()->route('bookings.index')->with('success', 'Booking berhasil diperbarui.');
    }

    public function destroy(Booking $booking)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $booking->delete();
        return redirect()->route('bookings.index')->with('success', 'Booking berhasil dihapus.');
    }
}
