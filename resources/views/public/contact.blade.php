@extends('layouts.public')

@section('title', 'Contact | Shishi Footsteps')
@section('description', 'Contact Shishi Footsteps to plan a private luxury safari.')

@section('content')
@php($cms = fn($key, $fallback='') => \App\Models\CmsContentBlock::value('contact', $key, $fallback))
@php($global = fn($key, $fallback='') => \App\Models\CmsContentBlock::value('global', $key, $fallback))
<x-public.page-hero label="Contact" :title="$cms('hero_title','Let us begin with a conversation.')" :subtitle="$cms('hero_subtitle')" :image="\App\Support\MediaPath::publicUrl($cms('hero_image'))" />

<section class="inquiry-section">
    <div class="inquiry-copy">
        <x-public.section-label label="Get In Touch" />
        <h2>{{ $cms('intro_title') }}</h2><p>{{ $cms('intro_text') }}</p>
        <div class="contact-detail"><span><i data-lucide="phone"></i></span><div><small>Call or WhatsApp</small><strong>{{ $global('phone','+254 725 346 022') }}</strong></div></div>
        <div class="contact-detail"><span><i data-lucide="mail"></i></span><div><small>General Inquiries</small><strong>{{ $global('email','info@shishifootsteps.com') }}</strong></div></div>
        <div class="contact-detail"><span><i data-lucide="mail"></i></span><div><small>Bookings</small><strong>{{ $global('bookings_email','bookings@shishifootsteps.com') }}</strong></div></div>
        <div class="contact-detail"><span><i data-lucide="map-pin"></i></span><div><small>Office</small><strong>{{ $global('address','Nairobi, Kenya') }}</strong></div></div>
    </div>
    <x-public.inquiry-form :destinations="$destinations" />
</section>
@endsection
