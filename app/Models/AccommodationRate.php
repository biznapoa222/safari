<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccommodationRate extends Model
{
    protected $fillable = [
        'accommodation_room_id', 'season_name', 'valid_from', 'valid_to',
        'meal_plan', 'rate', 'currency', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'valid_from' => 'date',
            'valid_to' => 'date',
            'rate' => 'decimal:2',
        ];
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(AccommodationRoom::class, 'accommodation_room_id');
    }
}
