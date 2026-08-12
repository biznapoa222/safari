@props(['title', 'description', 'image', 'icon' => 'sparkles', 'url' => null])

<article class="experience-card">
    <a href="{{ $url ?? route('public.experiences') }}" class="experience-card-link">
        <img src="{{ $image }}" alt="{{ $title }}" loading="lazy">
        <div>
            <span><i data-lucide="{{ $icon }}"></i></span>
            <h3>{{ $title }}</h3>
            <p>{{ $description }}</p>
        </div>
    </a>
</article>
