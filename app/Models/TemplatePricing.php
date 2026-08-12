<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TemplatePricing extends Model
{
    protected $table = 'template_pricing';

    protected $fillable = [
        'itinerary_template_id', 'currency', 'price_per_person',
        'single_supplement', 'total_cost', 'notes',
    ];

    protected function casts(): array
    {
        return ['price_per_person' => 'decimal:2', 'single_supplement' => 'decimal:2', 'total_cost' => 'decimal:2'];
    }

    public function template(): BelongsTo { return $this->belongsTo(ItineraryTemplate::class); }
}
