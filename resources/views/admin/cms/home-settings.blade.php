@extends('layouts.admin')

@section('title', 'Homepage Settings')

@section('content')
<div class="page-heading">
    <div>
        <p class="eyebrow">Website CMS</p>
        <h1>Homepage Settings</h1>
        <p>Control the public hero, featured website content, and homepage SEO metadata.</p>
    </div>
    <a href="{{ route('home') }}" target="_blank" class="button button-secondary"><i data-lucide="external-link"></i>View website</a>
</div>

@include('admin.partials.flash')

<form method="POST" action="{{ route('admin.cms.home-settings.update') }}" class="ops-panel" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="ops-form-grid website-settings-grid">
        <label class="span-2">Hero image URL
            <input name="hero_image" value="{{ old('hero_image', $settings->hero_image) }}" placeholder="https://...jpg or /storage/...webp">
        </label>

        <label>Hero title
            <input name="hero_title" value="{{ old('hero_title', $settings->hero_title) }}" required>
        </label>

        <label>Open Graph image URL
            <input name="open_graph_image" value="{{ old('open_graph_image', $settings->open_graph_image) }}">
        </label>

        <label class="span-2">Hero subtitle
            <textarea name="hero_subtitle" rows="3">{{ old('hero_subtitle', $settings->hero_subtitle) }}</textarea>
        </label>

        <section class="span-2 destination-media-manager">
            <div class="destination-media-heading">
                <div><strong>Destination image library</strong><small>Control the unique hero and menu/card gallery for every country. Paste image URLs or upload files; uploads replace the hero and append to the gallery.</small></div>
                <span><i data-lucide="images"></i> Editable website media</span>
            </div>
            @foreach(['kenya'=>'Kenya','tanzania'=>'Tanzania','uganda'=>'Uganda','rwanda'=>'Rwanda','south-africa'=>'South Africa','namibia'=>'Namibia','botswana'=>'Botswana'] as $slug => $countryName)
                @php $media = $settings->mediaFor($slug); @endphp
                <details class="destination-media-country" @if($loop->first) open @endif>
                    <summary><strong>{{ $countryName }}</strong><span>{{ count($media['gallery']) }} gallery images <i data-lucide="chevron-down"></i></span></summary>
                    <div class="destination-media-fields">
                        <label>Hero image URL<input name="destination_media[{{ $slug }}][hero]" value="{{ old("destination_media.{$slug}.hero", $media['hero']) }}"></label>
                        <label>Upload replacement hero<input type="file" name="destination_uploads[{{ $slug }}][hero]" accept="image/jpeg,image/png,image/webp"></label>
                        <div class="destination-media-preview"><img src="{{ \App\Support\MediaPath::publicUrl($media['hero']) }}" alt="{{ $countryName }} current hero"><small>Current hero</small></div>
                    </div>
                    <div class="destination-gallery-fields">
                        @foreach(array_pad($media['gallery'], 6, '') as $index => $url)
                            <label>Gallery image {{ $index + 1 }}<input name="destination_media[{{ $slug }}][gallery][]" value="{{ old("destination_media.{$slug}.gallery.{$index}", $url) }}"></label>
                        @endforeach
                    </div>
                    <label class="destination-gallery-upload">Add gallery images<input type="file" name="destination_uploads[{{ $slug }}][gallery][]" accept="image/jpeg,image/png,image/webp" multiple><small>Up to 8 images total. Each image can be changed later.</small></label>
                </details>
            @endforeach
        </section>

        <label>Featured destinations
            <select name="featured_destinations[]" multiple size="6">
                @foreach($destinations as $destination)
                    <option value="{{ $destination->id }}" @selected(in_array($destination->id, old('featured_destinations', $settings->featured_destinations ?? [])))>{{ $destination->name }}</option>
                @endforeach
            </select>
            <small>Leave blank to show the first active countries.</small>
        </label>

        <label>Featured safaris
            <select name="featured_safaris[]" multiple size="6">
                @foreach($safaris as $safari)
                    <option value="{{ $safari->id }}" @selected(in_array($safari->id, old('featured_safaris', $settings->featured_safaris ?? [])))>{{ $safari->title }}</option>
                @endforeach
            </select>
            <small>Leave blank to show featured published itineraries.</small>
        </label>

        <label>Featured activities
            <select name="featured_activities[]" multiple size="6">
                @foreach($activities as $activity)
                    <option value="{{ $activity->id }}" @selected(in_array($activity->id, old('featured_activities', $settings->featured_activities ?? [])))>{{ $activity->name }}</option>
                @endforeach
            </select>
            <small>Leave blank to show published website activities.</small>
        </label>

        <label class="settings-toggle">Published accommodation
            <span>
                <input type="checkbox" name="show_published_accommodation" value="1" @checked(old('show_published_accommodation', $settings->show_published_accommodation))>
                Display published accommodation on the public homepage ({{ $accommodationsCount }} currently published)
            </span>
        </label>

        <label>SEO title
            <input name="seo_title" value="{{ old('seo_title', $settings->seo_title) }}">
        </label>

        <label>SEO description
            <textarea name="seo_description" rows="4">{{ old('seo_description', $settings->seo_description) }}</textarea>
        </label>
    </div>

    @if($errors->any())
        <div class="ops-alert ops-alert--error" style="margin: 0 18px 18px;">
            <i data-lucide="triangle-alert"></i>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <div class="ops-form-footer">
        <a href="{{ route('admin.cms.index') }}" class="button button-secondary">All CMS pages</a>
        <button class="button button-primary"><i data-lucide="save"></i>Save homepage settings</button>
    </div>
</form>
@endsection
