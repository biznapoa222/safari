@extends('layouts.admin')
@section('title', 'Compare Accommodation Costs')
@section('content')
<div class="ops-page-heading">
    <div><p class="eyebrow">Rate intelligence</p><h1>Compare accommodation costs</h1><p>Compare room-specific buy-in and selling rates before selecting a hotel for a client.</p></div>
    <a class="button button-secondary" href="{{ route('admin.accommodations.index') }}"><i data-lucide="arrow-left"></i>Accommodations</a>
</div>
<section class="ops-panel">
    <form class="ops-filters" method="GET">
        <select name="location"><option value="">All locations</option>@foreach($locations as $location)<option @selected(request('location') === $location)>{{ $location }}</option>@endforeach</select>
        <input type="date" name="date" value="{{ request('date') }}">
        <button class="button button-primary">Compare available rates</button>
    </form>
    <div class="table-wrap ops-table-wrap">
        <table class="ops-table comparison-table"><thead><tr><th>Hotel</th><th>Location</th><th>Supplier</th><th>Guests</th><th>Room category</th><th>Season</th><th>Buy-in</th><th>Markup</th><th>Selling</th><th>Notes</th></tr></thead>
        <tbody>@forelse($rates as $rate)<tr><td><strong>{{ $rate->hotel_name }}</strong></td><td>{{ $rate->location }}<small>{{ $rate->country }}</small></td><td>{{ ucfirst($rate->supplier_type) }}</td><td><span class="guest-icons"><i data-lucide="users"></i>{{ $rate->max_adults + $rate->max_children }}</span></td><td>{{ $rate->room_name }}@if($rate->is_interconnecting)<small>Interconnecting</small>@endif</td><td>{{ $rate->season_name }}<small>{{ \Carbon\Carbon::parse($rate->valid_from)->format('d M') }} – {{ \Carbon\Carbon::parse($rate->valid_to)->format('d M Y') }}</small></td><td><span class="buy-price">{{ $rate->currency }} {{ number_format($rate->buy_rate, 2) }}</span></td><td>{{ number_format($rate->markup_percent, 1) }}%</td><td><strong class="sell-price">{{ $rate->currency }} {{ number_format($rate->sell_rate, 2) }}</strong></td><td class="notes-cell">{{ $rate->notes ?: '—' }}</td></tr>@empty<tr><td colspan="10" class="empty-cell">No rates match the selected date and location.</td></tr>@endforelse</tbody></table>
    </div>
</section>
@endsection
