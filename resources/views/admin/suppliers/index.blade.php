@extends('layouts.admin')
@section('title', 'Suppliers')
@section('content')
<x-admin.top-bar
    title="Suppliers"
    description="Supplier Directory"
    addLabel="New Supplier"
    addRoute="{{ route('admin.suppliers.create') }}"
    searchPlaceholder="Search suppliers..."
/>
@include('admin.partials.flash')
<form class="ops-filters" method="GET">
    <label class="ops-search"><i data-lucide="search"></i><input name="search" value="{{ request('search') }}" placeholder="Search suppliers..."></label>
    <select name="type" onchange="this.form.submit()">
        <option value="">All Types</option>
        @foreach($types as $k => $v)<option value="{{ $k }}" @selected(request('type') === $k)>{{ $v }}</option>@endforeach
    </select>
    <select name="country" onchange="this.form.submit()">
        <option value="">All Countries</option>
        @foreach($countries as $c)<option value="{{ $c }}" @selected(request('country') === $c)>{{ $c }}</option>@endforeach
    </select>
    <button class="button button-primary">Filter</button>
</form>
<div class="table-wrap">
    <table class="ops-table">
        <thead><tr><th>Name</th><th>Type</th><th>Country</th><th>Contact</th><th>Phone</th><th>Email</th><th>Classification</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($suppliers as $s)
            <tr>
                <td><strong>{{ $s->name }}</strong></td>
                <td><span class="status">{{ $types[$s->type] ?? $s->type }}</span></td>
                <td>{{ $s->country }}</td>
                <td>{{ $s->contact_person ?? '-' }}</td>
                <td>{{ $s->phone ?? '-' }}</td>
                <td>{{ $s->email ?? '-' }}</td>
                <td>{{ $s->classification ? ucwords(str_replace('_', ' ', $s->classification)) : '-' }}</td>
                <td>
                    <div class="ops-actions">
                        <a href="{{ route('admin.suppliers.edit', $s) }}"><i data-lucide="square-pen"></i></a>
                        <form method="POST" action="{{ route('admin.suppliers.destroy', $s) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button><i data-lucide="trash-2"></i></button></form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center text-muted">No suppliers found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="ops-pagination">{{ $suppliers->links() }}</div>
<style>.ops-actions { display: flex; gap: 0.25rem; } .ops-actions a, .ops-actions button { padding: 0.25rem; border: none; background: none; cursor: pointer; color: var(--text-muted); } .ops-actions a:hover, .ops-actions button:hover { color: var(--primary); }</style>
@endsection
