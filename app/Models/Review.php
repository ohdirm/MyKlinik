<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'doctor_id',
        'booking_id',
        'type',
        'rating',
        'comment',
        'status',
        'is_flagged',
        'moderation_note',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'is_flagged' => 'boolean',
        ];
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isHidden(): bool
    {
        return $this->status === 'hidden';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get star display for rating.
     */
    public function getRatingStarsAttribute(): string
    {
        return str_repeat('★', $this->rating).str_repeat('☆', 5 - $this->rating);
    }

    /**
     * Get rating summary for display.
     */
    public static function getSummary()
    {
        $reviews = self::where('status', 'published')->get();
        $total = $reviews->count();
        $average = $total > 0 ? round($reviews->avg('rating'), 1) : 0;
        
        $distribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $count = $reviews->where('rating', $i)->count();
            $percentage = $total > 0 ? round(($count / $total) * 100) : 0;
            $distribution[$i] = [
                'count' => $count,
                'percentage' => $percentage
            ];
        }

        return [
            'total' => $total,
            'average' => $average,
            'distribution' => $distribution
        ];
    }
}
