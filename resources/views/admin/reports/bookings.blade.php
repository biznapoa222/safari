@extends('layouts.admin')
@section('title', 'Booking Report')
@section('content')

<x-admin.top-bar
    title="Booking Report"
    description="Reports"
    :addButton="false"
    :search="false"
/>

<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem;">
    <div class="ops-panel" style="text-align:center;padding:1rem;">
        <small class="text-muted">Total</small><h3>{{ $summary['total'] }}</h3>
    </div>
    <div class="ops-panel" style="text-align:center;padding:1rem;">
        <small class="text-muted">Confirmed</small><h3 class="text-green">{{ $summary['confirmed'] }}</h3>
    </div>
    <div class="ops-panel" style="text-align:center;padding:1rem;">
        <small class="text-muted">Pending</small><h3 style="color:#f59e0b;">{{ $summary['pending'] }}</h3>
    </div>
    <div class="ops-panel" style="text-align:center;padding:1rem;">
        <small class="text-muted">Cancelled</small><h3 style="color:#ef4444;">{{ $summary['cancelled'] }}</h3>
    </div>
</div>

<section class="ops-panel">
    <div class="ops-panel-title"><h2>All Bookings</h2></div>
    <div class="table-wrap">
        <table class="ops-table">
            <thead><tr><th>Reference</th><th>Client</th><th>Status</th><th>Total</th><th>Paid</th><th>Date</th></tr></thead>
            <tbody>
                @forelse($bookings as $b)
                <tr>
                    <td><strong>{{ $b->reference }}</strong></td>
                    <td>{{ $b->lead?->name ?? 'N/A' }}</td>
                    <td><span class="status status--{{ $b->status }}">{{ \App\Models\Booking::$statuses[$b->status] ?? $b->status }}</span></td>
                    <td>{{ $b->currency }} {{ number_format($b->total_amount, 2) }}</td>
                    <td>{{ number_format($b->amount_paid, 2) }}</td>
                    <td>{{ $b->created_at->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted">No bookings found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="ops-pagination">{{ $bookings->links() }}</div>
</section>

<style>.text-green { color: #22c55e; }</style>
@endsection
