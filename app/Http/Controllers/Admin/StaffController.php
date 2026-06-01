<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class StaffController extends Controller
{
    public function index()
    {
        if (! auth()->user()->isSuperAdmin()) {
            abort(403, 'Anda tidak memiliki hak akses untuk fitur ini.');
        }

        $staffs = User::whereIn('role', ['admin', 'super_admin'])->latest()->get();

        return view('admin.staff.index', compact('staffs'));
    }

    public function create()
    {
        if (! auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        return view('admin.staff.create');
    }

    public function store(Request $request)
    {
        if (! auth()->user()->isSuperAdmin()) {
            abort(403);
        }
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'admin',
            'email_verified_at' => now(), // Otomatis verifikasi untuk staff
        ]);

        // Log Activity
        ActivityLog::log('Tambah Staff', "Mendaftarkan staff baru: {$validated['name']} ({$validated['email']}).");

        return redirect()->route('admin.staff.index')->with('success', 'Staff berhasil didaftarkan.');
    }

    public function edit(User $staff)
    {
        if (! auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        // Jangan izinkan edit super_admin lain jika ada (atau proteksi diri sendiri)
        if ($staff->isSuperAdmin() && $staff->id !== auth()->id()) {
            return back()->with('error', 'Anda tidak dapat mengubah akun Super Admin lainnya.');
        }

        return view('admin.staff.edit', compact('staff'));
    }

    public function update(Request $request, User $staff)
    {
        if (! auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        if ($staff->isSuperAdmin() && $staff->id !== auth()->id()) {
            return back()->with('error', 'Anda tidak memiliki hak untuk mengubah akun Super Admin ini.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$staff->id],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($validated['password']);
        }

        $staff->update($data);

        // Log Activity
        ActivityLog::log('Update Staff', "Memperbarui data staff: {$staff->name} ({$staff->email}).");

        return redirect()->route('admin.staff.index')->with('success', 'Data staff berhasil diperbarui.');
    }

    public function destroy(User $staff)
    {
        if (! auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        // Jangan biarkan admin menghapus dirinya sendiri
        if ($staff->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        if ($staff->isSuperAdmin()) {
            return back()->with('error', 'Akun Super Admin tidak dapat dihapus.');
        }

        $staffName = $staff->name;
        $staffEmail = $staff->email;
        $staff->delete();

        // Log Activity
        ActivityLog::log('Hapus Staff', "Menghapus akun staff: {$staffName} ({$staffEmail}).");

        return redirect()->route('admin.staff.index')->with('success', 'Staff berhasil dihapus.');
    }
}
