<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class AvailabilityService
{
    public function roomAvailable(int $roomTypeId, string $startsAt, string $endsAt, int $quantity, ?int $ignoreReservation = null): bool
    {
        $inventory = (int) DB::table('room_types')->where('id', $roomTypeId)->value('inventory');
        $booked = DB::table('reservations')
            ->where('reservation_type', 'room')
            ->where('resource_id', $roomTypeId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->when($ignoreReservation, fn ($query) => $query->where('id', '!=', $ignoreReservation))
            ->where('starts_at', '<', $endsAt)
            ->where('ends_at', '>', $startsAt)
            ->sum('quantity');

        return ($booked + $quantity) <= $inventory;
    }

    public function vehicleAvailable(int $vehicleId, string $startsAt, string $endsAt, ?int $ignoreReservation = null): bool
    {
        return ! DB::table('reservations')
            ->where('reservation_type', 'vehicle')
            ->where('resource_id', $vehicleId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->when($ignoreReservation, fn ($query) => $query->where('id', '!=', $ignoreReservation))
            ->where('starts_at', '<', $endsAt)
            ->where('ends_at', '>', $startsAt)
            ->exists();
    }

    public function routeWarning(?string $from, ?string $to): ?string
    {
        if (! $from || ! $to || strcasecmp($from, $to) === 0) {
            return null;
        }

        $route = DB::table('route_distances')
            ->where(fn ($query) => $query
                ->where('from_location', $from)->where('to_location', $to))
            ->orWhere(fn ($query) => $query
                ->where('from_location', $to)->where('to_location', $from))
            ->first();

        if (! $route) {
            return "No verified route exists between {$from} and {$to}. Add the distance before planning this movement.";
        }

        if (! $route->same_day_allowed || $route->distance_km > 650 || $route->minimum_hours > 10) {
            return $route->warning ?: "The {$route->distance_km} km route requires {$route->minimum_hours} hours and is not realistic as a same-day safari movement.";
        }

        return null;
    }
}
