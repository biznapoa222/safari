@extends('layouts.public')

@section('title', $itinerary->title.' Itinerary | Shishi Footsteps')
@section('description', $itinerary->summary ?? $itinerary->title)
@section('og_image', is_array($itinerary->images) && count($itinerary->images) ? \App\Support\MediaPath::publicUrl($itinerary->images[0]) : asset('images/itineraries/kenya-family-cover.webp'))

@section('content')
@php
    $hero = is_array($itinerary->images) && count($itinerary->images) ? \App\Support\MediaPath::publicUrl($itinerary->images[0]) : asset('images/itineraries/kenya-family-cover.webp');
@endphp

<x-public.page-hero
    :label="$itinerary->country ?? 'Itinerary'"
    :title="$itinerary->title"
    :subtitle="$itinerary->summary ?? 'A thoughtfully paced safari itinerary.'"
    :image="$hero"
/>

<section class="content-band">
    <div class="itinerary-detail-layout">
        <div class="itinerary-detail-main">
            <div class="itinerary-stats-bar">
                @if($itinerary->duration_days)
                    <div class="stat-badge"><i data-lucide="calendar-days"></i>{{ $itinerary->duration_days }} Days</div>
                @endif
                @if($itinerary->country)
                    <div class="stat-badge"><i data-lucide="map-pin"></i>{{ $itinerary->country }}</div>
                @endif
                @if($itinerary->region)
                    <div class="stat-badge"><i data-lucide="compass"></i>{{ $itinerary->region }}</div>
                @endif
                @if($itinerary->price_from)
                    <div class="stat-badge highlight"><i data-lucide="dollar-sign"></i>From ${{ number_format((float) $itinerary->price_from) }}</div>
                @endif
            </div>

            @if($itinerary->days->isNotEmpty())
                <section class="detail-block">
                    <h2 class="detail-section-title">Full Itinerary</h2>
                    <div class="itinerary-timeline">
                        @foreach($itinerary->days as $day)
                            <div class="timeline-entry">
                                <div class="timeline-marker">
                                    <span>{{ $day->day_number }}</span>
                                </div>
                                <div class="timeline-content">
                                    <h3>{{ $day->title ?? 'Day '.$day->day_number }}</h3>
                                    @if($day->location)
                                        <p class="timeline-location"><i data-lucide="map-pin"></i>{{ $day->location }}</p>
                                    @endif
                                    @if($day->activities)
                                        <p class="timeline-activities">{{ $day->activities }}</p>
                                    @endif
                                    <div class="timeline-meta">
                                        @if($day->meal_plan)<span><i data-lucide="utensils-crossed"></i>{{ $day->meal_plan }}</span>@endif
                                        @if($day->transfers)<span><i data-lucide="car"></i>{{ $day->transfers }}</span>@endif
                                        @if($day->notes)<span><i data-lucide="info"></i>{{ $day->notes }}</span>@endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            @if(!empty($itinerary->inclusions))
                <section class="detail-block">
                    <h2 class="detail-section-title">Inclusions</h2>
                    <ul class="detail-checklist">
                        @foreach($itinerary->inclusions as $inclusion)
                            <li><i data-lucide="check-circle"></i>{{ $inclusion }}</li>
                        @endforeach
                    </ul>
                </section>
            @endif

            @if(!empty($itinerary->exclusions))
                <section class="detail-block">
                    <h2 class="detail-section-title">Exclusions</h2>
                    <ul class="detail-checklist muted">
                        @foreach($itinerary->exclusions as $exclusion)
                            <li><i data-lucide="x-circle"></i>{{ $exclusion }}</li>
                        @endforeach
                    </ul>
                </section>
            @endif
        </div>

        <aside class="itinerary-detail-sidebar">
            <div class="detail-sidebar-card">
                <div class="detail-quick-facts">
                    @if($itinerary->duration_days)
                        <div class="quick-fact">
                            <i data-lucide="calendar-days"></i>
                            <div><strong>{{ $itinerary->duration_days }} days</strong><small>Duration</small></div>
                        </div>
                    @endif
                    @if($itinerary->price_from)
                        <div class="quick-fact">
                            <i data-lucide="dollar-sign"></i>
                            <div><strong>${{ number_format((float) $itinerary->price_from) }}</strong><small>From per person</small></div>
                        </div>
                    @endif
                    @if($itinerary->country)
                        <div class="quick-fact">
                            <i data-lucide="map-pin"></i>
                            <div><strong>{{ $itinerary->country }}</strong><small>Destination</small></div>
                        </div>
                    @endif
                </div>

                <a href="{{ route('public.booking', array_filter(['itinerary_id' => $itinerary->id ?? null, 'itinerary_slug' => $itinerary->slug ?? null, 'itinerary_title' => $itinerary->title ?? null, 'itinerary_url' => url()->current()])) }}" class="button dark-button" style="width:100%;justify-content:center;">
                    Request This Itinerary<i data-lucide="arrow-up-right"></i>
                </a>
            </div>

            @if($related->isNotEmpty())
                <div class="detail-sidebar-related">
                    <h3>More Itineraries</h3>
                    @foreach($related as $rel)
                        @php $relImg = is_array($rel->images) && count($rel->images) ? \App\Support\MediaPath::publicUrl($rel->images[0]) : asset('images/itineraries/kenya-family-cover.webp'); @endphp
                        <a href="{{ route('public.itineraries.show', $rel->slug) }}" class="related-item">
                            <img src="{{ $relImg }}" alt="{{ $rel->title }}" loading="lazy">
                            <div>
                                <strong>{{ $rel->title }}</strong>
                                <span>{{ $rel->duration_days ?? 'Custom' }} days</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </aside>
    </div>
</section>

<x-public.cta-section
    label="Your Safari"
    title="Turn This Itinerary Into Your Journey"
    text="This itinerary is a starting point. Share your preferences and our specialists will refine every detail to match your style, budget and travel dates."
    :image="$hero"
    buttonText="Start Planning This Route"
/>
@endsection
