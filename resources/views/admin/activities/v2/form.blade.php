@extends('layouts.admin')
@section('title', $activity ? 'Edit Activity' : 'Create Activity')
@section('content')
<div class="page-heading">
    <div>
        <p class="eyebrow">Activity Management</p>
        <h1>{{ $activity ? 'Edit: '.$activity->name : 'New Activity' }}</h1>
    </div>
    <div class="heading-actions">
        @if($activity)
            <a href="{{ route('admin.activities.preview', $activity) }}" class="button button-secondary"><i data-lucide="eye"></i>Preview</a>
        @endif
    </div>
</div>

@include('admin.partials.flash')

<form method="POST" action="{{ $activity ? route('admin.activities.update', $activity) : route('admin.activities.store') }}" class="ops-form">
    @csrf @if($activity) @method('PUT') @endif

    {{-- Basic Information --}}
    <section class="ops-panel">
        <div class="ops-panel-title"><h2>Basic Information</h2></div>
        <div class="ops-form-grid">
            <label class="span-2">Activity Name (English)<input name="name" value="{{ old('name', $activity->name ?? '') }}" required></label>
            <label>Country
                <select name="country">
                    @foreach(['Kenya','Tanzania','Uganda','South Africa','Namibia','Botswana'] as $c)
                        <option value="{{ $c }}" @selected(old('country', $activity->country ?? '') === $c)>{{ $c }}</option>
                    @endforeach
                </select>
            </label>
            <label>Region<input name="region" value="{{ old('region', $activity->region ?? '') }}"></label>
            <label>Location<input name="location" value="{{ old('location', $activity->location ?? '') }}" required></label>
            <label>Category
                <select name="activity_category_id">
                    <option value="">-- Select --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('activity_category_id', $activity->activity_category_id ?? '') == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </label>
            <label>Activity Type
                <select name="activity_status">
                    <option value="active" @selected(old('activity_status', $activity->activity_status ?? 'active') === 'active')>Active</option>
                    <option value="inactive" @selected(old('activity_status', $activity->activity_status ?? '') === 'inactive')>Inactive</option>
                </select>
            </label>
            <label>Min Pax<input type="number" name="min_pax" value="{{ old('min_pax', $activity->min_pax ?? '') }}" min="1"></label>
            <label>Min Age<input type="number" name="min_age" value="{{ old('min_age', $activity->min_age ?? '') }}" min="0"></label>
            <label>Duration (hours)<input type="number" name="duration_hours" value="{{ old('duration_hours', $activity->duration_hours ?? '') }}" min="1"></label>
            <label>Pickup Time<input name="pickup_time" value="{{ old('pickup_time', $activity->pickup_time ?? '') }}" placeholder="e.g. 07:00"></label>
            <label>Currency
                <select name="currency">
                    @foreach(['USD','EUR','GBP','KES','AUD','CAD'] as $cur)
                        <option value="{{ $cur }}" @selected(old('currency', $activity->currency ?? 'USD') === $cur)>{{ $cur }}</option>
                    @endforeach
                </select>
            </label>
        </div>
    </section>

    {{-- Descriptions --}}
    <section class="ops-panel">
        <div class="ops-panel-title"><h2>Description</h2></div>
        <div class="ops-form-grid">
            <label class="span-2">Description<textarea name="description" rows="6">{{ old('description', $activity->description ?? '') }}</textarea></label>
            <label class="span-2">Keywords<textarea name="keywords" rows="2" placeholder="Comma-separated">{{ old('keywords', $activity->keywords ?? '') }}</textarea></label>
            <label class="span-2">Tags<textarea name="tags" rows="2" placeholder="Comma-separated">{{ old('tags', $activity->tags ?? '') }}</textarea></label>
        </div>
    </section>

    {{-- Status & Publishing --}}
    @if($activity)
    <section class="ops-panel">
        <div class="ops-panel-title"><h2>Status & Publishing</h2></div>
        <div class="ops-form-grid">
            <label class="checkbox-label"><input type="checkbox" name="published_on_website" value="1" @checked(old('published_on_website', $activity->published_on_website))> Published on Website</label>
            <label class="checkbox-label"><input type="checkbox" name="show_on_mobile_app" value="1" @checked(old('show_on_mobile_app', $activity->show_on_mobile_app))> Show on Mobile App</label>
            <label>Price Status Current Year<input name="price_status_current_year" value="{{ old('price_status_current_year', $activity->price_status_current_year ?? '') }}"></label>
            <label>Price Status Next Year<input name="price_status_next_year" value="{{ old('price_status_next_year', $activity->price_status_next_year ?? '') }}"></label>
            <label>Payment Scheme Status<input name="payment_scheme_status" value="{{ old('payment_scheme_status', $activity->payment_scheme_status ?? '') }}"></label>
        </div>
    </section>
    @endif

    {{-- Suppliers --}}
    <section class="ops-panel">
        <div class="ops-panel-title"><h2>Suppliers</h2></div>
        <div class="ops-form-grid">
            <div class="span-2">
                <select name="suppliers[]" multiple class="tag-select">
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}"
                            @selected($activity && $activity->suppliers->contains($supplier->id))>
                            {{ $supplier->name }} ({{ $supplier->type }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </section>

    <div class="ops-form-footer">
        <a href="{{ route('admin.activities.index') }}" class="button button-secondary">Cancel</a>
        <button class="button button-primary"><i data-lucide="save"></i>{{ $activity ? 'Update Activity' : 'Create Activity' }}</button>
    </div>
</form>

{{-- Translations --}}
@if($activity)
<section class="ops-panel" style="margin-top: 2rem;">
    <div class="ops-panel-title"><h2>Translations</h2></div>
    <div class="ops-form-grid">
        @php $locales = ['en'=>'English','nl'=>'Dutch','fr'=>'French','de'=>'German','es'=>'Spanish','sv'=>'Swedish','no'=>'Norwegian','da'=>'Danish','it'=>'Italian','pl'=>'Polish','pt'=>'Portuguese']; @endphp
        @foreach($locales as $code => $label)
        <div class="translation-card">
            <strong>{{ $label }} ({{ $code }})</strong>
            @php $t = $activity->translations->where('locale', $code)->first(); @endphp
            <form method="POST" action="{{ route('admin.activities.translations.store', $activity) }}" style="display:contents;">
                @csrf
                <input type="hidden" name="locale" value="{{ $code }}">
                <input name="title" value="{{ old("titles.$code", $t->title ?? '') }}" placeholder="Title in {{ $label }}">
                <textarea name="description" rows="2" placeholder="Description">{{ old("descriptions.$code", $t->description ?? '') }}</textarea>
                <button class="button button-sm button-primary">Save</button>
            </form>
        </div>
        @endforeach
    </div>
</section>

{{-- Pricing --}}
<section class="ops-panel" style="margin-top: 2rem;">
    <div class="ops-panel-title"><h2>Pricing</h2>
        <button class="button button-sm button-secondary" onclick="document.getElementById('price-form').classList.toggle('hidden')">Add Price</button>
    </div>
    <form id="price-form" method="POST" action="{{ route('admin.activities.prices.store', $activity) }}" class="hidden" style="margin-bottom:1rem;">
        @csrf
        <div class="ops-form-grid">
            <label>Type
                <select name="type">
                    @foreach(['standard','resident','non_resident','child','group'] as $pt)
                        <option value="{{ $pt }}">{{ ucwords(str_replace('_', ' ', $pt)) }}</option>
                    @endforeach
                </select>
            </label>
            <label>Season
                <select name="season">
                    @foreach(['high','low','peak'] as $s)
                        <option value="{{ $s }}">{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </label>
            <label>Year<input type="number" name="year" value="{{ date('Y') }}" min="2024" max="2099"></label>
            <label>Price<input type="number" step="0.01" name="price" required></label>
            <label>Currency<input name="currency" value="USD" maxlength="3"></label>
            <label>Valid From<input type="date" name="valid_from"></label>
            <label>Valid To<input type="date" name="valid_to"></label>
            <div class="span-2"><button class="button button-primary">Save Price</button></div>
        </div>
    </form>
    @if($activity->prices->count())
    <div class="table-wrap">
        <table class="ops-table">
            <thead><tr><th>Type</th><th>Season</th><th>Year</th><th>Price</th><th>Currency</th><th>Valid</th><th></th></tr></thead>
            <tbody>
                @foreach($activity->prices as $price)
                <tr>
                    <td>{{ $price->type }}</td>
                    <td>{{ ucfirst($price->season) }}</td>
                    <td>{{ $price->year }}</td>
                    <td><strong>{{ number_format($price->price, 2) }}</strong></td>
                    <td>{{ $price->currency }}</td>
                    <td>{{ $price->valid_from?->format('d/m/Y') }} - {{ $price->valid_to?->format('d/m/Y') }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.activities.prices.destroy', [$activity, $price]) }}" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button class="row-action"><i data-lucide="trash-2"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</section>

{{-- Seasons --}}
<section class="ops-panel" style="margin-top: 2rem;">
    <div class="ops-panel-title"><h2>Season Dates</h2>
        <button class="button button-sm button-secondary" onclick="document.getElementById('season-form').classList.toggle('hidden')">Add Season</button>
    </div>
    <form id="season-form" method="POST" action="{{ route('admin.activities.seasons.store', $activity) }}" class="hidden" style="margin-bottom:1rem;">
        @csrf
        <div class="ops-form-grid">
            <label>Season
                <select name="name">
                    @foreach(['high'=>'High Season','low'=>'Low Season','peak'=>'Peak Season'] as $sk => $sl)
                        <option value="{{ $sk }}">{{ $sl }}</option>
                    @endforeach
                </select>
            </label>
            <label>Start Date<input type="date" name="start_date" required></label>
            <label>End Date<input type="date" name="end_date" required></label>
            <div><button class="button button-primary">Save</button></div>
        </div>
    </form>
    @if($activity->seasons->count())
    <div class="table-wrap">
        <table class="ops-table">
            <thead><tr><th>Season</th><th>Start</th><th>End</th><th></th></tr></thead>
            <tbody>
                @foreach($activity->seasons as $season)
                <tr>
                    <td>{{ ucfirst($season->name) }}</td>
                    <td>{{ $season->start_date->format('d/m/Y') }}</td>
                    <td>{{ $season->end_date->format('d/m/Y') }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.activities.seasons.destroy', [$activity, $season]) }}" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button class="row-action"><i data-lucide="trash-2"></i></button>
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
.ops-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.span-2 { grid-column: span 2; }
.checkbox-label { display: flex; align-items: center; gap: 0.5rem; cursor: pointer; }
.checkbox-label input { width: auto; }
.tag-select { min-height: 120px; width: 100%; }
.translation-card { border: 1px solid var(--border); padding: 0.75rem; border-radius: 0.5rem; }
.translation-card input, .translation-card textarea { width: 100%; margin-top: 0.25rem; font-size: 0.85rem; }
.translation-card button { margin-top: 0.25rem; }
.hidden { display: none; }
.row-action { border: none; background: none; cursor: pointer; color: var(--text-muted); padding: 0.25rem; }
.row-action:hover { color: #ef4444; }
</style>
@endpush
@endsection
