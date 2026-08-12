<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityPrice extends Model
{
    protected $fillable = [
        'activity_id', 'type', 'season', 'year', 'price', 'currency',
        'valid_from', 'valid_to',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'valid_from' => 'date',
            'valid_to' => 'date',
        ];
    }

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }
}
