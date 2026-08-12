<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsPage extends Model
{
    protected $fillable = [
        'title', 'slug', 'type', 'content', 'seo_title', 'seo_description',
        'cover_image', 'published', 'published_at',
    ];

    protected function casts(): array
    {
        return [
            'published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }
}
