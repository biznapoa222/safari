@extends('layouts.admin')
@section('title', $accommodation ? 'Edit Accommodation' : 'New Accommodation')
@section('content')
<div class="page-heading">
    <div>
        <p class="eyebrow">Accommodation</p>
        <h1>{{ $accommodation ? 'Edit: '.$accommodation->name : 'New Accommodation' }}</h1>
    </div>
</div>
@include('admin.partials.flash')

<form method="POST" action="{{ $accommodation ? route('admin.accommodations-v2.update', $accommodation) : route('admin.accommodations-v2.store') }}" class="ops-panel">
    @csrf @if($accommodation) @method('PUT') @endif
    <div class="ops-panel-title"><h2>Details</h2></div>
    <div class="ops-form-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
        <label class="span-2">Name<input name="name" value="{{ old('name', $accommodation->name ?? '') }}" required></label>
        <label>Type
            <select name="type">
                @foreach(\App\Models\Accommodation::$types as $k => $v)
                    <option value="{{ $k }}" @selected(old('type', $accommodation->type ?? '') === $k)>{{ $v }}</option>
                @endforeach
            </select>
        </label>
        <label>Country
            <select name="country">
                @foreach(['Kenya','Tanzania','Uganda','South Africa','Namibia','Botswana'] as $c)
                    <option value="{{ $c }}" @selected(old('country', $accommodation->country ?? '') === $c)>{{ $c }}</option>
                @endforeach
            </select>
        </label>
        <label>Region<input name="region" value="{{ old('region', $accommodation->region ?? '') }}"></label>
        <label>Category<input name="category" value="{{ old('category', $accommodation->category ?? '') }}"></label>
        <label>Luxury Level
            <select name="luxury_level">
                <option value="">-- Select --</option>
                @foreach(['luxury'=>'Luxury','premium'=>'Premium','mid_range'=>'Mid Range','budget'=>'Budget'] as $k => $v)
                    <option value="{{ $k }}" @selected(old('luxury_level', $accommodation->luxury_level ?? '') === $k)>{{ $v }}</option>
                @endforeach
            </select>
        </label>
        <label>Currency
            <select name="currency">
                @foreach(['USD','EUR','GBP','KES','AUD','CAD'] as $cur)
                    <option value="{{ $cur }}" @selected(old('currency', $accommodation->currency ?? 'USD') === $cur)>{{ $cur }}</option>
                @endforeach
            </select>
        </label>
        <label>Phone<input name="phone" value="{{ old('phone', $accommodation->phone ?? '') }}"></label>
        <label>Email<input type="email" name="email" value="{{ old('email', $accommodation->email ?? '') }}"></label>
        <label class="span-2">Website<input name="website" value="{{ old('website', $accommodation->website ?? '') }}"></label>
        <label class="span-2">Description<textarea name="description" rows="5">{{ old('description', $accommodation->description ?? '') }}</textarea></label>
        <label class="span-2">Notes<textarea name="notes" rows="3">{{ old('notes', $accommodation->notes ?? '') }}</textarea></label>

        @if($accommodation)
        <label class="checkbox-label"><input type="checkbox" name="published" value="1" @checked(old('published', $accommodation->published))> Published</label>
        <label class="checkbox-label"><input type="checkbox" name="featured" value="1" @checked(old('featured', $accommodation->featured))> Featured</label>
        <label>Status
            <select name="status">
                <option value="active" @selected(old('status', $accommodation->status) === 'active')>Active</option>
                <option value="inactive" @selected(old('status', $accommodation->status) === 'inactive')>Inactive</option>
            </select>
        </label>
        @endif
    </div>
    <div class="ops-form-footer">
        <a href="{{ route('admin.accommodations-v2.index') }}" class="button button-secondary">Cancel</a>
        <button class="button button-primary"><i data-lucide="save"></i>{{ $accommodation ? 'Update' : 'Create' }}</button>
    </div>
</form>

