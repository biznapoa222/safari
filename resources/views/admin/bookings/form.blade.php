@extends('layouts.admin')
@section('title', $booking ? 'Booking: '.$booking->reference : 'New Booking')
@section('content')
<div class="page-heading">
    <div><p class="eyebrow">Booking</p><h1>{{ $booking ? 'Edit: '.$booking->reference : 'New Booking' }}</h1></div>
</div>
@include('admin.partials.flash')

<form method="POST" action="{{ $booking ? route('admin.bookings.update', $booking) : route('admin.bookings.store') }}" class="ops-panel">
    @csrf @if($booking) @method('PUT') @endif
    <div class="ops-panel-title"><h2>Booking Details</h2></div>
    <div class="ops-form-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
        <label>Lead/Client
            <select name="lead_id">
                <option value="">-- Select Lead --</option>
                @foreach($leads as $l)
                    <option value="{{ $l->id }}" @selected(old('lead_id', $booking->lead_id ?? '') == $l->id)>{{ $l->name }} ({{ $l->email }})</option>
                @endforeach
            </select>
        </label>
        <label>Status
            <select name="status">
                @foreach(\App\Models\Booking::$statuses as $k => $v)
                    <option value="{{ $k }}" @selected(old('status', $booking->status ?? 'draft') === $k)>{{ $v }}</option>
                @endforeach
            </select>
        </label>
        <label>Start Date<input type="date" name="start_date" value="{{ old('start_date', $booking->start_date?->format('Y-m-d')) }}"></label>
        <label>End Date<input type="date" name="end_date" value="{{ old('end_date', $booking->end_date?->format('Y-m-d')) }}"></label>
        <label>Number of Guests<input type="number" name="guests" value="{{ old('guests', $booking->guests ?? 2) }}" min="1"></label>
        <label>Currency
            <select name="currency">
                @foreach(['USD','EUR','GBP','KES','AUD','CAD'] as $cur)
                    <option value="{{ $cur }}" @selected(old('currency', $booking->currency ?? 'USD') === $cur)>{{ $cur }}</option>
                @endforeach
            </select>
        </label>
        <label>Consultant
            <select name="assigned_consultant_id">
                <option value="">-- Select --</option>
                @foreach($consultants as $u)
                    <option value="{{ $u->id }}" @selected(old('assigned_consultant_id', $booking->assigned_consultant_id ?? '') == $u->id)>{{ $u->name }}</option>
                @endforeach
            </select>
        </label>
        <label class="span-2">Notes<textarea name="notes" rows="3">{{ old('notes', $booking->notes ?? '') }}</textarea></label>
    </div>

    @if($booking)
    <div class="ops-form-grid" style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;margin-top:1rem;padding-top:1rem;border-top:1px solid var(--border);">
        <label>Total Amount<input type="number" step="0.01" name="total_amount" value="{{ old('total_amount', $booking->total_amount) }}"></label>
        <label>Amount Paid<input type="number" step="0.01" name="amount_paid" value="{{ old('amount_paid', $booking->amount_paid) }}"></label>
        <label>Payment Status
            <select name="payment_status">
                <option value="unpaid" @selected(old('payment_status', $booking->payment_status) === 'unpaid')>Unpaid</option>
                <option value="partial" @selected(old('payment_status', $booking->payment_status) === 'partial')>Partial</option>
                <option value="paid" @selected(old('payment_status', $booking->payment_status) === 'paid')>Paid</option>
            </select>
        </label>
        <label class="span-3 checkbox-label" style="margin-top:0.5rem;">
            <input type="checkbox" name="cancellation_policy_accepted" value="1" @checked(old('cancellation_policy_accepted', $booking->cancellation_policy_accepted))>
            Customer has read and understood the cancellation policy
            @if($booking->cancellation_accepted_at)
                <small class="text-muted">(Accepted {{ $booking->cancellation_accepted_at->format('d/m/Y H:i') }})</small>
            @endif
        </label>
    </div>
    @endif

    <div class="ops-form-footer">
        <a href="{{ route('admin.bookings.index') }}" class="button button-secondary">Cancel</a>
        <button class="button button-primary"><i data-lucide="save"></i>{{ $booking ? 'Update' : 'Create' }}</button>
    </div>
</form>

