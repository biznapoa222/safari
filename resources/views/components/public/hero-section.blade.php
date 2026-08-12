@props([
    'label' => 'Welcome to Shishi Footsteps',
    'title',
    'subtitle' => null,
    'image',
    'primaryText' => 'Plan Your Safari',
    'primaryUrl' => null,
    'secondaryText' => null,
    'secondaryUrl' => null,
])

<section class="luxury-hero">
    <a href="{{ $primaryUrl ?? route('public.booking') }}" class="luxury-hero-image-link" aria-label="{{ $primaryText }}"><picture class="luxury-hero-media">
        <source srcset="{{ $image }}" type="image/webp">
        <img src="{{ $image }}" alt="{{ $title }}" fetchpriority="high">
    </picture></a>
    <div class="luxury-hero-shade"></div>
    <div class="luxury-hero-content">
        <x-public.section-label :label="$label" class="light" />
        <h1>{{ $title }}</h1>
        @if($subtitle)<p>{{ $subtitle }}</p>@endif
        <div class="hero-actions">
            <a href="{{ $primaryUrl ?? route('public.booking') }}" class="button hero-primary">{{ $primaryText }}<i data-lucide="arrow-up-right"></i></a>
            @if($secondaryText)
                <a href="{{ $secondaryUrl ?? route('public.destinations') }}" class="button hero-secondary">{{ $secondaryText }}</a>
            @endif
        </div>
    </div>
    <a href="#start" class="scroll-cue"><span>Scroll</span><i data-lucide="arrow-down"></i></a>
</section>
