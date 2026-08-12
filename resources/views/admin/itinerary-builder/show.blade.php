@extends('layouts.admin')
@section('title', $itinerary->title.' | Itinerary')
@section('content')
<div class="page-heading">
    <div>
        <p class="eyebrow"><a href="{{ route('admin.itinerary-builder.index') }}">Itinerary Builder</a></p>
        <h1>{{ $itinerary->title }}</h1>
        <p>{{ $itinerary->duration_days }} days @if($itinerary->country)/ {{ $itinerary->country }} @endif @if($itinerary->price_from)· From ${{ number_format((float) $itinerary->price_from) }}@endif</p>
    </div>
    <div class="heading-actions">
        <a href="{{ route('admin.itinerary-builder.edit', $itinerary->id) }}" class="button button-secondary"><i data-lucide="pencil"></i>Edit</a>
        <a href="{{ route('admin.itinerary-builder.index') }}" class="button button-ghost">Back</a>
    </div>
</div>

<section class="ops-panel">
    <div class="ops-panel-title"><h2>Itinerary Days</h2></div>
    <div style="padding:15px;display:grid;gap:10px;">
        @forelse($itinerary->days as $day)
            <div style="display:grid;grid-template-columns:60px 1fr;gap:16px;padding:16px;background:var(--bg-subtle);border:1px solid var(--line);border-radius:8px;">
                <div style="text-align:center;">
                    <div style="font-size:7px;font-weight:800;letter-spacing:1px;text-transform:uppercase;color:#8a7144;">Day</div>
                    <div style="font-family:var(--font-display);font-size:28px;line-height:1;">{{ $day->day_number }}</div>
                </div>
                <div>
                    <h3 style="margin:0 0 6px;font-size:16px;">{{ $day->title ?? 'Day '.$day->day_number }}</h3>
                    @if($day->location)<p style="margin:0 0 6px;display:flex;align-items:center;gap:5px;font-size:10px;color:#8a6430;font-weight:800;"><i data-lucide="map-pin" style="width:12px;"></i>{{ $day->location }}</p>@endif
                    @if($day->activities)<p style="margin:0 0 6px;font-size:12px;line-height:1.8;color:#54635b;">{{ $day->activities }}</p>@endif
                    <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:6px;">
                        @if($day->meal_plan)<span style="font-size:8px;font-weight:800;color:#6f8a7a;display:flex;align-items:center;gap:4px;"><i data-lucide="utensils-crossed" style="width:11px;"></i>{{ $day->meal_plan }}</span>@endif
                        @if($day->transfers)<span style="font-size:8px;font-weight:800;color:#6f8a7a;display:flex;align-items:center;gap:4px;"><i data-lucide="car" style="width:11px;"></i>{{ $day->transfers }}</span>@endif
                    </div>
                </div>
            </div>
        @empty
            <div style="text-align:center;padding:40px;color:#7d8b84;">
                <i data-lucide="map" style="width:32px;margin-bottom:12px;"></i>
                <p>No days added yet. Edit this itinerary to add days.</p>
            </div>
        @endforelse
    </div>
</section>
@endsection