@if($accommodation)
{{-- Rooms --}}
<section class="ops-panel" style="margin-top:2rem;">
    <div class="ops-panel-title"><h2>Room Types</h2>
        <button class="button button-sm button-secondary" onclick="document.getElementById('room-form').classList.toggle('hidden')">Add Room</button>
    </div>
    <form id="room-form" method="POST" action="{{ route('admin.accommodations-v2.rooms.store', $accommodation) }}" class="hidden" style="margin-bottom:1rem;">
        @csrf
        <div class="ops-form-grid" style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0.75rem;">
            <label>Room Name<input name="name" required></label>
            <label>Capacity<input type="number" name="capacity" value="2" min="1"></label>
            <label>Max Adults<input type="number" name="max_adults" value="2" min="1"></label>
            <label>Max Children<input type="number" name="max_children" value="0" min="0"></label>
            <label>Baby Max Age<input type="number" name="baby_max_age" value="2" min="0" max="17" required></label>
            <label>Child Min Age<input type="number" name="child_min_age" value="3" min="0" max="17" required></label>
            <label>Child Max Age<input type="number" name="child_max_age" value="11" min="0" max="17" required></label>
            <label>Adult Min Age<input type="number" name="adult_min_age" value="12" min="1" max="30" required></label>
            <label>Inventory<input type="number" name="inventory" value="1" min="1"></label>
            <label>Child Policy<textarea name="child_policy" rows="2"></textarea></label>
            <div><button class="button button-primary" style="margin-top:1.5rem;">Add</button></div>
        </div>
    </form>
    @if($accommodation->rooms->count())
    <div class="table-wrap">
        <table class="ops-table">
            <thead><tr><th>Room Name</th><th>Capacity</th><th>Adults</th><th>Children</th><th>Age rules</th><th>Inventory</th><th>Rates</th><th></th></tr></thead>
            <tbody>
                @foreach($accommodation->rooms as $room)
                <tr>
                    <td><strong>{{ $room->name }}</strong></td>
                    <td>{{ $room->capacity }}</td>
                    <td>{{ $room->max_adults }}</td>
                    <td>{{ $room->max_children }}</td>
                    <td><small>Baby 0-{{ $room->baby_max_age ?? 2 }} · Child {{ $room->child_min_age ?? 3 }}-{{ $room->child_max_age ?? 11 }} · Adult {{ $room->adult_min_age ?? 12 }}+</small></td>
                    <td>{{ $room->inventory }}</td>
                    <td>{{ $room->rates->count() }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.accommodations-v2.rooms.destroy', [$accommodation, $room]) }}" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button class="row-action"><i data-lucide="trash-2"></i></button>
                        </form>
                    </td>
                </tr>
                {{-- Rates for this room --}}
                @foreach($room->rates as $rate)
                <tr style="background:var(--bg-subtle);">
                    <td colspan="7" style="padding-left:2rem;">
                        <small>{{ $rate->season_name }}: {{ $rate->currency }} {{ number_format($rate->rate,2) }} ({{ $rate->meal_plan }}, {{ $rate->valid_from->format('d/m/Y') }} - {{ $rate->valid_to->format('d/m/Y') }})</small>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.accommodations-v2.rates.destroy', [$accommodation, $room, $rate]) }}" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button class="row-action"><i data-lucide="x"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
                {{-- Add rate form --}}
                <tr>
                    <td colspan="8" style="padding:0.5rem 1rem;">
                        <form method="POST" action="{{ route('admin.accommodations-v2.rates.store', [$accommodation, $room]) }}" style="display:flex;gap:0.5rem;align-items:end;flex-wrap:wrap;">
                            @csrf
                            <input name="season_name" placeholder="Season name" required style="width:120px;">
                            <input type="date" name="valid_from" required style="width:130px;">
                            <input type="date" name="valid_to" required style="width:130px;">
                            <input name="meal_plan" placeholder="Meal plan" value="Full Board" style="width:110px;">
                            <input type="number" step="0.01" name="rate" placeholder="Rate" required style="width:100px;">
                            <input name="currency" value="USD" style="width:60px;">
                            <button class="button button-sm button-primary">Add Rate</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</section>
@endif

@push('styles')
<style>
.checkbox-label { display: flex; align-items: center; gap: 0.5rem; cursor: pointer; }
.checkbox-label input { width: auto; }
.hidden { display: none; }
.row-action { border: none; background: none; cursor: pointer; color: var(--text-muted); padding: 0.25rem; }
.row-action:hover { color: #ef4444; }
</style>
@endpush
@endsection
