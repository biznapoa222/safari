@extends('layouts.admin')
@section('title', 'CRM - Leads')
@section('content')
<x-admin.top-bar
    title="Leads & Enquiries"
    description="Customer Relationship Management"
    :addButton="false"
    searchPlaceholder="Search by name, email, phone..."
/>
@include('admin.partials.flash')
<form class="ops-filters" method="GET">
    <label class="ops-search"><i data-lucide="search"></i><input name="search" value="{{ request('search') }}" placeholder="Search by name, email, phone..."></label>
    <select name="status" onchange="this.form.submit()">
        <option value="">All Statuses</option>
        @foreach($statuses as $k => $v)<option value="{{ $k }}" @selected(request('status') === $k)>{{ $v }}</option>@endforeach
    </select>
    <select name="source" onchange="this.form.submit()">
        <option value="">All Sources</option>
        @foreach($sources as $k => $v)<option value="{{ $k }}" @selected(request('source') === $k)>{{ $v }}</option>@endforeach
    </select>
    <select name="consultant" onchange="this.form.submit()">
        <option value="">All Consultants</option>
        @foreach($consultants as $u)<option value="{{ $u->id }}" @selected(request('consultant') == $u->id)>{{ $u->name }}</option>@endforeach
    </select>
    <button class="button button-primary">Filter</button>
</form>
<div class="table-wrap">
    <table class="ops-table">
        <thead><tr><th>Name</th><th>Contact</th><th>Source</th><th>Status</th><th>Destination</th><th>Travel Date</th><th>Guests</th><th>Value</th><th>Consultant</th><th>Created</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($leads as $lead)
            <tr>
                <td><strong>{{ $lead->name }}</strong></td>
                <td><small>{{ $lead->email }}<br>{{ $lead->phone ?? '' }}</small></td>
                <td><span class="status status--source">{{ $sources[$lead->source] ?? $lead->source }}</span></td>
                <td><span class="status status--{{ $lead->status }}">{{ $statuses[$lead->status] ?? $lead->status }}</span></td>
                <td>{{ $lead->destination ?? '-' }}</td>
                <td>{{ $lead->travel_date?->format('d/m/Y') ?? '-' }}</td>
                <td>{{ $lead->travelers }}</td>
                <td>{{ $lead->currency }} {{ number_format($lead->estimated_value ?? 0) }}</td>
                <td>{{ $lead->consultant?->name ?? 'Unassigned' }}</td>
                <td><small>{{ $lead->created_at->format('d/m/Y') }}</small></td>
                <td>
                    <div class="ops-actions">
                        <a href="{{ route('admin.leads.show', $lead) }}"><i data-lucide="eye"></i></a>
                        <a href="{{ route('admin.leads.show', $lead) }}#conversations"><i data-lucide="message-square"></i></a>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="11" class="text-center text-muted">No leads found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="ops-pagination">{{ $leads->links() }}</div>
<style>
.ops-actions { display: flex; gap: 0.25rem; }
.ops-actions a { padding: 0.25rem; color: var(--text-muted); }
.ops-actions a:hover { color: var(--primary); }
.status--source { background: var(--bg-subtle); color: var(--text); }
</style>
@endsection
