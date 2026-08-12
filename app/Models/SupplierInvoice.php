<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupplierInvoice extends Model
{
    protected $table = 'supplier_invoices';

    protected $fillable = [
        'quotation_id', 'reservation_id', 'uploaded_by', 'invoice_date', 'invoice_number',
        'company_name', 'amount', 'remaining_balance', 'currency', 'exchange_rate', 'invoice_type',
        'invoice_category', 'invoice_item_type', 'vat_rate', 'vat_amount', 'vat_reclaimable',
        'is_split_invoice', 'parent_invoice_id', 'comments', 'file_path', 'file_name',
        'payment_deadline', 'status', 'issue_notes', 'evaluated_by', 'evaluated_at',
    ];

    protected function casts(): array
    {
        return [
            'invoice_date' => 'date',
            'amount' => 'decimal:2',
            'remaining_balance' => 'decimal:2',
            'exchange_rate' => 'decimal:6',
            'vat_rate' => 'decimal:2',
            'vat_amount' => 'decimal:2',
            'vat_reclaimable' => 'boolean',
            'is_split_invoice' => 'boolean',
            'evaluated_at' => 'datetime',
            'payment_deadline' => 'date',
        ];
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluated_by');
    }

    public function parentInvoice(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_invoice_id');
    }

    public function splitInvoices(): HasMany
    {
        return $this->hasMany(self::class, 'parent_invoice_id');
    }

    public function evaluationEntries(): HasMany
    {
        return $this->hasMany(EvaluationEntry::class, 'supplier_invoice_id');
    }
}
