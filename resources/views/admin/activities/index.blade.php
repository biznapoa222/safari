@extends('layouts.admin')
@section('title', 'Activities & Pricing')
@section('content')
<x-admin.top-bar
    title="Activities & Pricing"
    description="Experience catalogue"
    addLabel="New Activity"
    addRoute="{{ route('admin.activities.create') }}"
    searchPlaceholder="Search activities..."
/>
@include('admin.partials.flash')
<div class="ops-two-column">
    <section class="ops-panel ops-form-panel">
        <div class="ops-panel-title"><div><h2>{{ $editing ? 'Edit activity' : 'New activity' }}</h2><p>Selling price is calculated from buy-in plus markup.</p></div></div>
        <form method="POST" action="{{ $editing ? route('admin.legacy-activities.update', $editing->id) : route('admin.legacy-activities.store') }}">
            @csrf @if($editing) @method('PUT') @endif
            <div class="ops-form-grid">
                <label class="span-2">Activity name<input name="name" value="{{ old('name', $editing->name ?? '') }}" required></label>
                <label>Category<input name="category" value="{{ old('category', $editing->category ?? 'Hiking') }}" required></label>
                <label>Supplier<input name="supplier" value="{{ old('supplier', $editing->supplier ?? '') }}"></label>
                <label>Country<input name="country" value="{{ old('country', $editing->country ?? 'Kenya') }}" required></label>
                <label>Location<input name="location" value="{{ old('location', $editing->location ?? '') }}" required></label>
                <label>Calculation<select name="calculation_type">@foreach(['per_person','per_vehicle','per_group'] as $option)<option value="{{ $option }}" @selected(old('calculation_type', $editing->calculation_type ?? 'per_person') === $option)>{{ ucwords(str_replace('_', ' ', $option)) }}</option>@endforeach</select></label>
                <label>Buy-in rate<input type="number" step="0.01" name="buy_rate" value="{{ old('buy_rate', $editing->buy_rate ?? '') }}" required></label>
                <label>Markup %<input type="number" step="0.01" name="markup_percent" value="{{ old('markup_percent', $editing->markup_percent ?? 20) }}" required></label>
                <label>Currency<input name="currency" maxlength="3" value="{{ old('currency', $editing->currency ?? 'USD') }}" required></label>
                <label>Daily capacity<input type="number" name="daily_capacity" value="{{ old('daily_capacity', $editing->daily_capacity ?? '') }}"></label>
                <label>Duration hours<input type="number" name="duration_hours" min="1" max="24" value="{{ old('duration_hours', $editing->duration_hours ?? 3) }}" required></label>
                <label>Status<select name="status"><option value="active" @selected(old('status', $editing->status ?? 'active') === 'active')>Active</option><option value="inactive" @selected(old('status', $editing->status ?? '') === 'inactive')>Inactive</option></select></label>
                <label class="span-2">Cost inclusions and notes<textarea name="notes" rows="4">{{ old('notes', $editing->notes ?? '') }}</textarea></label>
            </div>
            <div class="ops-form-footer">@if($editing)<a class="button button-secondary" href="{{ route('admin.legacy-activities.index') }}">Cancel</a>@endif<button class="button button-primary"><i data-lucide="save"></i>{{ $editing ? 'Update activity' : 'Create activity' }}</button></div>
        </form>
    </section>
    <section class="ops-panel">
        <form class="ops-filters" method="GET"><label class="ops-search"><i data-lucide="search"></i><input name="search" value="{{ request('search') }}" placeholder="Search activities"></label><button class="button button-primary">Search</button></form>
        <div class="table-wrap"><table class="ops-table"><thead><tr><th>Activity</th><th>Location</th><th>Basis</th><th>Buy-in</th><th>Markup</th><th>Selling</th><th>Capacity</th><th>Actions</th></tr></thead>
        <tbody>@foreach($activities as $activity)<tr><td><strong>{{ $activity->name }}</strong><small>{{ $activity->category }} - {{ $activity->supplier }}</small></td><td>{{ $activity->location }}<small>{{ $activity->country }}</small></td><td>{{ ucwords(str_replace('_', ' ', $activity->calculation_type)) }}</td><td><span class="buy-price">{{ $activity->currency }} {{ number_format($activity->buy_rate, 2) }}</span></td><td>{{ number_format($activity->markup_percent, 1) }}%</td><td><strong class="sell-price">{{ $activity->currency }} {{ number_format($activity->sell_rate, 2) }}</strong></td><td>{{ $activity->daily_capacity ?: 'Unlimited' }}</td><td><div class="ops-actions"><a href="{{ route('admin.legacy-activities.index', ['edit' => $activity->id]) }}"><i data-lucide="square-pen"></i></a><form method="POST" action="{{ route('admin.legacy-activities.destroy', $activity->id) }}" onsubmit="return confirm('Delete this activity?')">@csrf @method('DELETE')<button><i data-lucide="trash-2"></i></button></form></div></td></tr>@endforeach</tbody></table></div>
        <div class="ops-pagination">{{ $activities->links() }}</div>
    </section>
</div>
@endsection
