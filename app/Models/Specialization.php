<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specialization extends Model
{
    protected $fillable = ['value', 'label', 'is_default'];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
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
}
