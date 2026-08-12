@extends('layouts.public')

@section('title', $settings->seo_title ?? 'Shishi Footsteps | Luxury African Safaris')
@section('description', $settings->seo_description ?? 'Premium tailor-made safari journeys across East Africa.')

@section('content')
@php
    $cms = fn($key, $fallback = '') => \App\Models\CmsContentBlock::value('home', $key, $fallback);
    $images = [
        'hero' => \App\Support\MediaPath::publicUrl($cms('hero_image', $settings->hero_image)) ?: asset('images/itineraries/kenya-family-cover.webp'),
        'adventure' => asset('images/itineraries/tanzania-classic-cover.webp'),
        'luxury' => asset('images/itineraries/botswana-luxury-cover.webp'),
        'culture' => asset('images/itineraries/kenya-coast-day.webp'),
        'accommodation' => asset('images/itineraries/botswana-luxury-cover.webp'),
        'cta' => \App\Support\MediaPath::publicUrl($cms('cta_image', 'images/itineraries/tanzania-crater-day.webp')),
    ];
    $youtubeId = $cms('youtube_id') ?: '1CYVG70ZbyQ';

    $countryImages = collect(['Kenya','Tanzania','Uganda','Rwanda','South Africa','Namibia','Botswana'])->mapWithKeys(function ($country) use ($settings) {
        $media = $settings->mediaFor(\Illuminate\Support\Str::slug($country));
        return [$country => \App\Support\MediaPath::publicUrl($media['hero'])];
    })->all();

    $destinationCopy = [
        'Kenya' => 'Big cat country, private conservancies, dramatic migration crossings and warm coastal endings.',
        'Tanzania' => 'Serengeti plains, Ngorongoro drama and wild southern parks made for unrushed safaris.',
        'Uganda' => 'Gorilla trekking, chimpanzee forests, lake country and deeply moving wildlife encounters.',
        'Rwanda' => 'Gorilla trekking, rolling hills, golden monkeys and intimate luxury lodges with volcano views.',
        'South Africa' => 'Private reserves, refined lodges, wine country and effortless family-friendly safari routes.',
        'Namibia' => 'Desert-adapted wildlife, sculptural dunes and remote lodges beneath huge star-filled skies.',
        'Botswana' => 'Okavango waterways, mobile camps, elephant-rich landscapes and pristine wilderness.',
    ];

    $erpCountries = $destinations->keyBy(fn ($country) => strtolower($country->name));
    $countryCards = collect(array_keys($countryImages))->map(
        fn ($name) => $erpCountries->get(strtolower($name)) ?? (object) ['name' => $name]
    );

    $experienceDefaults = [
        ['Game Drives', 'Private morning and golden-hour drives with guides who read the land with patience.', 'binoculars', 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=900&q=82&fm=webp'],
        ['Balloon Safaris', 'Float above open plains before a celebratory bush breakfast in the soft early light.', 'sunrise', 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=900&q=82&fm=webp'],
        ['Cultural Visits', 'Meet communities through respectful, locally guided encounters that add meaning.', 'users', 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=900&q=82&fm=webp'],
        ['Beach Extensions', 'Ease from the bush to island air with handpicked coast and barefoot luxury stays.', 'waves', 'https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&w=900&q=82&fm=webp'],
        ['Gorilla Trekking', 'A rare forest journey to spend quiet, unforgettable time with mountain gorillas.', 'leaf', 'https://images.unsplash.com/photo-1540573133985-87b6da6d54a9?auto=format&fit=crop&w=900&q=82&fm=webp'],
        ['Honeymoon Safaris', 'Romantic camps, private decks, candlelit dinners and journeys paced around you.', 'heart', 'https://images.unsplash.com/photo-1491555103944-7c647fd857e6?auto=format&fit=crop&w=900&q=82&fm=webp'],
    ];

    $packageImages = [
        asset('images/itineraries/kenya-family-cover.webp'),
        asset('images/itineraries/tanzania-classic-cover.webp'),
        asset('images/itineraries/botswana-luxury-cover.webp'),
    ];

    $featuredAccommodation = $featuredAccommodations->first();
@endphp

<section class="reference-home-hero lively-home-hero" id="start" data-safari-hero>
    <img src="{{ $images['hero'] }}" alt="Private Shishi Footsteps safari">
    <div class="hero-youtube-container">
        <iframe
            class="hero-youtube-bg"
            src="https://www.youtube.com/embed/{{ $youtubeId }}?autoplay=1&mute=1&loop=1&playlist={{ $youtubeId }}&controls=0&showinfo=0&rel=0&modestbranding=1&playsinline=1&enablejsapi=1"
            title="Shishi Footsteps safari background video"
            allow="autoplay; encrypted-media; picture-in-picture"
            referrerpolicy="strict-origin-when-cross-origin"
            aria-hidden="true"
            tabindex="-1"></iframe>
    </div>
    <button type="button" class="home-hero-image-link" data-trip-planner-open aria-label="Plan a trip inspired by this safari"></button>
    <div class="hero-ambient" aria-hidden="true"><span></span><span></span><span></span></div>
    <div class="reference-hero-copy">
        <span>{{ $cms('hero_label', 'Private African journeys') }}</span>
        <h1>{!! nl2br(e($cms('hero_title', 'Travel Africa, your way.'))) !!}</h1>
        <p>{{ $cms('hero_text', 'Tailor-made safaris, trusted local expertise and thoughtful details from the first idea to the journey home.') }}</p>
        <div class="reference-hero-actions">
            <button type="button" class="hero-plan-button" data-trip-planner-open>{{ __('ui.plan_your_safari') }}<i data-lucide="arrow-up-right"></i></button>
            <a href="{{ route('public.destinations') }}">Explore destinations<i data-lucide="compass"></i></a>
        </div>
    </div>
    <div class="hero-journey-points" aria-label="Shishi Footsteps benefits">
        <span><i data-lucide="route"></i><b>100%</b> tailor-made</span>
        <span><i data-lucide="users"></i><b>Local</b> specialists</span>
        <span><i data-lucide="shield-check"></i><b>Trusted</b> support</span>
    </div>
</section>

<dialog class="trip-planner-dialog" data-trip-planner aria-labelledby="trip-planner-title">
    <div class="trip-planner-shell">
        <header>
            <a href="{{ route('home') }}"><img src="{{ asset('images/brand/shishi-paw-white.png') }}" alt=""><span>Shishi Footsteps</span></a>
            <button type="button" data-trip-planner-close aria-label="Close trip planner"><i data-lucide="x"></i></button>
        </header>
        <form method="GET" action="{{ route('public.booking') }}">
            <div class="trip-planner-intro">
                <span>Start with the essentials</span>
                <h2 id="trip-planner-title">Let’s plan your dream trip.</h2>
                <p>Share what you know now. A safari specialist will refine every detail with you.</p>
            </div>
            <div class="trip-planner-grid">
                <label class="span-2">Where would you like to go?
                    <select name="destination">
                        <option value="">Help me choose</option>
                        @foreach(['Kenya','Tanzania','Uganda','Rwanda','South Africa','Namibia','Botswana','Multi-country safari','Golf Safari'] as $country)<option>{{ $country }}</option>@endforeach
                    </select>
                </label>
                <label>Adults<input type="number" name="adults" min="1" max="60" value="2"></label>
                <label>Children<input type="number" name="children" min="0" max="60" value="0"></label>
                <label class="span-2">Estimated arrival date<input type="date" name="travel_date" value="{{ now()->addMonths(3)->toDateString() }}"></label>
                <label>Travel style
                    <select name="safari_type"><option>Tailor-made safari</option><option>Family safari</option><option>Honeymoon safari</option><option>Luxury lodge safari</option><option>Golf safari</option><option>Beach and safari</option></select>
                </label>
                <label>Budget per person
                    <select name="budget"><option value="">Not decided</option><option>$3,000 - $5,000 per person</option><option>$5,000 - $8,000 per person</option><option>$8,000 - $12,000 per person</option><option>$12,000+ per person</option></select>
                </label>
            </div>
            <button class="trip-planner-submit">Continue planning<i data-lucide="arrow-right"></i></button>
            <small><i data-lucide="lock-keyhole"></i>No obligation and no booking fees.</small>
        </form>
    </div>
</dialog>

<section class="intro-editorial" id="start">
    <div>
        <x-public.section-label :label="$cms('intro_label', 'Tailor-made African Safaris')" />
        <h2>{{ $cms('intro_title', 'Premium journeys crafted around you') }}</h2>
    </div>
    <p>{{ $cms('intro_text', 'Shishi Footsteps is a curated travel design company specializing in premium, tailor-made safaris across East Africa. Rather than selling fixed packages, we build every itinerary from scratch based on your interests, pace, comfort level, and travel goals.') }}</p>
</section>

<section class="feature-story-grid">
    <article>
        <a href="{{ route('public.safaris') }}" class="story-image-link"><img src="{{ $images['adventure'] }}" alt="Safari adventure" loading="lazy"></a>
        <div><x-public.section-label label="01" class="light" /><h3>Safari</h3><p>Private wildlife journeys with expert guides, from the Maasai Mara to the Serengeti plains, gorilla trekking to Big Five encounters. Every safari is shaped around your pace, interests and comfort.</p></div>
    </article>
    <article>
        <a href="{{ route('public.accommodations') }}" class="story-image-link"><img src="{{ $images['luxury'] }}" alt="Luxury lodge" loading="lazy"></a>
        <div><x-public.section-label label="02" class="light" /><h3>Luxury</h3><p>Beautiful lodges, private decks, considered service, wellness retreats and comfort after every wild day in Africa's finest destinations. We select stays for location, guiding, atmosphere and how they make you feel.</p></div>
    </article>
    <article>
        <a href="{{ route('public.experiences') }}" class="story-image-link"><img src="{{ $images['culture'] }}" alt="Cultural safari experience" loading="lazy"></a>
        <div><x-public.section-label label="03" class="light" /><h3>Culture</h3><p>Respectful local encounters, community visits and cultural experiences that make the journey richer than wildlife alone. These moments add meaning to every African safari.</p></div>
    </article>
</section>

<section class="content-band destinations-band">
    <div class="section-heading">
        <div>
            <x-public.section-label label="Destinations" />
            <h2>{{ $cms('destinations_title', 'Kenya, Tanzania, Uganda, Rwanda, South Africa, Namibia, Botswana') }}</h2>
        </div>
        <a href="{{ route('public.destinations') }}">View all destinations<i data-lucide="arrow-right"></i></a>
    </div>
    <div class="destination-grid">
        @foreach($countryCards as $country)
            @php $name = $country->name; @endphp
            <x-public.destination-card
                :title="$name"
                :description="$destinationCopy[$name] ?? 'Handpicked safari country shaped around wildlife, season, comfort and your travel style.'"
                :image="$countryImages[$name] ?? reset($countryImages)"
                :url="route('public.destinations.show', \Illuminate\Support\Str::slug($name))"
            />
        @endforeach
    </div>
</section>

<section class="content-band accommodation-band">
    <x-public.accommodation-card
        :title="$featuredAccommodation?->name ?? 'Private camps and lodges with a sense of place'"
        :description="$featuredAccommodation?->description ?? 'Stay in intimate safari camps, refined lodges and private retreats chosen for location, guiding, service and atmosphere. Every stay is matched to the kind of journey you want to feel.'"
        :meta="$featuredAccommodation ? trim(($featuredAccommodation->country ?? '').' / '.($featuredAccommodation->region ?? ''), ' /') : 'Luxury lodges / Tented camps / Private retreats'"
        :image="$featuredAccommodation && !empty($featuredAccommodation->images) ? (is_array($featuredAccommodation->images) ? $featuredAccommodation->images[0] : $featuredAccommodation->images) : $images['accommodation']"
        :slug="$featuredAccommodation?->slug ?? null"
    />
</section>

<section class="content-band experiences-band">
    <div class="section-heading centered">
        <div>
            <x-public.section-label label="Experiences" />
            <h2>{{ $cms('experiences_title', 'Shape each day around what moves you.') }}</h2>
        </div>
    </div>
    <div class="experience-grid">
        @forelse($activities->take(6) as $activity)
            @php $translation = $activity->translation(); @endphp
            <x-public.experience-card
                :title="$translation?->title ?? $activity->name"
                :description="$translation?->short_description ?? $activity->description ?? 'A carefully guided safari experience designed around place, season and your travel style.'"
                :image="is_array($activity->images ?? null) && count($activity->images) ? $activity->images[0] : $experienceDefaults[$loop->index % count($experienceDefaults)][3]"
                icon="sparkles"
                :url="$activity->slug ? route('public.experiences.show', $activity->slug) : null"
            />
        @empty
            @foreach($experienceDefaults as $experience)
                <x-public.experience-card :title="$experience[0]" :description="$experience[1]" :icon="$experience[2]" :image="$experience[3]" />
            @endforeach
        @endforelse
    </div>
</section>

<section class="content-band packages-band">
    <div class="section-heading">
        <div>
            <x-public.section-label label="Featured Safaris" />
            <h2>{{ $cms('safaris_title', 'Safari packages ready to become personal.') }}</h2>
        </div>
        <a href="{{ route('public.safaris') }}">Explore safaris<i data-lucide="arrow-right"></i></a>
    </div>
    <div class="safari-grid">
        @forelse($packages->take(3) as $package)
            @php
                $_pkg = $package;
                $_isContent = $_pkg instanceof \App\Models\ContentItem;
                $_trans = $_isContent ? $_pkg->translation() : null;
                if ($_isContent) {
                    $_pTitle = $_trans?->title ?? $_pkg->name ?? 'Safari Package';
                    $_pSummary = $_trans?->short_description ?? 'A curated safari package from the Shishi Footsteps collection.';
                } else {
                    $_pTitle = $_pkg->title ?? 'Safari Package';
                    $_pSummary = $_pkg->summary ?? 'A curated safari package from the Shishi Footsteps collection.';
                }
                $_pDuration = ($_pkg->duration_days ?? null) ? $_pkg->duration_days.' days' : null;
                $_pPrice = ($_pkg->price_from ?? null) ? '$'.number_format((float) $_pkg->price_from) : null;
                $_pImg = !$_isContent && is_array($_pkg->images ?? null) && count($_pkg->images) ? \App\Support\MediaPath::publicUrl($_pkg->images[0]) : $packageImages[$loop->index % count($packageImages)];
            @endphp
            <x-public.safari-package-card :title="$_pTitle" :summary="$_pSummary" :image="$_pImg" :duration="$_pDuration" :country="$_pkg->country ?? null" :price="$_pPrice" :slug="$_isContent ? null : ($_pkg->slug ?? null)" />
        @empty
            <x-public.safari-package-card title="Great Migration Private Safari" summary="A classic East African wildlife journey with elegant camps, private guiding and flexible pacing." :image="$packageImages[0]" duration="8 days" country="Kenya" price="$5,800" />
            <x-public.safari-package-card title="Gorillas and Savannahs" summary="A moving combination of forest trekking, lakeside calm and big game encounters." :image="$packageImages[1]" duration="9 days" country="Uganda" price="$7,200" />
            <x-public.safari-package-card title="Desert and Delta Escape" summary="Remote landscapes, water safaris, silent skies and refined wilderness lodges." :image="$packageImages[2]" duration="10 days" country="Namibia / Botswana" price="$8,900" />
        @endforelse
    </div>
</section>

<section class="home-golf-showcase">
    <div class="section-heading">
        <div><x-public.section-label label="African Golf Holidays" /><h2>Play Africa’s most memorable fairways.</h2></div>
        <a href="{{ route('public.golf') }}">Explore golf holidays<i data-lucide="arrow-right"></i></a>
    </div>
    <p class="home-golf-intro">From a focused championship week to a multi-country golf circuit, we arrange tee times, caddies, club hire, private transfers and course-friendly stays around the way you want to play.</p>
    <div class="home-golf-grid">
        <article class="home-golf-card home-golf-card--wide" data-tilt-card>
            <a href="{{ route('public.golf') }}#golf-courses"><img src="https://images.unsplash.com/photo-1535131749006-b7f58c99034b?auto=format&fit=crop&w=1400&q=86&fm=webp" alt="Golfer driving from a championship tee" loading="lazy"><span><small>Championship play</small><strong>Explore premier courses</strong><i data-lucide="arrow-up-right"></i></span></a>
        </article>
        <article class="home-golf-card" data-tilt-card>
            <a href="{{ route('public.golf') }}#golf-packages"><img src="https://images.unsplash.com/photo-1592919505780-303950717480?auto=format&fit=crop&w=1100&q=86&fm=webp" alt="Championship golf course with rolling fairways" loading="lazy"><span><small>Tailor-made routes</small><strong>View golf holidays</strong><i data-lucide="arrow-up-right"></i></span></a>
        </article>
        <article class="home-golf-card" data-tilt-card>
            <a href="{{ route('public.booking', ['safari_type'=>'Golf safari']) }}"><img src="https://images.unsplash.com/photo-1530028828-25e8270793c5?auto=format&fit=crop&w=1100&q=86&fm=webp" alt="Professional golf clubs ready for a round" loading="lazy"><span><small>Your game, your pace</small><strong>Plan your golf trip</strong><i data-lucide="arrow-up-right"></i></span></a>
        </article>
    </div>
</section>

@if($blogPosts->isNotEmpty())
<section class="content-band" style="background:#f1eadb;">
    <div class="section-heading">
        <div>
            <x-public.section-label label="Journal" />
            <h2>Field notes for better journeys</h2>
        </div>
        <a href="{{ route('public.blog') }}">All articles<i data-lucide="arrow-right"></i></a>
    </div>
    <div class="blog-grid">
        @foreach($blogPosts as $post)
            <article class="blog-card">
                <a href="{{ route('public.blog.post', $post->slug) }}" class="blog-image-link"><img src="{{ $post->cover_image ?: 'https://images.unsplash.com/photo-1489392191049-fc10c97e64b6?auto=format&fit=crop&w=900&q=82&fm=webp' }}" alt="{{ $post->title }}" loading="lazy"></a>
                <div>
                    <x-public.section-label :label="$post->published_at?->format('M d, Y') ?? 'Travel Guide'" />
                    <h2>{{ $post->title }}</h2>
                    <p>{{ $post->seo_description ?? Str::limit(strip_tags($post->content), 140) }}</p>
                    <a href="{{ route('public.blog.post', $post->slug) }}">Read more<i data-lucide="arrow-up-right"></i></a>
                </div>
            </article>
        @endforeach
    </div>
</section>
@endif

<section class="responsible-section">
    <div>
        <x-public.section-label label="Responsible Travel" class="light" />
        <h2>Travel that leaves more than footprints.</h2>
    </div>
    <article><i data-lucide="hand-heart"></i><h3>Community support</h3><p>We favour partners who invest in local employment, fair opportunity and meaningful community programs across East Africa.</p></article>
    <article><i data-lucide="shield-check"></i><h3>Conservation</h3><p>Safari choices can protect habitat, fund rangers and keep wildlife corridors alive for the future. We support conservation-conscious travel.</p></article>
    <article><i data-lucide="leaf"></i><h3>Eco-conscious travel</h3><p>We prioritise thoughtful routing, lower-impact stays and operators who take sustainability seriously across every journey we design.</p></article>
</section>

<x-public.cta-section
    label="Your Private Safari"
    title="Let Us Design Your Safari Journey"
    text="Tell us your preferences and our specialists will shape a tailor-made itinerary with the right destinations, pace, guides and lodges — built from scratch around you."
    :image="$images['cta']"
    buttonText="Start Planning"
/>
@endsection
