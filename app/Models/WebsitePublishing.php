<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsitePublishing extends Model
{
    protected $fillable = ['publishable_type', 'publishable_id', 'is_published', 'published_at'];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function publishable()
    {
        return $this->morphTo();
    }
}
