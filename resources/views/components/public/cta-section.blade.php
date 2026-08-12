@props(['label' => 'Start Planning', 'title', 'text' => null, 'image', 'buttonText' => 'Start Planning', 'url' => null])

<section class="cta-banner">
    <a href="{{ $url ?? route('public.booking') }}" class="cta-image-link" aria-label="{{ $buttonText }}"><img src="{{ $image }}" alt="{{ $title }}" loading="lazy"></a>
    <div></div>
    <article>
        <x-public.section-label :label="$label" class="light" />
        <h2>{{ $title }}</h2>
        @if($text)<p>{{ $text }}</p>@endif
        <a href="{{ $url ?? route('public.booking') }}" class="button hero-primary">{{ $buttonText }}<i data-lucide="arrow-up-right"></i></a>
    </article>
</section>
