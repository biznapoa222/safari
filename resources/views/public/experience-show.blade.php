@extends('layouts.public')

@section('title', $activity->name.' | Shishi Footsteps')
@section('description', $activity->description ?? $activity->name)
@section('og_image', is_array($activity->images) && count($activity->images) ? $activity->images[0] : 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=1800&q=82&fm=webp')

@section('content')
@php
    $translation = $activity->translation();
    $pName = $translation?->title ?? $activity->name;
    $pDesc = $translation?->description ?? $activity->description ?? '';
    $hero = is_array($activity->images) && count($activity->images) ? $activity->images[0] : 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=1800&q=82&fm=webp';
@endphp

<x-public.page-hero
    :label="$activity->category?->name ?? 'Experience'"
    :title="$pName"
    :subtitle="($activity->country ?? '').($activity->region ? ' / '.$activity->region : '')"
    :image="$hero"
/>

<section class="content-band">
    <div class="experience-detail-layout">
        <div class="experience-detail-main">
            @if(is_array($activity->images) && count($activity->images) > 1)
                <section class="detail-block">
                    <h2 class="detail-section-title">Gallery</h2>
                    <div class="experience-gallery">
                        @foreach($activity->images as $img)
                            <div class="gallery-item">
                                <img src="{{ $img }}" alt="{{ $pName }}" loading="lazy">
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            @if($pDesc)
                <section class="detail-block">
                    <h2 class="detail-section-title">About This Experience</h2>
                    <div class="detail-description">{{ $pDesc }}</div>
                </section>
            @endif

            @if($activity->seasons->isNotEmpty())
                <section class="detail-block">
                    <h2 class="detail-section-title">Available Seasons</h2>
                    <div class="season-list">
                        @foreach($activity->seasons as $season)
                            <div class="season-item">
                                <i data-lucide="calendar"></i>
                                <div>
                                    <strong>{{ $season->name }}</strong>
                                    <span>{{ \Carbon\Carbon::parse($season->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($season->end_date)->format('M d, Y') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            @if($activity->prices->isNotEmpty())
                <section class="detail-block">
                    <h2 class="detail-section-title">Pricing</h2>
                    <div class="price-grid">
                        @foreach($activity->prices as $price)
                            <div class="price-card">
                                <span class="price-type">{{ $price->type ?? 'Standard' }}</span>
                                @if($price->season)<span class="price-season">{{ $price->season }}</span>@endif
                                @if($price->year)<span class="price-year">{{ $price->year }}</span>@endif
                                <span class="price-amount">${{ number_format((float) $price->price, 2) }}</span>
                                <span class="price-currency">{{ $price->currency ?? 'USD' }}</span>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            @if($activity->keywords)
                <section class="detail-block">
                    <h2 class="detail-section-title">Tags</h2>
                    <div class="detail-tags">
                        @foreach(explode(',', $activity->keywords) as $keyword)
                            <span class="tag">{{ trim($keyword) }}</span>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>

        <aside class="experience-detail-sidebar">
            <div class="detail-sidebar-card">
                <div class="detail-quick-facts">
                    @if($activity->country)
                        <div class="quick-fact">
                            <i data-lucide="map-pin"></i>
                            <div><strong>{{ $activity->country }}</strong><small>Country</small></div>
                        </div>
                    @endif
                    @if($activity->region)
                        <div class="quick-fact">
                            <i data-lucide="compass"></i>
                            <div><strong>{{ $activity->region }}</strong><small>Region</small></div>
                        </div>
                    @endif
                    @if($activity->duration_hours)
                        <div class="quick-fact">
                            <i data-lucide="clock"></i>
                            <div><strong>{{ $activity->duration_hours }} hours</strong><small>Duration</small></div>
                        </div>
                    @endif
                    @if($activity->min_pax)
                        <div class="quick-fact">
                            <i data-lucide="users"></i>
                            <div><strong>Min {{ $activity->min_pax }} pax</strong><small>Group Size</small></div>
                        </div>
                    @endif
                    @if($activity->min_age)
                        <div class="quick-fact">
                            <i data-lucide="baby"></i>
                            <div><strong>Min age {{ $activity->min_age }}+</strong><small>Age Requirement</small></div>
                        </div>
                    @endif
                    @if($activity->location)
                        <div class="quick-fact">
                            <i data-lucide="map"></i>
                            <div><strong>{{ $activity->location }}</strong><small>Meeting Point</small></div>
                        </div>
                    @endif
                    @if($activity->pickup_time)
                        <div class="quick-fact">
                            <i data-lucide="clock"></i>
                            <div><strong>{{ $activity->pickup_time }}</strong><small>Pickup Time</small></div>
                        </div>
                    @endif
                </div>

                <a href="{{ route('public.booking') }}" class="button dark-button" style="width:100%;justify-content:center;">
                    Add to My Safari<i data-lucide="arrow-up-right"></i>
                </a>
            </div>

            <div class="detail-sidebar-categories">
                <h3>Experience Categories</h3>
                @foreach($categories as $cat)
                    <a href="{{ route('public.experiences').'#'.$cat->slug }}" class="category-link {{ $cat->id === $activity->activity_category_id ? 'is-active' : '' }}">
                        {{ $cat->name }}
                        <span>{{ $cat->activities_count }}</span>
                    </a>
                @endforeach
            </div>
        </aside>
    </div>
</section>

<x-public.cta-section
    :label="$activity->category?->name ?? 'Experience'"
    :title="'Experience '.$pName"
    text="Our specialists can include this experience in your custom safari itinerary at the best seasonal rates."
    :image="$hero"
    buttonText="Inquire About This Experience"
/>
@endsection
