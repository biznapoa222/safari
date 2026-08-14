@extends('layouts.public')

@section('title', 'Request a Proposal | Shishi Footsteps')
@section('description', 'Request a private safari proposal from a Shishi Footsteps trip advisor. Share your country, dates and travel style for a written itinerary and quote.')

@section('content')
@php
    $hero = 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=1800&q=82&fm=webp';
    $cms = fn ($key, $fallback = '') => \App\Models\CmsContentBlock::value('booking', $key, $fallback);
    $global = fn ($key, $fallback = '') => \App\Models\CmsContentBlock::value('global', $key, $fallback);
    $proposalCountries = collect($destinations)->pluck('name')->merge(['Kenya', 'Tanzania', 'Uganda', 'Rwanda', 'South Africa', 'Namibia', 'Botswana'])->unique()->values();
@endphp

<x-public.page-hero
    label="Private proposal"
    title="Request a private proposal"
    subtitle="A trip advisor will shape the country, the lodges and the pace into a written itinerary and quote."
    :image="\App\Support\MediaPath::publicUrl($cms('hero_image', $hero))"
/>

<section class="proposal-rhythm" id="start">
    <x-public.section-label label="How a proposal works" />
    <h2>From a conversation to a written journey.</h2>
    <ol>
        <li><span>01</span><strong>Tell us the shape</strong><p>Country or circuit, rough dates, who is travelling, and whether golf, gorillas or a beach ending matter.</p></li>
        <li><span>02</span><strong>A specialist designs</strong><p>Your advisor chooses parks, nights and lodges around season, availability and the way you like to travel.</p></li>
        <li><span>03</span><strong>You receive the quote</strong><p>A clear itinerary, what is included, and a price. Revise it until the route feels like yours, then confirm when you are ready.</p></li>
    </ol>
</section>

<section class="inquiry-section proposal-inquiry" id="proposal">
    <div class="inquiry-copy">
        <x-public.section-label label="Speak with a trip advisor" />
        <h2>Begin with the details that matter.</h2>
        <p>Share as much or as little as you know. We will come back with the right questions, then a private proposal — not a generic package.</p>
        <div class="contact-detail"><span><i data-lucide="phone"></i></span><div><small>Call or WhatsApp</small><strong>{{ $global('phone', '+254 725 346 022') }}</strong></div></div>
        <div class="contact-detail"><span><i data-lucide="mail"></i></span><div><small>Email</small><strong>{{ $global('bookings_email', $global('email', 'bookings@shishifootsteps.com')) }}</strong></div></div>
        <p class="proposal-note">Proposals are complimentary. Lodges and gorilla permits are only held once you accept and a deposit is paid.</p>
    </div>
    <x-public.inquiry-form
        :destinations="$destinations"
        :selected-itinerary="$selectedItinerary"
        :prefill-destination="$prefillDestination"
        :prefill-interest="$prefillInterest ?? null"
        :country-names="$proposalCountries"
        variant="proposal"
    />
</section>

<x-public.cta-section
    label="Still deciding"
    title="Read the journal of questions."
    text="Seasons, permits, packing, golf and how we travel — answered before you write to us."
    image="https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?auto=format&fit=crop&w=1800&q=82&fm=webp"
    buttonText="Browse the FAQs"
    :url="route('public.faqs')"
/>
@endsection
