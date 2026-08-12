@extends('layouts.admin')
@section('title', 'Travel Request '.$request->request_number)
@section('body_class', 'admin-body--travel-request-workspace')
@section('content')
@php
    $section = request('section', 'proposals');
    $sections = [
        'general' => ['circle-info', 'GENERAL INFO'],
        'proposals' => ['files', 'PROPOSALS'],
        'customer' => ['user-round', 'CUSTOMER INFO'],
        'manual-mails' => ['mail', 'MANUAL MAILS'],
        'automated-mails' => ['send', 'AUTOMATED MAILS'],
        'invoices' => ['receipt', 'INVOICES'],
        'names' => ['contact', 'NAMES AND ADDRESSES'],
        'movements' => ['route', 'DAILY MOVEMENTS'],
        'flights' => ['plane', 'FLIGHT TICKET REQUESTS'],
    ];
    if (!array_key_exists($section, $sections)) $section = 'proposals';
    $proposalLabels = $proposalStatuses;
    $proposalStatus = function ($status) {
        return match ($status) {
            'draft', 'active' => 'planning',
            'sent' => 'quotation_check',
            'accepted' => 'preconfirmed',
            'confirmed' => 'confirmed',
            'in_progress', 'completed' => 'operated',
            'cancelled' => 'cancelled',
            'dodo' => 'dodo',
            default => 'planning',
        };
    };
    $requestJourney = ['new', 'existing', 'preconfirmed', 'confirmed', 'operated', 'dodo'];
    $proposalJourney = ['new', 'planning', 'quotation_check', 'preconfirmed', 'confirmed', 'operated', 'dodo'];
    $allowedJourneyStatus = function (string $current, array $stages): array {
        $index = array_search($current, $stages, true);
        $allowed = $index === false ? [$current] : [$current, $stages[$index + 1] ?? null];
        if ($current !== 'cancelled' && $current !== 'dodo') $allowed[] = 'cancelled';
        return array_filter($allowed);
    };
    $allowedRequestStatuses = $allowedJourneyStatus($workspaceStatus, $requestJourney);
    $clientName = trim($request->client?->name ?: $request->client_name) ?: $request->request_number;
    $traveller = trim($request->traveller_name ?? '') ?: $clientName;
@endphp

