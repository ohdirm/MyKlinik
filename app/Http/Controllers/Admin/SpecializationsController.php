<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::latest()->paginate(15);

        return view('admin.doctors.index', compact('doctors'));
    }

    public function create()
    {
        return view('admin.doctors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'specialization' => 'required|in:UMUM,SPESIALIS_ANAK,SPESIALIS_KANDUNGAN,SPESIALIS_PENYAKIT_DALAM,SPESIALIS_BEDAH,SPESIALIS_MATA,SPESIALIS_THT,SPESIALIS_KULIT,SPESIALIS_JANTUNG',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|max:10240',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('doctors', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        $doctor = Doctor::create($validated);

        // Create default doctor status
        $doctor->status()->create([
            'current_status' => 'AVAILABLE',
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.doctors.index')->with('success', 'Dokter berhasil ditambahkan.');
    }

    public function show(Doctor $doctor)
    {
        return redirect()->route('admin.doctors.edit', $doctor);
    }

    public function edit(Doctor $doctor)
    {
        return view('admin.doctors.edit', compact('doctor'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'specialization' => 'required|in:UMUM,SPESIALIS_ANAK,SPESIALIS_KANDUNGAN,SPESIALIS_PENYAKIT_DALAM,SPESIALIS_BEDAH,SPESIALIS_MATA,SPESIALIS_THT,SPESIALIS_KULIT,SPESIALIS_JANTUNG',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|max:10240',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('photo')) {
            if ($doctor->photo) {
                Storage::disk('public')->delete($doctor->photo);
            }
            $validated['photo'] = $request->file('photo')->store('doctors', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        $doctor->update($validated);

        return redirect()->route('admin.doctors.index')->with('success', 'Data dokter berhasil diperbarui.');
    }

    public function destroy(Doctor $doctor)
    {
        if ($doctor->photo) {
            Storage::disk('public')->delete($doctor->photo);
        }

        $doctor->delete();

        return redirect()->route('admin.doctors.index')->with('success', 'Dokter berhasil dihapus.');
    }
}
