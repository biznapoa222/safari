<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestFile extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'request_id', 'user_id', 'filename', 'original_name', 'file_path',
        'file_type', 'file_size', 'category', 'notes',
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
