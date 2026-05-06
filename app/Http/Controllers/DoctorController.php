<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Specialization;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::with(['clinic', 'specialization'])->latest()->paginate(10);
        return view('doctors.index', compact('doctors'));
    }

    public function create()
    {
        $clinics = Clinic::orderBy('name')->get();
        $specializations = Specialization::orderBy('name')->get();
        return view('doctors.create', compact('clinics', 'specializations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'clinic_id' => 'required|exists:clinics,id',
            'specialization_id' => 'required|exists:specializations,id',
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Doctor::create($validated);

        return redirect()->route('doctors.index')->with('success', 'Dokter berhasil ditambahkan.');
    }

    public function edit(Doctor $doctor)
    {
        $clinics = Clinic::orderBy('name')->get();
        $specializations = Specialization::orderBy('name')->get();
        return view('doctors.edit', compact('doctor', 'clinics', 'specializations'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $validated = $request->validate([
            'clinic_id' => 'required|exists:clinics,id',
            'specialization_id' => 'required|exists:specializations,id',
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $doctor->update($validated);

        return redirect()->route('doctors.index')->with('success', 'Dokter berhasil diperbarui.');
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->delete();
        return redirect()->route('doctors.index')->with('success', 'Dokter berhasil dihapus.');
    }
}
