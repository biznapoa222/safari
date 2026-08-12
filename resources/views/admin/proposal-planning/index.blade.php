@extends('layouts.admin')
@section('title', 'Proposals Planning')
@section('content')
@php
    $base = request()->except(['page', 'stage', 'trip', 'step']);
    $stageUrl = fn($value) => route('admin.proposal-planning.index', [...$base, 'stage' => $value]);
    $tripUrl = fn($value) => route('admin.proposal-planning.index', [...$base, 'stage' => 'confirmed', 'trip' => $value]);
    $stepUrl = fn($value) => route('admin.proposal-planning.index', [...$base, 'stage' => 'planning', 'step' => $value]);
@endphp

<section class="planning-board">
    <header class="planning-header">
        <div class="planning-title"><i data-lucide="calendar-range"></i><strong>Proposals Planning</strong><span>Live workflow</span></div>
        <nav class="country-tabs" aria-label="Country">
            @foreach(['' => 'All countries', 'Tanzania' => 'Tanzania', 'Kenya' => 'Kenya', 'Uganda' => 'Uganda', 'South Africa' => 'South Africa'] as $value => $label)
                <a class="{{ request('country', '') === $value ? 'is-active' : '' }}" href="{{ route('admin.proposal-planning.index', [...request()->except(['page', 'country']), 'country' => $value]) }}">{{ $label }}</a>
            @endforeach
        </nav>
        <nav class="stage-tabs" aria-label="Proposal stage">
            <a class="{{ $stage === 'planning' ? 'is-active' : '' }}" href="{{ $stageUrl('planning') }}">Planning</a>
            <a class="{{ $stage === 'pre-confirmed' ? 'is-active' : '' }}" href="{{ $stageUrl('pre-confirmed') }}">Pre-confirmed</a>
            <a class="{{ $stage === 'confirmed' ? 'is-active' : '' }}" href="{{ $stageUrl('confirmed') }}">Confirmed</a>
        </nav>
        @if($stage === 'confirmed')
        <nav class="workflow-tabs" aria-label="Trip stage">
            <a class="{{ $tripTab === 'upcoming' ? 'is-active' : '' }}" href="{{ $tripUrl('upcoming') }}">Upcoming trips</a>
            <a class="{{ $tripTab === 'in-operation' ? 'is-active' : '' }}" href="{{ $tripUrl('in-operation') }}">Trips in operation</a>
            <a class="{{ $tripTab === 'operated' ? 'is-active' : '' }}" href="{{ $tripUrl('operated') }}">Operated trips</a>
            <a class="{{ $tripTab === 'evaluated' ? 'is-active' : '' }}" href="{{ $tripUrl('evaluated') }}">Evaluated trips</a>
        </nav>
        @elseif($stage === 'planning')
        <nav class="workflow-tabs" aria-label="Planning step">
            @foreach(['all' => 'All planning', 'in-planning' => 'In planning by seller', 'quotation-check' => 'Quotation check', 'team-leader-check' => 'Quotation & team leader check', 'done' => 'Done'] as $value => $label)
                <a class="{{ $planningStep === $value ? 'is-active' : '' }}" href="{{ $stepUrl($value) }}">{{ $label }}</a>
            @endforeach
        </nav>
        @endif
    </header>

    @include('admin.partials.flash')

    <form method="GET" class="planning-filters">
        <input type="hidden" name="stage" value="{{ $stage }}">
        @if($stage === 'confirmed')<input type="hidden" name="trip" value="{{ $tripTab }}">@endif
        @if($stage === 'planning')<input type="hidden" name="step" value="{{ $planningStep }}">@endif
        <label><i data-lucide="search"></i><input type="search" name="search" value="{{ request('search') }}" placeholder="Search by request ID, request name or proposal name"></label>
        <label><i data-lucide="user-round"></i><select name="seller"><option value="">Filter by seller</option>@foreach($sellers as $seller)<option value="{{ $seller->id }}" @selected((string) request('seller') === (string) $seller->id)>{{ $seller->name }}</option>@endforeach</select></label>
        <label><i data-lucide="layers-3"></i><select name="type"><option value="">Itinerary, Custom, Manual, Group</option>@foreach(['Itinerary','Custom','Manual','Group'] as $type)<option @selected(request('type') === $type)>{{ $type }}</option>@endforeach</select></label>
        <input type="hidden" name="country" value="{{ request('country') }}">
        <button class="planning-filter-button"><i data-lucide="sliders-horizontal"></i>Filter</button>
    </form>

    <div class="planning-tools">
        <div><strong>Legend:</strong><span class="legend legend--none">No payments created</span><span class="legend legend--partial">Not all payments paid</span><span class="legend legend--paid">All payments paid</span></div>
        <a class="export-button" href="{{ route('admin.proposal-planning.export', request()->query()) }}"><i data-lucide="download"></i>Export Excel / CSV</a>
    </div>

    <div class="planning-table-wrap">
        <table class="planning-table">
            <thead>
                <tr>
                    <th>Request</th><th>Proposal name</th><th>Seller</th><th>First day</th><th>Last day</th>
                    <th>Reservations person</th><th>Reservations evaluation</th><th>Itinerary</th>
                    <th>Mobile proposal?</th><th>Jeeps planned?</th><th>Daily movements</th><th>Pre departure</th>
                    <th>Type</th><th>Planning note</th><th>Workflow</th>
                </tr>
            </thead>
            <tbody>
            @forelse($records as $record)
                <tr class="payment-row payment-row--{{ $record->payment_state }}">
                    <td><a href="{{ route('admin.quotations.show', $record->id) }}">{{ $record->reference }}</a><small>@if($record->client_token)<a class="client-preview-link" href="{{ route('proposal.client', $record->client_token) }}" target="_blank" title="Open client proposal">{{ $record->client_name }} ↗</a>@else{{ $record->client_name }}@endif</small></td>
                    <td><a href="{{ route('admin.quotations.show', $record->id) }}">Open: {{ $record->title }}</a><small>{{ $record->duration_days }} days · {{ $record->guest_count }} guests</small></td>
                    <td><span class="seller-dot" style="--dot-hue:{{ ($record->seller_id ?? 1) * 47 % 360 }}"></span>{{ $record->seller_name }}</td>
                    <td>{{ \Carbon\Carbon::parse($record->start_date)->format('d-m-Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($record->end_date)->format('d-m-Y') }}</td>
                    <td>{{ $record->reservation_person }}<small>{{ $record->confirmed_reservations }}/{{ $record->reservation_count }} confirmed</small></td>
                    <td><span class="state-pill state-pill--{{ $record->reservation_state === 'Confirmed' ? 'green' : ($record->reservation_state === 'New' ? 'yellow' : 'blue') }}">{{ $record->reservation_state }}</span></td>
                    <td><span class="bool-pill {{ $record->days_complete ? 'is-yes' : '' }}">{{ $record->days_complete ? '✓' : 'No' }}</span></td>
                    <td><span class="bool-pill">No</span></td>
                    @foreach(['jeeps_planned_at','daily_movements_checked_at','pre_departure_checked_at'] as $field)
                    <td>
                        <form method="POST" action="{{ route('admin.proposal-planning.toggle', $record->id) }}">@csrf<input type="hidden" name="field" value="{{ $field }}"><button class="bool-pill {{ $record->{$field} ? 'is-yes' : 'is-no' }}" title="Toggle checklist">{{ $record->{$field} ? '✓' : '⊘' }}</button></form>
                    </td>
                    @endforeach
                    <td>{{ $record->proposal_type }}</td>
                    <td>
                        <details class="planning-note"><summary>{{ $record->planning_note ?: 'ADD NOTE' }}</summary><form method="POST" action="{{ route('admin.proposal-planning.note', $record->id) }}">@csrf @method('PUT')<textarea name="planning_note" rows="3" placeholder="Planning note">{{ $record->planning_note }}</textarea><input name="whatsapp_status" value="{{ $record->whatsapp_status }}" placeholder="WhatsApp status"><button>Save note</button></form></details>
                    </td>
                    <td>
                        @if($record->stage !== 'confirmed')
                        <form method="POST" action="{{ route('admin.proposal-planning.advance', $record->id) }}">@csrf<button class="advance-button">Advance <i data-lucide="arrow-right"></i></button></form>
                        @elseif($record->trip_stage === 'evaluated')
                        <a class="advance-button" href="{{ route('admin.evaluations.show', $record->id) }}">Evaluation <i data-lucide="arrow-up-right"></i></a>
                        @else
                        <a class="advance-button" href="{{ route('admin.quotations.show', $record->id) }}">Open <i data-lucide="arrow-up-right"></i></a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="15" class="planning-empty"><i data-lucide="calendar-check"></i><strong>No trips at this stage</strong><span>Trips move here automatically as their dates and workflow change.</span></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="planning-pagination">{{ $records->links() }}</div>
</section>
@endsection

@push('styles')
<style>
.page-content:has(.planning-board){padding:0 0 35px;background:#fff}.planning-board{min-height:calc(100vh - 76px);color:#30362f;font-size:10px}.planning-header{color:#fff;background:#506820}.planning-title{height:42px;padding:0 16px;display:flex;align-items:center;gap:9px;border-bottom:7px solid #fff}.planning-title svg{width:15px}.planning-title strong{font-size:13px}.planning-title span{margin-left:auto;padding:4px 8px;color:#dce8bd;background:rgba(255,255,255,.1);border-radius:10px;font-size:8px;text-transform:uppercase;letter-spacing:.7px}.country-tabs,.stage-tabs,.workflow-tabs{height:36px;padding:0 18px;display:flex;align-items:stretch;gap:8px}.country-tabs a,.stage-tabs a,.workflow-tabs a{padding:0 10px;display:flex;align-items:center;color:#afbd91;font-size:9px;font-weight:800;text-transform:uppercase;letter-spacing:.2px;border-bottom:1px solid transparent}.country-tabs a:hover,.stage-tabs a:hover,.workflow-tabs a:hover,.country-tabs a.is-active,.stage-tabs a.is-active,.workflow-tabs a.is-active{color:#fff;border-color:#fff}.country-tabs{background:#4b631c}.stage-tabs{border-top:1px solid rgba(255,255,255,.05)}.workflow-tabs{height:38px;background:#4a611e}.planning-filters{padding:13px 16px 5px;display:grid;grid-template-columns:1.4fr 1fr 1fr auto;gap:9px}.planning-filters label{height:35px;display:flex;align-items:center;border-bottom:1px solid #abb2ac}.planning-filters label svg{width:14px;color:#7d867f}.planning-filters input,.planning-filters select{width:100%;height:100%;padding:0 8px;border:0;outline:0;background:transparent;color:#495149;font-size:9px}.planning-filter-button,.export-button{height:32px;padding:0 12px;display:inline-flex;align-items:center;gap:6px;border:1px solid #dfe3df;border-radius:4px;background:#fff;color:#485148;font-size:8px;font-weight:800;text-transform:uppercase;cursor:pointer;box-shadow:0 2px 5px rgba(21,38,27,.07)}.planning-filter-button svg,.export-button svg{width:13px}.planning-tools{padding:15px 18px 12px;display:flex;align-items:flex-end;justify-content:space-between;gap:12px}.planning-tools>div{display:flex;align-items:center;gap:7px;flex-wrap:wrap}.planning-tools strong{width:100%;font-size:9px}.legend{padding:5px 9px;border-radius:12px;font-size:8px}.legend--none{background:#ffefa1}.legend--partial{background:#efa0a0}.legend--paid{background:#a8d9ad}.planning-table-wrap{width:100%;overflow:auto;border-top:1px solid #d9ded9}.planning-table{width:100%;min-width:1550px;border-collapse:collapse;font-size:8px}.planning-table th{height:41px;padding:7px 10px;color:#586159;background:#fff;text-align:left;font-size:7px;vertical-align:bottom;white-space:nowrap}.planning-table td{height:47px;padding:6px 10px;border-top:1px solid rgba(82,100,83,.16);vertical-align:middle}.planning-table td>a{color:#2584b5;text-decoration:underline}.planning-table small{margin-top:3px;display:block;color:#526059;font-size:7px}.payment-row--none{background:#fff1a8}.payment-row--partial{background:#ee999d}.payment-row--paid{background:#a7d8ac}.seller-dot{width:14px;height:20px;margin-right:5px;display:inline-block;vertical-align:middle;background:hsl(var(--dot-hue),80%,52%);border-radius:10px}.state-pill,.bool-pill{min-width:27px;padding:5px 8px;display:inline-flex;align-items:center;justify-content:center;color:#fff;background:#a2a5a2;border:0;border-radius:12px;font-size:8px;white-space:nowrap}.state-pill--green,.bool-pill.is-yes{background:#44aa58}.state-pill--yellow{color:#574f24;background:#ffe135}.state-pill--blue{background:#2486d1}.bool-pill.is-no{background:#ff4742}.planning-table form{margin:0}.planning-table button.bool-pill{cursor:pointer}.planning-note{position:relative}.planning-note summary{max-width:115px;padding:5px 9px;overflow:hidden;color:#fff;background:#a2a5a2;border-radius:12px;font-size:7px;font-weight:800;white-space:nowrap;text-overflow:ellipsis;cursor:pointer;list-style:none}.planning-note[open] form{position:absolute;z-index:15;right:0;top:27px;width:230px;padding:10px;display:grid;gap:7px;background:#fff;border:1px solid #d8ded9;border-radius:7px;box-shadow:0 14px 35px rgba(25,46,31,.2)}.planning-note textarea,.planning-note input{width:100%;padding:7px;border:1px solid #d8ded9;border-radius:4px;font-size:9px}.planning-note button,.advance-button{min-height:27px;padding:0 9px;display:inline-flex;align-items:center;justify-content:center;gap:4px;color:#fff;background:#536c24;border:0;border-radius:4px;font-size:7px;font-weight:800;cursor:pointer}.advance-button svg{width:11px}.planning-empty{height:190px!important;text-align:center}.planning-empty svg,.planning-empty strong,.planning-empty span{margin:5px auto;display:block}.planning-empty svg{width:30px;color:#617a33}.planning-empty strong{font-size:13px}.planning-empty span{color:#7b887f}.planning-pagination{padding:15px 18px}.planning-board .alert{margin:12px 18px 0}
.client-preview-link{color:#176f9d!important;text-decoration:underline}.client-preview-link:hover{color:#0d4f72!important}
@media(max-width:900px){.planning-filters{grid-template-columns:1fr}.planning-tools{align-items:flex-start;flex-direction:column}.country-tabs,.stage-tabs,.workflow-tabs{overflow-x:auto}.country-tabs a,.stage-tabs a,.workflow-tabs a{flex:0 0 auto}.planning-title span{display:none}}
</style>
@endpush
