@extends('layouts.admin')
@section('title', 'Flight Ticket Bookings')
@section('content')
<x-admin.top-bar
    title="Flight Bookings"
    description="Aviation"
    addLabel="New Flight Booking"
    addRoute="{{ route('admin.flights.store') }}"
    searchPlaceholder="Search flights..."
/>
@include('admin.partials.flash')
<div class="ops-two-column flight-layout">
<section class="ops-panel ops-form-panel">
    <div class="ops-panel-title"><div><h2>{{ $editing ? 'Edit flight booking' : 'New flight request' }}</h2><p>Use exact details from the airline reservation.</p></div></div>
    <form method="POST" action="{{ $editing ? route('admin.flights.update', $editing->id) : route('admin.flights.store') }}">@csrf @if($editing) @method('PUT') @endif
        <div class="ops-form-grid">
            <label class="span-2">Client<select name="client_id"><option value="">Walk-in / not linked</option>@foreach($clients as $client)<option value="{{ $client->id }}" @selected(old('client_id', $editing->client_id ?? '') == $client->id)>{{ $client->name }} — {{ $client->email }}</option>@endforeach</select></label>
            <label>Passenger name<input name="passenger_name" value="{{ old('passenger_name', $editing->passenger_name ?? '') }}" required></label>
            <label>Passenger type<select name="passenger_type">@foreach(['adult','child','infant'] as $value)<option @selected(old('passenger_type', $editing->passenger_type ?? 'adult') === $value)>{{ $value }}</option>@endforeach</select></label>
            <label>Passport number<input name="passport_number" value="{{ old('passport_number', $editing->passport_number ?? '') }}"></label>
            <label>Airline<input name="airline" value="{{ old('airline', $editing->airline ?? '') }}" placeholder="Kenya Airways" required></label>
            <label>Flight number<input name="flight_number" value="{{ old('flight_number', $editing->flight_number ?? '') }}" placeholder="KQ 482" required></label>
            <label>Flight type<select name="flight_type">@foreach(['domestic','international','charter'] as $value)<option @selected(old('flight_type', $editing->flight_type ?? request('type', 'domestic')) === $value)>{{ $value }}</option>@endforeach</select></label>
            <label>Cabin class<select name="cabin_class">@foreach(['economy','premium_economy','business','first'] as $value)<option value="{{ $value }}" @selected(old('cabin_class', $editing->cabin_class ?? 'economy') === $value)>{{ ucwords(str_replace('_', ' ', $value)) }}</option>@endforeach</select></label>
            <label>Origin IATA<input name="origin_code" maxlength="3" value="{{ old('origin_code', $editing->origin_code ?? 'NBO') }}" required></label>
            <label>Destination IATA<input name="destination_code" maxlength="3" value="{{ old('destination_code', $editing->destination_code ?? '') }}" required></label>
            <label>Departure<input type="datetime-local" name="departure_at" value="{{ old('departure_at', isset($editing) && $editing ? \Carbon\Carbon::parse($editing->departure_at)->format('Y-m-d\TH:i') : '') }}" required></label>
            <label>Arrival<input type="datetime-local" name="arrival_at" value="{{ old('arrival_at', isset($editing) && $editing ? \Carbon\Carbon::parse($editing->arrival_at)->format('Y-m-d\TH:i') : '') }}" required></label>
            <label>PNR / locator<input name="pnr" value="{{ old('pnr', $editing->pnr ?? '') }}"></label>
            <label>Ticket number<input name="ticket_number" value="{{ old('ticket_number', $editing->ticket_number ?? '') }}"></label>
            <label>Baggage allowance<input name="baggage_allowance" value="{{ old('baggage_allowance', $editing->baggage_allowance ?? '23kg checked + 7kg cabin') }}"></label>
            <label>Supplier / consolidator<input name="supplier" value="{{ old('supplier', $editing->supplier ?? '') }}"></label>
            <label>Base fare<input type="number" step="0.01" name="base_fare" value="{{ old('base_fare', $editing->base_fare ?? '') }}" required></label>
            <label>Taxes & fees<input type="number" step="0.01" name="taxes" value="{{ old('taxes', $editing->taxes ?? 0) }}" required></label>
            <label>Markup %<input type="number" step="0.01" name="markup_percent" value="{{ old('markup_percent', $editing->markup_percent ?? 10) }}" required></label>
            <label>Currency<input name="currency" maxlength="3" value="{{ old('currency', $editing->currency ?? 'USD') }}" required></label>
            <label>Payment deadline<input type="date" name="payment_deadline" value="{{ old('payment_deadline', $editing->payment_deadline ?? '') }}"></label>
            <label>Payment status<select name="payment_status">@foreach(['unpaid','part_paid','paid','refunded'] as $value)<option value="{{ $value }}" @selected(old('payment_status', $editing->payment_status ?? 'unpaid') === $value)>{{ ucwords(str_replace('_', ' ', $value)) }}</option>@endforeach</select></label>
            <label>Booking status<select name="booking_status">@foreach(['requested','on_hold','confirmed','ticketed','cancelled','completed'] as $value)<option value="{{ $value }}" @selected(old('booking_status', $editing->booking_status ?? 'requested') === $value)>{{ ucwords(str_replace('_', ' ', $value)) }}</option>@endforeach</select></label>
            <label class="span-2">Ticketing notes<textarea name="notes" rows="4">{{ old('notes', $editing->notes ?? '') }}</textarea></label>
        </div>
        <div class="ops-form-footer">@if($editing)<a class="button button-secondary" href="{{ route('admin.flights.index') }}">Cancel</a>@endif<button class="button button-primary"><i data-lucide="save"></i>{{ $editing ? 'Update booking' : 'Create request' }}</button></div>
    </form>
