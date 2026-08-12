@extends('layouts.public')

@section('title', 'About Us | Shishi Footsteps')
@section('description', 'Shishi Footsteps is a curated travel design company specialising in premium, tailor-made safari experiences across East Africa.')

@section('content')
@php($cms = fn($key, $fallback='') => \App\Models\CmsContentBlock::value('about', $key, $fallback))
<x-public.page-hero :label="$cms('hero_label','About Shishi Footsteps')" :title="$cms('hero_title','Crafted with care, guided by Africa.')" :subtitle="$cms('hero_subtitle')" :image="\App\Support\MediaPath::publicUrl($cms('hero_image'))" />

<section class="about-editorial">
    <div>
        <x-public.section-label label="Who We Are" />
        <h2>{{ $cms('intro_title','Luxury is personal, not loud.') }}</h2>
    </div>
    <p>{{ $cms('intro_text','Shishi Footsteps is a curated travel design company specialising in premium, tailor-made experiences across East Africa.') }}</p>
    <p>We specialise in Kenya, Tanzania, Uganda, Rwanda and South Africa. These destinations offer extraordinary diversity, from the wildlife-rich Maasai Mara and Serengeti plains to Rwanda's gorilla trekking experiences, from high-altitude sports training hubs to coastal beach escapes. We focus on regions where we have trusted partnerships, deep operational knowledge, and strong logistical support.</p>
    <p>Unlike companies that sell fixed packages, we create tailor-made journeys shaped around your travel goals, budget, interests, and timing. Our approach combines deep local knowledge with international service standards, ensuring every detail — from wildlife viewing routes to lodge selection — is carefully curated. We focus on immersive experiences, conservation-conscious travel, and seamless logistics so your safari feels effortless and meaningful.</p>

    <div class="principle-grid">
        <article><i data-lucide="compass"></i><h3>Tailor-made design</h3><p>Every route starts with your season, comfort level, interests and travel rhythm. We build from scratch, never from a template.</p></article>
        <article><i data-lucide="award"></i><h3>Specialist expertise</h3><p>Deep knowledge in safari, golf tourism, wellness coordination and sports travel across East Africa's premier destinations.</p></article>
        <article><i data-lucide="handshake"></i><h3>Trusted partnerships</h3><p>We work with vetted guides, lodges and suppliers chosen for quality, service standards and conservation ethos.</p></article>
        <article><i data-lucide="shield-check"></i><h3>Professional coordination</h3><p>Meticulous logistics and on-ground support from the first inquiry to your return home, ensuring a seamless experience.</p></article>
        <article><i data-lucide="leaf"></i><h3>Responsible travel</h3><p>We favour travel that supports conservation, local communities and long-term wilderness value across every journey.</p></article>
        <article><i data-lucide="heart"></i><h3>Personalised service</h3><p>Multilingual support, individual attention and journeys designed around your comfort, pace and travel style.</p></article>
    </div>
</section>

<section class="content-band" style="background:#e9e2d2;">
    <div class="section-heading centered">
        <div>
            <x-public.section-label label="Our Mission" />
            <h2>{{ $cms('mission_title','To be the premier provider of luxury safari experiences in Africa') }}</h2>
        </div>
    </div>
    <p style="max-width:720px;margin:0 auto;text-align:center;color:#54635b;font-size:14px;line-height:2;">{{ $cms('mission_text') }}</p>
</section>

<section class="content-band" style="background:var(--sf-porcelain);">
    <div class="section-heading centered">
        <div>
            <x-public.section-label label="Our Vision" />
            <h2>{{ $cms('vision_title','Travel that leaves more than footprints') }}</h2>
        </div>
    </div>
    <p style="max-width:720px;margin:0 auto;text-align:center;color:#54635b;font-size:14px;line-height:2;">{{ $cms('vision_text') }}</p>
</section>

<x-public.cta-section
    label="Your Private Safari"
    :title="$cms('cta_title', 'Let Us Design Your Journey')"
    :text="$cms('cta_text', 'Tell us what you are dreaming of, and we will shape a safari with the right destinations, pace, guides and lodges — built from scratch around you.')"
    :image="\App\Support\MediaPath::publicUrl($cms('cta_image')) ?: 'https://images.unsplash.com/photo-1534177616072-ef7dc120449d?auto=format&fit=crop&w=1800&q=82&fm=webp'"
    buttonText="Start Planning"
/>
@endsection
