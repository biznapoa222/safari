@extends('layouts.public')

@section('title', $accommodation->name.' | Shishi Footsteps')
@section('description', $accommodation->description ?? $accommodation->name)
@section('og_image', is_array($accommodation->images) && count($accommodation->images) ? $accommodation->images[0] : 'https://images.unsplash.com/photo-1491555103944-7c647fd857e6?auto=format&fit=crop&w=1800&q=82&fm=webp')

@section('content')
@php
    $hero = is_array($accommodation->images) && count($accommodation->images) ? $accommodation->images[0] : 'https://images.unsplash.com/photo-1491555103944-7c647fd857e6?auto=format&fit=crop&w=1800&q=82&fm=webp';
    $typeLabel = \App\Models\Accommodation::$types[$accommodation->type] ?? $accommodation->type ?? 'Accommodation';
@endphp

<x-public.page-hero
    :label="$typeLabel"
    :title="$accommodation->name"
    :subtitle="trim(($accommodation->country ?? '').' / '.($accommodation->region ?? ''), ' /')"
    :image="$hero"
/>

<section class="content-band">
    <div class="accommodation-detail-layout">
        <div class="accommodation-detail-main">
            @if($accommodation->description)
                <section class="detail-block">
                    <h2 class="detail-section-title">About {{ $accommodation->name }}</h2>
                    <div class="detail-description">{{ $accommodation->description }}</div>
                </section>
            @endif

            @if($accommodation->rooms->isNotEmpty())
                <section class="detail-block">
                    <h2 class="detail-section-title">Rooms & Suites</h2>
                    <div class="room-grid">
                        @foreach($accommodation->rooms as $room)
                            <article class="room-card">
                                <div class="room-card-header">
                                    <h3>{{ $room->name }}</h3>
                                    @if($room->capacity)<span class="room-capacity"><i data-lucide="users"></i>Up to {{ $room->capacity }} guests</span>@endif
                                </div>
                                @if($room->max_adults || $room->max_children)
                                    <div class="room-occupancy">
                                        @if($room->max_adults)<span><i data-lucide="user"></i>{{ $room->max_adults }} adults max</span>@endif
                                        @if($room->max_children)<span><i data-lucide="baby"></i>{{ $room->max_children }} children max</span>@endif
                                    </div>
                                @endif
                                @if($room->inventory)
                                    <p class="room-inventory">{{ $room->inventory }} room(s) available</p>
                                @endif
                                @if($room->rates->isNotEmpty())
                                    <div class="room-rates">
                                        <span class="rates-label">Seasonal Rates</span>
                                        <table class="rates-table">
                                            <thead>
                                                <tr><th>Season</th><th>Valid</th><th>Rate/Night</th><th>Meal Plan</th></tr>
                                            </thead>
                                            <tbody>
                                                @foreach($room->rates as $rate)
                                                    <tr>
                                                        <td><strong>{{ $rate->season_name }}</strong></td>
                                                        <td>{{ $rate->valid_from?->format('M d') }} - {{ $rate->valid_to?->format('M d, Y') }}</td>
                                                        <td class="rate-amount">${{ number_format((float) $rate->rate, 2) }}</td>
                                                        <td>{{ $rate->meal_plan ?? '—' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        @if($room->rates->first()?->currency)
                                            <span class="rates-currency">All rates in {{ $room->rates->first()->currency }}</span>
                                        @endif
                                    </div>
                                @endif
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif

            @if($accommodation->notes)
                <section class="detail-block">
                    <h2 class="detail-section-title">Additional Notes</h2>
                    <div class="detail-description">{{ $accommodation->notes }}</div>
                </section>
            @endif
        </div>

        <aside class="accommodation-detail-sidebar">
            <div class="detail-sidebar-card">
                <div class="detail-quick-facts">
                    @if($accommodation->country)
                        <div class="quick-fact">
                            <i data-lucide="map-pin"></i>
                            <div><strong>{{ $accommodation->country }}</strong><small>Country</small></div>
                        </div>
                    @endif
                    @if($accommodation->region)
                        <div class="quick-fact">
                            <i data-lucide="compass"></i>
                            <div><strong>{{ $accommodation->region }}</strong><small>Region</small></div>
                        </div>
                    @endif
                    @if($accommodation->type)
                        <div class="quick-fact">
                            <i data-lucide="building-2"></i>
                            <div><strong>{{ $typeLabel }}</strong><small>Type</small></div>
                        </div>
                    @endif
                    @if($accommodation->luxury_level)
                        <div class="quick-fact">
                            <i data-lucide="star"></i>
                            <div><strong>{{ $accommodation->luxury_level }}</strong><small>Luxury Level</small></div>
                        </div>
                    @endif
                    @if($accommodation->category)
                        <div class="quick-fact">
                            <i data-lucide="tag"></i>
                            <div><strong>{{ $accommodation->category }}</strong><small>Category</small></div>
                        </div>
                    @endif
                </div>

                <a href="{{ route('public.booking') }}" class="button dark-button" style="width:100%;justify-content:center;">
                    Include in My Safari<i data-lucide="arrow-up-right"></i>
                </a>

                @if($accommodation->website || $accommodation->phone || $accommodation->email)
                    <div class="detail-contact-info">
                        <h4>Contact</h4>
                        @if($accommodation->website)<a href="{{ $accommodation->website }}" target="_blank" rel="noopener"><i data-lucide="globe"></i>{{ $accommodation->website }}</a>@endif
                        @if($accommodation->phone)<span><i data-lucide="phone"></i>{{ $accommodation->phone }}</span>@endif
                        @if($accommodation->email)<span><i data-lucide="mail"></i>{{ $accommodation->email }}</span>@endif
                    </div>
                @endif
            </div>

            @if($related->isNotEmpty())
                <div class="detail-sidebar-related">
                    <h3>More Accommodations</h3>
                    @foreach($related as $rel)
                        @php $relImg = is_array($rel->images) && count($rel->images) ? $rel->images[0] : 'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?auto=format&fit=crop&w=1200&q=82&fm=webp'; @endphp
                        <a href="{{ route('public.accommodations.show', $rel->slug) }}" class="related-item">
                            <img src="{{ $relImg }}" alt="{{ $rel->name }}" loading="lazy">
                            <div>
                                <strong>{{ $rel->name }}</strong>
                                <span>{{ $rel->country ?? '' }}@if($rel->region) / {{ $rel->region }}@endif</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </aside>
    </div>
</section>

<x-public.cta-section
    label="Accommodation"
    title="Book This Stay As Part of Your Journey"
    text="Our specialists will include this accommodation in your custom safari itinerary, matching room types and seasons to your travel dates."
    :image="$hero"
    buttonText="Inquire Now"
/>
@endsection
