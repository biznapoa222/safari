<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'email', 'phone', 'country', 'source', 'status',
        'assigned_consultant_id', 'notes', 'estimated_value', 'currency',
        'travel_date', 'travelers', 'destination', 'interests',
        'first_response_at', 'quotation_sent_at', 'booking_at',
    ];

    protected function casts(): array
    {
        return [
            'estimated_value' => 'decimal:2',
            'travel_date' => 'date',
            'first_response_at' => 'datetime',
            'quotation_sent_at' => 'datetime',
            'booking_at' => 'datetime',
        ];
    }

    public static array $sources = [
        'website' => 'Website',
        'whatsapp' => 'WhatsApp',
        'email' => 'Email',
        'referral' => 'Referral',
        'social_media' => 'Social Media',
    ];

    public static array $statuses = [
        'new' => 'New',
        'contacted' => 'Contacted',
        'proposal_sent' => 'Proposal Sent',
        'negotiating' => 'Negotiating',
        'confirmed' => 'Confirmed',
        'lost' => 'Lost',
    ];

    public function consultant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_consultant_id');
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
