@extends('layouts.admin')
@section('title', 'Duplicate Invoice Detection')
@section('content')
<div class="ops-page-heading">
    <div><p class="eyebrow">Validation</p><h1>Duplicate Invoice Detection</h1><p>System-wide search for duplicate invoice numbers within the same proposal.</p></div>
    <div class="heading-actions"><a class="button button-secondary" href="{{ route('admin.evaluations.overview') }}"><i data-lucide="arrow-left"></i>Dashboard</a></div>
</div>
@include('admin.partials.flash')

<section class="ops-panel">
    <div class="ops-panel-title"><h2>Potential Duplicates</h2></div>
    @forelse($duplicates as $dup)
    <div class="ops-panel" style="margin-top:0.5rem;border-left:4px solid var(--danger)">
        <div style="display:flex;justify-content:space-between;align-items:center">
            <div>
                <strong>{{ $dup->invoice_number }}</strong> — {{ $dup->company_name }}
                <small style="display:block;color:var(--muted)">Duplicate: {{ $dup->duplicate_company }} (Invoice #{{ $dup->duplicate_id }})</small>
            </div>
            <span class="ops-pill ops-pill--red">DUPLICATE</span>
        </div>
    </div>
    @empty
    <div class="empty-cell" style="padding:2rem;text-align:center">
        <i data-lucide="check-circle-2" style="width:2rem;height:2rem;color:var(--success)"></i>
        <p><strong>No duplicate invoices found.</strong></p>
    </div>
    @endforelse
</section>
@endsection
