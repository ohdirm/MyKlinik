<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Specialization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $query = Doctor::latest();

        if ($request->filled('specialization')) {
            $query->where('specialization', $request->specialization);
        }

        $doctors = $query->paginate(15)->withQueryString();
        $specializations = Specialization::orderBy('label')->get();

        return view('admin.doctors.index', compact('doctors', 'specializations'));
    }

    public function create()
    {
        $specializations = Specialization::orderBy('is_default', 'desc')
            ->orderBy('label')
            ->get();

        return view('admin.doctors.create', compact('specializations'));
    }

    public function store(Request $request)
    {
        $validValues = Specialization::pluck('value')->toArray();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'specialization' => ['required', 'string', 'in:'.implode(',', $validValues)],
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
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
        $specializations = Specialization::orderBy('is_default', 'desc')
            ->orderBy('label')
            ->get();

        return view('admin.doctors.edit', compact('doctor', 'specializations'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $validValues = Specialization::pluck('value')->toArray();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'specialization' => ['required', 'string', 'in:'.implode(',', $validValues)],
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
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
