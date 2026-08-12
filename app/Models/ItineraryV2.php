<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItineraryV2 extends Model
{
    use SoftDeletes;

    protected $table = 'itineraries_v2';

    protected $fillable = [
        'title', 'slug', 'summary', 'duration_days', 'country', 'region',
        'price_from', 'currency', 'inclusions', 'exclusions', 'notes',
        'published', 'featured', 'images',
    ];

    protected function casts(): array
    {
        return [
            'price_from' => 'decimal:2',
            'published' => 'boolean',
            'featured' => 'boolean',
            'inclusions' => 'array',
            'exclusions' => 'array',
            'images' => 'array',
        ];
    }

    public function days(): HasMany
    {
        return $this->hasMany(ItineraryDayV2::class, 'itinerary_v2_id')->orderBy('sort_order');
    }
}
