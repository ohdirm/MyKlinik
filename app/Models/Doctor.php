<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Doctor extends Model
{
    protected $fillable = [
        'name',
        'specialization',
        'photo',
        'bio',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function status(): HasOne
    {
        return $this->hasOne(DoctorStatus::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get average rating from reviews.
     */
    public function getAverageRatingAttribute(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    /**
     * Get human-readable specialization label.
     */
    public function getSpecializationLabelAttribute(): string
    {
        return match ($this->specialization) {
            'UMUM' => 'Umum',
            'SPESIALIS_ANAK' => 'Spesialis Anak',
            'SPESIALIS_KANDUNGAN' => 'Spesialis Kandungan',
            'SPESIALIS_PENYAKIT_DALAM' => 'Spesialis Penyakit Dalam',
            'SPESIALIS_BEDAH' => 'Spesialis Bedah',
            'SPESIALIS_MATA' => 'Spesialis Mata',
            'SPESIALIS_THT' => 'Spesialis THT',
            'SPESIALIS_KULIT' => 'Spesialis Kulit',
            'SPESIALIS_JANTUNG' => 'Spesialis Jantung',
            default => $this->specialization,
        };
    }

    /**
     * Get initials for avatar.
     */
    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);

        return strtoupper(
            collect($words)->take(2)->map(fn ($w) => $w[0])->implode('')
        );
    }
}
