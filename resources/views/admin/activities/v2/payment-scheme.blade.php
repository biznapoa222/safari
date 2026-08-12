@extends('layouts.admin')
@section('title', 'Payment Scheme - '.$activity->name)
@section('content')
<div class="page-heading">
    <div>
        <p class="eyebrow">Activity Payment Scheme</p>
        <h1>{{ $activity->name }}</h1>
    </div>
    <div class="heading-actions">
        <a href="{{ route('admin.activities.edit', $activity) }}" class="button button-secondary">Back to Activity</a>
    </div>
</div>

@include('admin.partials.flash')

<form method="POST" action="{{ route('admin.activities.payment-scheme.update', $activity) }}" class="ops-panel">
    @csrf @method('PUT')
    <div class="ops-panel-title"><h2>Payment Scheme Settings</h2></div>
    <div class="ops-form-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;max-width:600px;">
        <label>Deposit Percentage (%)<input type="number" step="0.01" min="0" max="100" name="deposit_percent" value="{{ old('deposit_percent', $scheme->deposit_percent ?? 50) }}" required></label>
        <label class="span-2">Full Payment Rules<textarea name="full_payment_rules" rows="3">{{ old('full_payment_rules', $scheme->full_payment_rules ?? '') }}</textarea></label>
        <label class="span-2">Cancellation Rules<textarea name="cancellation_rules" rows="4">{{ old('cancellation_rules', $scheme->cancellation_rules ?? '') }}</textarea></label>
        <div class="span-2"><button class="button button-primary">Save Payment Scheme</button></div>
    </div>
</form>
@endsection
