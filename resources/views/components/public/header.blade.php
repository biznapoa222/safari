@php
    $navCountries = [['Kenya','kenya'],['Tanzania','tanzania'],['Uganda','uganda'],['Rwanda','rwanda'],['South Africa','south-africa'],['Namibia','namibia'],['Botswana','botswana']];
    $websiteSettings = \App\Models\WebsiteSetting::home();
    $golfCountries = ['kenya','tanzania','uganda','rwanda','south-africa'];
@endphp

@if (false)
<div class="trust-strip">
    <div>
        <img class="trust-paw" src="{{ asset('images/brand/shishi-paw-white.png') }}" alt="">
        <x-language-switcher variant="public" />
        <span class="currency-choice"><b>$</b> U.S. Dollar <i data-lucide="chevron-down"></i></span>
    </div>
    <div class="review-proof">
        <span class="proof-mark"><i data-lucide="badge-check"></i></span><b>Travelers’ Choice</b>
        <span><strong>4.9/5</strong> ★★★★★<small>Based on verified reviews</small></span>
        <span><strong>4.8/5</strong> ★★★★★<small>Guest-rated journeys</small></span>
    </div>
    <nav><a href="{{ route('public.golf') }}">Golf</a><a href="{{ route('public.about') }}">About us</a></nav>
</div>

@endif

<header class="public-header safari-reference-header" data-public-header>
    <a href="{{ route('home') }}" class="reference-brand"><img src="{{ asset('images/brand/shishi-footsteps-green.png') }}" alt="Shishi Footsteps"></a>
    <nav class="public-nav reference-nav" aria-label="Primary navigation">
        @foreach ($navCountries as [$name, $slug])
            @php
                $countryMedia = $websiteSettings->mediaFor($slug);
                $image = \App\Support\MediaPath::publicUrl($countryMedia['hero']);
                $menuGallery = collect($countryMedia['gallery'])->map(fn ($path) => \App\Support\MediaPath::publicUrl($path))->filter()->values();
            @endphp
            <div class="country-nav">
                <a href="{{ route('public.destinations.show', $slug) }}">{{ $name }} <i data-lucide="chevron-down"></i></a>
                <div class="country-mega">
                    <div class="country-mega-main">
                        <h2>VIEW {{ strtoupper($name) }} SAFARI IDEAS</h2>
                        <div class="country-mega-grid">
                            @php
                                $sectionKeys = ['safaris-and-tours','discover','national-parks','accommodation','highlights','activities','wildlife'];
                            @endphp
                            @foreach ([['Safaris and tours','binoculars'],['Discover '.$name,'compass'],['National parks','trees'],['Accommodation','bed-double'],['Highlights','sparkles'],['Activities','footprints'],['Wildlife','paw-print'],['Golf safaris','flag']] as $i => $tile)
                                <a href="{{ $i === 7 ? (in_array($slug, $golfCountries) ? route('public.tee-off.country', $slug) : route('public.golf')) : route('public.destinations.section', [$slug, $sectionKeys[$i]]) }}">
                                    <img src="{{ $menuGallery[$i % max(1, $menuGallery->count())] ?? $image }}" alt="{{ $tile[0] }} in {{ $name }}"><span><i data-lucide="{{ $tile[1] }}"></i>{{ $tile[0] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <aside>
                        <h3>START THE ADVENTURE!</h3><a href="{{ route('public.destinations.show', $slug) }}" class="mega-feature-image"><img src="{{ $image }}" alt="{{ $name }}"></a>
                        <p>Tell us what you love and our specialists will build your private {{ $name }} journey.</p>
                        <a href="{{ route('public.booking', ['destination' => $name]) }}">{{ __('ui.plan_your_safari') }}</a>
                    </aside>
                </div>
            </div>
        @endforeach
        <div class="country-nav golf-nav">
            <a href="{{ route('public.golf') }}">Golf <i data-lucide="chevron-down"></i></a>
            <div class="country-mega">
                <div class="country-mega-main">
                    <h2>GOLF SAFARI</h2>
                    <div class="country-mega-grid">
                        <a href="{{ route('public.golf') }}"><img src="{{ asset('images/wordpress/golf-787826_1280.png') }}" alt="All golf safaris"><span><i data-lucide="flag"></i>All Golf Safaris</span></a>
                        <a href="{{ route('public.tee-off.country', 'kenya') }}"><img src="{{ asset('images/wordpress/golf-1208900_1280.jpg') }}" alt="Kenya golf"><span><i data-lucide="flag"></i>Kenya Golf</span></a>
                        <a href="{{ route('public.tee-off.country', 'rwanda') }}"><img src="{{ asset('images/wordpress/kigali-4811535-scaled.jpg') }}" alt="Rwanda golf"><span><i data-lucide="flag"></i>Rwanda Golf</span></a>
                        <a href="{{ route('public.tee-off.country', 'south-africa') }}"><img src="{{ asset('images/wordpress/sa-golf.jpeg') }}" alt="South Africa golf"><span><i data-lucide="flag"></i>South Africa Golf</span></a>
                    </div>
                </div>
                <aside>
                    <h3>Start the adventure!</h3>
                    <a href="{{ route('public.golf') }}" class="mega-feature-image"><img src="{{ asset('images/wordpress/golf-787826_1280.png') }}" alt="Golf"></a>
                    <p>Championship golf, carefully timed tee sheets and smooth travel between Africa's most rewarding courses.</p>
                    <a href="{{ route('public.golf') }}">Explore golf</a>
                </aside>
            </div>
        </div>
    </nav>
    <div class="reference-actions">
        <a href="{{ route('public.booking') }}" class="reference-request">{{ __('ui.plan_your_safari') }}</a>
        <button class="public-menu-button" type="button" data-public-menu aria-label="Open navigation"><i data-lucide="menu"></i></button>
    </div>
</header>
