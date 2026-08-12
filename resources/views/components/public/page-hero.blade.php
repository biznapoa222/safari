@props(['label', 'title', 'subtitle' => null, 'image', 'url' => null, 'youtubeId' => null])

<section class="page-hero">
    <a href="{{ $url ?? '#start' }}" class="page-hero-image-link" aria-label="Explore {{ $title }}"><img src="{{ $image }}" alt="{{ $title }}" loading="eager"></a>
    @if($youtubeId)
        <div class="hero-youtube-container" data-hero-video>
            <iframe
                class="hero-youtube-bg"
                src="https://www.youtube.com/embed/{{ $youtubeId }}?autoplay=1&mute=1&loop=1&playlist={{ $youtubeId }}&controls=0&showinfo=0&rel=0&modestbranding=1&playsinline=1&enablejsapi=1"
                title="{{ $title }} background video"
                allow="autoplay; encrypted-media; picture-in-picture"
                referrerpolicy="strict-origin-when-cross-origin"
                aria-hidden="true"
                tabindex="-1"></iframe>
        </div>
    @endif
    <div></div>
    <article>
        <x-public.section-label :label="$label" class="light" />
        <h1>{{ $title }}</h1>
        @if($subtitle)<p>{{ $subtitle }}</p>@endif
    </article>
</section>
