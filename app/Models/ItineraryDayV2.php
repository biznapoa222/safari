<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItineraryDayV2 extends Model
{
    protected $table = 'itinerary_days_v2';

    protected $fillable = [
        'itinerary_v2_id', 'day_number', 'title', 'location', 'accommodation_id',
        'activities', 'meal_plan', 'transfers', 'notes', 'sort_order',
    ];

    public function itinerary(): BelongsTo
    {
        return $this->belongsTo(ItineraryV2::class, 'itinerary_v2_id');
    }
}
