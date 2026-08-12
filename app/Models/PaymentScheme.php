<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentScheme extends Model
{
    protected $fillable = [
        'schemeable_type', 'schemeable_id', 'deposit_percent',
        'full_payment_rules', 'cancellation_rules',
    ];

    protected function casts(): array
    {
        return ['deposit_percent' => 'decimal:2'];
    }

    public function schemeable()
    {
        return $this->morphTo();
    }
}
