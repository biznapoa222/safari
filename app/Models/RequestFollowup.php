<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestFollowup extends Model
{
    protected $fillable = [
        'request_id', 'user_id', 'title', 'description', 'followup_date',
        'status', 'reminder_type', 'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'followup_date' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function request(): BelongsTo
    {
        return $this->belongsTo(Request::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
