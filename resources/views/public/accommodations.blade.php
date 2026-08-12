@extends('layouts.public')

@section('title', 'Accommodation | Shishi Footsteps')
@section('description', 'Luxury lodges, tented camps and private retreats selected for Shishi Footsteps safari journeys.')

@section('content')
@php
    $cms = fn($key,$fallback='') => \App\Models\CmsContentBlock::value('accommodations',$key,$fallback);
    $hero = 'https://images.unsplash.com/photo-1491555103944-7c647fd857e6?auto=format&fit=crop&w=1800&q=82&fm=webp';
    $fallback = 'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?auto=format&fit=crop&w=1200&q=82&fm=webp';
@endphp

<x-public.page-hero label="Accommodation" :title="$cms('hero_title')" :subtitle="$cms('hero_subtitle')" :image="\App\Support\MediaPath::publicUrl($cms('hero_image',$hero))" />

<section class="content-band accommodation-list">
        @forelse($accommodations as $accommodation)
            @php $image = is_array($accommodation->images ?? null) && count($accommodation->images) ? $accommodation->images[0] : $fallback; @endphp
            <x-public.accommodation-card
                :title="$accommodation->name"
                :description="$accommodation->description ?? 'A published Shishi Footsteps accommodation partner selected for comfort, service and safari access.'"
                :meta="trim(($accommodation->country ?? '').' / '.($accommodation->region ?? '').' / '.($accommodation->type ?? ''), ' /')"
                :image="$image"
                :reverse="$loop->even"
                :slug="$accommodation->slug"
            />
    @empty
        <x-public.accommodation-card title="Luxury lodges and private safari camps" description="Our accommodation collection is being updated and will appear here as lodges and camps are published." :image="$fallback" meta="Coming soon from our collection" />
    @endforelse
    <div class="pagination-wrap">{{ $accommodations->links() }}</div>
</section>
@endsection
