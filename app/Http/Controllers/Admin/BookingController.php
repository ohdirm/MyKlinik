<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Doctor;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with('doctor', 'schedule');

        if ($request->filled('date')) {
            $query->whereDate('exam_date', $request->date);
        }

        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->latest()->paginate(15);
        $doctors = Doctor::where('is_active', true)->get();

        return view('admin.bookings.index', compact('bookings', 'doctors'));
    }

    public function confirm(int $id)
    {
        $booking = Booking::with('doctor')->findOrFail($id);
        $booking->update(['status' => 'CONFIRMED']);

        $msg = urlencode(
            "Halo {$booking->patient_name}, booking Anda di MyKlinik911 telah DIKONFIRMASI.\n"
            ."Kode: {$booking->booking_code}\n"
            ."Antrian: {$booking->queue_number}\n"
            ."Dokter: {$booking->doctor->name}\n"
            ."Tanggal: {$booking->exam_date->format('d/m/Y')}"
        );

        $phone = ltrim($booking->phone, '0');
        $waLink = "https://wa.me/62{$phone}?text={$msg}";

        return response()->json(['success' => true, 'wa_link' => $waLink]);
    }

    public function reject(Request $request, int $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:10',
        ]);

        $booking = Booking::findOrFail($id);
        $booking->update([
            'status' => 'REJECTED',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return response()->json(['success' => true]);
    }

    public function done(int $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'DONE']);

        return response()->json(['success' => true]);
    }
}
