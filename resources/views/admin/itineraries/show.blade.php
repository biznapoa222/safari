@extends('layouts.admin')
@section('title', $itinerary->title)

@section('content')
<div class="itinerary-preview-toolbar">
    <a class="button button-secondary" href="{{ route('admin.itineraries.index') }}"><i data-lucide="arrow-left"></i>Itinerary list</a>
    <div><a class="button button-secondary" href="{{ route('admin.itineraries.edit', $itinerary) }}"><i data-lucide="square-pen"></i>Edit</a><a class="button button-primary" href="{{ route('admin.itineraries.pdf', $itinerary) }}"><i data-lucide="file-down"></i>Download PDF</a></div>
</div>

<article class="itinerary-preview">
    <header class="itinerary-hero {{ $itinerary->cover_image ? '' : 'no-image' }}">
        @if($itinerary->cover_image)<img src="{{ $itinerary->cover_image_url }}" alt="{{ $itinerary->title }}">@endif
        <div class="itinerary-hero-overlay"></div>
        <div class="itinerary-hero-content"><span>{{ $itinerary->countries }} · {{ $itinerary->travel_style }}</span><h1>{{ $itinerary->title }}</h1><p>{{ $itinerary->summary }}</p></div>
    </header>
    <div class="itinerary-facts">
        <div><small>Duration</small><strong>{{ $itinerary->duration_days }} days / {{ $itinerary->nights }} nights</strong></div>
        <div><small>Route</small><strong>{{ $itinerary->start_location }} to {{ $itinerary->end_location }}</strong></div>
        <div><small>Best time</small><strong>{{ $itinerary->best_time ?: 'Year-round' }}</strong></div>
        <div><small>From</small><strong>{{ $itinerary->currency }} {{ number_format($itinerary->price_from) }} per person</strong></div>
    </div>
    <section class="itinerary-introduction"><p class="eyebrow">Your journey</p><h2>An East African story, thoughtfully paced</h2><div>{!! nl2br(e($itinerary->description ?: $itinerary->summary)) !!}</div></section>
    <section class="itinerary-preview-days">
        <div class="preview-section-heading"><p class="eyebrow">Day by day</p><h2>Your safari program</h2></div>
        @foreach($itinerary->days as $day)
            <article class="preview-day">
                <div class="preview-day-number"><span>Day</span><strong>{{ str_pad($day->day_number, 2, '0', STR_PAD_LEFT) }}</strong></div>
                <div class="preview-day-content">
                    <span>{{ $day->location }}</span><h3>{{ $day->title }}</h3>
                    @if($day->primary_image)<img class="preview-day-primary" src="{{ $day->primary_image_url }}" alt="{{ $day->title }}" loading="lazy">@endif
                    <p class="day-lead">{{ $day->summary }}</p>
                    <div class="day-description">{!! nl2br(e($day->description)) !!}</div>
                    @php $dayActivities = is_string($day->activities) ? json_decode($day->activities, true) ?: [] : ($day->activities ?: []); @endphp
                    @if($dayActivities)<div class="day-activities">@foreach($dayActivities as $activity)<span><i data-lucide="check"></i>{{ $activity }}</span>@endforeach</div>@endif
                    <div class="day-logistics">
                        @if($day->accommodation)<span><i data-lucide="bed-double"></i><b>Stay</b>{{ $day->accommodation }}</span>@endif
                        @if($day->meal_plan)<span><i data-lucide="utensils"></i><b>Meals</b>{{ $day->meal_plan }}</span>@endif
                        @if($day->distance_km)<span><i data-lucide="car-front"></i><b>Journey</b>{{ $day->distance_km }} km · {{ number_format($day->driving_hours, 1) }} hrs</span>@endif
                    </div>
                    @if($day->images->isNotEmpty())<div class="preview-day-gallery">@foreach($day->images->take(3) as $image)<img src="{{ $image->url }}" alt="{{ $image->alt_text }}" loading="lazy">@endforeach</div>@endif
                </div>
            </article>
        @endforeach
    </section>
    <section class="itinerary-inclusions">
        <div><p class="eyebrow">Included</p><h2>What is covered</h2>@foreach($itinerary->inclusions ?? [] as $item)<p><i data-lucide="check-circle-2"></i>{{ $item }}</p>@endforeach</div>
        <div><p class="eyebrow">Not included</p><h2>Plan separately</h2>@foreach($itinerary->exclusions ?? [] as $item)<p><i data-lucide="x-circle"></i>{{ $item }}</p>@endforeach</div>
    </section>
    @if($itinerary->important_notes)<section class="itinerary-notes"><h2>Important journey notes</h2><p>{{ $itinerary->important_notes }}</p></section>@endif
</article>
@endsection
