@extends('layouts.admin')
@section('title', $activity->name.' | Activity')
@section('content')
<div class="page-heading">
    <div>
        <p class="eyebrow"><a href="{{ route('admin.activities.index') }}">Activities</a></p>
        <h1>{{ $activity->name }}</h1>
        <p>{{ $activity->category?->name ?? 'Uncategorized' }} @if($activity->country)/ {{ $activity->country }} @endif</p>
    </div>
    <div class="heading-actions">
        <a href="{{ route('admin.activities.edit', $activity->id) }}" class="button button-secondary"><i data-lucide="pencil"></i>Edit</a>
        <a href="{{ route('admin.activities.index') }}" class="button button-ghost">Back</a>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 320px;gap:18px;align-items:start;">
    <section class="ops-panel">
        <div class="ops-panel-title"><h2>Details</h2></div>
        <div style="padding:15px;display:grid;gap:14px;">
            @if($activity->description)<div><strong style="font-size:7px;letter-spacing:1px;text-transform:uppercase;color:#6a7b73;">Description</strong><p style="margin-top:6px;font-size:12px;line-height:1.9;">{{ $activity->description }}</p></div>@endif
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;">
                @if($activity->duration_hours)<div><strong style="font-size:7px;letter-spacing:1px;text-transform:uppercase;color:#6a7b73;">Duration</strong><p style="margin-top:4px;font-size:11px;">{{ $activity->duration_hours }} hours</p></div>@endif
                @if($activity->min_pax)<div><strong style="font-size:7px;letter-spacing:1px;text-transform:uppercase;color:#6a7b73;">Min Pax</strong><p style="margin-top:4px;font-size:11px;">{{ $activity->min_pax }}</p></div>@endif
                @if($activity->min_age)<div><strong style="font-size:7px;letter-spacing:1px;text-transform:uppercase;color:#6a7b73;">Min Age</strong><p style="margin-top:4px;font-size:11px;">{{ $activity->min_age }}+</p></div>@endif
                @if($activity->location)<div><strong style="font-size:7px;letter-spacing:1px;text-transform:uppercase;color:#6a7b73;">Location</strong><p style="margin-top:4px;font-size:11px;">{{ $activity->location }}</p></div>@endif
                @if($activity->pickup_time)<div><strong style="font-size:7px;letter-spacing:1px;text-transform:uppercase;color:#6a7b73;">Pickup</strong><p style="margin-top:4px;font-size:11px;">{{ $activity->pickup_time }}</p></div>@endif
                @if($activity->currency)<div><strong style="font-size:7px;letter-spacing:1px;text-transform:uppercase;color:#6a7b73;">Currency</strong><p style="margin-top:4px;font-size:11px;">{{ $activity->currency }}</p></div>@endif
            </div>
        </div>
    </section>
    <aside style="display:grid;gap:14px;">
        <section class="ops-panel">
            <div class="ops-panel-title"><h2>Status</h2></div>
            <div style="padding:15px;display:grid;gap:10px;">
                <div><strong style="font-size:7px;letter-spacing:1px;text-transform:uppercase;color:#6a7b73;">Website</strong><p style="margin-top:4px;"><span class="ops-pill ops-pill--{{ $activity->published_on_website ? 'success' : 'muted' }}">{{ $activity->published_on_website ? 'Published' : 'Draft' }}</span></p></div>
                <div><strong style="font-size:7px;letter-spacing:1px;text-transform:uppercase;color:#6a7b73;">Activity Status</strong><p style="margin-top:4px;font-size:11px;">{{ $activity->activity_status ?? 'Active' }}</p></div>
            </div>
        </section>
        @if($activity->prices->isNotEmpty())
        <section class="ops-panel">
            <div class="ops-panel-title"><h2>Prices</h2></div>
            <div style="padding:15px;display:grid;gap:8px;">
                @foreach($activity->prices as $price)
                    <div style="display:flex;justify-content:space-between;font-size:11px;padding:6px 0;border-bottom:1px solid var(--line);">
                        <span>{{ $price->type ?? 'Standard' }} @if($price->season)({{ $price->season }})@endif</span>
                        <strong>${{ number_format((float) $price->price, 2) }}</strong>
                    </div>
                @endforeach
            </div>
        </section>
        @endif
    </aside>
</div>
@endsection
