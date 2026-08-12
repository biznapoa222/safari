<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentLink extends Model
{
    protected $fillable = [
        'booking_id', 'token', 'type', 'amount', 'currency',
        'gateway', 'is_used', 'expires_at', 'used_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'is_used' => 'boolean',
            'expires_at' => 'datetime',
            'used_at' => 'datetime',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
