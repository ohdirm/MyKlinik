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
     * Looks up from the specializations table first, falls back to formatted value.
     */
    public function getSpecializationLabelAttribute(): string
    {
        static $cache = [];

        $value = $this->specialization;

        if (!isset($cache[$value])) {
            $spec = \App\Models\Specialization::where('value', $value)->first();
            $cache[$value] = $spec
                ? $spec->label
                : ucwords(strtolower(str_replace('_', ' ', $value)));
        }

        return $cache[$value];
    }

    /**
     * Get initials for avatar.
     */
    public function getInitialsAttribute(): string
    {
        $titles = ['dr.', 'dr', 'sp.', 'sp', 'prof.', 'prof', 'dra.', 'dra', 'drs.', 'drs'];
        $words = collect(explode(' ', $this->name))
            ->filter(fn ($w) => !in_array(strtolower(trim($w)), $titles))
            ->values();

        if ($words->isEmpty()) {
            return strtoupper(substr($this->name, 0, 1));
        }

        return strtoupper(
            $words->take(2)->map(fn ($w) => mb_substr($w, 0, 1))->implode('')
        );
    }

    /**
     * Get photo URL or fallback.
     */
    public function getPhotoUrlAttribute(): ?string
    {
        if ($this->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->photo)) {
            return asset('storage/' . $this->photo);
        }

        // Check if photo column has a full URL or relative to public
        if ($this->photo && (str_starts_with($this->photo, 'http') || file_exists(public_path($this->photo)))) {
            return str_starts_with($this->photo, 'http') ? $this->photo : asset($this->photo);
        }

        return null;
    }
}
