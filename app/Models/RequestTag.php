<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestTag extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'request_id', 'tag',
    ];

    public function request(): BelongsTo
    {
        return $this->belongsTo(Request::class);
    }
}
