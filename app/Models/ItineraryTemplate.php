<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItineraryTemplate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'trip_name', 'destination_id', 'duration_days', 'category',
        'overview', 'highlights', 'includes', 'excludes', 'terms',
        'booking_terms', 'payment_schedule', 'cancellation_policy', 'refund_policy',
        'important_notes', 'notes', 'images', 'status',
    ];

    protected function casts(): array
    {
        return [
            'duration_days' => 'integer',
            'images' => 'array',
        ];
    }

    public function destination(): BelongsTo { return $this->belongsTo(Destination::class); }
    public function days(): HasMany { return $this->hasMany(TemplateDay::class)->orderBy('day_number'); }
    public function pricing(): HasMany { return $this->hasMany(TemplatePricing::class); }

    public static function categories(): array
    {
        return [
            'luxury' => 'Luxury',
            'midrange' => 'Midrange',
            'budget' => 'Budget',
            'camping' => 'Camping',
            'honeymoon' => 'Honeymoon',
            'family' => 'Family',
            'group' => 'Group',
            'private' => 'Private',
        ];
    }
}
