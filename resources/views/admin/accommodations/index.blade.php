@extends('layouts.admin')
@section('title', 'Accommodations')

@section('content')
<x-admin.top-bar
    title="Accommodations"
    description="Accommodation directory"
    addLabel="New Accommodation"
    addRoute="{{ route('admin.accommodations.create') }}"
    searchPlaceholder="Search accommodations..."
/>
@include('admin.partials.flash')

<section class="ops-panel">
    <form class="ops-filters" method="GET">
        <label class="ops-search"><i data-lucide="search"></i><input name="search" value="{{ request('search') }}" placeholder="Search hotel, destination or country"></label>
        <select name="country"><option value="">All countries</option>@foreach($countries as $country)<option @selected(request('country') === $country)>{{ $country }}</option>@endforeach</select>
        <button class="button button-primary">Search</button>
        <a class="button button-secondary" href="{{ route('admin.accommodations.index') }}">Reset</a>
    </form>
    <div class="country-tabs">
        <a class="{{ !request('country') ? 'is-active' : '' }}" href="{{ route('admin.accommodations.index') }}">All</a>
        @foreach($countries as $country)<a class="{{ request('country') === $country ? 'is-active' : '' }}" href="{{ route('admin.accommodations.index', ['country' => $country]) }}">{{ $country }}</a>@endforeach
    </div>
    <div class="table-wrap ops-table-wrap">
        <table class="ops-table">
            <thead><tr><th>Accommodation</th><th>Destination</th><th>Translations</th><th>Tier</th><th>Stars</th><th>Rooms</th><th>Rates</th><th>Status</th><th>Website</th><th>Actions</th></tr></thead>
            <tbody>
            @forelse($hotels as $hotel)
                <tr>
                    <td><strong>{{ $hotel->name }}</strong><small>{{ $hotel->reservation_email }}</small></td>
                    <td>{{ $hotel->destination_name }}<small>{{ $hotel->country }}</small></td>
                    <td><div class="translation-badges compact">@foreach(config('safari.languages') as $code => $lang)<span class="{{ in_array($code, $hotel->translations) ? 'complete' : '' }}">{{ $lang['badge'] }}</span>@endforeach</div></td>
                    <td><span class="ops-pill ops-pill--blue">{{ $hotel->tier ? ucfirst($hotel->tier) : 'Standard' }}</span></td>
                    <td>{{ $hotel->star_rating ? $hotel->star_rating.' star' : '—' }}</td>
                    <td><strong>{{ $hotel->room_count }}</strong><small>room types</small></td>
                    <td><span class="ops-pill {{ $hotel->rate_count ? 'ops-pill--green' : 'ops-pill--red' }}">{{ $hotel->rate_count ? 'Complete' : 'Missing' }}</span></td>
                    <td><span class="ops-pill {{ $hotel->status ? 'ops-pill--green' : 'ops-pill--red' }}">{{ $hotel->status ? 'Active' : 'Inactive' }}</span></td>
                    <td>@if($hotel->website)<a href="{{ $hotel->website }}" target="_blank" rel="noopener">Open site</a>@else — @endif</td>
                    <td><div class="ops-actions"><a title="Edit" href="{{ route('admin.accommodations.edit', $hotel->id) }}"><i data-lucide="square-pen"></i></a><form method="POST" action="{{ route('admin.accommodations.destroy', $hotel->id) }}" onsubmit="return confirm('Delete this accommodation and all its rates?')">@csrf @method('DELETE')<button title="Delete"><i data-lucide="trash-2"></i></button></form></div></td>
                </tr>
            @empty
                <tr><td colspan="10" class="empty-cell">No accommodations match this filter.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="ops-pagination">{{ $hotels->links() }}</div>
</section>
@endsection
