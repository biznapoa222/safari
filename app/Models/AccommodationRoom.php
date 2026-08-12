<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccommodationRoom extends Model
{
    protected $fillable = [
        'accommodation_id', 'name', 'capacity', 'max_adults', 'max_children',
        'baby_max_age', 'child_min_age', 'child_max_age', 'adult_min_age',
        'child_policy', 'inventory', 'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function accommodation(): BelongsTo
    {
        return $this->belongsTo(Accommodation::class);
    }

    public function rates(): HasMany
    {
        return $this->hasMany(AccommodationRate::class);
    }
}
