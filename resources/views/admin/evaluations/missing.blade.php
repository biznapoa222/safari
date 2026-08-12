@extends('layouts.admin')
@section('title', 'Missing Invoices — ' . $record->reference)
@section('content')
<div class="proposal-toolbar">
    <div><span>{{ $record->reference }}</span><strong>Missing invoices</strong><small>{{ $record->client_name }}</small></div>
    <div><a href="{{ route('admin.evaluations.show', $record->id) }}"><i data-lucide="arrow-left"></i>Back to evaluation</a></div>
</div>
@include('admin.partials.flash')

<div class="ops-panel">
    <div class="ops-panel-title"><h2>Missing Invoice Engine</h2><p>Automatically detected itinerary items without assigned invoices.</p></div>

    @foreach(['accommodation', 'activities', 'transport', 'jeep', 'guide', 'supplements', 'park_fees', 'misc'] as $group)
        @if($missing[$group]['total'] > 0)
        <details class="ops-panel" style="margin-top:0.5rem" open>
            <summary class="ops-panel-title" style="cursor:pointer">
                <div><h3>{{ ucfirst($group) }} <span class="ops-pill ops-pill--red">{{ $missing[$group]['total'] }} missing</span></h3></div>
                <i data-lucide="chevron-down"></i>
            </summary>
            <div class="table-wrap">
                <table class="ops-table">
                    <thead><tr><th>Item</th><th>Type</th><th>Supplier</th><th>Date</th><th>System Rate</th></tr></thead>
                    <tbody>
                    @foreach($missing[$group]['items'] as $item)
                    <tr>
                        <td><strong>{{ $item->title }}</strong></td>
                        <td><span class="item-type item-type--{{ $item->item_type }}">{{ $item->item_type }}</span></td>
                        <td>{{ $item->supplier ?: 'N/A' }}</td>
                        <td>{{ $item->service_date ? \Carbon\Carbon::parse($item->service_date)->format('d M Y') : '-' }}</td>
                        <td>${{ number_format($item->system_rate, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody></table>
            </div>
        </details>
        @endif
    @endforeach

    @if(collect($missing)->sum('total') === 0)
    <div class="empty-cell" style="padding:2rem;text-align:center">
        <i data-lucide="check-circle-2" style="width:2rem;height:2rem;color:var(--success);margin-bottom:0.5rem"></i>
        <p><strong>All invoices have been assigned.</strong></p>
        <p>No missing invoices detected for this proposal.</p>
    </div>
    @endif
</div>
@endsection
