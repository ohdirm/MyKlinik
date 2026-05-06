<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    /** @use HasFactory<\Database\Factories\BookingFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'schedule_id',
        'booking_date',
        'preferred_start',
        'preferred_end',
        'queue_number',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
            'preferred_start' => 'datetime:H:i',
            'preferred_end' => 'datetime:H:i',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    protected static function booted()
    {
        static::creating(function ($booking) {
            if (empty($booking->queue_number)) {
                $lastQueue = static::where('schedule_id', $booking->schedule_id)
                    ->where('booking_date', $booking->booking_date)
                    ->max('queue_number');
                $booking->queue_number = $lastQueue ? $lastQueue + 1 : 1;
            }
        });
    }
}
