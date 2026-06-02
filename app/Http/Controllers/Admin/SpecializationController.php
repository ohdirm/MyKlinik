<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
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
            'keywords' => 'nullable|string|max:2000',
        ]);

        $label = trim($request->label);
        $inputKeywords = $request->keywords;

        if (empty($inputKeywords)) {
            $keywords = $this->generateDefaultKeywords($label);
        } else {
            $keywords = $this->parseKeywords($inputKeywords);
        }

        $value = strtoupper(Str::slug($label, '_'));

        if (Specialization::where('value', $value)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Spesialisasi sudah ada.',
            ], 422);
        }

        $spec = Specialization::create([
            'value' => $value,
            'label' => $label,
            'keywords' => $keywords ?: null,
            'is_default' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Spesialisasi \"{$label}\" berhasil ditambahkan.",
            'spec' => [
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
            'keywords' => 'nullable|string|max:2000',
        ]);

        $keywords = $this->parseKeywords($request->keywords);

        $specialization->update([
            'label' => trim($request->label),
            'keywords' => $keywords ?: null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Spesialisasi berhasil diperbarui.',
            'spec' => [
                'id' => $specialization->id,
                'value' => $specialization->value,
                'label' => $specialization->label,
                'keywords' => $specialization->keywords,
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
        $inUse = Doctor::where('specialization', $specialization->value)->exists();
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

    /**
     * Generate basic keywords based on the specialization name.
     *
     * @return string[]
     */
    private function generateDefaultKeywords(string $label): array
    {
        $label = mb_strtolower($label);
        $clean = str_replace(['spesialis', 'dokter'], '', $label);
        $core = trim($clean);

        if (empty($core)) {
            return [];
        }

        $defaults = [
            $core,
            "sakit {$core}",
            "masalah {$core}",
            "keluhan {$core}",
        ];

        // Add individual words from the core label as fallback keywords
        $words = array_filter(explode(' ', preg_replace('/[^\w\s]/', '', $core)));
        if (count($words) > 1) {
            foreach ($words as $word) {
                if (strlen($word) > 3) { // Only add significant words
                    $defaults[] = $word;
                }
            }
        }

        // Specific mappings for common terms
        $mappings = [
            'gigi' => ['sakit gigi', 'cabut gigi', 'gusi', 'behel', 'karang gigi'],
            'mata' => ['buram', 'rabun', 'perih mata', 'katarak'],
            'anak' => ['bayi', 'imunisasi', 'tumbuh kembang', 'rewel'],
            'tht' => ['telinga', 'hidung', 'tenggorokan', 'amandel'],
            'kulit' => ['gatal', 'jerawat', 'eksim', 'ruam'],
            'kandungan' => ['hamil', 'kehamilan', 'haid', 'menstruasi'],
        ];

        foreach ($mappings as $key => $extras) {
            if (str_contains($core, $key)) {
                $defaults = array_merge($defaults, $extras);
            }
        }

        return array_unique($defaults);
    }

    /**
     * Parse comma-separated keywords string into a clean array.
     *
     * @return string[]
     */
    private function parseKeywords(?string $raw): array
    {
        if (! $raw) {
            return [];
        }

        return collect(explode(',', $raw))
            ->map(fn (string $k) => mb_strtolower(trim($k)))
            ->filter()
            ->unique()
            ->values()
            ->toArray();
    }
}
