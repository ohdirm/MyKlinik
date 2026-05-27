<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $profile = auth()->user()->patientProfile;
        $familyProfiles = auth()->user()->familyProfiles()->latest()->get();

        return view('profile.index', compact('profile', 'familyProfiles'));
    }

    public function update(ProfileUpdateRequest $request)
    {
        $validated = $request->validated();

        auth()->user()->patientProfile()->updateOrCreate(
            ['user_id' => auth()->id()],
            $validated
        );

        return redirect()->route('profile.index')->with('success', 'Profile berhasil disimpan.');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'profile_photo.required' => 'Pilih foto terlebih dahulu.',
            'profile_photo.image' => 'File harus berupa gambar.',
            'profile_photo.mimes' => 'Format gambar harus JPG, PNG, atau WebP.',
            'profile_photo.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        $profile = auth()->user()->patientProfile;

        if (! $profile) {
            return back()->withErrors(['profile_photo' => 'Lengkapi data profile terlebih dahulu.']);
        }

        // Delete old photo if exists
        if ($profile->profile_photo) {
            Storage::disk('public')->delete($profile->profile_photo);
        }

        $path = $request->file('profile_photo')->store('profile-photos', 'public');
        $profile->update(['profile_photo' => $path]);

        return back()->with('success', 'Foto profile berhasil diperbarui.');
    }

    /**
     * API endpoint to check profile completeness.
     */
    public function checkComplete(): JsonResponse
    {
        return response()->json([
            'complete' => auth()->user()->hasCompleteProfile(),
        ]);
    }
}
