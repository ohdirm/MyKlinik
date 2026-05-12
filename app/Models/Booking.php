<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = [
        'booking_code',
        'patient_name',
        'nik',
        'phone',
        'birth_date',
        'gender',
        'address',
        'province',
        'district',
        'sub_district',
        'village',
        'doctor_id',
        'schedule_id',
        'exam_date',
        'queue_number',
        'status',
        'rejection_reason',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'exam_date' => 'date',
            'queue_number' => 'integer',
        ];
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    /**
     * Get status badge CSS class.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'PENDING' => 'bg-yellow-100 text-yellow-800',
            'CONFIRMED' => 'bg-green-100 text-green-800',
            'REJECTED' => 'bg-red-100 text-red-800',
            'DONE' => 'bg-gray-100 text-gray-800',
            'CANCELLED' => 'bg-gray-100 text-gray-500',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Menghitung estimasi kedatangan berdasarkan antrean.
     * Asumsi 15 menit per pasien sejak jadwal dimulai.
     */
    public function getEstimatedTimeAttribute(): ?string
    {
        if (! $this->schedule || ! $this->queue_number) {
            return null;
        }

        $startTime = \Carbon\Carbon::createFromTimeString($this->schedule->start_time);
        $minutesToAdd = ($this->queue_number - 1) * 15;
        
        return $startTime->addMinutes($minutesToAdd)->format('H:i');
    }
}
