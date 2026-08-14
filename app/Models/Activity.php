<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'destination_id', 'description', 'duration',
        'is_included', 'price', 'currency', 'image', 'images', 'status',
        'country', 'region', 'location', 'activity_category_id',
        'published_on_website', 'min_pax', 'min_age', 'duration_hours',
    ];

    protected function casts(): array
    {
        return [
            'is_included' => 'boolean',
            'price' => 'decimal:2',
            'status' => 'boolean',
            'images' => 'array',
            'published_on_website' => 'boolean',
        ];
    }

    public function destination(): BelongsTo { return $this->belongsTo(Destination::class); }
    public function category(): BelongsTo { return $this->belongsTo(ActivityCategory::class, 'activity_category_id'); }
    public function prices(): HasMany { return $this->hasMany(ActivityPrice::class); }
    public function seasons(): HasMany { return $this->hasMany(ActivitySeason::class); }

    public function translations(): HasMany
    {
        return $this->hasMany(ActivityTranslation::class);
    }

    public function suppliers(): BelongsToMany
    {
        return $this->belongsToMany(Supplier::class, 'activity_supplier');
    }

    public function paymentScheme(): MorphOne
    {
        return $this->morphOne(PaymentScheme::class, 'schemeable');
    }

    public function translation(?string $locale = null): ?ActivityTranslation
    {
        $locale ??= app()->getLocale();

        return $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', 'en');
    }
}
