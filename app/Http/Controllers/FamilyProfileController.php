<?php

namespace App\Http\Controllers;

use App\Http\Requests\FamilyProfileRequest;
use App\Models\FamilyProfile;

class FamilyProfileController extends Controller
{
    public function store(FamilyProfileRequest $request)
    {
        auth()->user()->familyProfiles()->create($request->validated());

        return redirect()->route('profile.index', ['tab' => 'keluarga'])
            ->with('success', 'Anggota keluarga berhasil ditambahkan.');
    }

    public function update(FamilyProfileRequest $request, FamilyProfile $familyProfile)
    {
        // Ensure ownership
        if ($familyProfile->user_id !== auth()->id()) {
            abort(403);
        }

        $familyProfile->update($request->validated());

        return redirect()->route('profile.index', ['tab' => 'keluarga'])
            ->with('success', 'Data anggota keluarga berhasil diperbarui.');
    }

    public function destroy(FamilyProfile $familyProfile)
    {
        // Ensure ownership
        if ($familyProfile->user_id !== auth()->id()) {
            abort(403);
        }

        $familyProfile->delete();

        return redirect()->route('profile.index', ['tab' => 'keluarga'])
            ->with('success', 'Anggota keluarga berhasil dihapus.');
    }
}
