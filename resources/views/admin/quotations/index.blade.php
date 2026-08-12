@extends('layouts.admin')
@section('title', 'Quotations')
@section('content')
<x-admin.top-bar
    title="Quotations"
    description="Proposals"
    addLabel="New Quotation"
    addRoute="{{ route('admin.quotations.create') }}"
    searchPlaceholder="Search quotations..."
/>
@include('admin.partials.flash')
<section class="ops-panel">
    <form class="ops-filters" method="GET"><label class="ops-search"><i data-lucide="search"></i><input name="search" value="{{ request('search') }}" placeholder="Reference, client or safari"></label><select name="status"><option value="">All statuses</option>@foreach(['draft','active','sent','accepted','confirmed','in_progress','completed','cancelled'] as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ ucwords(str_replace('_', ' ', $status)) }}</option>@endforeach</select><button class="button button-primary">Filter</button></form>
    <div class="table-wrap"><table class="ops-table"><thead><tr><th>Quotation</th><th>Client</th><th>Trip</th><th>Buy-in</th><th>Selling</th><th>Margin</th><th>Status</th><th>Actions</th></tr></thead><tbody>
    @forelse($quotations as $quote)<tr><td><strong>{{ $quote->reference }}</strong><small>{{ $quote->title }}</small></td><td>{{ $quote->client_name }}</td><td>{{ $quote->duration_days }} days · {{ $quote->guest_count }} guests<small>{{ \Carbon\Carbon::parse($quote->start_date)->format('d M Y') }}</small></td><td><span class="buy-price">{{ $quote->currency }} {{ number_format($quote->buy_total, 2) }}</span></td><td><strong>{{ $quote->currency }} {{ number_format($quote->sell_total, 2) }}</strong></td><td><span class="ops-pill {{ $quote->margin_total >= 0 ? 'ops-pill--green' : 'ops-pill--red' }}">{{ $quote->currency }} {{ number_format($quote->margin_total, 2) }}</span></td><td><span class="ops-pill ops-pill--blue">{{ ucwords(str_replace('_', ' ', $quote->status)) }}</span></td><td><div class="ops-actions"><a href="{{ route('admin.quotations.show', $quote->id) }}"><i data-lucide="square-pen"></i></a><form method="POST" action="{{ route('admin.quotations.destroy', $quote->id) }}" onsubmit="return confirm('Delete quotation?')">@csrf @method('DELETE')<button><i data-lucide="trash-2"></i></button></form></div></td></tr>@empty<tr><td colspan="8" class="empty-cell">No quotations found.</td></tr>@endforelse
    </tbody></table></div><div class="ops-pagination">{{ $quotations->links() }}</div>
</section>
@endsection
