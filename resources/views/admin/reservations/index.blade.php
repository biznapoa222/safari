@extends('layouts.admin')
@section('title', 'Reservations')
@section('content')
<x-admin.top-bar
    title="Reservations"
    description="Operations"
    addLabel="New Reservation"
    addRoute="{{ route('admin.reservations.store') }}"
    searchPlaceholder="Search reservations..."
/>
<div class="ops-actions-bar"><a class="button button-secondary" href="{{ route('admin.evaluations.invoices') }}"><i data-lucide="upload-cloud"></i>Supplier invoices</a><a class="button button-secondary" href="{{ route('admin.evaluations.index') }}"><i data-lucide="clipboard-check"></i>Evaluations</a></div>
@include('admin.partials.flash')
<section class="ops-panel">
<form class="ops-filters" method="GET"><select name="status"><option value="">All statuses</option>@foreach(['pending','requested','confirmed','rejected','cancelled','completed'] as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>@endforeach</select><button class="button button-primary">Filter</button></form>
<div class="table-wrap"><table class="ops-table"><thead><tr><th>Client / quotation</th><th>Reservation</th><th>Schedule</th><th>Assigned</th><th>Actual cost</th><th>Paid</th><th>Deadline</th><th>Status</th><th></th></tr></thead><tbody>
@forelse($reservations as $reservation)<tr><td><strong>{{ $reservation->client_name }}</strong><small><a href="{{ route('admin.quotations.show', $reservation->quotation_id) }}">{{ $reservation->quotation_reference }} · {{ $reservation->quotation_title }}</a></small></td><td>{{ ucfirst($reservation->reservation_type) }}<small>{{ $reservation->supplier }} · Qty {{ $reservation->quantity }}</small></td><td>{{ \Carbon\Carbon::parse($reservation->starts_at)->format('d M Y H:i') }}<small>{{ \Carbon\Carbon::parse($reservation->ends_at)->format('d M Y H:i') }}</small></td><td>{{ $reservation->assigned_person ?: 'Unassigned' }}<small>{{ $reservation->number_plate }}</small></td><td>{{ number_format($reservation->actual_cost, 2) }}</td><td>{{ number_format($reservation->paid_amount, 2) }}</td><td>{{ $reservation->payment_deadline ? \Carbon\Carbon::parse($reservation->payment_deadline)->format('d M Y') : '—' }}</td><td><span class="ops-pill {{ $reservation->status === 'confirmed' ? 'ops-pill--green' : 'ops-pill--blue' }}">{{ ucfirst($reservation->status) }}</span></td><td><a class="ops-icon-link" href="{{ route('admin.quotations.show', $reservation->quotation_id).'#reservations' }}"><i data-lucide="square-pen"></i></a></td></tr>@empty<tr><td colspan="9" class="empty-cell">No reservations match this filter.</td></tr>@endforelse
</tbody></table></div><div class="ops-pagination">{{ $reservations->links() }}</div>
</section>
@endsection
