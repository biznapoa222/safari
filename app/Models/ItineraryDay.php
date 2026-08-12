<?php

namespace App\Models;

use App\Support\MediaPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ItineraryDay extends Model
{
    protected $fillable = [
        'itinerary_id', 'day_number', 'title', 'location', 'accommodation', 'meal_plan',
        'distance_km', 'driving_hours', 'summary', 'description', 'activities',
        'overnight', 'primary_image',
    ];

    protected function casts(): array
    {
        return [
            'activities' => 'array',
            'driving_hours' => 'decimal:2',
        ];
    }

    public function itinerary(): BelongsTo
    {
        return $this->belongsTo(Itinerary::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ItineraryImage::class)->orderBy('sort_order');
    }

    public function getPrimaryImageUrlAttribute(): ?string
    {
        return MediaPath::publicUrl($this->primary_image);
    }
}
