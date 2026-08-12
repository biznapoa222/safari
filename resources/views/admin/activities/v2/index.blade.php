@extends('layouts.admin')
@section('title', __('catalogue.title'))
@section('content')
<x-admin.top-bar
    :title="__('catalogue.title')"
    :description="__('catalogue.description')"
    :addLabel="__('catalogue.new')"
    addRoute="{{ route('admin.activities.create') }}"
    :searchPlaceholder="__('catalogue.search_placeholder')"
/>

@include('admin.partials.flash')

{{-- Country Tabs --}}
<div class="country-tabs">
    @php $countries = ['Kenya', 'Tanzania', 'Uganda', 'South Africa', 'Namibia', 'Botswana']; @endphp
    <a href="{{ route('admin.activities.index') }}" class="{{ !request('country') ? 'is-active' : '' }}">{{ __('catalogue.all') }}</a>
    @foreach($countries as $c)
        <a href="{{ route('admin.activities.index', array_merge(request()->query(), ['country' => $c])) }}"
           class="{{ request('country') === $c ? 'is-active' : '' }}">{{ $c }}</a>
    @endforeach
</div>

{{-- Search & Filters --}}
<form class="ops-filters" method="GET">
    <label class="ops-search">
        <i data-lucide="search"></i>
        <input name="search" value="{{ request('search') }}" placeholder="{{ __('catalogue.search_placeholder') }}">
    </label>
    <select name="status" onchange="this.form.submit()">
        <option value="">{{ __('catalogue.all_statuses') }}</option>
        <option value="active" @selected(request('status') === 'active')>{{ __('catalogue.active') }}</option>
        <option value="inactive" @selected(request('status') === 'inactive')>{{ __('catalogue.inactive') }}</option>
    </select>
    <button class="button button-primary">{{ __('catalogue.search') }}</button>
</form>

{{-- Activities Table --}}
<div class="table-wrap">
    <table class="ops-table activity-table">
        <thead>
            <tr>
                <th>{{ __('catalogue.activity_title') }} ({{ strtoupper(app()->getLocale()) }})</th>
                <th>{{ __('catalogue.min_pax') }}</th><th>{{ __('catalogue.min_age') }}</th><th>{{ __('catalogue.location') }}</th>
                <th>{{ __('catalogue.price_cy') }}</th><th>{{ __('catalogue.price_ny') }}</th><th>{{ __('catalogue.payment_scheme') }}</th>
                <th>{{ __('catalogue.status') }}</th><th>{{ __('catalogue.currency') }}</th><th>{{ __('catalogue.published') }}</th><th>{{ __('catalogue.mobile_app') }}</th><th>{{ __('catalogue.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($activities as $activity)
            @php $localized=$activity->translation(app()->getLocale()); @endphp
            <tr>
                <td><strong>{{ $localized?->title ?? $activity->name }}</strong>@if($localized?->description)<small>{{ \Illuminate\Support\Str::limit($localized->description,70) }}</small>@endif</td>
                <td>{{ $activity->min_pax ?? '-' }}</td>
                <td>{{ $activity->min_age ?? '-' }}</td>
                <td>{{ $localized?->location ?? $activity->location }}<small>{{ ($localized?->region ?? $activity->region) ? ', '.($localized?->region ?? $activity->region) : '' }}</small></td>
                <td>{{ $activity->price_status_current_year ?? '-' }}</td>
                <td>{{ $activity->price_status_next_year ?? '-' }}</td>
                <td>{{ $activity->payment_scheme_status ?? '-' }}</td>
                <td><span class="status status--{{ $activity->activity_status }}">{{ __('catalogue.'.$activity->activity_status) }}</span></td>
                <td>{{ $activity->currency }}</td>
                <td>@if($activity->published_on_website)<i data-lucide="check-circle" class="text-green"></i>@else<i data-lucide="x-circle" class="text-red"></i>@endif</td>
                <td>@if($activity->show_on_mobile_app)<i data-lucide="check-circle" class="text-green"></i>@else<i data-lucide="x-circle" class="text-red"></i>@endif</td>
                <td>
                    <div class="ops-actions">
                        <a href="{{ route('admin.activities.edit', $activity) }}" title="Edit"><i data-lucide="square-pen"></i></a>
                        <a href="{{ route('admin.activities.preview', $activity) }}" title="Preview"><i data-lucide="eye"></i></a>
                        <a href="{{ route('admin.activities.payment-scheme.edit', $activity) }}" title="Payment Scheme"><i data-lucide="credit-card"></i></a>
                        <form method="POST" action="{{ route('admin.activities.destroy', $activity) }}" onsubmit="return confirm('Soft-delete this activity?')">
                            @csrf @method('DELETE')
                            <button title="Delete"><i data-lucide="trash-2"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="12" class="text-center text-muted">{{ __('catalogue.none') }}</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="ops-pagination">{{ $activities->links() }}</div>

@push('styles')
<style>
.country-tabs { display: flex; gap: 0; margin-bottom: 1.5rem; border-bottom: 2px solid var(--border); overflow-x: auto; }
.country-tabs a { padding: 0.6rem 1.2rem; font-size: 0.85rem; font-weight: 500; color: var(--text-muted); border-bottom: 2px solid transparent; margin-bottom: -2px; transition: all 0.2s; white-space: nowrap; text-decoration: none; }
.country-tabs a:hover, .country-tabs a.is-active { color: var(--primary); border-bottom-color: var(--primary); }
.activity-table { font-size: 0.8rem; }
.activity-table th, .activity-table td { padding: 0.5rem 0.4rem; }
.activity-table td small { display: block; font-size: 0.7rem; color: var(--text-muted); }
.text-green { color: #22c55e; width: 16px; height: 16px; }
.text-red { color: #ef4444; width: 16px; height: 16px; }
.ops-actions { display: flex; gap: 0.25rem; align-items: center; }
.ops-actions a, .ops-actions button { padding: 0.25rem; border: none; background: none; cursor: pointer; color: var(--text-muted); }
.ops-actions a:hover, .ops-actions button:hover { color: var(--primary); }
</style>
@endpush
@endsection
