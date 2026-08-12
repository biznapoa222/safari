@extends('layouts.public')

@section('title', 'Safaris | Shishi Footsteps')
@section('description', 'Browse featured luxury safari packages and private tailor-made journeys from Shishi Footsteps.')

@section('content')
@php
    $cms = fn($key,$fallback='') => \App\Models\CmsContentBlock::value('safaris',$key,$fallback);
    $hero = asset('images/itineraries/tanzania-classic-cover.webp');
    $countries = ['Kenya','Tanzania','Uganda','Rwanda','South Africa','Namibia','Botswana'];
@endphp

<x-public.page-hero label="Safari Packages" :title="$cms('hero_title')" :subtitle="$cms('hero_subtitle')" :image="\App\Support\MediaPath::publicUrl($cms('hero_image',$hero))" />

<section class="content-band">
    <div class="safari-filters" style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;justify-content:space-between;margin:0 0 22px;position:relative">
        <div style="flex:1;min-width:280px;position:relative">
            <x-public.search-with-dropdown
                placeholder="Search safaris by title, country or activity..."
                :endpoint="route('public.search.itineraries')"
                :extra="' '"
            />
        </div>
        <select id="safariCountryFilter" style="min-width:180px;height:42px;padding:0 12px;border:1px solid #d9d0c1;border-radius:9px;font-size:10px;background:#F8F5EF;color:var(--text);outline:none">
            <option value="">All countries</option>
            @foreach($countries as $c)
            <option value="{{ $c }}" @selected(request('country') === $c)>{{ $c }}</option>
            @endforeach
        </select>
        <button id="safariReset" type="button" class="button button-secondary" style="height:42px;font-size:10px">Reset</button>
    </div>

    <div id="safariGridWrap">
        @include('public.partials._safari_grid')
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var dropdown = document.querySelector('.search-dropdown');
    var search = dropdown ? dropdown.querySelector('.search-dropdown-input') : null;
    var country = document.getElementById('safariCountryFilter');
    var reset = document.getElementById('safariReset');
    var wrap = document.getElementById('safariGridWrap');
    var timer;
    var firstRun = true;

    function fetchGrid(page) {
        var params = new URLSearchParams();
        if (search && search.value.trim()) params.set('search', search.value.trim());
        if (country && country.value) params.set('country', country.value);
        if (page) params.set('page', page);
        var url = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        if (!firstRun) history.replaceState(null, '', url);
        firstRun = false;

        wrap.style.opacity = '0.55';
        fetch(url + (url.includes('?') ? '&' : '?') + 'ajax=1', { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                wrap.innerHTML = data.html || '';
                wrap.style.opacity = '1';
                if (window.lucide && typeof window.lucide.createIcons === 'function') window.lucide.createIcons();
            })
            .catch(function() { wrap.style.opacity = '1'; });
    }

    function debounce() { clearTimeout(timer); timer = setTimeout(function(){ fetchGrid(); }, 250); }

    search?.addEventListener('input', debounce);
    country?.addEventListener('change', function(){ fetchGrid(); });
    reset?.addEventListener('click', function(){
        if (search) search.value='';
        country.value='';
        fetchGrid();
    });

    wrap.addEventListener('click', function(e){
        var a = e.target.closest('.pagination-wrap a');
        if (!a) return;
        var href = a.getAttribute('href');
        if (!href) return;
        e.preventDefault();
        var pageMatch = href.match(/[?&]page=(\d+)/);
        fetchGrid(pageMatch ? pageMatch[1] : 1);
    });
});
</script>
@endpush
@endsection
