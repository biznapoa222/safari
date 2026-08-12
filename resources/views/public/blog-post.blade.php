@extends('layouts.public')

@section('title', $post->seo_title ?: $post->title.' | Shishi Footsteps')
@section('description', $post->seo_description ?: \Illuminate\Support\Str::limit(strip_tags($post->content), 150))
@section('og_image', $post->cover_image ?: $settings->open_graph_image)

@section('content')
<x-public.page-hero label="Travel Guide" :title="$post->title" :subtitle="$post->seo_description" :image="$post->cover_image ?: 'https://images.unsplash.com/photo-1489392191049-fc10c97e64b6?auto=format&fit=crop&w=1800&q=82&fm=webp'" />

<article class="article-body">
    {!! $post->content !!}
</article>

<x-public.cta-section label="Plan With Us" title="Turn inspiration into a private safari." text="Our specialists will help you translate ideas into a polished route." image="https://images.unsplash.com/photo-1534177616072-ef7dc120449d?auto=format&fit=crop&w=1800&q=82&fm=webp" />
@endsection
