@extends('layouts.public')
@section('title', $activity->name)
@section('content')
<section style="padding:4rem 2rem;max-width:900px;margin:0 auto;">
    <a href="{{ route('admin.activities.edit', $activity) }}" class="button button-secondary">&larr; Back to Edit</a>
    <h1 style="font-size:2.5rem;margin:1rem 0 0.5rem;">{{ $activity->translations->where('locale', 'en')->first()?->title ?? $activity->name }}</h1>
    <p style="color:var(--text-muted);margin-bottom:2rem;">{{ $activity->location }}, {{ $activity->country }}</p>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:2rem;">
        <div><strong>Duration:</strong> {{ $activity->duration_hours ? $activity->duration_hours.' hours' : 'N/A' }}</div>
        <div><strong>Min Pax:</strong> {{ $activity->min_pax ?? 'N/A' }}</div>
        <div><strong>Min Age:</strong> {{ $activity->min_age ?? 'N/A' }}</div>
        <div><strong>Pickup Time:</strong> {{ $activity->pickup_time ?? 'N/A' }}</div>
        <div><strong>Category:</strong> {{ $activity->category?->name ?? 'N/A' }}</div>
        <div><strong>Currency:</strong> {{ $activity->currency }}</div>
    </div>

    @if($activity->description)
        <div style="margin-bottom:2rem;">
            <h3>Description</h3>
            <p>{{ $activity->description }}</p>
        </div>
    @endif

    @if($activity->prices->count())
        <h3>Pricing</h3>
        <div class="table-wrap">
            <table class="ops-table">
                <thead><tr><th>Type</th><th>Season</th><th>Year</th><th>Price</th></tr></thead>
                <tbody>
                    @foreach($activity->prices as $price)
                        <tr>
                            <td>{{ ucwords(str_replace('_', ' ', $price->type)) }}</td>
                            <td>{{ ucfirst($price->season) }}</td>
                            <td>{{ $price->year }}</td>
                            <td><strong>{{ $price->currency }} {{ number_format($price->price, 2) }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- Pricing Cards --}}
    @php
        $priceByType = $activity->prices->groupBy('type');
    @endphp
    @if($priceByType->count())
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1rem;margin-top:2rem;">
            @foreach($priceByType as $type => $prices)
                <div style="border:1px solid var(--border);padding:1rem;border-radius:0.5rem;">
                    <h4>{{ ucwords(str_replace('_', ' ', $type)) }}</h4>
                    @foreach($prices as $p)
                        <div><small>{{ ucfirst($p->season) }} {{ $p->year }}</small><br><strong>{{ $p->currency }} {{ number_format($p->price, 2) }}</strong></div>
                    @endforeach
                </div>
            @endforeach
        </div>
    @endif
</section>
@endsection
