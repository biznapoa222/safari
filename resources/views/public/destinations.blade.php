@extends('layouts.public')

@section('title', 'Destinations | Shishi Footsteps')
@section('description', 'Explore luxury safari destinations across Kenya, Tanzania, Uganda, Rwanda, South Africa, Namibia and Botswana.')

@section('content')
@php
    $cms = fn($key,$fallback='') => \App\Models\CmsContentBlock::value('destinations',$key,$fallback);
    $hero = \App\Support\MediaPath::publicUrl($cms('hero_image', 'https://images.unsplash.com/photo-1489392191049-fc10c97e64b6?auto=format&fit=crop&w=1800&q=82&fm=webp'));
    $countryImages = [
        'Kenya' => 'https://images.unsplash.com/photo-1534177616072-ef7dc120449d?auto=format&fit=crop&w=900&q=82&fm=webp',
        'Tanzania' => 'https://images.unsplash.com/photo-1523805009345-7448845a9e53?auto=format&fit=crop&w=900&q=82&fm=webp',
        'Uganda' => 'https://images.unsplash.com/photo-1540573133985-87b6da6d54a9?auto=format&fit=crop&w=900&q=82&fm=webp',
        'Rwanda' => 'https://images.unsplash.com/photo-1517853782856-d7cc5de7a7fc?auto=format&fit=crop&w=900&q=82&fm=webp',
        'South Africa' => 'https://images.unsplash.com/photo-1484318571209-661cf29a69c3?auto=format&fit=crop&w=900&q=82&fm=webp',
        'Namibia' => 'https://images.unsplash.com/photo-1516483638261-f4dbaf036963?auto=format&fit=crop&w=900&q=82&fm=webp',
        'Botswana' => 'https://images.unsplash.com/photo-1509099836639-18ba1795216d?auto=format&fit=crop&w=900&q=82&fm=webp',
    ];
    $copy = [
        'Kenya' => 'Big cat country, private conservancies, the Maasai Mara river crossings, Laikipia wildlife and a warm coastal ending.',
        'Tanzania' => 'Endless Serengeti plains, the Ngorongoro Crater, wild southern parks and the slopes of Kilimanjaro.',
        'Uganda' => 'Gorilla trekking in misty forests, chimpanzee encounters, crater lake country and deeply moving wildlife encounters.',
        'Rwanda' => 'Mountain gorilla trekking, rolling hills, golden monkey tracking and intimate luxury lodges with volcano views.',
        'South Africa' => 'Private Big Five reserves, refined lodges, Cape Town icons, wine country and family-friendly safari routes.',
        'Namibia' => 'Desert-adapted wildlife, sculptural dunes, remote lodges beneath huge star-filled skies and Sossusvlei.',
        'Botswana' => 'Okavango Delta waterways, Chobe elephant herds, mokoro channels and pristine private concessions.',
    ];
    $cards = $destinations->isNotEmpty() ? $destinations : collect(array_keys($countryImages))->map(fn ($name) => (object) ['name' => $name]);
@endphp

<x-public.page-hero label="Destinations" title="The wild, chosen with intention." subtitle="We specialize in Kenya, Tanzania, Uganda, Rwanda, South Africa, Namibia and Botswana — destinations offering extraordinary diversity, from wildlife-rich plains to gorilla forests, desertscapes and coastal escapes." :image="$hero" />

<section class="content-band">
    <div class="section-heading">
        <div><x-public.section-label label="Where To Go" /><h2>Signature safari countries</h2></div>
        <a href="{{ route('public.booking') }}">Ask a specialist<i data-lucide="arrow-right"></i></a>
    </div>
    <div class="destination-grid">
        @foreach($cards as $country)
            <x-public.destination-card
                :title="$country->name"
                :description="$copy[$country->name] ?? 'A Shishi Footsteps safari destination selected around season, wildlife and comfort.'"
                :image="$countryImages[$country->name] ?? reset($countryImages)"
            />
        @endforeach
    </div>
</section>

<x-public.cta-section label="Tailor Your Route" title="Not sure which country fits?" text="Tell us your travel dates, style and wildlife wishlist. We focus on regions where we have trusted partnerships, deep operational knowledge and strong logistical support — ensuring the strongest route for the season." image="https://images.unsplash.com/photo-1504432842672-1a79f78e4084?auto=format&fit=crop&w=1800&q=82&fm=webp" />
@endsection
