@extends('layouts.admin')
@section('title', 'Booking: '.$booking->reference)
@section('content')
<div class="page-heading">
    <div><p class="eyebrow">Booking Details</p><h1>{{ $booking->reference }}</h1><p>{{ $booking->lead?->name ?? 'No client' }}</p></div>
    <div class="heading-actions">
        <a href="{{ route('admin.bookings.edit', $booking) }}" class="button button-primary"><i data-lucide="square-pen"></i>Edit</a>
    </div>
</div>
@include('admin.partials.flash')
<section class="ops-panel">
    <div class="ops-panel-title"><h2>Booking Summary</h2></div>
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;text-align:center;">
        <div><small class="text-muted">Status</small><div><span class="status status--{{ $booking->status }}">{{ \App\Models\Booking::$statuses[$booking->status] ?? $booking->status }}</span></div></div>
        <div><small class="text-muted">Total</small><h3>{{ $booking->currency }} {{ number_format($booking->total_amount,2) }}</h3></div>
        <div><small class="text-muted">Paid</small><h3 class="text-green">{{ $booking->currency }} {{ number_format($booking->amount_paid,2) }}</h3></div>
        <div><small class="text-muted">Balance</small><h3 style="color:{{ $booking->balance > 0 ? '#ef4444' : '#22c55e' }}">{{ $booking->currency }} {{ number_format($booking->balance,2) }}</h3></div>
    </div>
</section>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-top:1rem;">
    <section class="ops-panel">
        <div class="ops-panel-title"><h2>Payment History</h2></div>
        @forelse($booking->payments as $p)
        <div style="display:flex;justify-content:space-between;padding:0.5rem 0;border-bottom:1px solid var(--border);">
            <div><strong>{{ $p->reference }}</strong><br><small>{{ \App\Models\Payment::$methods[$p->method] ?? $p->method }} · {{ ucfirst($p->type) }}</small></div>
            <div style="text-align:right;"><strong>{{ $p->currency }} {{ number_format($p->amount,2) }}</strong><br><small>{{ $p->paid_at?->format('d/m/Y') }}</small></div>
        </div>
        @empty
        <p class="text-muted">No payments recorded.</p>
        @endforelse
    </section>
    <section class="ops-panel">
        <div class="ops-panel-title"><h2>Payment Links</h2></div>
        @forelse($booking->paymentLinks as $pl)
        <div style="padding:0.5rem 0;border-bottom:1px solid var(--border);">
            <div><strong>{{ ucfirst($pl->type) }}</strong> · {{ $pl->currency }} {{ number_format($pl->amount,2) }}</div>
            <small>{{ $pl->is_used ? 'Used '.($pl->used_at?->format('d/m/Y H:i') ?? '') : 'Active' }}</small>
            <br><small><a href="{{ route('admin.payments.links.show', $pl->token) }}" target="_blank">{{ route('admin.payments.links.show', $pl->token) }}</a></small>
        </div>
        @empty
        <p class="text-muted">No payment links generated.</p>
        @endforelse
    </section>
</div>
<style>.text-green { color: #22c55e; }</style>
@endsection
