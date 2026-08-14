@extends('layouts.public')

@section('title', 'Frequently Asked Questions | Shishi Footsteps')
@section('description', 'Answers on safari planning, Kenya, Tanzania, gorilla trekking, South Africa, Namibia, Botswana, golf, lodges, visas, health and how to request a Shishi Footsteps proposal.')

@section('content')
@php
    $cms = fn ($key, $fallback = '') => \App\Models\CmsContentBlock::value('faqs', $key, $fallback);
    $hero = $cms('hero_image') ?: 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=2200&q=84&fm=webp';
@endphp

<x-public.page-hero
    class="faqs-hero"
    label="Travel notes"
    :title="$cms('hero_title', 'Questions, before the journey')"
    :subtitle="$cms('hero_subtitle', 'Countries, seasons, permits, golf, lodges and how a private proposal comes together.')"
    :image="\App\Support\MediaPath::publicUrl($hero)"
/>

<section class="faqs-editorial" id="start">
    <div>
        <x-public.section-label label="How can we help?" />
        <h2>{{ $cms('editorial_title', 'Ask us anything') }}</h2>
    </div>
    <p>{{ $cms('editorial_text', 'From first enquiry to the last sundowner: destinations, wildlife seasons, camps, golf, families and the practical details of travelling with Shishi Footsteps.') }}</p>
</section>

<nav class="faqs-index" aria-label="FAQ topics">
    @foreach($faqGroups as $group)
        <a href="#{{ $group['id'] }}">{{ $group['label'] }}</a>
    @endforeach
</nav>

<section class="faqs-content-band" id="faqs">
    @foreach($faqGroups as $group)
        <article class="faqs-chapter">
            @if(!empty($group['image']))
                <figure class="faqs-scene{{ $loop->even ? ' faqs-scene--end' : '' }}">
                    <img src="{{ \App\Support\MediaPath::publicUrl($group['image']) }}" alt="{{ $group['image_alt'] ?? $group['label'] }}" loading="lazy">
                    <figcaption>
                        <small>Field notes</small>
                        {{ $group['display'] ?? $group['label'] }}
                    </figcaption>
                </figure>
            @endif
            <div class="faqs-category" id="{{ $group['id'] }}">
                <h3 class="{{ !empty($group['image']) ? 'visually-hidden' : 'faqs-category-label' }}">{{ $group['label'] }}</h3>
                @foreach($group['items'] as $item)
                    <details class="faq-item" @if($loop->parent->first && $loop->first) open @endif>
                        <summary class="faq-question"><span>{{ $item['q'] }}</span><i data-lucide="chevron-down"></i></summary>
                        <div class="faq-answer"><p>{{ $item['a'] }}</p></div>
                    </details>
                @endforeach
            </div>
        </article>
    @endforeach
</section>

<x-public.cta-section
    label="Still have a question"
    title="Ask a trip advisor instead."
    text="If the answer depends on your dates, your family or a particular lodge, a private proposal is the cleaner next step."
    image="https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=1800&q=82&fm=webp"
    buttonText="Request a proposal"
    :url="route('public.booking')"
/>
@endsection
