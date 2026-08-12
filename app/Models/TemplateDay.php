<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TemplateDay extends Model
{
    protected $fillable = [
        'itinerary_template_id', 'day_number', 'date', 'title',
        'destination_id', 'hotel_id', 'hotel_name', 'room_type', 'meal_plan',
        'morning_activity', 'afternoon_activity', 'evening_activity',
        'description', 'destination_intro', 'image', 'wildlife_highlights',
        'included_services', 'optional_activities', 'notes', 'sort_order',
    ];

    public function template(): BelongsTo { return $this->belongsTo(ItineraryTemplate::class); }
    public function destination(): BelongsTo { return $this->belongsTo(Destination::class); }
    public function hotel(): BelongsTo { return $this->belongsTo(Hotel::class); }
    public function activities(): HasMany { return $this->hasMany(TemplateDayActivity::class)->orderBy('sort_order'); }
}
