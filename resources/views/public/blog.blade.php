@extends('layouts.public')

@section('title', ($destination ? $destination.' journal' : 'Blog').' | Shishi Footsteps')
@section('description', $destination ? 'Safari stories, seasonal notes and travel inspiration for '.$destination.' from Shishi Footsteps.' : 'Safari planning notes, travel inspiration and destination stories from Shishi Footsteps.')

@section('content')
@php $hero = 'https://images.unsplash.com/photo-1493246507139-91e8fad9978e?auto=format&fit=crop&w=1800&q=82&fm=webp'; $cms=fn($key,$fallback='')=>\App\Models\CmsContentBlock::value('blog',$key,$fallback); $destination = $destination ?? ''; @endphp

<x-public.page-hero label="Travel Guides" :title="$destination ? $destination.' journal' : $cms('hero_title')" :subtitle="$destination ? 'Stories, seasonal notes and planning ideas for a private '.$destination.' safari.' : $cms('hero_subtitle')" :image="\App\Support\MediaPath::publicUrl($cms('hero_image',$hero))" />

<section class="content-band">
    <div class="blog-grid">
        @forelse($posts as $post)
            <article class="blog-card">
                <a href="{{ route('public.blog.post', $post->slug) }}" class="blog-image-link"><img src="{{ \App\Support\MediaPath::publicUrl($post->cover_image) ?: 'https://images.unsplash.com/photo-1489392191049-fc10c97e64b6?auto=format&fit=crop&w=900&q=82&fm=webp' }}" alt="{{ $post->title }}" loading="lazy"></a>
                <div>
                    <x-public.section-label :label="$post->published_at?->format('M d, Y') ?? 'Travel Guide'" />
                    <h2>{{ $post->title }}</h2>
                    <p>{{ $post->seo_description ?? \Illuminate\Support\Str::limit(strip_tags($post->content), 140) }}</p>
                    <a href="{{ route('public.blog.post', $post->slug) }}">Read more<i data-lucide="arrow-up-right"></i></a>
                </div>
            </article>
        @empty
            <article class="empty-public-state"><h2>Stories are being written.</h2><p>Our travel journal with safari insights, destination guides and planning inspiration will be published soon.</p></article>
        @endforelse
    </div>
    <div class="pagination-wrap">{{ $posts->links() }}</div>
</section>
@endsection
