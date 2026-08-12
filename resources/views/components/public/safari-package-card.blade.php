@props(['title', 'summary', 'image', 'duration' => null, 'country' => null, 'price' => null, 'url' => null, 'slug' => null])

<article class="safari-card">
    <a href="{{ $url ?? ($slug ? route('public.safaris.show', $slug) : route('public.booking')) }}" class="safari-card-link">
        <img src="{{ $image }}" alt="{{ $title }}" loading="lazy">
        <div class="safari-card-body">
            <div class="safari-meta">
                @if($duration)<span><i data-lucide="calendar-days"></i>{{ $duration }}</span>@endif
                @if($country)<span><i data-lucide="map-pin"></i>{{ $country }}</span>@endif
            </div>
            <h3>{{ $title }}</h3>
            <p>{{ $summary }}</p>
            <div class="safari-card-footer">
                <span>{{ $price ? 'From '.$price : 'Tailor-made pricing' }}</span>
                <span class="safari-card-cta">View itinerary<i data-lucide="arrow-up-right"></i></span>
            </div>
        </div>
    </a>
</article>
