<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluationEntry extends Model
{
    protected $table = 'evaluation_entries';

    protected $fillable = [
        'quotation_id', 'quotation_day_id', 'quotation_item_id', 'reservation_id',
        'supplier_invoice_id', 'evaluated_by', 'item_type', 'title', 'supplier', 'supplier_name',
        'service_date', 'service_end_date', 'arrival_date', 'departure_date',
        'quantity', 'number_of_rooms', 'number_of_nights', 'adults', 'children',
        'system_rate', 'invoice_rate', 'allocated_amount', 'discrepancy', 'variance_percent',
        'is_overcharge', 'is_undercharge', 'is_mismatch', 'is_duplicate_check',
        'meal_plan', 'room_configuration', 'room_type',
        'rate_matches', 'dates_match', 'meal_plan_matches',
        'room_configuration_matches', 'room_type_matches',
        'status', 'issue_notes', 'evaluated_at',
    ];

    protected function casts(): array
    {
        return [
            'service_date' => 'date',
            'service_end_date' => 'date',
            'arrival_date' => 'date',
            'departure_date' => 'date',
            'system_rate' => 'decimal:2',
            'invoice_rate' => 'decimal:2',
            'allocated_amount' => 'decimal:2',
            'discrepancy' => 'decimal:2',
            'variance_percent' => 'decimal:2',
            'quantity' => 'decimal:2',
            'rate_matches' => 'boolean',
            'dates_match' => 'boolean',
            'meal_plan_matches' => 'boolean',
            'room_configuration_matches' => 'boolean',
            'room_type_matches' => 'boolean',
            'is_overcharge' => 'boolean',
            'is_undercharge' => 'boolean',
            'is_mismatch' => 'boolean',
            'is_duplicate_check' => 'boolean',
            'evaluated_at' => 'datetime',
        ];
    }

    public function supplierInvoice(): BelongsTo
    {
        return $this->belongsTo(SupplierInvoice::class);
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluated_by');
    }
}
