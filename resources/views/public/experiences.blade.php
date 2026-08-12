@extends('layouts.public')

@section('title', 'Experiences | Shishi Footsteps')
@section('description', 'Game drives, balloon safaris, gorilla trekking, cultural visits, beach extensions and honeymoon safari experiences.')

@section('content')
@php
    $cms = fn($key,$fallback='') => \App\Models\CmsContentBlock::value('experiences',$key,$fallback);
    $hero = 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1800&q=82&fm=webp';
    $fallback = [
        'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=900&q=82&fm=webp',
        'https://images.unsplash.com/photo-1540573133985-87b6da6d54a9?auto=format&fit=crop&w=900&q=82&fm=webp',
        'https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&w=900&q=82&fm=webp',
    ];
@endphp

<x-public.page-hero label="Experiences" :title="$cms('hero_title')" :subtitle="$cms('hero_subtitle')" :image="\App\Support\MediaPath::publicUrl($cms('hero_image',$hero))" />

<section class="content-band">
    <div class="experience-grid">
        @forelse($activities as $activity)
            @php $translation = $activity->translation(); @endphp
            @php $image = is_array($activity->images ?? null) && count($activity->images) ? $activity->images[0] : $fallback[$loop->index % count($fallback)]; @endphp
            <x-public.experience-card :title="$translation?->title ?? $activity->name" :description="$translation?->short_description ?? $activity->description ?? 'A handpicked safari experience designed around place, season and your travel style.'" :image="$image" icon="sparkles" :url="$activity->slug ? route('public.experiences.show', $activity->slug) : null" />
        @empty
            <x-public.experience-card title="Game Drives" description="Private guiding in the strongest wildlife areas for the season." :image="$fallback[0]" icon="binoculars" />
            <x-public.experience-card title="Gorilla Trekking" description="A rare forest encounter shaped with care and good timing." :image="$fallback[1]" icon="leaf" />
            <x-public.experience-card title="Beach Extensions" description="Soft landings after the bush, from Zanzibar to the Indian Ocean." :image="$fallback[2]" icon="waves" />
        @endforelse
    </div>
    <div class="pagination-wrap">{{ $activities->links() }}</div>
</section>
@endsection
