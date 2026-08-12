@extends('layouts.admin')
@section('title', 'Accommodations')
@section('content')
<x-admin.top-bar
    title="Accommodations"
    description="Accommodation directory"
    addLabel="New Accommodation"
    addRoute="{{ route('admin.accommodations-v2.create') }}"
    searchPlaceholder="Search accommodations..."
/>
@include('admin.partials.flash')
<form class="ops-filters" method="GET">
    <label class="ops-search"><i data-lucide="search"></i><input name="search" value="{{ request('search') }}" placeholder="Search accommodations..."></label>
    <select name="country" onchange="this.form.submit()">
        <option value="">All Countries</option>
        @foreach($countries as $c)<option value="{{ $c }}" @selected(request('country') === $c)>{{ $c }}</option>@endforeach
    </select>
    <select name="type" onchange="this.form.submit()">
        <option value="">All Types</option>
        @foreach($types as $k => $v)<option value="{{ $k }}" @selected(request('type') === $k)>{{ $v }}</option>@endforeach
    </select>
    <button class="button button-primary">Filter</button>
</form>
<div class="table-wrap">
    <table class="ops-table">
        <thead><tr><th>Name</th><th>Type</th><th>Country</th><th>Region</th><th>Level</th><th>Rooms</th><th>Published</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($accommodations as $a)
            <tr>
                <td><strong>{{ $a->name }}</strong></td>
                <td>{{ Accommodation::$types[$a->type] ?? $a->type }}</td>
                <td>{{ $a->country }}</td>
                <td>{{ $a->region ?? '-' }}</td>
                <td>{{ $a->luxury_level ? ucwords(str_replace('_', ' ', $a->luxury_level)) : '-' }}</td>
                <td>{{ $a->rooms_count }}</td>
                <td>@if($a->published)<i data-lucide="check-circle" class="text-green">@else<i data-lucide="x-circle" class="text-red">@endif</td>
                <td><span class="status status--{{ $a->status }}">{{ ucfirst($a->status) }}</span></td>
                <td>
                    <div class="ops-actions">
                        <a href="{{ route('admin.accommodations-v2.edit', $a) }}"><i data-lucide="square-pen"></i></a>
                        <form method="POST" action="{{ route('admin.accommodations-v2.destroy', $a) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button><i data-lucide="trash-2"></i></button></form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="9" class="text-center text-muted">No accommodations found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="ops-pagination">{{ $accommodations->links() }}</div>
@push('styles')
<style>
.text-green { color: #22c55e; width: 16px; height: 16px; }
.text-red { color: #ef4444; width: 16px; height: 16px; }
.ops-actions { display: flex; gap: 0.25rem; }
.ops-actions a, .ops-actions button { padding: 0.25rem; border: none; background: none; cursor: pointer; color: var(--text-muted); }
.ops-actions a:hover, .ops-actions button:hover { color: var(--primary); }
</style>
@endpush
@endsection