<section class="travel-request-workspace">
    <header class="travel-request-header">
        <div class="travel-request-heading">
            <h1>Travel Request ({{ $request->language ?: 'en' }}): {{ $traveller }}@if($traveller !== $clientName) - {{ $clientName }}@endif</h1>
        </div>
        <div class="travel-request-actions">
            @if($request->converted_to_quote_id)
                <a href="{{ route('admin.quotations.pdf', $request->converted_to_quote_id) }}" class="workspace-action"><i data-lucide="download"></i> DOWNLOAD TRAVEL VOUCHER</a>
            @endif
            <a href="mailto:{{ $request->client_email }}" class="workspace-action"><i data-lucide="mail"></i> SEND MULTI PROPOSAL MAIL</a>
        </div>
    </header>

    <div class="travel-request-body">
        <aside class="travel-request-sidebar">
            <nav>
                @foreach($sections as $key => [$icon, $label])
                    <a href="{{ route('admin.requests.show', ['request' => $request->id, 'section' => $key]) }}" class="{{ $section === $key ? 'is-active' : '' }}"><i data-lucide="{{ $icon }}"></i><span>{{ $label }}</span></a>
                @endforeach
            </nav>
            <a class="workspace-back-link" href="{{ route('admin.requests.index') }}"><i data-lucide="arrow-left"></i> BACK TO REQUESTS</a>
        </aside>

        <main class="travel-request-main">
            @include('admin.partials.flash')
            @if($section === 'proposals')
                <section class="workspace-section workspace-proposals">
                    <div class="workspace-table-wrap">
                        <table class="workspace-table">
                            <thead><tr><th>LMS</th><th>Mobile Sale</th><th>Proposal Name</th><th>Status</th><th>Country</th><th>First Day</th><th>Planning Status</th><th>Quotation Check Status</th><th>Pre-Confirmed Quotation Check Status</th><th>Pre-Confirmed At</th><th>Pre-Confirmed By</th><th>Reservation Person</th><th>Created Date</th><th>Updated Date</th><th>Confirmation Date</th><th>Cancellation Date</th><th>Actions</th></tr></thead>
                            <tbody>
                            @forelse($proposals as $proposal)
                                @php $statusKey = $proposalStatus($proposal->status); @endphp
                                @php $allowedProposalStatuses = $allowedJourneyStatus($statusKey, $proposalJourney); @endphp
                                <tr>
                                    <td><span class="workspace-toggle-icon" title="LMS"><i data-lucide="{{ $proposal->is_lms ? 'check' : 'minus' }}"></i></span></td><td><span class="workspace-toggle-icon" title="Mobile Sale"><i data-lucide="{{ $proposal->is_mobile_sale ? 'check' : 'minus' }}"></i></span></td>
                                    <td><a href="{{ route('admin.quotations.show', $proposal->id) }}">{{ $proposal->title }}</a><small>{{ $proposal->reference }}</small></td>
                                    <td><select class="workspace-proposal-status" data-status-url="{{ route('admin.requests.proposals.status', [$request->id, $proposal->id]) }}">@foreach($proposalLabels as $value => $label)<option value="{{ $value }}" @selected($statusKey === $value) @disabled(!in_array($value, $allowedProposalStatuses, true))>{{ $label }}</option>@endforeach</select></td>
                                    <td>{{ $proposal->country ?: $request->country ?: '—' }}</td><td>{{ $proposal->start_date ? \Carbon\Carbon::parse($proposal->start_date)->format('d-m-Y') : '—' }}</td><td><strong>{{ $statusKey === 'planning' ? 'In Planning' : ($proposalLabels[$statusKey] ?? '—') }}</strong><small>By {{ $proposal->seller_name ?: 'NO USER' }}</small></td><td><strong>{{ $proposal->quotation_checked_at ? 'Checked' : 'No status' }}</strong><small>by NO USER</small></td><td><strong>{{ $proposal->leader_checked_at ? 'Pre-confirmation' : 'No status' }}</strong><small>by {{ $proposal->preconfirmed_by_name ?: 'NO USER' }}</small></td><td>{{ $proposal->pre_confirmed_at ? \Carbon\Carbon::parse($proposal->pre_confirmed_at)->format('d-m-Y H:i') : '—' }}</td><td>{{ $proposal->preconfirmed_by_name ?: '—' }}</td><td>{{ $proposal->reservation_person ?: '—' }}</td><td>{{ $proposal->created_at ? \Carbon\Carbon::parse($proposal->created_at)->format('d-m-Y H:i') : '—' }}</td><td>{{ $proposal->updated_at ? \Carbon\Carbon::parse($proposal->updated_at)->format('d-m-Y H:i') : '—' }}</td><td>{{ $proposal->confirmation_date ? \Carbon\Carbon::parse($proposal->confirmation_date)->format('d-m-Y H:i') : '—' }}</td><td>{{ $proposal->cancellation_date ? \Carbon\Carbon::parse($proposal->cancellation_date)->format('d-m-Y H:i') : '—' }}</td>
                                    <td class="workspace-actions-cell"><button type="button" class="proposal-actions-trigger" data-proposal-id="{{ $proposal->id }}" data-quote-url="{{ route('admin.quotations.show', $proposal->id) }}" aria-haspopup="menu" aria-expanded="false"><i data-lucide="more-horizontal"></i></button></td>
                                </tr>
                            @empty
                                <tr><td colspan="17" class="workspace-empty">No proposals linked to this request yet.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="workspace-create-proposal"><form method="POST" action="{{ route('admin.requests.proposals.store', $request->id) }}">@csrf<label>Trip theme<select name="trip_theme" required><option value="">Select trip theme</option>@foreach($tripThemes as $theme)<option value="{{ $theme }}">{{ $theme }}</option>@endforeach</select></label><button class="workspace-primary-button"><i data-lucide="plus"></i> CREATE PROPOSAL</button></form></div>
                </section>
            @elseif($section === 'general')
                <section class="workspace-section"><div class="workspace-section-heading"><div><span>REQUEST DETAILS</span><h2>General Info</h2></div><div class="workspace-general-actions"><div class="workspace-request-status"><label>REQUEST STATUS<div class="workspace-status-control"><select data-request-status data-url="{{ route('admin.requests.workspace-status', $request->id) }}">@foreach($workspaceStatuses as $value => $label)<option value="{{ $value }}" @selected($workspaceStatus === $value) @disabled(!in_array($value, $allowedRequestStatuses, true))>{{ $label }}</option>@endforeach</select><button type="button" data-save-request-status disabled>Save</button></div></label></div><a class="workspace-small-button" href="{{ route('admin.requests.edit', $request->id) }}">EDIT REQUEST</a></div></div><div class="workspace-info-grid">@foreach(['Request number' => $request->request_number, 'Status' => $request->status_label, 'Client name' => $request->client_name, 'Traveller name' => $traveller, 'Email' => $request->client_email, 'Phone' => $request->client_phone, 'Nationality' => $request->nationality, 'Country' => $request->country, 'Company' => $request->company, 'Arrival' => $request->arrival_date?->format('d M Y'), 'Departure' => $request->departure_date?->format('d M Y'), 'Nights' => $request->nights, 'Guests' => ($request->adults ?? 0) + ($request->children ?? 0) + ($request->infants ?? 0), 'Adults' => $request->adults, 'Children' => $request->children, 'Infants' => $request->infants, 'Destination' => $request->destination, 'Accommodation' => $request->accommodation_tier, 'Travel type' => $request->travel_type, 'Budget' => ($request->currency ?: 'USD').' '.number_format($request->budget ?: 0, 2), 'Requirements' => collect(['Flight' => $request->flight_required, 'Pickup' => $request->pickup_required, 'Guide' => $request->guide_required, 'Visa' => $request->visa_required, 'Insurance' => $request->insurance_required])->filter()->keys()->implode(', ')] as $label => $value)<div><span>{{ $label }}</span><strong>{{ $value ?: '—' }}</strong></div>@endforeach</div></section>
            @elseif($section === 'customer')
                <section class="workspace-section"><div class="workspace-section-heading"><div><span>LINKED CUSTOMER</span><h2>Customer Info</h2></div></div><div class="workspace-info-grid">@foreach(['Name' => $request->client?->name ?: $request->client_name, 'Email' => $request->client?->email ?: $request->client_email, 'Phone' => $request->client?->phone ?: $request->client_phone, 'Country' => $request->client?->country ?: $request->country, 'Nationality' => $request->client?->nationality ?: $request->nationality, 'Passport' => $request->client?->passport] as $label => $value)<div><span>{{ $label }}</span><strong>{{ $value ?: '—' }}</strong></div>@endforeach</div></section>
            @elseif($section === 'invoices')
                <section class="workspace-section"><div class="workspace-section-heading"><div><span>FINANCE</span><h2>Invoices</h2></div></div><div class="workspace-table-wrap"><table class="workspace-table workspace-table-simple"><thead><tr><th>Reference</th><th>Date</th><th>Amount</th><th>Method</th></tr></thead><tbody>@forelse($invoices as $invoice)<tr><td>{{ $invoice->reference }}</td><td>{{ $invoice->paid_at }}</td><td>{{ $invoice->currency }} {{ number_format($invoice->amount, 2) }}</td><td>{{ $invoice->method }}</td></tr>@empty<tr><td colspan="4" class="workspace-empty">No invoices or payments linked to this request.</td></tr>@endforelse</tbody></table></div></section>
            @elseif($section === 'movements')
                <section class="workspace-section"><div class="workspace-section-heading"><div><span>ITINERARY OPERATIONS</span><h2>Daily Movements</h2></div></div><div class="workspace-table-wrap"><table class="workspace-table workspace-table-simple"><thead><tr><th>Day</th><th>Date</th><th>From</th><th>To</th><th>Activity / Accommodation</th><th>Notes</th></tr></thead><tbody>@forelse($movements as $movement)<tr><td>{{ $movement->day_number }}</td><td>{{ $movement->travel_date }}</td><td>{{ $movement->from_location ?: '—' }}</td><td>{{ $movement->to_location ?: '—' }}</td><td>{{ $movement->description ?: '—' }}</td><td>—</td></tr>@empty<tr><td colspan="6" class="workspace-empty">No daily movements linked to this request.</td></tr>@endforelse</tbody></table></div></section>
            @elseif($section === 'manual-mails' || $section === 'automated-mails')
                <section class="workspace-section"><div class="workspace-section-heading"><div><span>MESSAGING</span><h2>{{ $section === 'manual-mails' ? 'Manual Mails' : 'Automated Mails' }}</h2></div><a class="workspace-small-button" href="mailto:{{ $request->client_email }}">COMPOSE EMAIL</a></div><div class="workspace-mail-list">@forelse($reservationEmails as $mail)<article><div><strong>{{ $mail->subject }}</strong><small>{{ $mail->recipient }} · {{ $mail->status }}</small></div><span>{{ $mail->sent_at ?: $mail->created_at }}</span></article>@empty<div class="workspace-empty">No emails linked directly to this request.</div>@endforelse</div></section>
            @elseif($section === 'names')
                <section class="workspace-section"><div class="workspace-section-heading"><div><span>TRAVELLERS</span><h2>Names and Addresses</h2></div></div><div class="workspace-empty">No additional traveller or address records linked to this request.</div></section>
            @elseif($section === 'flights')
                <section class="workspace-section"><div class="workspace-section-heading"><div><span>OPERATIONS</span><h2>Flight Ticket Requests</h2></div></div><div class="workspace-table-wrap"><table class="workspace-table workspace-table-simple"><thead><tr><th>Traveller</th><th>Origin</th><th>Destination</th><th>Departure</th><th>Return / Arrival</th><th>Airline</th><th>Booking Status</th><th>Ticket Status</th><th>Assigned User</th></tr></thead><tbody>@forelse($flightRequests as $flight)<tr><td>{{ $flight->passenger_name }}</td><td>{{ $flight->origin_code }}</td><td>{{ $flight->destination_code }}</td><td>{{ $flight->departure_at }}</td><td>{{ $flight->arrival_at }}</td><td>{{ $flight->airline }}</td><td>{{ $flight->booking_status }}</td><td>{{ $flight->ticket_number ?: 'Pending' }}</td><td>—</td></tr>@empty<tr><td colspan="9" class="workspace-empty">No flight ticket requests linked to this request.</td></tr>@endforelse</tbody></table></div></section>
            @endif
        </main>
    </div>
