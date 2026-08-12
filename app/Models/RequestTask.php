<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestTask extends Model
{
    protected $fillable = [
        'request_id', 'title', 'description', 'assigned_to', 'deadline',
        'priority', 'status', 'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'date',
            'completed_at' => 'datetime',
        ];
    }

    public function request(): BelongsTo
    {
        return $this->belongsTo(Request::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Pending',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            default => ucfirst($this->status),
        };
    }

    public function getPriorityLabelAttribute(): string
    {
        return match ($this->priority) {
            'low' => 'Low',
            'medium' => 'Medium',
            'high' => 'High',
            'urgent' => 'Urgent',
            default => ucfirst($this->priority),
        };
    }

    public function getIsOverdueAttribute(): bool
    {
        if (!$this->deadline || $this->status === 'completed' || $this->status === 'cancelled') {
            return false;
        }

        return Carbon::parse($this->deadline)->isPast();
    }
}
