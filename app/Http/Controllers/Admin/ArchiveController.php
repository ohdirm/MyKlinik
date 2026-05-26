<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Doctor;
use Illuminate\Http\Request;

class ArchiveController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with('doctor', 'schedule');

        // Only show completed/processed statuses by default
        if (! $request->filled('status')) {
            $query->whereIn('status', ['DONE', 'REJECTED', 'CANCELLED', 'EXPIRED']);
        } else {
            $query->where('status', $request->status);
        }

        // Search by NIK, Name, or Booking Code
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('patient_name', 'like', "%{$s}%")
                    ->orWhere('nik', 'like', "%{$s}%")
                    ->orWhere('booking_code', 'like', "%{$s}%")
                    ->orWhere('complaint', 'like', "%{$s}%");
            });
        }

        // Filter by Doctor
        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        // Filter by Date Range
        if ($request->filled('start_date')) {
            $query->whereDate('exam_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('exam_date', '<=', $request->end_date);
        }

        $bookings = $query->latest('exam_date')->paginate(15)->withQueryString();
        $doctors = Doctor::where('is_active', true)->get();

        return view('admin.archive.index', compact('bookings', 'doctors'));
    }

    public function destroy(int $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete(); // This will trigger soft delete

        return redirect()->back()->with('success', 'Data riwayat pendaftaran berhasil diarsipkan (dihapus).');
    }
}
