<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Specialization;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SpecializationController extends Controller
{
    /**
     * Show admin specialization management page.
     */
    public function index()
    {
        $specializations = Specialization::orderBy('is_default', 'desc')
            ->orderBy('label')
            ->get();

        return view('admin.specializations.index', compact('specializations'));
    }

    /**
     * Store a new custom specialization via AJAX.
     */
    public function store(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:100',
        ]);

        $label = trim($request->label);
        $value = strtoupper(Str::slug($label, '_'));

        if (Specialization::where('value', $value)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Spesialisasi sudah ada.',
            ], 422);
        }

        $spec = Specialization::create([
            'value'      => $value,
            'label'      => $label,
            'is_default' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Spesialisasi \"{$label}\" berhasil ditambahkan.",
            'spec'    => [
                'value' => $spec->value,
                'label' => $spec->label,
            ],
        ]);
    }

    /**
     * Update specialization label via AJAX.
     */
    public function update(Request $request, Specialization $specialization)
    {
        $request->validate([
            'label' => 'required|string|max:100',
        ]);

        $specialization->update([
            'label' => trim($request->label),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Spesialisasi berhasil diperbarui.',
            'spec'    => [
                'id'    => $specialization->id,
                'value' => $specialization->value,
                'label' => $specialization->label,
            ],
        ]);
    }

    /**
     * Delete a custom specialization via AJAX.
     * Default specializations cannot be deleted.
     */
    public function destroy(Specialization $specialization)
    {
        if ($specialization->is_default) {
            return response()->json([
                'success' => false,
                'message' => 'Spesialisasi default tidak dapat dihapus.',
            ], 403);
        }

        // Check if any doctor uses this specialization
        $inUse = \App\Models\Doctor::where('specialization', $specialization->value)->exists();
        if ($inUse) {
            return response()->json([
                'success' => false,
                'message' => 'Spesialisasi sedang digunakan oleh dokter dan tidak dapat dihapus.',
            ], 422);
        }

        $label = $specialization->label;
        $specialization->delete();

        return response()->json([
            'success' => true,
            'message' => "Spesialisasi \"{$label}\" berhasil dihapus.",
        ]);
    }
}
