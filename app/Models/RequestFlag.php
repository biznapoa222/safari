<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestFlag extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'request_id', 'user_id', 'color', 'note',
    ];

    public function request(): BelongsTo
    {
        return $this->belongsTo(Request::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
