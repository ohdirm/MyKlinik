<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientProfile extends Model
{
    /** @var list<string> */
    protected $fillable = [
        'user_id',
        'nik',
        'full_name',
        'birth_date',
        'gender',
        'phone_number',
        'address',
        'profile_photo',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if all required profile fields are filled.
     */
    public function isComplete(): bool
    {
        return $this->nik
            && $this->full_name
            && $this->birth_date
            && $this->gender
            && $this->phone_number;
    }
}
