<?php

namespace App\Models;

use App\Support\MediaPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItineraryImage extends Model
{
    protected $fillable = [
        'itinerary_id', 'itinerary_day_id', 'path', 'caption', 'alt_text',
        'credit', 'sort_order', 'is_cover',
    ];

    protected function casts(): array
    {
        return ['is_cover' => 'boolean'];
    }

    public function itinerary(): BelongsTo
    {
        return $this->belongsTo(Itinerary::class);
    }

    public function day(): BelongsTo
    {
        return $this->belongsTo(ItineraryDay::class, 'itinerary_day_id');
    }

    public function getUrlAttribute(): ?string
    {
        return MediaPath::publicUrl($this->path);
    }
}
