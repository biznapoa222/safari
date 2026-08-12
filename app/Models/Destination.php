<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Destination extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'country', 'description', 'highlights', 'wildlife', 'climate',
        'best_time_to_visit', 'hero_image', 'gallery', 'activities_list', 'status',
    ];

    protected function casts(): array
    {
        return ['gallery' => 'array', 'status' => 'boolean'];
    }

    public function hotels(): HasMany { return $this->hasMany(Hotel::class); }
    public function activities(): HasMany { return $this->hasMany(Activity::class); }
}
