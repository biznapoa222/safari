<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Request extends Model
{
    use SoftDeletes;

    protected $table = 'requests';

    protected $fillable = [
        'request_number', 'request_date', 'client_id', 'client_name',
        'client_email', 'client_phone', 'nationality', 'country',
        'adults', 'children', 'infants', 'arrival_date', 'departure_date',
        'nights', 'destination', 'budget', 'accommodation_tier', 'travel_type',
        'source', 'language', 'priority', 'status', 'rating', 'is_diamond',
        'flag_color', 'assigned_to', 'assigned_consultant_id', 'company',
        'internal_notes', 'special_requests', 'seller_notes', 'quote_value',
        'currency', 'transport', 'flight_required', 'pickup_required',
        'guide_required', 'visa_required', 'insurance_required',
        'converted_to_quote_at', 'converted_to_quote_id',
        'cancelled_at', 'cancelled_reason', 'itinerary_template_id',
    ];

    protected function casts(): array
    {
        return [
            'request_date' => 'date',
            'arrival_date' => 'date',
            'departure_date' => 'date',
            'converted_to_quote_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'is_diamond' => 'boolean',
            'flight_required' => 'boolean',
            'pickup_required' => 'boolean',
            'guide_required' => 'boolean',
            'visa_required' => 'boolean',
            'insurance_required' => 'boolean',
            'budget' => 'decimal:2',
            'quote_value' => 'decimal:2',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function consultant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_consultant_id');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(RequestNote::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(RequestTask::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(RequestFile::class);
    }

    public function history(): HasMany
    {
        return $this->hasMany(RequestHistory::class);
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(RequestStatusLog::class);
    }

    public function followups(): HasMany
    {
        return $this->hasMany(RequestFollowup::class);
    }

    public function flags(): HasMany
    {
        return $this->hasMany(RequestFlag::class);
    }

    public function itineraryTemplate(): BelongsTo
    {
        return $this->belongsTo(ItineraryTemplate::class, 'itinerary_template_id');
    }

    public function tags(): HasMany
    {
        return $this->hasMany(RequestTag::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return static::statusOptions()[$this->status] ?? ucfirst(str_replace('_', ' ', $this->status));
    }

    public static function statusOptions(): array
    {
        return [
            'new' => 'New',
            'quote_sent' => 'Proposal Sent/Awaiting Feedback',
            'first_follow_up' => '1st Follow up sent',
            'contact_phone_proposal_sent' => 'Contact(Phone) - Proposal Sent',
            'contact_email' => 'Contact(Email)',
            'contact_whatsapp' => 'Contact(WhatsApp)',
            'follow_up_no_response' => 'Followed up via Phone, WhatsApp & Email - No response yet',
            'checking' => 'No customer contact, just checking something',
            'cancelled' => 'Cancelled',
            'confirmed' => 'Confirmed',
            'travelled' => 'Operated',
            'preconfirmed' => 'Preconfirmed',
            'archived' => 'Dodo',
        ];
    }

    public static function workspaceStatusOptions(): array
    {
        return [
            'new' => 'New',
            'existing' => 'Existing',
            'preconfirmed' => 'Pre-Confirmed',
            'confirmed' => 'Confirmed',
            'operated' => 'Operated',
            'cancelled' => 'Cancelled',
            'dodo' => 'DODO',
        ];
    }

    public static function storedStatusForWorkspace(string $status): string
    {
        return match ($status) {
            'existing' => 'contacted',
            'preconfirmed' => 'qualified',
            'operated' => 'travelled',
            'dodo' => 'archived',
            default => $status,
        };
    }

    public function workspaceStatus(): string
    {
        return match ($this->status) {
            'contacted', 'quote_sent', 'first_follow_up', 'contact_phone_proposal_sent', 'contact_email', 'contact_whatsapp', 'follow_up_no_response', 'checking', 'converted' => 'existing',
            'qualified', 'preconfirmed' => 'preconfirmed',
            'travelled', 'operated', 'completed', 'booked' => 'operated',
            'archived', 'dodo' => 'dodo',
            default => $this->status,
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

    public function getAccommodationTierLabelAttribute(): string
    {
        return match ($this->accommodation_tier) {
            'luxury' => 'Luxury',
            'midrange' => 'Midrange',
            'budget' => 'Budget',
            'camping' => 'Camping',
            default => $this->accommodation_tier ? ucfirst($this->accommodation_tier) : null,
        };
    }

    public function getSourceLabelAttribute(): string
    {
        return match ($this->source) {
            'manual' => 'Manual',
            'website' => 'Website',
            'whatsapp' => 'WhatsApp',
            'email' => 'Email',
            'walk_in' => 'Walk In',
            'api' => 'API',
            default => $this->source ? ucfirst($this->source) : null,
        };
    }

    public function getInitialsAttribute(): string
    {
        return collect(explode(' ', $this->client_name))
            ->filter()
            ->take(2)
            ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
            ->implode('');
    }

    public function scopeFilter($query, array $filters)
    {
        return $query->when($filters['search'] ?? null, fn ($q, $search) => $q->where(function ($q) use ($search) {
            $q->where('client_name', 'like', "%{$search}%")
                ->orWhere('client_email', 'like', "%{$search}%")
                ->orWhere('client_phone', 'like', "%{$search}%")
                ->orWhere('request_number', 'like', "%{$search}%")
                ->orWhere('destination', 'like', "%{$search}%")
                ->orWhere('country', 'like', "%{$search}%")
                ->orWhere('source', 'like', "%{$search}%")
                ->orWhere('language', 'like', "%{$search}%")
                ->orWhere('status', 'like', "%{$search}%")
                ->orWhereHas('assignedUser', fn ($user) => $user->where('name', 'like', "%{$search}%"));
        }))
        ->when($filters['status'] ?? null, function ($q, $status) {
            $aliases = [
                'contacted' => ['contacted', 'quote_sent', 'first_follow_up', 'contact_phone_proposal_sent', 'contact_email', 'contact_whatsapp', 'follow_up_no_response', 'checking', 'converted'],
                'qualified' => ['qualified', 'preconfirmed'],
                'travelled' => ['travelled', 'operated', 'booked', 'completed'],
                'archived' => ['archived', 'dodo'],
            ];
            return $q->whereIn('status', $aliases[$status] ?? [$status]);
        })
        ->when($filters['priority'] ?? null, fn ($q, $priority) => $q->where('priority', $priority))
        ->when($filters['source'] ?? null, fn ($q, $source) => $q->where('source', $source))
        ->when($filters['assigned_to'] ?? null, fn ($q, $id) => $q->where(function ($q) use ($id) {
            $q->where('assigned_to', $id)->orWhere('assigned_consultant_id', $id);
        }))
        ->when($filters['travel_type'] ?? null, fn ($q, $type) => $q->where('travel_type', $type))
        ->when($filters['country'] ?? null, fn ($q, $country) => $q->where('country', $country))
        ->when($filters['request_types'] ?? null, function ($q, $types) {
            $q->where(function ($typeQuery) use ($types) {
                foreach ((array) $types as $type) {
                    $typeQuery->orWhere(function ($match) use ($type) {
                        match ($type) {
                            'itinerary' => $match->whereNotNull('itinerary_template_id'),
                            'custom' => $match->whereNull('itinerary_template_id')->whereIn('source', ['website', 'email']),
                            'manual' => $match->where('source', 'manual'),
                            'group' => $match->where('travel_type', 'group'),
                            default => $match->whereRaw('1 = 0'),
                        };
                    });
                }
            });
        })
        ->when($filters['accommodation_tier'] ?? null, fn ($q, $tier) => $q->where('accommodation_tier', $tier))
        ->when($filters['date_from'] ?? null, fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
        ->when($filters['date_to'] ?? null, fn ($q, $date) => $q->whereDate('created_at', '<=', $date))
        ->when($filters['arrival_from'] ?? null, fn ($q, $date) => $q->whereDate('arrival_date', '>=', $date))
        ->when($filters['arrival_to'] ?? null, fn ($q, $date) => $q->whereDate('arrival_date', '<=', $date))
        ->when($filters['followup_from'] ?? null, fn ($q, $date) => $q->whereHas('followups', fn ($followup) => $followup->whereDate('followup_date', '>=', $date)))
        ->when($filters['followup_to'] ?? null, fn ($q, $date) => $q->whereHas('followups', fn ($followup) => $followup->whereDate('followup_date', '<=', $date)))
        ->when($filters['client_id'] ?? null, fn ($q, $id) => $q->where('client_id', $id))
        ->when($filters['is_diamond'] ?? null, fn ($q, $val) => $q->where('is_diamond', $val))
        ->when($filters['flag_color'] ?? null, fn ($q, $color) => $q->where('flag_color', $color));
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public static function generateRequestNumber(): string
    {
        return 'REQ-'.now()->format('YmdHis');
    }

    public function logStatus(?string $from, string $to, ?string $notes = null): RequestStatusLog
    {
        return $this->statusLogs()->create([
            'user_id' => Auth::id(),
            'from_status' => $from,
            'to_status' => $to,
            'notes' => $notes,
        ]);
    }

    public function logHistory(string $field, ?string $oldValue, ?string $newValue, ?string $description = null): RequestHistory
    {
        return $this->history()->create([
            'user_id' => Auth::id(),
            'field' => $field,
            'old_value' => $oldValue,
            'new_value' => $newValue,
            'description' => $description ?? "Changed {$field}",
            'created_at' => now(),
        ]);
    }
}
