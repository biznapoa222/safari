@extends('layouts.public')

@section('title', $safari->title.' | Shishi Footsteps')
@section('description', $safari->summary ?? $safari->title)
@section('og_image', is_array($safari->images) && count($safari->images) ? \App\Support\MediaPath::publicUrl($safari->images[0]) : asset('images/itineraries/kenya-family-cover.webp'))

@section('content')
@php
    $hero = is_array($safari->images) && count($safari->images) ? \App\Support\MediaPath::publicUrl($safari->images[0]) : asset('images/itineraries/kenya-family-cover.webp');
@endphp

<x-public.page-hero
    :label="$safari->country ?? 'Safari'"
    :title="$safari->title"
    :subtitle="$safari->summary ?? 'Experience the journey of a lifetime.'"
    :image="$hero"
/>

<section class="content-band">
    <div class="safari-detail-layout">
        <div class="safari-detail-main">
            @if($safari->days->isNotEmpty())
                <h2 class="detail-section-title">Day-by-Day Itinerary</h2>
                @foreach($safari->days as $day)
                    <article class="itinerary-day-card">
                        <div class="itinerary-day-number">
                            <span>Day</span>
                            <strong>{{ $day->day_number }}</strong>
                        </div>
                        <div class="itinerary-day-body">
                            <h3>{{ $day->title ?? 'Day '.$day->day_number }}</h3>
                            @if($day->location)<p class="itinerary-day-location"><i data-lucide="map-pin"></i>{{ $day->location }}</p>@endif
                            @if($day->activities)<p class="itinerary-day-activities">{{ $day->activities }}</p>@endif
                            <div class="itinerary-day-meta">
                                @if($day->meal_plan)<span><i data-lucide="utensils-crossed"></i>{{ $day->meal_plan }}</span>@endif
                                @if($day->transfers)<span><i data-lucide="car"></i>{{ $day->transfers }}</span>@endif
                                @if($day->notes)<span><i data-lucide="info"></i>{{ $day->notes }}</span>@endif
                            </div>
                        </div>
                    </article>
                @endforeach
            @endif

            @if(!empty($safari->inclusions))
                <section class="detail-block">
                    <h2 class="detail-section-title">Inclusions</h2>
                    <ul class="detail-checklist">
                        @foreach($safari->inclusions as $inclusion)
                            <li><i data-lucide="check-circle"></i>{{ $inclusion }}</li>
                        @endforeach
                    </ul>
                </section>
            @endif

            @if(!empty($safari->exclusions))
                <section class="detail-block">
                    <h2 class="detail-section-title">Exclusions</h2>
                    <ul class="detail-checklist muted">
                        @foreach($safari->exclusions as $exclusion)
                            <li><i data-lucide="x-circle"></i>{{ $exclusion }}</li>
                        @endforeach
                    </ul>
                </section>
            @endif
        </div>

        <aside class="safari-detail-sidebar">
            <div class="detail-sidebar-card">
                <div class="detail-price">
                    @if($safari->price_from)
                        <span class="detail-price-label">From</span>
                        <span class="detail-price-value">${{ number_format((float) $safari->price_from) }}</span>
                        <span class="detail-price-note">per person sharing</span>
                    @else
                        <span class="detail-price-label">Tailor-Made Pricing</span>
                        <span class="detail-price-value">Custom</span>
                    @endif
                </div>

                <div class="detail-quick-facts">
                    @if($safari->duration_days)
                        <div class="quick-fact">
                            <i data-lucide="calendar-days"></i>
                            <div><strong>{{ $safari->duration_days }} days</strong><small>Duration</small></div>
                        </div>
                    @endif
                    @if($safari->country)
                        <div class="quick-fact">
                            <i data-lucide="map-pin"></i>
                            <div><strong>{{ $safari->country }}</strong><small>Destination</small></div>
                        </div>
                    @endif
                    @if($safari->region)
                        <div class="quick-fact">
                            <i data-lucide="compass"></i>
                            <div><strong>{{ $safari->region }}</strong><small>Region</small></div>
                        </div>
                    @endif
                    <div class="quick-fact">
                        <i data-lucide="users"></i>
                        <div><strong>Private</strong><small>Exclusively yours</small></div>
                    </div>
                </div>

                <a href="{{ route('public.booking', array_filter(['itinerary_id' => $safari->id ?? null, 'itinerary_slug' => $safari->slug ?? null, 'itinerary_title' => $safari->title ?? null, 'itinerary_url' => url()->current()])) }}" class="button dark-button" style="width:100%;justify-content:center;">
                    Plan This Safari<i data-lucide="arrow-up-right"></i>
                </a>
            </div>

            @if($related->isNotEmpty())
                <div class="detail-sidebar-related">
                    <h3>Other Safaris</h3>
                    @foreach($related as $rel)
                        @php $relImg = is_array($rel->images) && count($rel->images) ? \App\Support\MediaPath::publicUrl($rel->images[0]) : asset('images/itineraries/kenya-family-cover.webp'); @endphp
                        <a href="{{ route('public.safaris.show', $rel->slug) }}" class="related-item">
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
    label="Your Private Safari"
    title="Let Us Shape This Journey Around You"
    text="Tell us your preferred dates and travel style, and our specialists will refine this itinerary to match exactly what you envision."
    :image="$hero"
    buttonText="Start Planning"
/>
@endsection
