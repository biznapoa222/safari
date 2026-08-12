<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContentItem extends Model
{
    protected $fillable = [
        'type', 'name', 'country', 'location', 'status', 'price_from', 'rating',
        'duration_days', 'featured', 'published_at', 'metadata',
    ];

    protected function casts(): array
    {
        return [
            'featured' => 'boolean',
            'published_at' => 'datetime',
            'metadata' => 'array',
            'price_from' => 'decimal:2',
        ];
    }

    public function translations(): HasMany
    {
        return $this->hasMany(ContentTranslation::class);
    }

    public function translation(?string $locale = null): ?ContentTranslation
    {
        $locale ??= app()->getLocale();

        return $this->translations->firstWhere('language_code', $locale)
            ?? $this->translations->firstWhere('language_code', 'en');
    }

    public function translationCompleteness(): int
    {
        return (int) round(($this->translations->count() / count(config('safari.languages'))) * 100);
    }
}
