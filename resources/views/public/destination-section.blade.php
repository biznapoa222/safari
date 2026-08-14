@extends('layouts.public')

@section('title', $sectionData['title'].' | Shishi Footsteps')
@section('description', $sectionData['summary'])

@section('content')
<x-public.page-hero
    :label="$sectionData['eyebrow']"
    :title="$sectionData['title']"
    :subtitle="$sectionData['summary']"
    :image="$sectionData['image']"
    :url="route('public.booking', ['destination' => $name, 'interest' => $sectionData['nav']])"
/>

<nav class="country-breadcrumb">
    <a href="{{ route('home') }}">Home</a><span>&rsaquo;</span>
    <a href="{{ route('public.destinations') }}">Countries</a><span>&rsaquo;</span>
    <a href="{{ route('public.destinations.show', $slug) }}">{{ $name }}</a><span>&rsaquo;</span>
    <b>{{ $sectionData['nav'] }}</b>
</nav>

<section class="destination-section-page">
    <aside class="destination-section-menu">
        <strong>{{ $name }} guide</strong>
        @foreach($sections as $key => $item)
            <a href="{{ route('public.destinations.section', [$slug, $key]) }}" @class(['active' => $key === $section])>
                {{ $item['nav'] }}
            </a>
        @endforeach
        @if(in_array($slug, ['kenya', 'tanzania', 'uganda', 'rwanda', 'south-africa'], true))
            <a href="{{ route('public.tee-off.country', $slug) }}">Golf safari</a>
        @else
            <a href="{{ route('public.golf') }}">Golf safari</a>
        @endif
        <a href="{{ route('public.blog', ['destination' => $name]) }}">All journal stories</a>
    </aside>

    <article class="destination-section-story">
        <x-public.section-label :label="$sectionData['eyebrow']" />
        <h2>{{ $sectionData['heading'] }}</h2>
        @foreach($sectionData['paragraphs'] as $i => $paragraph)
            <p>{{ $paragraph }}</p>
        @endforeach

        <div class="destination-section-points">
            @foreach($sectionData['bullets'] as $bullet)
                <span><i data-lucide="check"></i>{{ $bullet }}</span>
            @endforeach
        </div>

        <div class="destination-section-actions">
            <a href="{{ route('public.booking', ['destination' => $name, 'interest' => $sectionData['nav']]) }}" class="button hero-primary">Plan your {{ $name }} trip<i data-lucide="arrow-up-right"></i></a>
            <a href="{{ route('public.destinations.show', $slug) }}" class="button hero-secondary">View full {{ $name }} overview<i data-lucide="arrow-up-right"></i></a>
            @if($section === 'journal')
                <a href="{{ route('public.blog', ['destination' => $name]) }}" class="button hero-secondary">Browse the full journal<i data-lucide="book-open"></i></a>
            @endif
            @if($section === 'reviews')
                <a href="{{ route('public.booking', ['destination' => $name]) }}" class="button hero-secondary">Request a proposal<i data-lucide="pen-line"></i></a>
            @endif
        </div>
    </article>
</section>

@if($section === 'reviews' && ($travellerReviews ?? []))
    <section class="traveller-reviews-band" id="reviews">
        <div class="section-heading">
            <div>
                <x-public.section-label label="Guest notes" />
                <h2>{{ $name }} in their words</h2>
            </div>
        </div>
        <div class="traveller-reviews">
            @foreach($travellerReviews as $review)
                <article class="traveller-review{{ $loop->even ? ' traveller-review--end' : '' }}">
                    <img src="{{ \App\Support\MediaPath::publicUrl($review['image']) }}" alt="{{ $review['image_alt'] ?? $review['trip'] }}" loading="lazy">
                    <div>
                        <blockquote>{{ $review['quote'] }}</blockquote>
                        <p>
                            <strong>{{ $review['name'] }}</strong>
                            <span>{{ $review['from'] }} · {{ $review['trip'] }}</span>
                        </p>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
@endif

