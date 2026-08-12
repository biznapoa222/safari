<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'type', 'name', 'country', 'region', 'contact_person', 'phone',
        'email', 'website', 'gps_coordinates', 'classification', 'notes', 'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public static array $types = [
        'accommodation' => 'Accommodation',
        'activity' => 'Activity',
        'transport' => 'Transport',
        'airline' => 'Airline',
        'guide' => 'Guide',
        'transfer' => 'Transfer Company',
        'restaurant' => 'Restaurant',
        'charter' => 'Charter Company',
    ];

    public static array $classifications = [
        'accommodation' => ['luxury' => 'Luxury', 'premium' => 'Premium', 'mid_range' => 'Mid Range', 'budget' => 'Budget'],
        'transport' => ['land_cruiser' => 'Land Cruiser', 'van' => 'Van', 'bus' => 'Bus', 'helicopter' => 'Helicopter', 'aircraft' => 'Aircraft'],
        'activity' => ['game_drive' => 'Game Drive', 'cultural' => 'Cultural', 'adventure' => 'Adventure', 'marine' => 'Marine'],
    ];

    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class, 'activity_supplier');
    }
}
