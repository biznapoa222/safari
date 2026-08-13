<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quotation extends Model
{
    protected $table = 'quotations';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'valid_until' => 'date',
            'duration_days' => 'integer',
            'guest_count' => 'integer',
            'office_markup_percent' => 'decimal:2',
            'misc_markup_percent' => 'decimal:2',
            'exchange_rate' => 'decimal:6',
            'buy_total' => 'decimal:2',
            'sell_total' => 'decimal:2',
            'margin_total' => 'decimal:2',
            'frozen' => 'boolean',
            'is_lms' => 'boolean',
            'is_mobile_sale' => 'boolean',
            'pre_confirmed_at' => 'datetime',
            'confirmation_date' => 'datetime',
            'cancellation_date' => 'datetime',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function request(): BelongsTo
    {
        return $this->belongsTo(Request::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
