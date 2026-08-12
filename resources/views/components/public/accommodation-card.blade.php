@props(['title', 'description', 'image', 'meta' => null, 'reverse' => false, 'slug' => null])

<article class="accommodation-feature {{ $reverse ? 'reverse' : '' }}">
    <div class="accommodation-image">
        <a href="{{ $slug ? route('public.accommodations.show', $slug) : route('public.accommodations') }}">
            <img src="{{ $image }}" alt="{{ $title }}" loading="lazy">
        </a>
    </div>
    <div class="accommodation-copy">
        <x-public.section-label label="Luxury Accommodation" />
        <h2>{{ $title }}</h2>
        <p>{{ $description }}</p>
        @if($meta)<span>{{ $meta }}</span>@endif
        <a href="{{ $slug ? route('public.accommodations.show', $slug) : route('public.accommodations') }}" class="button dark-button">
            {{ $slug ? 'View Details' : 'Explore Accommodation' }}<i data-lucide="arrow-up-right"></i>
        </a>
    </div>
</article>
