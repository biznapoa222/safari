@extends('layouts.admin')
@section('title', $itinerary ? 'Edit Itinerary' : 'New Itinerary')
@section('content')
<div class="page-heading">
    <div><p class="eyebrow">Itinerary Builder</p><h1>{{ $itinerary ? 'Edit: '.$itinerary->title : 'New Itinerary' }}</h1></div>
</div>
@include('admin.partials.flash')

<form method="POST" action="{{ $itinerary ? route('admin.itinerary-builder.update', $itinerary) : route('admin.itinerary-builder.store') }}" class="ops-panel">
    @csrf @if($itinerary) @method('PUT') @endif
    <div class="ops-panel-title"><h2>Itinerary Details</h2></div>
    <div class="ops-form-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
        <label class="span-2">Title<input name="title" value="{{ old('title', $itinerary->title ?? '') }}" required></label>
        <label>Duration (days)<input type="number" name="duration_days" value="{{ old('duration_days', $itinerary->duration_days ?? 1) }}" min="1"></label>
        <label>Country<input name="country" value="{{ old('country', $itinerary->country ?? '') }}"></label>
        <label>Region<input name="region" value="{{ old('region', $itinerary->region ?? '') }}"></label>
        <label>Price From<input type="number" step="0.01" name="price_from" value="{{ old('price_from', $itinerary->price_from ?? '') }}"></label>
        <label>Currency
            <select name="currency">
                @foreach(['USD','EUR','GBP','KES','AUD','CAD'] as $cur)
                    <option value="{{ $cur }}" @selected(old('currency', $itinerary->currency ?? 'USD') === $cur)>{{ $cur }}</option>
                @endforeach
            </select>
        </label>
        <label class="span-2">Summary<textarea name="summary" rows="4">{{ old('summary', $itinerary->summary ?? '') }}</textarea></label>
        <label class="span-2">Inclusions (one per line)<textarea name="inclusions" rows="4">{{ old('inclusions', $itinerary ? (is_array($itinerary->inclusions) ? implode("\n", $itinerary->inclusions) : '') : '') }}</textarea></label>
        <label class="span-2">Exclusions (one per line)<textarea name="exclusions" rows="4">{{ old('exclusions', $itinerary ? (is_array($itinerary->exclusions) ? implode("\n", $itinerary->exclusions) : '') : '') }}</textarea></label>
        <label class="span-2">Notes<textarea name="notes" rows="3">{{ old('notes', $itinerary->notes ?? '') }}</textarea></label>
        @if($itinerary)
        <label class="checkbox-label"><input type="checkbox" name="published" value="1" @checked(old('published', $itinerary->published))> Published</label>
        <label class="checkbox-label"><input type="checkbox" name="featured" value="1" @checked(old('featured', $itinerary->featured))> Featured</label>
        @endif
    </div>
    <div class="ops-form-footer">
        <a href="{{ route('admin.itinerary-builder.index') }}" class="button button-secondary">Cancel</a>
        <button class="button button-primary"><i data-lucide="save"></i>{{ $itinerary ? 'Update' : 'Create' }}</button>
    </div>
</form>

@if($itinerary)
<h3 style="margin:2rem 0 1rem;">Day-by-Day Plan</h3>
<div id="days-container" class="day-list">
    @foreach($itinerary->days as $day)
    <div class="ops-panel day-card" data-day-id="{{ $day->id }}" style="margin-bottom:0.75rem;">
        <div class="day-header" style="display:flex;justify-content:space-between;align-items:center;cursor:pointer;">
            <h4 style="margin:0;">Day {{ $day->day_number }}: {{ $day->title }}</h4>
            <div style="display:flex;gap:0.5rem;">
                <button class="button button-sm button-secondary" onclick="this.closest('.day-card').querySelector('.day-body').classList.toggle('hidden')">Edit</button>
                <form method="POST" action="{{ route('admin.itinerary-builder.days.destroy', [$itinerary, $day]) }}" onsubmit="return confirm('Delete day?')">@csrf @method('DELETE')<button class="button button-sm button-danger">Del</button></form>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.itinerary-builder.days.update', [$itinerary, $day]) }}" class="day-body hidden" style="margin-top:1rem;">
            @csrf @method('PUT')
            <div class="ops-form-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;">
                <label>Title<input name="title" value="{{ $day->title }}"></label>
                <label>Location<input name="location" value="{{ $day->location ?? '' }}"></label>
                <label>Accommodation ID<input type="number" name="accommodation_id" value="{{ $day->accommodation_id ?? '' }}"></label>
                <label>Meal Plan<input name="meal_plan" value="{{ $day->meal_plan ?? '' }}"></label>
                <label>Activities<textarea name="activities" rows="2">{{ $day->activities ?? '' }}</textarea></label>
                <label>Transfers<textarea name="transfers" rows="2">{{ $day->transfers ?? '' }}</textarea></label>
                <label class="span-2">Notes<textarea name="notes" rows="2">{{ $day->notes ?? '' }}</textarea></label>
                <div class="span-2"><button class="button button-primary">Save Day</button></div>
            </div>
        </form>
    </div>
    @endforeach
</div>

{{-- Add Day --}}
<form method="POST" action="{{ route('admin.itinerary-builder.days.store', $itinerary) }}" class="ops-panel" style="margin-top:1rem;">
    @csrf
    <div class="ops-panel-title"><h2>Add Day</h2></div>
    <div class="ops-form-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;">
        <label>Day Number<input type="number" name="day_number" value="{{ $itinerary->days->count() + 1 }}" min="1"></label>
        <label>Title<input name="title" placeholder="Day {{ $itinerary->days->count() + 1 }}"></label>
        <label>Location<input name="location"></label>
        <label>Meal Plan<input name="meal_plan"></label>
        <label class="span-2">Activities<textarea name="activities" rows="2"></textarea></label>
        <label class="span-2">Transfers<textarea name="transfers" rows="2"></textarea></label>
        <label class="span-2">Notes<textarea name="notes" rows="2"></textarea></label>
        <div class="span-2"><button class="button button-primary">Add Day</button></div>
    </div>
</form>
@endif

@push('styles')
<style>
.checkbox-label { display: flex; align-items: center; gap: 0.5rem; cursor: pointer; }
.checkbox-label input { width: auto; }
.hidden { display: none; }
.day-card { border-left: 3px solid var(--primary); }
.day-header h4 { font-size: 1rem; }
</style>
@endpush
@endsection
