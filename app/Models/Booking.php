<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'reference', 'lead_id', 'client_id', 'status', 'start_date', 'end_date',
        'guests', 'total_amount', 'amount_paid', 'balance', 'currency',
        'payment_status', 'assigned_consultant_id', 'notes',
        'cancellation_policy_accepted', 'cancellation_accepted_at',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'total_amount' => 'decimal:2',
            'amount_paid' => 'decimal:2',
            'balance' => 'decimal:2',
            'cancellation_policy_accepted' => 'boolean',
            'cancellation_accepted_at' => 'datetime',
        ];
    }

    public static array $statuses = [
        'draft' => 'Draft',
        'quotation_sent' => 'Quotation Sent',
        'pending_deposit' => 'Pending Deposit',
        'deposit_paid' => 'Deposit Paid',
        'partially_paid' => 'Partially Paid',
        'fully_paid' => 'Fully Paid',
        'confirmed' => 'Confirmed',
        'cancelled' => 'Cancelled',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function consultant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_consultant_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(BookingItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function paymentLinks(): HasMany
    {
        return $this->hasMany(PaymentLink::class);
    }
}
