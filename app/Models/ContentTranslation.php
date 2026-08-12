<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContentTranslation extends Model
{
    protected $fillable = [
        'content_item_id', 'language_code', 'title', 'short_description', 'full_description',
        'seo_title', 'seo_description', 'slug', 'status', 'generated_at', 'upgraded_at',
    ];

    protected function casts(): array
    {
        return [
            'generated_at' => 'datetime',
            'upgraded_at' => 'datetime',
        ];
    }

    public function contentItem(): BelongsTo
    {
        return $this->belongsTo(ContentItem::class);
    }
}
