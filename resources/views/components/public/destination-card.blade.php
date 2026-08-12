@props(['title', 'description', 'image', 'url' => null])

<article class="destination-card">
    <a href="{{ $url ?? route('public.safaris') }}" class="destination-card-media" aria-label="Explore {{ $title }}"><img src="{{ $image }}" alt="{{ $title }}" loading="lazy"></a>
    <div class="card-shade"></div>
    <div>
        <h3>{{ $title }}</h3>
        <p>{{ $description }}</p>
        <a href="{{ $url ?? route('public.safaris') }}">View Safaris<i data-lucide="arrow-up-right"></i></a>
    </div>
</article>
