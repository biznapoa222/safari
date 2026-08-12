@extends('layouts.public')

@section('title', 'Plan Your Safari | Shishi Footsteps')
@section('description', 'Plan a tailor-made African safari with Shishi Footsteps.')

@section('content')
@php $hero = 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=1800&q=82&fm=webp'; $cms=fn($key,$fallback='')=>\App\Models\CmsContentBlock::value('booking',$key,$fallback); $global=fn($key,$fallback='')=>\App\Models\CmsContentBlock::value('global',$key,$fallback); @endphp

<x-public.page-hero label="Private Safari Planning" :title="$cms('hero_title')" :subtitle="$cms('hero_subtitle')" :image="\App\Support\MediaPath::publicUrl($cms('hero_image',$hero))" />

<section class="inquiry-section">
    <div class="inquiry-copy">
        <x-public.section-label label="Speak With A Specialist" />
        <h2>Tell us about your journey.</h2>
        <p>Complete the short form and a Shishi Footsteps specialist will contact you to refine the route, stays and experiences.</p>
        <div class="contact-detail"><span><i data-lucide="phone"></i></span><div><small>Call or WhatsApp</small><strong>{{ $global('phone','+254 725 346 022') }}</strong></div></div>
        <div class="contact-detail"><span><i data-lucide="mail"></i></span><div><small>Email</small><strong>{{ $global('email','info@shishifootsteps.com') }}</strong></div></div>
    </div>
    <x-public.inquiry-form :destinations="$destinations" :selected-itinerary="$selectedItinerary" :prefill-destination="$prefillDestination" />
</section>
@endsection
