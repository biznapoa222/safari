<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title><?php echo e($quotation->reference); ?> · <?php echo e($quotation->client_name); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root{--olive:#50691d;--olive-dark:#435d15;--blue:#199ce7;--green:#42aa53;--line:#d6dbd7;--ink:#283229;--muted:#718077}
        *{box-sizing:border-box}html{scroll-behavior:smooth}body{margin:0;color:var(--ink);background:#fff;font:10px/1.45 Inter,Arial,sans-serif}a{color:inherit;text-decoration:none}button{font:inherit}
        .client-top{height:31px;padding:0 13px;display:flex;align-items:center;justify-content:space-between;color:#fff;background:var(--olive-dark)}.client-top strong{font-size:10px}.client-actions{display:flex;gap:18px}.client-actions a,.client-actions button{padding:0;color:#fff;background:0;border:0;font-size:7px;font-weight:700;text-transform:uppercase;cursor:pointer}.travel-info{height:28px;padding:0 17px;display:flex;align-items:center;gap:8px;color:#fff;background:var(--blue);font-weight:600}.travel-info span{width:14px;height:14px;display:grid;place-items:center;border:1px solid #fff;border-radius:50%;font-size:8px}
        .portal-shell{min-height:calc(100vh - 59px);padding:4px 0 20px 17px;display:grid;grid-template-columns:82px minmax(0,1fr);gap:0}.portal-nav{position:sticky;top:0;height:calc(100vh - 66px);padding:4px;background:var(--olive);overflow-y:auto}.portal-nav a{min-height:35px;padding:0 9px;display:flex;align-items:center;gap:7px;color:#becaa8;font-size:7px;font-weight:700;text-transform:uppercase}.portal-nav a:first-child,.portal-nav a:hover{color:#fff;background:rgba(255,255,255,.1)}.portal-nav i{width:8px;height:8px;display:block;border:1px solid currentColor;border-radius:2px}.portal-nav .sub{padding-left:22px;min-height:28px;font-size:6px}.portal-nav .disabled{opacity:.36;pointer-events:none}
        .portal-content{min-width:0;padding:10px 4vw 50px}.proposal-sheet{width:min(100%,680px);margin:0 auto}.sheet-section{padding:0 0 22px;scroll-margin-top:15px}.sheet-section h2{margin:0 0 11px;font-size:10px}.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:9px 16px}.field{min-height:34px;border-bottom:1px solid #aeb7b0}.field.full{grid-column:1/-1}.field small{display:block;color:#738077;font-size:6px}.field strong{display:block;margin-top:3px;font-size:8px;font-weight:500}.status-line{display:flex;align-items:center;gap:6px}.status-line em{width:8px;height:8px;background:#48a956;border-radius:2px}.hint{margin:6px 0 0;color:#8a958e;font-size:6px}.exchange-table,.compact-table{width:100%;border-collapse:collapse}.exchange-table th,.compact-table th{height:27px;color:#5a675e;text-align:left;font-size:6px;font-weight:500;border-bottom:1px solid var(--line)}.exchange-table td,.compact-table td{height:29px;font-size:7px;border-bottom:1px solid #edf0ed}.currency-flag{width:10px;height:7px;margin-right:4px;display:inline-block;background:linear-gradient(#2446a7 0 33%,#fff 33% 66%,#da2738 66%)}
        .room-row{display:grid;grid-template-columns:70px 1fr 1fr 130px;gap:12px;align-items:end}.room-label{height:34px;padding-top:15px;border-bottom:1px solid #aeb7b0}.chip{display:inline-block;margin:2px 2px 0 0;padding:3px 6px;background:#ecefed;border-radius:2px;font-size:6px}.green-button{margin-top:7px;padding:5px 8px;color:#fff;background:var(--green);border:0;border-radius:2px;font-size:6px;font-weight:700;text-transform:uppercase;cursor:pointer}.document-block{margin-top:15px}.document-actions{display:flex;gap:5px}.empty-docs{padding:12px;text-align:center;color:#8a958e;font-size:6px}.program-day{margin-bottom:7px;border:1px solid var(--line)}.program-day summary{height:35px;padding:0 10px;display:flex;align-items:center;justify-content:space-between;cursor:pointer;list-style:none;background:#fafbfa}.program-day summary strong{font-size:8px}.program-day summary span{color:#6f7d74;font-size:7px}.program-body{padding:10px;border-top:1px solid var(--line)}.program-body p{margin:0 0 7px;color:#56645b;font-size:8px}.program-item{padding:5px 0;display:flex;justify-content:space-between;border-top:1px solid #edf0ed;font-size:7px}.summary-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:6px}.summary-card{padding:9px;border:1px solid var(--line)}.summary-card small,.summary-card strong{display:block}.summary-card small{color:var(--muted);font-size:6px}.summary-card strong{margin-top:4px;font-size:10px}.payment-progress{height:5px;margin-top:8px;background:#e5e9e6}.payment-progress span{height:100%;display:block;background:var(--green)}.client-footer{padding:12px 17px;color:#738077;border-top:1px solid var(--line);font-size:7px;text-align:center}
        @media(max-width:760px){.client-actions a:first-child{display:none}.portal-shell{padding-left:0;grid-template-columns:1fr}.portal-nav{position:sticky;z-index:4;top:0;width:100%;height:42px;display:flex;overflow-x:auto}.portal-nav a{flex:0 0 auto;min-height:34px}.portal-nav .sub,.portal-nav .disabled{display:none}.portal-content{padding:18px 14px}.form-grid,.summary-grid{grid-template-columns:1fr 1fr}.room-row{grid-template-columns:1fr 1fr}.client-top{height:auto;min-height:42px;gap:8px}.client-top strong{font-size:8px}.client-actions{gap:8px}}
        @media print{.client-actions,.portal-nav,.travel-info{display:none}.client-top{color:#283229;background:#fff;border-bottom:1px solid #ccc}.portal-shell{display:block;padding:0}.portal-content{padding:20px}.proposal-sheet{width:100%;max-width:none}.program-day{break-inside:avoid}}
    </style>
</head>
<body>
    <header class="client-top">
        <strong>Proposal: <?php echo e($quotation->reference); ?> · <?php echo e($quotation->client_name); ?> (<?php echo e($quotation->guest_count); ?> pax) · <?php echo e(ucwords(str_replace('_', ' ', $quotation->status))); ?> · <?php echo e($quotation->seller_name ?: 'Shishi Footsteps'); ?></strong>
        <nav class="client-actions"><a href="<?php echo e(route('home')); ?>">Back to Shishi Footsteps</a><a href="<?php echo e(route('public.booking.form', request()->route('token'))); ?>">Booking form</a><button onclick="window.print()">Download PDF</button><a href="mailto:<?php echo e($quotation->client_email); ?>?subject=<?php echo e(rawurlencode($quotation->reference.' proposal')); ?>">Contact your planner</a></nav>
    </header>
    <div class="travel-info"><span>i</span>Information of Travel Request</div>

    <main class="portal-shell">
        <nav class="portal-nav" aria-label="Proposal sections">
            <a href="#settings"><i></i>Settings</a><a href="#persons"><i></i>Persons</a><a href="#program"><i></i>Program</a><a href="#rooms"><i></i>Room options</a><a href="#overview"><i></i>Overview</a><a class="sub" href="#overview">PDFs</a><a class="sub" href="#overview">Snapshots</a><a href="#reservations"><i></i>Reservations</a><a class="disabled" href="#"><i></i>Evaluation</a><a class="disabled" href="#"><i></i>Payment deadlines</a><a href="#payments"><i></i>Payment schedules</a>
        </nav>

        <div class="portal-content">
            <article class="proposal-sheet">
                <section id="settings" class="sheet-section">
                    <h2>Settings</h2>
                    <div class="form-grid">
                        <div class="field"><small>Proposal name</small><strong><?php echo e($quotation->title); ?></strong></div>
                        <div class="field"><small>Request base type</small><strong><?php echo e($quotation->proposal_type); ?></strong></div>
                        <div class="field"><small>Status</small><strong class="status-line"><em></em><?php echo e(ucwords(str_replace('_', ' ', $quotation->status))); ?></strong></div>
                        <div class="field"><small>First day</small><strong><?php echo e(\Carbon\Carbon::parse($quotation->start_date)->format('d-m-Y')); ?></strong></div>
                    </div>
                </section>

                <section class="sheet-section">
                    <h2>Exchange Rates</h2>
                    <table class="exchange-table"><thead><tr><th>From</th><th>To</th><th>Rate</th></tr></thead><tbody><tr><td><span class="currency-flag"></span><?php echo e($quotation->currency); ?></td><td><?php echo e($quotation->currency); ?></td><td><?php echo e(number_format((float)$quotation->exchange_rate, 4)); ?></td></tr></tbody></table>
                    <p class="hint">Exchange rates are automatically frozen for this proposal.</p>
                </section>

                <section class="sheet-section">
                    <h2>Start location (<?php echo e(strtoupper(substr($quotation->workflow_country,0,2))); ?>)</h2>
                    <div class="field full"><small>Start location of this trip</small><strong><?php echo e($quotation->start_location); ?></strong></div>
                    <p class="hint">This is the start location for the proposal.</p>
                </section>

                <section id="persons" class="sheet-section">
                    <h2>Airport Representative Person</h2>
                    <div class="field full"><small>Airport representative person</small><strong><?php echo e($reservations->pluck('assigned_person')->filter()->first() ?: 'To be confirmed'); ?></strong></div>
                </section>

                <section id="rooms" class="sheet-section">
                    <h2>Room settings</h2>
                    <?php $__empty_1 = true; $__currentLoopData = $roomItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="room-row"><div class="room-label">Room <?php echo e($loop->iteration); ?></div><div class="field"><small>Room name</small><strong><?php echo e($room->title); ?></strong></div><div class="field"><small>Persons in this room</small><span class="chip"><?php echo e($quotation->client_name); ?></span></div><div class="field"><small>Selected room type</small><strong><?php echo e(ucwords(str_replace('_',' ',$room->calculation_type))); ?></strong></div></div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="room-row"><div class="room-label">Room 1</div><div class="field"><small>Room name</small><strong>To be confirmed</strong></div><div class="field"><small>Persons in this room</small><span class="chip"><?php echo e($quotation->client_name); ?></span></div><div class="field"><small>Selected room type</small><strong>Double</strong></div></div>
                    <?php endif; ?>
                </section>

                <?php $__currentLoopData = ['Customer Briefing','Guide Briefing','Tickets']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <section class="sheet-section document-block">
                    <h2><?php echo e($document); ?></h2>
                    <table class="compact-table"><thead><tr><th>File Name</th><th>Type</th><th>Size</th><th>Created date</th><th>Created by</th><th>Action</th></tr></thead></table>
                    <?php $clientDocs=$documents->where('category',match($document){'Customer Briefing'=>'customer_briefing','Guide Briefing'=>'guide_briefing','Tickets'=>'ticket'}); ?>
                    <?php $__empty_1 = true; $__currentLoopData = $clientDocs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><div class="program-item"><span><?php echo e($doc->file_name); ?> · <?php echo e(number_format($doc->size/1024,1)); ?> KB</span><a class="green-button" href="<?php echo e(route('proposal.client.document',[request()->route('token'),$doc->id])); ?>">Download</a></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><div class="empty-docs">There are no uploaded files yet</div><?php endif; ?>
                </section>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <section id="program" class="sheet-section">
                    <h2>Program</h2>
                    <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <details class="program-day" <?php echo e($loop->first ? 'open' : ''); ?>><summary><strong>Day <?php echo e($day->day_number); ?> · <?php echo e(\Carbon\Carbon::parse($day->travel_date)->format('d-m-Y')); ?></strong><span><?php echo e($day->from_location); ?><?php if($day->to_location): ?> → <?php echo e($day->to_location); ?><?php endif; ?></span></summary><div class="program-body"><p><?php echo e($day->description ?: 'Your detailed program is being prepared.'); ?></p><?php $__currentLoopData = $day->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div class="program-item"><span><?php echo e($item->title); ?></span><span><?php echo e(ucfirst($item->item_type)); ?></span></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div></details>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </section>

                <section id="overview" class="sheet-section">
                    <h2>Overview</h2>
                    <div class="summary-grid"><div class="summary-card"><small>Duration</small><strong><?php echo e($quotation->duration_days); ?> days</strong></div><div class="summary-card"><small>Travellers</small><strong><?php echo e($quotation->guest_count); ?></strong></div><div class="summary-card"><small>Proposal total</small><strong><?php echo e($quotation->currency); ?> <?php echo e(number_format($proposalTotal,2)); ?></strong></div><div class="summary-card"><small>Balance</small><strong><?php echo e($quotation->currency); ?> <?php echo e(number_format(max(0,$proposalTotal-$totalPaid),2)); ?></strong></div></div>
                </section>

                <section id="reservations" class="sheet-section"><h2>Reservations</h2><table class="compact-table"><thead><tr><th>Service</th><th>Supplier</th><th>Date</th><th>Status</th></tr></thead><tbody><?php $__empty_1 = true; $__currentLoopData = $reservations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reservation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><tr><td><?php echo e(ucfirst($reservation->reservation_type)); ?></td><td><?php echo e($reservation->supplier ?: 'To be confirmed'); ?></td><td><?php echo e(\Carbon\Carbon::parse($reservation->starts_at)->format('d-m-Y')); ?></td><td><?php echo e(ucfirst($reservation->status)); ?></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="4">Reservations are being prepared.</td></tr><?php endif; ?></tbody></table></section>

                <section id="payments" class="sheet-section"><h2>Payment schedule</h2><div class="field"><small>Paid</small><strong><?php echo e($quotation->currency); ?> <?php echo e(number_format($totalPaid,2)); ?> of <?php echo e(number_format($proposalTotal,2)); ?></strong><div class="payment-progress"><span style="width:<?php echo e($proposalTotal > 0 ? min(100,($totalPaid/$proposalTotal)*100) : 0); ?>%"></span></div></div></section>
            </article>
        </div>
    </main>
    <footer class="client-footer">Private proposal prepared for <?php echo e($quotation->client_name); ?> by Shishi Footsteps. Please do not forward this secure link.</footer>
</body>
</html>
<?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\public\client-proposal.blade.php ENDPATH**/ ?>