</section>
<section class="ops-panel">
    <form class="ops-filters" method="GET"><label class="ops-search"><i data-lucide="search"></i><input name="search" value="{{ request('search') }}" placeholder="Passenger, PNR, ticket or flight"></label><select name="type"><option value="">All flight types</option>@foreach(['domestic','international','charter'] as $type)<option @selected(request('type') === $type)>{{ $type }}</option>@endforeach</select><select name="status"><option value="">All statuses</option>@foreach(['requested','on_hold','confirmed','ticketed','cancelled','completed'] as $status)<option @selected(request('status') === $status)>{{ $status }}</option>@endforeach</select><button class="button button-primary">Filter</button></form>
    <div class="table-wrap"><table class="ops-table"><thead><tr><th>Request / passenger</th><th>Flight</th><th>Schedule</th><th>PNR / ticket</th><th>Fare</th><th>Selling</th><th>Payment</th><th>Status</th><th>Actions</th></tr></thead><tbody>
    @forelse($flights as $flight)<tr><td><strong>{{ $flight->request_reference }}</strong><small>{{ $flight->passenger_name }} · {{ ucfirst($flight->passenger_type) }} · {{ $flight->client_name }}</small></td><td><strong>{{ $flight->airline }} {{ $flight->flight_number }}</strong><small>{{ $flight->origin_code }} → {{ $flight->destination_code }} · {{ ucwords(str_replace('_', ' ', $flight->cabin_class)) }}</small></td><td>{{ \Carbon\Carbon::parse($flight->departure_at)->format('d M Y H:i') }}<small>Arrive {{ \Carbon\Carbon::parse($flight->arrival_at)->format('d M H:i') }}</small></td><td>{{ $flight->pnr ?: 'Not assigned' }}<small>{{ $flight->ticket_number ?: 'Not ticketed' }}</small></td><td><span class="buy-price">{{ $flight->currency }} {{ number_format($flight->base_fare + $flight->taxes, 2) }}</span><small>{{ number_format($flight->markup_percent, 1) }}% markup</small></td><td><strong class="sell-price">{{ $flight->currency }} {{ number_format($flight->selling_total, 2) }}</strong></td><td><span class="ops-pill {{ $flight->payment_status === 'paid' ? 'ops-pill--green' : 'ops-pill--red' }}">{{ ucwords(str_replace('_', ' ', $flight->payment_status)) }}</span></td><td><span class="ops-pill ops-pill--blue">{{ ucwords(str_replace('_', ' ', $flight->booking_status)) }}</span></td><td><div class="ops-actions"><a href="{{ route('admin.flights.index', ['edit' => $flight->id]) }}"><i data-lucide="square-pen"></i></a><form method="POST" action="{{ route('admin.flights.destroy', $flight->id) }}" onsubmit="return confirm('Delete this flight booking?')">@csrf @method('DELETE')<button><i data-lucide="trash-2"></i></button></form></div></td></tr>@empty<tr><td colspan="9" class="empty-cell">No flight bookings match this filter.</td></tr>@endforelse
    </tbody></table></div><div class="ops-pagination">{{ $flights->links() }}</div>
</section>
</div>
@endsection