@if($booking)
{{-- Payments --}}
<section class="ops-panel" style="margin-top:1.5rem;">
    <div class="ops-panel-title"><h2>Payments ({{ $booking->payments->count() }})</h2>
        <button class="button button-sm button-secondary" onclick="document.getElementById('payment-form').classList.toggle('hidden')">Record Payment</button>
        <button class="button button-sm button-secondary" onclick="document.getElementById('link-form').classList.toggle('hidden')">Generate Payment Link</button>
    </div>
    <form id="payment-form" method="POST" action="{{ route('admin.payments.store', $booking) }}" class="hidden" style="margin-bottom:1rem;">
        @csrf
        <div class="ops-form-grid" style="display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:0.75rem;">
            <input type="number" step="0.01" name="amount" placeholder="Amount" required>
            <select name="currency">@foreach(['USD','EUR','GBP','KES'] as $cur)<option value="{{ $cur }}">{{ $cur }}</option>@endforeach</select>
            <select name="method">
                @foreach(\App\Models\Payment::$methods as $k => $v)<option value="{{ $k }}">{{ $v }}</option>@endforeach
            </select>
            <select name="type">
                <option value="payment">Payment</option>
                <option value="deposit">Deposit</option>
                <option value="balance">Balance</option>
            </select>
            <input type="date" name="paid_at" value="{{ date('Y-m-d') }}" required>
            <button class="button button-primary">Record</button>
        </div>
    </form>
    <form id="link-form" method="POST" action="{{ route('admin.payments.links.store', $booking) }}" class="hidden" style="margin-bottom:1rem;">
        @csrf
        <div class="ops-form-grid" style="display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:0.75rem;">
            <select name="type"><option value="payment">Full Payment</option><option value="deposit">Deposit</option><option value="balance">Balance</option></select>
            <input type="number" step="0.01" name="amount" placeholder="Amount" required>
            <select name="currency">@foreach(['USD','EUR','GBP','KES'] as $cur)<option value="{{ $cur }}">{{ $cur }}</option>@endforeach</select>
            <button class="button button-primary">Generate Link</button>
        </div>
    </form>
    @if($booking->payments->count())
    <div class="table-wrap">
        <table class="ops-table">
            <thead><tr><th>Reference</th><th>Amount</th><th>Currency</th><th>Method</th><th>Type</th><th>Status</th><th>Date</th><th></th></tr></thead>
            <tbody>
                @foreach($booking->payments as $p)
                <tr>
                    <td>{{ $p->reference }}</td>
                    <td><strong>{{ number_format($p->amount, 2) }}</strong></td>
                    <td>{{ $p->currency }}</td>
                    <td>{{ \App\Models\Payment::$methods[$p->method] ?? $p->method }}</td>
                    <td>{{ ucfirst($p->type) }}</td>
                    <td><span class="status status--{{ $p->status }}">{{ ucfirst($p->status) }}</span></td>
                    <td>{{ $p->paid_at?->format('d/m/Y') ?? '-' }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.payments.destroy', $p) }}" onsubmit="return confirm('Delete payment?')">@csrf @method('DELETE')<button class="row-action"><i data-lucide="trash-2"></i></button></form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    @if($booking->paymentLinks->count())
    <div style="margin-top:1rem;">
        <h3>Payment Links</h3>
        @foreach($booking->paymentLinks as $pl)
        <div style="display:flex;justify-content:space-between;padding:0.5rem;background:var(--bg-subtle);border-radius:0.375rem;margin-bottom:0.25rem;">
            <span>{{ ucfirst($pl->type) }} - {{ $pl->currency }} {{ number_format($pl->amount,2) }}</span>
            <span>{{ $pl->is_used ? 'Used' : 'Active' }}</span>
            <small><a href="{{ route('admin.payments.links.show', $pl->token) }}" target="_blank">View Link</a></small>
        </div>
        @endforeach
    </div>
    @endif
</section>

{{-- Summary --}}
<section class="ops-panel" style="margin-top:1rem;">
    <div class="ops-panel-title"><h2>Financial Summary</h2></div>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;text-align:center;">
        <div style="padding:1rem;background:var(--bg-subtle);border-radius:0.5rem;">
            <small class="text-muted">Total Amount</small>
            <h3>{{ $booking->currency }} {{ number_format($booking->total_amount, 2) }}</h3>
        </div>
        <div style="padding:1rem;background:var(--bg-subtle);border-radius:0.5rem;">
            <small class="text-muted">Amount Paid</small>
            <h3 class="text-green">{{ $booking->currency }} {{ number_format($booking->amount_paid, 2) }}</h3>
        </div>
        <div style="padding:1rem;background:var(--bg-subtle);border-radius:0.5rem;">
            <small class="text-muted">Balance Due</small>
            <h3 style="color:{{ $booking->balance > 0 ? '#ef4444' : '#22c55e' }}">{{ $booking->currency }} {{ number_format($booking->balance, 2) }}</h3>
        </div>
    </div>
</section>
@endif

@push('styles')
<style>
.checkbox-label { display: flex; align-items: center; gap: 0.5rem; cursor: pointer; }
.checkbox-label input { width: auto; }
.hidden { display: none; }
.row-action { border: none; background: none; cursor: pointer; color: var(--text-muted); padding: 0.25rem; }
.row-action:hover { color: #ef4444; }
.text-green { color: #22c55e; }
</style>
@endpush
@endsection