@if($section !== 'reviews' && ($journalPosts ?? collect())->isNotEmpty())
    <section class="content-band destination-related">
        <div class="section-heading">
            <div>
                <x-public.section-label label="The journal" />
                <h2>{{ $name }} stories to read</h2>
            </div>
            <a href="{{ route('public.blog', ['destination' => $name]) }}">All articles<i data-lucide="arrow-right"></i></a>
        </div>
        <div class="destination-related-grid">
            @foreach($journalPosts as $post)
                <a href="{{ route('public.blog.post', $post->slug) }}" class="destination-related-card">
                    <img src="{{ \App\Support\MediaPath::publicUrl($post->cover_image) ?: 'https://images.unsplash.com/photo-1489392191049-fc10c97e64b6?auto=format&fit=crop&w=900&q=84&fm=webp' }}" alt="{{ $post->title }}" loading="lazy">
                    <small>Journal</small>
                    <strong>{{ $post->title }}</strong>
                    <span>{{ $post->published_at?->format('M d, Y') ?: 'Travel story' }}</span>
                </a>
            @endforeach
        </div>
    </section>
@endif

@if($safaris->isNotEmpty() || $activities->isNotEmpty() || $accommodations->isNotEmpty())
    <section class="content-band destination-related">
        <div class="section-heading">
            <div>
                <x-public.section-label label="Related ideas" />
                <h2>{{ $name }} experiences to explore</h2>
            </div>
        </div>
        <div class="destination-related-grid">
            @foreach($safaris as $safari)
                <a href="{{ route('public.safaris.show', $safari->slug) }}" class="destination-related-card">
                    <img src="{{ is_array($safari->images ?? null) && count($safari->images) ? \App\Support\MediaPath::publicUrl($safari->images[0]) : asset('images/itineraries/kenya-family-cover.webp') }}" alt="{{ $safari->title }}" loading="lazy">
                    <small>Safari</small>
                    <strong>{{ $safari->title }}</strong>
                    <span>{{ $safari->duration_days }} days</span>
                </a>
            @endforeach
            @foreach($accommodations as $accommodation)
                <a href="{{ $accommodation->slug ? route('public.accommodations.show', $accommodation->slug) : route('public.accommodations') }}" class="destination-related-card">
                    <img src="{{ is_array($accommodation->images ?? null) && count($accommodation->images) ? \App\Support\MediaPath::publicUrl($accommodation->images[0]) : asset('images/itineraries/botswana-luxury-cover.webp') }}" alt="{{ $accommodation->name }}" loading="lazy">
                    <small>Accommodation</small>
                    <strong>{{ $accommodation->name }}</strong>
                    <span>{{ trim(($accommodation->region ?? '').' / '.($accommodation->type ?? ''), ' /') ?: $name }}</span>
                </a>
            @endforeach
            @foreach($activities as $activity)
                @php $actImg = is_array($activity->images ?? null) && count($activity->images) ? $activity->images[0] : ($activity->image ?? null); @endphp
                <a href="{{ $activity->slug ? route('public.experiences.show', $activity->slug) : route('public.experiences') }}" class="destination-related-card">
                    <img src="{{ $actImg ? \App\Support\MediaPath::publicUrl($actImg) : asset('images/itineraries/kenya-coast-day.webp') }}" alt="{{ $activity->name }}" loading="lazy">
                    <small>Activity</small>
                    <strong>{{ $activity->name }}</strong>
                    <span>{{ $activity->location ?: $name }}</span>
                </a>
            @endforeach
        </div>
    </section>
@endif

<x-public.cta-section
    label="{{ $name }} Safari"
    title="Ready to shape this into a real itinerary?"
    text="Tell us your dates, travellers and travel style. We will turn the right {{ $name }} ideas into a smooth private journey."
    :image="$sectionData['image']"
    buttonText="Plan your trip"
    :url="route('public.booking', ['destination' => $name, 'interest' => $sectionData['nav']])"
/>
@endsection
