@extends('layouts.public')

@section('title', $name.' Safari Tours | Shishi Footsteps')
@section('description', 'Tailor-made '.$name.' safari tours, private itineraries, trusted lodges and expert local planning.')

@section('content')
@php
    $countryMedia = $settings->mediaFor($slug);
    $heroImage = \App\Support\MediaPath::publicUrl($countryMedia['hero']);
    $galleryImages = collect($countryMedia['gallery'])->map(fn ($path) => \App\Support\MediaPath::publicUrl($path))->filter()->values();
    $copy = [
        'kenya' => 'Big cats, private conservancies, the Great Migration and warm Indian Ocean endings.',
        'tanzania' => 'Serengeti plains, Ngorongoro drama, Kilimanjaro and wild southern parks.',
        'uganda' => 'Gorilla forests, chimpanzees, the Nile and deeply moving wildlife encounters.',
        'rwanda' => 'Volcanoes, mountain gorillas, golden monkeys and refined highland lodges.',
        'south-africa' => 'Private reserves, Cape landscapes, wine country and exceptional golf.',
        'namibia' => 'Sculptural dunes, desert-adapted wildlife and immense starlit wilderness.',
        'botswana' => 'Okavango waterways, elephant-rich landscapes and pristine mobile safaris.',
    ];
@endphp

<section class="country-tour-hero">
    <a href="{{ route('public.booking', ['destination' => $name]) }}" class="country-hero-image-link" aria-label="Plan a {{ $name }} safari"><img src="{{ $heroImage }}" alt="{{ $name }} safari"></a>
    <div><span>Tailor-made journeys</span><h1>{{ strtoupper($name) }} TOURS</h1><p>{{ ($countryGuide?->seo_description ?? null) ?: $copy[$slug] }}</p></div>
</section>

<nav class="country-breadcrumb">
    <a href="{{ route('home') }}">Home</a><span>&rsaquo;</span>
    <a href="{{ route('public.destinations') }}">Countries</a><span>&rsaquo;</span><b>{{ $name }}</b>
</nav>

<section class="tour-catalogue">
    <aside>
        <strong>Types</strong>
        <label><input type="checkbox"> Safari</label>
        <label><input type="checkbox"> Family travel</label>
        <label><input type="checkbox"> Luxury</label>
        <label><input type="checkbox"> Golf</label>
        <strong>Travel time</strong>
        <p>All journeys are tailor-made around your preferred dates.</p>
    </aside>
    <main>
        <div class="tour-catalogue-head">
            <div><span>{{ $safaris->count() ?: 'Private' }} journeys</span><h2>{{ $name }} safari ideas</h2></div>
            <p>These are starting points. We refine every route, lodge and experience around you.</p>
        </div>
        <div class="reference-tour-list">
            @forelse ($safaris as $safari)
                <article>
                    <div class="tour-image">
                        <a href="{{ route('public.safaris.show', $safari->slug) }}" class="tour-image-link"><img src="{{ is_array($safari->images) && count($safari->images) ? \App\Support\MediaPath::publicUrl($safari->images[0]) : ($galleryImages[$loop->index % max(1, $galleryImages->count())] ?? $heroImage) }}" alt="{{ $safari->title }}"></a>
                        <button type="button" aria-label="Save {{ $safari->title }}"><i data-lucide="heart"></i></button>
                        <span>{{ $name }}</span>
                    </div>
                    <div>
                        <small>{{ $safari->duration_days }} days</small><h3>{{ $safari->title }}</h3>
                        <p>{{ \Illuminate\Support\Str::limit($safari->summary, 170) }}</p>
                        <ul>
                            @foreach ($safari->days->take(4) as $day)
                                <li>{{ $day->title ?: 'Day '.$day->day_number }}</li>
                            @endforeach
                        </ul>
                        <a href="{{ route('public.safaris.show', $safari->slug) }}">View this trip</a>
                    </div>
                </article>
            @empty
                @php
                    $ideas = [
                        ['Classic '.$name.' Safari', 'Private guiding, signature wildlife areas and handpicked camps.'],
                        [$name.' Family Adventure', 'A flexible journey with child-friendly pacing and memorable experiences.'],
                        ['Luxury '.$name.' & Golf', 'Championship fairways paired with beautiful landscapes and private safari days.'],
                    ];
                @endphp
                @foreach ($ideas as $idea)
                    <article>
                        <div class="tour-image"><a href="{{ route('public.booking', ['destination' => $name, 'message' => $idea[0]]) }}" class="tour-image-link"><img src="{{ $galleryImages[$loop->index % max(1, $galleryImages->count())] ?? $heroImage }}" alt="{{ $idea[0] }}"></a><span>{{ $name }}</span></div>
                        <div><small>Tailor-made</small><h3>{{ $idea[0] }}</h3><p>{{ $idea[1] }}</p><a href="{{ route('public.booking', ['destination' => $name]) }}">Request this trip</a></div>
                    </article>
                @endforeach
            @endforelse
        </div>
    </main>
</section>
@endsection
