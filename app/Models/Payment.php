<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'booking_id', 'reference', 'amount', 'currency', 'method',
        'gateway', 'gateway_session_id', 'gateway_response',
        'status', 'type', 'paid_at', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_at' => 'datetime',
            'gateway_response' => 'array',
        ];
    }

    public static array $methods = [
        'credit_card' => 'Credit Card',
        'bank_transfer' => 'Bank Transfer',
        'paypal' => 'PayPal',
        'wise' => 'Wise',
        'stripe' => 'Stripe',
        'flutterwave' => 'Flutterwave',
        'pesapal' => 'Pesapal',
        'mpesa' => 'Mpesa',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
