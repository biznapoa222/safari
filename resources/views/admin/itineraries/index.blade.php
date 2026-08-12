@extends('layouts.admin')
@section('title', 'Itinerary List')

@section('content')
<x-admin.top-bar
    title="Itineraries"
    description="Legacy Itineraries"
    addLabel="New Itinerary"
    addRoute="{{ route('admin.itineraries.create') }}"
    searchPlaceholder="Search itineraries..."
/>
@include('admin.partials.flash')

<section class="ops-panel">
    <form class="ops-filters" method="GET">
        <label class="ops-search"><i data-lucide="search"></i><input name="search" value="{{ request('search') }}" placeholder="Search title, code or country"></label>
        <select name="status"><option value="">All statuses</option>@foreach(['draft','published','archived'] as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>@endforeach</select>
        <button class="button button-primary">Search</button>
        <a class="button button-secondary" href="{{ route('admin.itineraries.index') }}">Reset</a>
    </form>

    <div class="itinerary-grid itinerary-grid--list">
        @forelse($itineraries as $itinerary)
            <article class="itinerary-card">
                <a class="itinerary-card-image" href="{{ route('admin.itineraries.show', $itinerary) }}">
                    @if($itinerary->cover_image)
                        <img src="{{ $itinerary->cover_image_url }}" alt="{{ $itinerary->title }}" loading="lazy">
                    @else
                        <span><i data-lucide="image-plus"></i>Add cover image</span>
                    @endif
                    <div class="itinerary-image-shade"></div>
                    <span class="itinerary-duration"><i data-lucide="calendar-days"></i>{{ $itinerary->duration_days }} days · {{ $itinerary->nights }} nights</span>
                </a>
                <div class="itinerary-card-body">
                    <div class="itinerary-card-meta"><span>{{ $itinerary->code }}</span><span class="itinerary-status itinerary-status--{{ $itinerary->status }}"><i></i>{{ ucfirst($itinerary->status) }}</span></div>
                    <h2><a href="{{ route('admin.itineraries.show', $itinerary) }}">{{ $itinerary->title }}</a></h2>
                    <p>{{ \Illuminate\Support\Str::limit($itinerary->summary, 145) }}</p>
                    <div class="itinerary-card-stats">
                        <span><i data-lucide="map-pin"></i>{{ $itinerary->countries }}</span>
                        <span><i data-lucide="calendar-range"></i>{{ $itinerary->days_count }} planned days</span>
                        <span><i data-lucide="images"></i>{{ $itinerary->images_count }} images</span>
                    </div>
                    <div class="itinerary-card-footer">
                        <div class="itinerary-price"><small>Starting from</small><strong>{{ $itinerary->currency }} {{ number_format($itinerary->price_from) }}</strong><span>per person</span></div>
                        <div class="itinerary-card-actions">
                            <a class="itinerary-action itinerary-action--primary" href="{{ route('admin.itineraries.edit', $itinerary) }}"><i data-lucide="square-pen"></i>Edit itinerary</a>
                            <a class="itinerary-action" title="Preview" href="{{ route('admin.itineraries.show', $itinerary) }}"><i data-lucide="eye"></i></a>
                            <a class="itinerary-action" title="Download PDF" href="{{ route('admin.itineraries.pdf', $itinerary) }}"><i data-lucide="file-down"></i></a>
                            <form method="POST" action="{{ route('admin.itineraries.duplicate', $itinerary) }}">@csrf<button class="itinerary-action" title="Duplicate"><i data-lucide="copy"></i></button></form>
                            <form method="POST" action="{{ route('admin.itineraries.destroy', $itinerary) }}" onsubmit="return confirm('Delete this itinerary and all its days and images?')">@csrf @method('DELETE')<button class="itinerary-action itinerary-action--danger" title="Delete"><i data-lucide="trash-2"></i></button></form>
                        </div>
                    </div>
                </div>
            </article>
        @empty
            <div class="ops-empty itinerary-empty"><i data-lucide="map"></i><h2>No itineraries found</h2><p>Create the first detailed safari program and add a cover image and daily plan.</p></div>
        @endforelse
    </div>
    <div class="ops-pagination">{{ $itineraries->links() }}</div>
</section>
@endsection
