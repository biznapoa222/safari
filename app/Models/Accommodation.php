<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Accommodation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'type', 'country', 'region', 'category', 'description', 'notes',
        'website', 'phone', 'email', 'luxury_level', 'currency', 'published',
        'featured', 'images', 'metadata', 'status',
    ];

    protected function casts(): array
    {
        return [
            'published' => 'boolean',
            'featured' => 'boolean',
            'images' => 'array',
            'metadata' => 'array',
        ];
    }

    public static array $types = [
        'hotel' => 'Hotel', 'lodge' => 'Lodge', 'camp' => 'Camp',
        'luxury_camp' => 'Luxury Camp', 'villa' => 'Villa', 'resort' => 'Resort',
    ];

    public function rooms(): HasMany
    {
        return $this->hasMany(AccommodationRoom::class);
    }
}
