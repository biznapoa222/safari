@extends('layouts.admin')
@section('title', 'Itinerary Builder')
@section('content')
<x-admin.top-bar
    title="Itinerary Builder"
    description="Safari Programs"
    addLabel="New Itinerary"
    addRoute="{{ route('admin.itinerary-builder.create') }}"
    searchPlaceholder="Search itineraries..."
/>
@include('admin.partials.flash')
<form class="ops-filters" method="GET">
    <label class="ops-search"><i data-lucide="search"></i><input name="search" value="{{ request('search') }}" placeholder="Search itineraries..."></label>
    <button class="button button-primary">Search</button>
</form>
<div class="table-wrap">
    <table class="ops-table">
        <thead><tr><th>Title</th><th>Days</th><th>Country</th><th>Price From</th><th>Published</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($itineraries as $i)
            <tr>
                <td><strong>{{ $i->title }}</strong></td>
                <td>{{ $i->duration_days }} days</td>
                <td>{{ $i->country ?? '-' }}</td>
                <td>{{ $i->currency }} {{ number_format($i->price_from ?? 0) }}</td>
                <td>@if($i->published)<i data-lucide="check-circle" class="text-green">@else<i data-lucide="x-circle" class="text-red">@endif</td>
                <td>
                    <div class="ops-actions">
                        <a href="{{ route('admin.itinerary-builder.edit', $i) }}"><i data-lucide="square-pen"></i></a>
                        <form method="POST" action="{{ route('admin.itinerary-builder.destroy', $i) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button><i data-lucide="trash-2"></i></button></form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center text-muted">No itineraries.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="ops-pagination">{{ $itineraries->links() }}</div>
<style>.text-green { color: #22c55e; width:16px; height:16px; } .text-red { color: #ef4444; width:16px; height:16px; } .ops-actions { display: flex; gap: 0.25rem; } .ops-actions a, .ops-actions button { padding: 0.25rem; border: none; background: none; cursor: pointer; color: var(--text-muted); } .ops-actions a:hover, .ops-actions button:hover { color: var(--primary); }</style>
@endsection
