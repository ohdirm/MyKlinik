<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specialization extends Model
{
    protected $fillable = ['value', 'label', 'is_default', 'keywords'];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
            'keywords' => 'array',
        ];
    }

    /**
     * Get all specializations as key=>value array for selects.
     */
    public static function asOptions(): array
    {
        return static::orderBy('is_default', 'desc')
            ->orderBy('label')
            ->pluck('label', 'value')
            ->toArray();
    }

    /**
     * Match a patient complaint to the best specialization.
     *
     * @return array{specialization: ?self, score: int}
     */
    public static function matchComplaint(string $complaint): array
    {
        $complaint = mb_strtolower(trim($complaint));
        $bestMatch = null;
        $bestScore = 0;

        $specializations = static::whereNotNull('keywords')->get();

        foreach ($specializations as $spec) {
            $score = 0;
            $keywords = $spec->keywords ?? [];

            foreach ($keywords as $keyword) {
                if (str_contains($complaint, mb_strtolower($keyword))) {
                    $score++;
                }
            }

            if ($score > $bestScore) {
                $bestScore = $score;
                $bestMatch = $spec;
            }
        }

        return [
            'specialization' => $bestMatch,
            'score' => $bestScore,
        ];
    }
}
