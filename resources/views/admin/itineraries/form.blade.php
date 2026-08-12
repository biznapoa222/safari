@extends('layouts.admin')
@section('title', $itinerary ? 'Edit Itinerary' : 'New Itinerary')

@section('content')
<div class="ops-page-heading">
    <div><p class="eyebrow">Itinerary studio</p><h1>{{ $itinerary?->title ?? 'New itinerary' }}</h1><p>Build a complete reusable safari with daily descriptions, logistics, accommodation and photography.</p></div>
    <div class="heading-actions">
        @if($itinerary)
            <a class="button button-secondary" href="{{ route('admin.itineraries.show', $itinerary) }}"><i data-lucide="eye"></i>Preview</a>
            <a class="button button-primary" href="{{ route('admin.itineraries.pdf', $itinerary) }}"><i data-lucide="file-down"></i>Download PDF</a>
        @endif
        <a class="button button-secondary" href="{{ route('admin.itineraries.index') }}"><i data-lucide="arrow-left"></i>List</a>
    </div>
</div>
@include('admin.partials.flash')

<div class="ops-detail-layout itinerary-editor">
    <nav class="ops-side-tabs">
        <a href="#overview"><i data-lucide="notebook-text"></i>Overview</a>
        @if($itinerary)
            <a href="#days"><i data-lucide="route"></i>Day by day</a>
            <a href="#gallery"><i data-lucide="images"></i>Gallery</a>
            <a href="#publishing"><i data-lucide="globe-2"></i>Publishing</a>
        @endif
    </nav>
    <div class="ops-detail-content">
        <section id="overview" class="ops-panel ops-form-panel">
            <div class="ops-panel-title"><div><h2>Program overview</h2><p>Client-facing information, route details, pricing and publishing status.</p></div></div>
            <form method="POST" enctype="multipart/form-data" action="{{ $itinerary ? route('admin.itineraries.update', $itinerary) : route('admin.itineraries.store') }}">
                @csrf @if($itinerary) @method('PUT') @endif
                <div class="ops-form-grid">
                    <label>Reference code<input name="code" value="{{ old('code', $itinerary?->code) }}" placeholder="Generated automatically"></label>
                    <label>Status<select name="status">@foreach(['draft','published','archived'] as $status)<option value="{{ $status }}" @selected(old('status', $itinerary?->status ?? 'draft') === $status)>{{ ucfirst($status) }}</option>@endforeach</select></label>
                    <label class="span-2">Itinerary title<input name="title" value="{{ old('title', $itinerary?->title) }}" required></label>
                    <label>Countries<input name="countries" value="{{ old('countries', $itinerary?->countries ?? 'Kenya') }}" placeholder="Kenya, Tanzania" required></label>
                    <label>Travel style<input name="travel_style" value="{{ old('travel_style', $itinerary?->travel_style ?? 'Private tailor-made safari') }}" required></label>
                    <label>Days<input type="number" name="duration_days" value="{{ old('duration_days', $itinerary?->duration_days ?? 7) }}" min="1" required></label>
                    <label>Nights<input type="number" name="nights" value="{{ old('nights', $itinerary?->nights ?? 6) }}" min="0" required></label>
                    <label>Minimum guests<input type="number" name="minimum_guests" value="{{ old('minimum_guests', $itinerary?->minimum_guests ?? 2) }}" min="1" required></label>
                    <label>Maximum guests<input type="number" name="maximum_guests" value="{{ old('maximum_guests', $itinerary?->maximum_guests ?? 12) }}" min="1" required></label>
                    <label>Price from<input type="number" step="0.01" name="price_from" value="{{ old('price_from', $itinerary?->price_from ?? 0) }}" min="0" required></label>
                    <label>Currency<input name="currency" maxlength="3" value="{{ old('currency', $itinerary?->currency ?? 'USD') }}" required></label>
                    <label>Start location<input name="start_location" value="{{ old('start_location', $itinerary?->start_location) }}"></label>
                    <label>End location<input name="end_location" value="{{ old('end_location', $itinerary?->end_location) }}"></label>
                    <label>Difficulty<input name="difficulty" value="{{ old('difficulty', $itinerary?->difficulty ?? 'Easy') }}" required></label>
                    <label>Accommodation level<input name="accommodation_level" value="{{ old('accommodation_level', $itinerary?->accommodation_level ?? 'Luxury lodges and camps') }}"></label>
                    <label class="span-2">Best time to travel<input name="best_time" value="{{ old('best_time', $itinerary?->best_time) }}" placeholder="June to October and January to March"></label>
                    <label class="span-2">Short summary<textarea name="summary" rows="3" required>{{ old('summary', $itinerary?->summary) }}</textarea></label>
                    <label class="span-2">Detailed introduction<textarea name="description" rows="7">{{ old('description', $itinerary?->description) }}</textarea></label>
                    <label>Inclusions, one per line<textarea name="inclusions_text" rows="8">{{ old('inclusions_text', implode("\n", $itinerary?->inclusions ?? [])) }}</textarea></label>
                    <label>Exclusions, one per line<textarea name="exclusions_text" rows="8">{{ old('exclusions_text', implode("\n", $itinerary?->exclusions ?? [])) }}</textarea></label>
                    <label class="span-2">Important notes<textarea name="important_notes" rows="4">{{ old('important_notes', $itinerary?->important_notes) }}</textarea></label>
                    <label class="span-2 itinerary-file-field">Cover image<input type="file" name="cover_image_upload" accept="image/jpeg,image/png,image/webp"><small>JPG, PNG or WebP, maximum 8 MB. Landscape photography works best.</small></label>
                    @if($itinerary?->cover_image)<div class="span-2 current-cover"><img src="{{ $itinerary->cover_image_url }}" alt="{{ $itinerary->title }}"></div>@endif
                    <label class="check-label"><input type="checkbox" name="featured" value="1" @checked(old('featured', $itinerary?->featured))> Feature this itinerary</label>
                    <label id="publishing">SEO title<input name="seo_title" value="{{ old('seo_title', $itinerary?->seo_title) }}"></label>
                    <label class="span-2">SEO description<textarea name="seo_description" rows="3">{{ old('seo_description', $itinerary?->seo_description) }}</textarea></label>
                </div>
                <div class="ops-form-footer"><button class="button button-primary"><i data-lucide="save"></i>{{ $itinerary ? 'Save itinerary' : 'Create itinerary' }}</button></div>
            </form>
        </section>

        @if($itinerary)
        <section id="days" class="ops-panel ops-form-panel">
            <div class="ops-panel-title"><div><h2>Day-by-day program</h2><p>Each day can have detailed copy, route logistics, accommodation, meals and multiple images.</p></div><span class="ops-pill ops-pill--blue">{{ $itinerary->days->count() }} of {{ $itinerary->duration_days }} days</span></div>
            <details class="itinerary-add-day" open>
                <summary><i data-lucide="plus-circle"></i>Add itinerary day</summary>
                @include('admin.itineraries.partials.day-form', ['day' => null])
            </details>
            <div class="itinerary-day-editor-list">
                @foreach($itinerary->days as $day)
                    <details class="itinerary-day-editor" @if($loop->first) open @endif>
                        <summary>
                            @if($day->primary_image)<img src="{{ $day->primary_image_url }}" alt="{{ $day->title }}" loading="lazy">@else<span class="day-image-placeholder"><i data-lucide="image"></i></span>@endif
                            <b>Day {{ $day->day_number }}</b><strong>{{ $day->title }}</strong><small>{{ $day->location }} @if($day->accommodation) · {{ $day->accommodation }} @endif</small><i data-lucide="chevron-down"></i>
                        </summary>
                        @include('admin.itineraries.partials.day-form', ['day' => $day])
                        @if($day->images->isNotEmpty())
                            <div class="day-mini-gallery">
                                @foreach($day->images as $image)
                                    <div><img src="{{ $image->url }}" alt="{{ $image->alt_text }}" loading="lazy"><form method="POST" action="{{ route('admin.itineraries.images.destroy', [$itinerary, $image]) }}">@csrf @method('DELETE')<button title="Remove"><i data-lucide="x"></i></button></form></div>
                                @endforeach
                            </div>
                        @endif
                        <form class="day-delete-form" method="POST" action="{{ route('admin.itineraries.days.destroy', [$itinerary, $day]) }}" onsubmit="return confirm('Delete this complete day?')">@csrf @method('DELETE')<button class="button danger-button"><i data-lucide="trash-2"></i>Delete day</button></form>
                    </details>
                @endforeach
            </div>
        </section>

        <section id="gallery" class="ops-panel ops-form-panel">
            <div class="ops-panel-title"><div><h2>Itinerary gallery</h2><p>Upload multiple images, add credits and choose a new cover from the gallery.</p></div></div>
            <form class="itinerary-gallery-upload" method="POST" enctype="multipart/form-data" action="{{ route('admin.itineraries.images.store', $itinerary) }}">
                @csrf
                <label>Images<input type="file" name="images[]" accept="image/jpeg,image/png,image/webp" multiple required></label>
                <label>Shared caption<input name="caption" placeholder="Optional image caption"></label>
                <label>Photo credit<input name="credit" placeholder="Optional photographer or supplier"></label>
                <button class="button button-primary"><i data-lucide="upload"></i>Upload images</button>
            </form>
            <div class="itinerary-gallery">
                @forelse($itinerary->images->whereNull('itinerary_day_id') as $image)
                    <figure>
                        <img src="{{ $image->url }}" alt="{{ $image->alt_text }}" loading="lazy">
                        <figcaption><span>{{ $image->caption ?: 'Itinerary image' }}<small>{{ $image->credit }}</small></span><div>
                            @if($itinerary->cover_image !== $image->path)<form method="POST" action="{{ route('admin.itineraries.images.cover', [$itinerary, $image]) }}">@csrf<button title="Use as cover"><i data-lucide="star"></i></button></form>@else<span class="gallery-cover-label">Cover</span>@endif
                            <form method="POST" action="{{ route('admin.itineraries.images.destroy', [$itinerary, $image]) }}">@csrf @method('DELETE')<button title="Remove"><i data-lucide="trash-2"></i></button></form>
                        </div></figcaption>
                    </figure>
                @empty
                    <div class="ops-empty">No gallery images yet. Upload several landscapes above.</div>
                @endforelse
            </div>
        </section>
        @endif
    </div>
</div>
@endsection
