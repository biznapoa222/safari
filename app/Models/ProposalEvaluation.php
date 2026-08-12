<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProposalEvaluation extends Model
{
    protected $table = 'proposal_evaluations';

    protected $fillable = [
        'quotation_id', 'reservation_officer_id', 'status', 'approved_by', 'evaluated_by',
        'approved_at', 'started_at', 'completed_at',
        'total_entries', 'matched_entries', 'missing_invoices', 'total_variance', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'total_entries' => 'integer',
            'matched_entries' => 'integer',
            'missing_invoices' => 'integer',
            'total_variance' => 'decimal:2',
        ];
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluated_by');
    }

    public function reservationOfficer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reservation_officer_id');
    }
}
