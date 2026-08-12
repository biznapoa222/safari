@extends('layouts.admin')
@section('title', 'Bookings')
@section('content')
<x-admin.top-bar
    title="Bookings"
    description="Bookings Manager"
    addLabel="New Booking"
    addRoute="{{ route('admin.bookings.create') }}"
    searchPlaceholder="Search by reference or client..."
/>
@include('admin.partials.flash')
<form class="ops-filters" method="GET">
    <label class="ops-search"><i data-lucide="search"></i><input name="search" value="{{ request('search') }}" placeholder="Search by reference or client..."></label>
    <select name="status" onchange="this.form.submit()">
        <option value="">All Statuses</option>
        @foreach($statuses as $k => $v)<option value="{{ $k }}" @selected(request('status') === $k)>{{ $v }}</option>@endforeach
    </select>
    <select name="payment_status" onchange="this.form.submit()">
        <option value="">All Payments</option>
        <option value="unpaid" @selected(request('payment_status') === 'unpaid')>Unpaid</option>
        <option value="partial" @selected(request('payment_status') === 'partial')>Partial</option>
        <option value="paid" @selected(request('payment_status') === 'paid')>Paid</option>
    </select>
    <button class="button button-primary">Filter</button>
</form>
<div class="table-wrap">
    <table class="ops-table">
        <thead><tr><th>Reference</th><th>Client</th><th>Status</th><th>Dates</th><th>Guests</th><th>Total</th><th>Paid</th><th>Balance</th><th>Payment</th><th>Consultant</th><th>Created</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($bookings as $b)
            <tr>
                <td><strong>{{ $b->reference }}</strong></td>
                <td>{{ $b->lead?->name ?? 'N/A' }}</td>
                <td><span class="status status--{{ $b->status }}">{{ $statuses[$b->status] ?? $b->status }}</span></td>
                <td><small>{{ $b->start_date?->format('d/m/Y') ?? '-' }} - {{ $b->end_date?->format('d/m/Y') ?? '-' }}</small></td>
                <td>{{ $b->guests }}</td>
                <td><strong>{{ $b->currency }} {{ number_format($b->total_amount, 2) }}</strong></td>
                <td class="text-green">{{ number_format($b->amount_paid, 2) }}</td>
                <td>{{ number_format($b->balance, 2) }}</td>
                <td><span class="status status--{{ $b->payment_status }}">{{ ucfirst($b->payment_status) }}</span></td>
                <td>{{ $b->consultant?->name ?? '-' }}</td>
                <td><small>{{ $b->created_at->format('d/m/Y') }}</small></td>
                <td>
                    <div class="ops-actions">
                        <a href="{{ route('admin.bookings.edit', $b) }}"><i data-lucide="square-pen"></i></a>
                        <a href="{{ route('admin.bookings.show', $b) }}"><i data-lucide="eye"></i></a>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="12" class="text-center text-muted">No bookings found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="ops-pagination">{{ $bookings->links() }}</div>
<style>
.ops-actions { display: flex; gap: 0.25rem; }
.ops-actions a { padding: 0.25rem; color: var(--text-muted); }
.ops-actions a:hover { color: var(--primary); }
.text-green { color: #22c55e; }
</style>
@endsection
