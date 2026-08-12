<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hotel extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'destination_id', 'star_rating', 'tier', 'meal_plan',
        'description', 'amenities', 'hero_image', 'gallery', 'website', 'gps', 'rates', 'status',
    ];

    protected function casts(): array
    {
        return ['amenities' => 'array', 'gallery' => 'array', 'rates' => 'array', 'star_rating' => 'integer', 'status' => 'boolean'];
    }

    public function destination(): BelongsTo { return $this->belongsTo(Destination::class); }
}
