<?php

namespace App\Services;

use App\Models\Request;
use Illuminate\Database\Eloquent\Builder;

class RequestFilterService
{
    private Builder $query;

    public function apply(array $filters): Builder
    {
        $this->query = Request::query();

        $this->applySearch($filters)
            ->applyStatus($filters)
            ->applySource($filters)
            ->applyLanguage($filters)
            ->applyCompany($filters)
            ->applyAssignedTo($filters)
            ->applyDateRange($filters)
            ->applyFollowupDate($filters)
            ->applyCountry($filters)
            ->applyRequestTypes($filters)
            ->applyDestination($filters)
            ->applyAccommodationTier($filters)
            ->applyTravelType($filters)
            ->applyPriority($filters)
            ->applyRating($filters)
            ->applyIsDiamond($filters)
            ->applyFlagColor($filters);

        return $this->query;
    }

    private function applySearch(array $filters): static
    {
        if (empty($filters['search'])) {
            return $this;
        }

        $search = $filters['search'];
        $this->query->where(function (Builder $q) use ($search) {
            $q->where('request_number', 'like', "%{$search}%")
                ->orWhere('client_name', 'like', "%{$search}%")
                ->orWhere('client_email', 'like', "%{$search}%")
                ->orWhere('client_phone', 'like', "%{$search}%")
                ->orWhere('destination', 'like', "%{$search}%")
                ->orWhere('company', 'like', "%{$search}%")
                ->orWhere('country', 'like', "%{$search}%")
                ->orWhere('source', 'like', "%{$search}%")
                ->orWhere('language', 'like', "%{$search}%")
                ->orWhere('status', 'like', "%{$search}%")
                ->orWhereHas('assignedUser', fn (Builder $user) => $user->where('name', 'like', "%{$search}%"));
        });

        return $this;
    }

    private function applyStatus(array $filters): static
    {
        if (empty($filters['status'])) {
            return $this;
        }

        $statuses = explode(',', $filters['status']);
        $aliases = [
            'contacted' => ['contacted', 'quote_sent', 'first_follow_up', 'contact_phone_proposal_sent', 'contact_email', 'contact_whatsapp', 'follow_up_no_response', 'checking', 'converted'],
            'qualified' => ['qualified', 'preconfirmed'],
            'travelled' => ['travelled', 'operated', 'booked', 'completed'],
            'archived' => ['archived', 'dodo'],
        ];
        $statuses = collect($statuses)->flatMap(fn ($status) => $aliases[$status] ?? [$status])->unique()->values()->all();
        if (count($statuses) === 1) {
            $this->query->where('status', $statuses[0]);
        } else {
            $this->query->whereIn('status', $statuses);
        }

        return $this;
    }

    private function applySource(array $filters): static
    {
        if (!empty($filters['source'])) {
            $this->query->where('source', $filters['source']);
        }

        return $this;
    }

    private function applyLanguage(array $filters): static
    {
        if (!empty($filters['language'])) {
            $this->query->where('language', $filters['language']);
        }

        return $this;
    }

    private function applyCompany(array $filters): static
    {
        if (!empty($filters['company'])) {
            $this->query->where('company', 'like', "%{$filters['company']}%");
        }

        return $this;
    }

    private function applyAssignedTo(array $filters): static
    {
        if (!empty($filters['assigned_to'])) {
            $id = $filters['assigned_to'];
            $this->query->where(function (Builder $q) use ($id) {
                $q->where('assigned_to', $id)->orWhere('assigned_consultant_id', $id);
            });
        }

        return $this;
    }

    private function applyCountry(array $filters): static
    {
        if (!empty($filters['country'])) {
            $this->query->where('country', $filters['country']);
        }

        return $this;
    }

    private function applyRequestTypes(array $filters): static
    {
        $types = array_filter((array) ($filters['request_types'] ?? []));
        if (!$types) {
            return $this;
        }

        $this->query->where(function (Builder $query) use ($types) {
            foreach ($types as $type) {
                $query->orWhere(function (Builder $match) use ($type) {
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

        return $this;
    }

    private function applyDateRange(array $filters): static
    {
        if (!empty($filters['date_type'])) {
            $column = match ($filters['date_type']) {
                'arrival_date' => 'arrival_date',
                'follow_up_date' => 'follow_up_date',
                'created_date' => 'created_at',
                'travel_date' => 'arrival_date',
                default => 'created_at',
            };

            if (!empty($filters['date_from'])) {
                $this->query->whereDate($column, '>=', $filters['date_from']);
            }

            if (!empty($filters['date_to'])) {
                $this->query->whereDate($column, '<=', $filters['date_to']);
            }
        } else {
            if (!empty($filters['date_from'])) {
                $this->query->whereDate('created_at', '>=', $filters['date_from']);
            }

            if (!empty($filters['date_to'])) {
                $this->query->whereDate('created_at', '<=', $filters['date_to']);
            }

            if (!empty($filters['arrival_from'])) {
                $this->query->whereDate('arrival_date', '>=', $filters['arrival_from']);
            }

            if (!empty($filters['arrival_to'])) {
                $this->query->whereDate('arrival_date', '<=', $filters['arrival_to']);
            }
        }

        return $this;
    }

    private function applyFollowupDate(array $filters): static
    {
        if (!empty($filters['followup_from'])) {
            $this->query->whereHas('followups', fn (Builder $query) => $query->whereDate('followup_date', '>=', $filters['followup_from']));
        }

        if (!empty($filters['followup_to'])) {
            $this->query->whereHas('followups', fn (Builder $query) => $query->whereDate('followup_date', '<=', $filters['followup_to']));
        }

        return $this;
    }

    private function applyDestination(array $filters): static
    {
        if (!empty($filters['destination'])) {
            $this->query->where('destination', 'like', "%{$filters['destination']}%");
        }

        return $this;
    }

    private function applyAccommodationTier(array $filters): static
    {
        if (!empty($filters['accommodation_tier'])) {
            $this->query->where('accommodation_tier', $filters['accommodation_tier']);
        }

        return $this;
    }

    private function applyTravelType(array $filters): static
    {
        if (!empty($filters['travel_type'])) {
            $this->query->where('travel_type', $filters['travel_type']);
        }

        return $this;
    }

    private function applyPriority(array $filters): static
    {
        if (!empty($filters['priority'])) {
            $this->query->where('priority', $filters['priority']);
        }

        return $this;
    }

    private function applyRating(array $filters): static
    {
        if (!empty($filters['rating'])) {
            $this->query->where('rating', $filters['rating']);
        }

        return $this;
    }

    private function applyIsDiamond(array $filters): static
    {
        if (isset($filters['is_diamond']) && $filters['is_diamond'] !== '') {
            $this->query->where('is_diamond', filter_var($filters['is_diamond'], FILTER_VALIDATE_BOOLEAN));
        }

        return $this;
    }

    private function applyFlagColor(array $filters): static
    {
        if (!empty($filters['flag_color'])) {
            $this->query->where('flag_color', $filters['flag_color']);
        }

        return $this;
    }
}
