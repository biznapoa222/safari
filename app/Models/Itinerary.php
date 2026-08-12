<?php

namespace App\Models;

use App\Support\MediaPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Itinerary extends Model
{
    protected $fillable = [
        'code', 'title', 'slug', 'countries', 'summary', 'description', 'duration_days',
        'nights', 'minimum_guests', 'maximum_guests', 'price_from', 'currency',
        'travel_style', 'difficulty', 'start_location', 'end_location', 'best_time',
        'accommodation_level', 'status', 'featured', 'cover_image', 'inclusions',
        'exclusions', 'important_notes', 'seo_title', 'seo_description', 'published_at',
    ];

    protected function casts(): array
    {
        return [
            'featured' => 'boolean',
            'price_from' => 'decimal:2',
            'inclusions' => 'array',
            'exclusions' => 'array',
            'published_at' => 'datetime',
        ];
    }

    public function days(): HasMany
    {
        return $this->hasMany(ItineraryDay::class)->orderBy('day_number');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ItineraryImage::class)->orderBy('sort_order');
    }

    public function getCoverImageUrlAttribute(): ?string
    {
        return MediaPath::publicUrl($this->cover_image);
    }
}