</section>

<div id="proposal-action-menu" class="proposal-action-menu" hidden>
    <button type="button" class="proposal-action-menu-item" data-action="open"><i data-lucide="folder-open"></i><span>Open Proposal</span></button>
    <button type="button" class="proposal-action-menu-item" data-action="price"><i data-lucide="calculator"></i><span>Show Price Overview</span></button>
    <button type="button" class="proposal-action-menu-item" data-action="duplicate"><i data-lucide="copy"></i><span>Duplicate</span></button>
    <button type="button" class="proposal-action-menu-item proposal-action-menu-item--danger" data-action="delete"><i data-lucide="trash-2"></i><span>Delete Proposal</span></button>
</div>

@push('scripts')
<script>
    var csrf = document.querySelector('meta[name="csrf-token"]')?.content;
    var notify = function (message, error) { var node = document.createElement('div'); node.className = 'workspace-toast ' + (error ? 'is-error' : ''); node.textContent = message; document.body.appendChild(node); setTimeout(function () { node.remove(); }, 2600); };
    var requestStatus = document.querySelector('[data-request-status]');
    var saveRequestStatus = document.querySelector('[data-save-request-status]');
    if (requestStatus && saveRequestStatus) {
        requestStatus.dataset.previous = requestStatus.value;
        requestStatus.addEventListener('change', function () { saveRequestStatus.disabled = requestStatus.value === requestStatus.dataset.previous; });
        saveRequestStatus.addEventListener('click', function () {
            var previous = requestStatus.dataset.previous;
            saveRequestStatus.disabled = true;
            saveRequestStatus.textContent = 'Saving...';
            fetch(requestStatus.dataset.url, { method: 'POST', headers: {'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest'}, body: JSON.stringify({status: requestStatus.value}) }).then(function (response) { return response.json().then(function (data) { if (!response.ok) throw new Error(data.message || 'Request status could not be saved.'); return data; }); }).then(function (data) { requestStatus.dataset.previous = requestStatus.value; notify(data.label + ' saved. Refreshing journey...'); window.setTimeout(function () { window.location.reload(); }, 450); }).catch(function (error) { requestStatus.value = previous; notify(error.message, true); saveRequestStatus.disabled = false; }).finally(function () { saveRequestStatus.textContent = 'Save'; });
        });
    }
    document.querySelectorAll('.workspace-proposal-status').forEach(function (select) { select.dataset.previous = select.value; select.addEventListener('change', function () { var previous = select.dataset.previous; select.disabled = true; fetch(select.dataset.statusUrl, { method: 'PATCH', headers: {'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest'}, body: JSON.stringify({status: select.value}) }).then(function (response) { if (!response.ok) throw new Error(); return response.json(); }).then(function (data) { select.dataset.previous = select.value; notify(data.label + ' saved.'); }).catch(function () { select.value = previous; notify('Proposal status could not be saved.', true); }).finally(function () { select.disabled = false; }); }); });
    var requestId = {{ $request->id }};
    var proposalActionMenu = document.getElementById('proposal-action-menu');
    var activeTrigger = null;
    var activeProposalId = null;
    var closeProposalActionMenu = function () {
        if (!proposalActionMenu) return;
        proposalActionMenu.hidden = true;
        if (activeTrigger) activeTrigger.setAttribute('aria-expanded', 'false');
        activeTrigger = null;
        activeProposalId = null;
    };
    var positionProposalActionMenu = function (button) {
        var rect = button.getBoundingClientRect();
        var menuWidth = 220;
        var menuHeight = proposalActionMenu.offsetHeight || 180;
        var left = rect.right - menuWidth;
        var top = rect.bottom + 6;
        if (left < 8) left = 8;
        if (left + menuWidth > window.innerWidth - 8) left = window.innerWidth - menuWidth - 8;
        if (top + menuHeight > window.innerHeight - 8) top = rect.top - menuHeight - 6;
        if (top < 8) top = 8;
        proposalActionMenu.style.left = left + 'px';
        proposalActionMenu.style.top = top + 'px';
    };
    var openProposalActionMenu = function (button) {
        if (!proposalActionMenu) return;
        activeTrigger = button;
        activeProposalId = button.getAttribute('data-proposal-id');
        positionProposalActionMenu(button);
        proposalActionMenu.hidden = false;
        button.setAttribute('aria-expanded', 'true');
    };
    document.addEventListener('click', function (event) {
        var trigger = event.target.closest('.proposal-actions-trigger');
        if (trigger) {
            event.preventDefault();
            event.stopPropagation();
            if (activeTrigger === trigger && !proposalActionMenu.hidden) { closeProposalActionMenu(); }
            else { closeProposalActionMenu(); openProposalActionMenu(trigger); }
            return;
        }
        if (!proposalActionMenu.hidden && !proposalActionMenu.contains(event.target)) { closeProposalActionMenu(); }
    });
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && !proposalActionMenu.hidden) { closeProposalActionMenu(); }
    });
    ['scroll', 'resize'].forEach(function (evt) {
        window.addEventListener(evt, function () { if (!proposalActionMenu.hidden) { closeProposalActionMenu(); } }, true);
    });
    proposalActionMenu.addEventListener('click', function (event) {
        var item = event.target.closest('.proposal-action-menu-item');
        if (!item) return;
        var action = item.getAttribute('data-action');
        var quoteUrl = activeTrigger ? activeTrigger.getAttribute('data-quote-url') : null;
        var proposalId = activeProposalId;
        closeProposalActionMenu();
        if (!proposalId) return;
        if (action === 'open') { if (quoteUrl) window.location.href = quoteUrl; return; }
        if (action === 'price') { if (quoteUrl) window.location.href = quoteUrl.split('?')[0] + '?tab=overview'; return; }
        if (action === 'duplicate' || action === 'delete') {
            if (action === 'delete' && !window.confirm('Delete this proposal?')) return;
            item.disabled = true;
            fetch('/admin/requests/' + requestId + '/proposals/' + proposalId + (action === 'duplicate' ? '/duplicate' : ''), { method: action === 'delete' ? 'DELETE' : 'POST', headers: {'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest'} }).then(function (response) { return response.json().then(function (data) { if (!response.ok) throw new Error(data.message || 'Action could not be completed.'); return data; }); }).then(function (data) { notify(data.message || 'Done.'); window.setTimeout(function () { window.location.reload(); }, 600); }).catch(function (error) { item.disabled = false; notify(error.message, true); });
        }
    });
    document.querySelector('.workspace-create-proposal form')?.addEventListener('submit', function () { var button = this.querySelector('button[type="submit"]'); if (button) { button.disabled = true; button.textContent = 'CREATING...'; } });
</script>
@endpush
@endsection
