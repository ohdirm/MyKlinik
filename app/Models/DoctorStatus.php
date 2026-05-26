<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoctorStatus extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'doctor_id',
        'current_status',
        'current_queue_number',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'current_queue_number' => 'integer',
            'updated_at' => 'datetime',
        ];
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Get status badge CSS class.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->current_status) {
            'AVAILABLE' => 'bg-green-100 text-green-800',
            'IN_EXAMINATION' => 'bg-yellow-100 text-yellow-800',
            'NEXT_AVAILABLE' => 'bg-brand/20 text-brand-dark',
            'UNAVAILABLE' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get human-readable status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->current_status) {
            'AVAILABLE' => 'Tersedia',
            'IN_EXAMINATION' => 'Sedang Memeriksa',
            'NEXT_AVAILABLE' => 'Segera Tersedia',
            'UNAVAILABLE' => 'Tidak Tersedia',
            default => $this->current_status,
        };
    }
}
