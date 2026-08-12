<?php $__env->startSection('title', 'Travel Request '.$request->request_number); ?>
<?php $__env->startSection('body_class', 'admin-body--travel-request-workspace'); ?>
<?php $__env->startSection('content'); ?>
<?php
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
?>

<section class="travel-request-workspace">
    <header class="travel-request-header">
        <div class="travel-request-heading">
            <h1>Travel Request (<?php echo e($request->language ?: 'en'); ?>): <?php echo e($traveller); ?><?php if($traveller !== $clientName): ?> - <?php echo e($clientName); ?><?php endif; ?></h1>
        </div>
        <div class="travel-request-actions">
            <?php if($request->converted_to_quote_id): ?>
                <a href="<?php echo e(route('admin.quotations.pdf', $request->converted_to_quote_id)); ?>" class="workspace-action"><i data-lucide="download"></i> DOWNLOAD TRAVEL VOUCHER</a>
            <?php endif; ?>
            <a href="mailto:<?php echo e($request->client_email); ?>" class="workspace-action"><i data-lucide="mail"></i> SEND MULTI PROPOSAL MAIL</a>
        </div>
    </header>

    <div class="travel-request-body">
        <aside class="travel-request-sidebar">
            <nav>
                <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => [$icon, $label]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('admin.requests.show', ['request' => $request->id, 'section' => $key])); ?>" class="<?php echo e($section === $key ? 'is-active' : ''); ?>"><i data-lucide="<?php echo e($icon); ?>"></i><span><?php echo e($label); ?></span></a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </nav>
            <a class="workspace-back-link" href="<?php echo e(route('admin.requests.index')); ?>"><i data-lucide="arrow-left"></i> BACK TO REQUESTS</a>
        </aside>

        <main class="travel-request-main">
            <?php echo $__env->make('admin.partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php if($section === 'proposals'): ?>
                <section class="workspace-section workspace-proposals">
                    <div class="workspace-table-wrap">
                        <table class="workspace-table">
                            <thead><tr><th>LMS</th><th>Mobile Sale</th><th>Proposal Name</th><th>Status</th><th>Country</th><th>First Day</th><th>Planning Status</th><th>Quotation Check Status</th><th>Pre-Confirmed Quotation Check Status</th><th>Pre-Confirmed At</th><th>Pre-Confirmed By</th><th>Reservation Person</th><th>Created Date</th><th>Updated Date</th><th>Confirmation Date</th><th>Cancellation Date</th><th>Actions</th></tr></thead>
                            <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $proposals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proposal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php $statusKey = $proposalStatus($proposal->status); ?>
                                <?php $allowedProposalStatuses = $allowedJourneyStatus($statusKey, $proposalJourney); ?>
                                <tr>
                                    <td><span class="workspace-toggle-icon" title="LMS"><i data-lucide="<?php echo e($proposal->is_lms ? 'check' : 'minus'); ?>"></i></span></td><td><span class="workspace-toggle-icon" title="Mobile Sale"><i data-lucide="<?php echo e($proposal->is_mobile_sale ? 'check' : 'minus'); ?>"></i></span></td>
                                    <td><a href="<?php echo e(route('admin.quotations.show', $proposal->id)); ?>"><?php echo e($proposal->title); ?></a><small><?php echo e($proposal->reference); ?></small></td>
                                    <td><select class="workspace-proposal-status" data-status-url="<?php echo e(route('admin.requests.proposals.status', [$request->id, $proposal->id])); ?>"><?php $__currentLoopData = $proposalLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($value); ?>" <?php if($statusKey === $value): echo 'selected'; endif; ?> <?php if(!in_array($value, $allowedProposalStatuses, true)): echo 'disabled'; endif; ?>><?php echo e($label); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></td>
                                    <td><?php echo e($proposal->country ?: $request->country ?: '—'); ?></td><td><?php echo e($proposal->start_date ? \Carbon\Carbon::parse($proposal->start_date)->format('d-m-Y') : '—'); ?></td><td><strong><?php echo e($statusKey === 'planning' ? 'In Planning' : ($proposalLabels[$statusKey] ?? '—')); ?></strong><small>By <?php echo e($proposal->seller_name ?: 'NO USER'); ?></small></td><td><strong><?php echo e($proposal->quotation_checked_at ? 'Checked' : 'No status'); ?></strong><small>by NO USER</small></td><td><strong><?php echo e($proposal->leader_checked_at ? 'Pre-confirmation' : 'No status'); ?></strong><small>by <?php echo e($proposal->preconfirmed_by_name ?: 'NO USER'); ?></small></td><td><?php echo e($proposal->pre_confirmed_at ? \Carbon\Carbon::parse($proposal->pre_confirmed_at)->format('d-m-Y H:i') : '—'); ?></td><td><?php echo e($proposal->preconfirmed_by_name ?: '—'); ?></td><td><?php echo e($proposal->reservation_person ?: '—'); ?></td><td><?php echo e($proposal->created_at ? \Carbon\Carbon::parse($proposal->created_at)->format('d-m-Y H:i') : '—'); ?></td><td><?php echo e($proposal->updated_at ? \Carbon\Carbon::parse($proposal->updated_at)->format('d-m-Y H:i') : '—'); ?></td><td><?php echo e($proposal->confirmation_date ? \Carbon\Carbon::parse($proposal->confirmation_date)->format('d-m-Y H:i') : '—'); ?></td><td><?php echo e($proposal->cancellation_date ? \Carbon\Carbon::parse($proposal->cancellation_date)->format('d-m-Y H:i') : '—'); ?></td>
                                    <td class="workspace-actions-cell"><button type="button" class="proposal-actions-trigger" data-proposal-id="<?php echo e($proposal->id); ?>" data-quote-url="<?php echo e(route('admin.quotations.show', $proposal->id)); ?>" aria-haspopup="menu" aria-expanded="false"><i data-lucide="more-horizontal"></i></button></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td colspan="17" class="workspace-empty">No proposals linked to this request yet.</td></tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="workspace-create-proposal"><form method="POST" action="<?php echo e(route('admin.requests.proposals.store', $request->id)); ?>"><?php echo csrf_field(); ?><label>Trip theme<select name="trip_theme" required><option value="">Select trip theme</option><?php $__currentLoopData = $tripThemes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $theme): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($theme); ?>"><?php echo e($theme); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></label><button class="workspace-primary-button"><i data-lucide="plus"></i> CREATE PROPOSAL</button></form></div>
                </section>
            <?php elseif($section === 'general'): ?>
                <section class="workspace-section"><div class="workspace-section-heading"><div><span>REQUEST DETAILS</span><h2>General Info</h2></div><div class="workspace-general-actions"><div class="workspace-request-status"><label>REQUEST STATUS<div class="workspace-status-control"><select data-request-status data-url="<?php echo e(route('admin.requests.workspace-status', $request->id)); ?>"><?php $__currentLoopData = $workspaceStatuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($value); ?>" <?php if($workspaceStatus === $value): echo 'selected'; endif; ?> <?php if(!in_array($value, $allowedRequestStatuses, true)): echo 'disabled'; endif; ?>><?php echo e($label); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select><button type="button" data-save-request-status disabled>Save</button></div></label></div><a class="workspace-small-button" href="<?php echo e(route('admin.requests.edit', $request->id)); ?>">EDIT REQUEST</a></div></div><div class="workspace-info-grid"><?php $__currentLoopData = ['Request number' => $request->request_number, 'Status' => $request->status_label, 'Client name' => $request->client_name, 'Traveller name' => $traveller, 'Email' => $request->client_email, 'Phone' => $request->client_phone, 'Nationality' => $request->nationality, 'Country' => $request->country, 'Company' => $request->company, 'Arrival' => $request->arrival_date?->format('d M Y'), 'Departure' => $request->departure_date?->format('d M Y'), 'Nights' => $request->nights, 'Guests' => ($request->adults ?? 0) + ($request->children ?? 0) + ($request->infants ?? 0), 'Adults' => $request->adults, 'Children' => $request->children, 'Infants' => $request->infants, 'Destination' => $request->destination, 'Accommodation' => $request->accommodation_tier, 'Travel type' => $request->travel_type, 'Budget' => ($request->currency ?: 'USD').' '.number_format($request->budget ?: 0, 2), 'Requirements' => collect(['Flight' => $request->flight_required, 'Pickup' => $request->pickup_required, 'Guide' => $request->guide_required, 'Visa' => $request->visa_required, 'Insurance' => $request->insurance_required])->filter()->keys()->implode(', ')]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div><span><?php echo e($label); ?></span><strong><?php echo e($value ?: '—'); ?></strong></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div></section>
            <?php elseif($section === 'customer'): ?>
                <section class="workspace-section"><div class="workspace-section-heading"><div><span>LINKED CUSTOMER</span><h2>Customer Info</h2></div></div><div class="workspace-info-grid"><?php $__currentLoopData = ['Name' => $request->client?->name ?: $request->client_name, 'Email' => $request->client?->email ?: $request->client_email, 'Phone' => $request->client?->phone ?: $request->client_phone, 'Country' => $request->client?->country ?: $request->country, 'Nationality' => $request->client?->nationality ?: $request->nationality, 'Passport' => $request->client?->passport]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div><span><?php echo e($label); ?></span><strong><?php echo e($value ?: '—'); ?></strong></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div></section>
            <?php elseif($section === 'invoices'): ?>
                <section class="workspace-section"><div class="workspace-section-heading"><div><span>FINANCE</span><h2>Invoices</h2></div></div><div class="workspace-table-wrap"><table class="workspace-table workspace-table-simple"><thead><tr><th>Reference</th><th>Date</th><th>Amount</th><th>Method</th></tr></thead><tbody><?php $__empty_1 = true; $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><tr><td><?php echo e($invoice->reference); ?></td><td><?php echo e($invoice->paid_at); ?></td><td><?php echo e($invoice->currency); ?> <?php echo e(number_format($invoice->amount, 2)); ?></td><td><?php echo e($invoice->method); ?></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="4" class="workspace-empty">No invoices or payments linked to this request.</td></tr><?php endif; ?></tbody></table></div></section>
            <?php elseif($section === 'movements'): ?>
                <section class="workspace-section"><div class="workspace-section-heading"><div><span>ITINERARY OPERATIONS</span><h2>Daily Movements</h2></div></div><div class="workspace-table-wrap"><table class="workspace-table workspace-table-simple"><thead><tr><th>Day</th><th>Date</th><th>From</th><th>To</th><th>Activity / Accommodation</th><th>Notes</th></tr></thead><tbody><?php $__empty_1 = true; $__currentLoopData = $movements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $movement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><tr><td><?php echo e($movement->day_number); ?></td><td><?php echo e($movement->travel_date); ?></td><td><?php echo e($movement->from_location ?: '—'); ?></td><td><?php echo e($movement->to_location ?: '—'); ?></td><td><?php echo e($movement->description ?: '—'); ?></td><td>—</td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="6" class="workspace-empty">No daily movements linked to this request.</td></tr><?php endif; ?></tbody></table></div></section>
            <?php elseif($section === 'manual-mails' || $section === 'automated-mails'): ?>
                <section class="workspace-section"><div class="workspace-section-heading"><div><span>MESSAGING</span><h2><?php echo e($section === 'manual-mails' ? 'Manual Mails' : 'Automated Mails'); ?></h2></div><a class="workspace-small-button" href="mailto:<?php echo e($request->client_email); ?>">COMPOSE EMAIL</a></div><div class="workspace-mail-list"><?php $__empty_1 = true; $__currentLoopData = $reservationEmails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><article><div><strong><?php echo e($mail->subject); ?></strong><small><?php echo e($mail->recipient); ?> · <?php echo e($mail->status); ?></small></div><span><?php echo e($mail->sent_at ?: $mail->created_at); ?></span></article><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><div class="workspace-empty">No emails linked directly to this request.</div><?php endif; ?></div></section>
            <?php elseif($section === 'names'): ?>
                <section class="workspace-section"><div class="workspace-section-heading"><div><span>TRAVELLERS</span><h2>Names and Addresses</h2></div></div><div class="workspace-empty">No additional traveller or address records linked to this request.</div></section>
            <?php elseif($section === 'flights'): ?>
                <section class="workspace-section"><div class="workspace-section-heading"><div><span>OPERATIONS</span><h2>Flight Ticket Requests</h2></div></div><div class="workspace-table-wrap"><table class="workspace-table workspace-table-simple"><thead><tr><th>Traveller</th><th>Origin</th><th>Destination</th><th>Departure</th><th>Return / Arrival</th><th>Airline</th><th>Booking Status</th><th>Ticket Status</th><th>Assigned User</th></tr></thead><tbody><?php $__empty_1 = true; $__currentLoopData = $flightRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $flight): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><tr><td><?php echo e($flight->passenger_name); ?></td><td><?php echo e($flight->origin_code); ?></td><td><?php echo e($flight->destination_code); ?></td><td><?php echo e($flight->departure_at); ?></td><td><?php echo e($flight->arrival_at); ?></td><td><?php echo e($flight->airline); ?></td><td><?php echo e($flight->booking_status); ?></td><td><?php echo e($flight->ticket_number ?: 'Pending'); ?></td><td>—</td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="9" class="workspace-empty">No flight ticket requests linked to this request.</td></tr><?php endif; ?></tbody></table></div></section>
            <?php endif; ?>
        </main>
    </div>
</section>

<div id="proposal-action-menu" class="proposal-action-menu" hidden>
    <button type="button" class="proposal-action-menu-item" data-action="open"><i data-lucide="folder-open"></i><span>Open Proposal</span></button>
    <button type="button" class="proposal-action-menu-item" data-action="price"><i data-lucide="calculator"></i><span>Show Price Overview</span></button>
    <button type="button" class="proposal-action-menu-item" data-action="duplicate"><i data-lucide="copy"></i><span>Duplicate</span></button>
    <button type="button" class="proposal-action-menu-item proposal-action-menu-item--danger" data-action="delete"><i data-lucide="trash-2"></i><span>Delete Proposal</span></button>
</div>

<?php $__env->startPush('scripts'); ?>
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
    var requestId = <?php echo e($request->id); ?>;
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
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\requests\show.blade.php ENDPATH**/ ?>