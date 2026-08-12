@extends('layouts.admin')
@section('title', $page ? 'Edit Page' : 'New Page')
@section('content')
<div class="page-heading">
    <div><p class="eyebrow">CMS</p><h1>{{ $page ? 'Edit: '.$page->title : 'New Page' }}</h1></div>
</div>
@include('admin.partials.flash')
<form method="POST" action="{{ $page ? route('admin.cms.pages.update', $page) : route('admin.cms.pages.store') }}" class="ops-panel" enctype="multipart/form-data">
    @csrf @if($page) @method('PUT') @endif
    <div class="ops-form-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
        <label class="span-2">Page Title<input name="title" value="{{ old('title', $page->title ?? '') }}" required></label>
        <label>Type
            <select name="type">
                <option value="page" @selected(old('type', $page->type ?? '') === 'page')>Page</option>
                <option value="blog" @selected(old('type', $page->type ?? '') === 'blog')>Blog Post</option>
                <option value="destination" @selected(old('type', $page->type ?? '') === 'destination')>Destination</option>
            </select>
        </label>
        <label>Cover Image URL<input name="cover_image" value="{{ old('cover_image', $page->cover_image ?? '') }}"></label>
        <label>Upload / replace cover image<input type="file" name="cover_upload" accept="image/jpeg,image/png,image/webp,image/gif"><small>JPG, PNG, WebP or GIF; max 8 MB.</small></label>
        <label>SEO Title<input name="seo_title" value="{{ old('seo_title', $page->seo_title ?? '') }}"></label>
        <label class="span-2">SEO Description<textarea name="seo_description" rows="2">{{ old('seo_description', $page->seo_description ?? '') }}</textarea></label>
        <label class="span-2">Content<textarea name="content" rows="15" style="font-family:monospace;">{{ old('content', $page->content ?? '') }}</textarea></label>
        @if($page)
        <label class="checkbox-label"><input type="checkbox" name="published" value="1" @checked(old('published', $page->published))> Published</label>
        @endif
    </div>
    <div class="ops-form-footer">
        <a href="{{ route('admin.cms.index') }}" class="button button-secondary">Cancel</a>
        <button class="button button-primary"><i data-lucide="save"></i>{{ $page ? 'Update' : 'Create' }}</button>
    </div>
</form>
<style>.checkbox-label { display: flex; align-items: center; gap: 0.5rem; } .checkbox-label input { width: auto; }</style>
@endsection
