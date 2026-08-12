<?php $__env->startSection('title', 'Reservations KPI Dashboard'); ?>
<?php $__env->startSection('content'); ?>
<div class="ops-page-heading">
    <div>
        <p class="eyebrow">Performance analytics</p>
        <h1>Reservations KPI Dashboard</h1>
        <p>Real-time reservation performance, evaluation metrics and team productivity.</p>
    </div>
    <div class="heading-actions">
        <button class="button button-secondary" onclick="window.print()"><i data-lucide="printer"></i>Print</button>
        <a class="button button-primary" href="<?php echo e(route('admin.reports.weekly')); ?>"><i data-lucide="file-chart-column"></i>Full report</a>
    </div>
</div>

<?php echo $__env->make('admin.partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="kpi-dashboard">
    
    <section class="stats-grid">
        <article class="stat-card">
            <div class="stat-icon stat-icon--blue"><i data-lucide="files"></i></div>
            <p>Total Proposals</p>
            <h2><?php echo e($officerKpis[0]['total_proposals'] ?? 0); ?></h2>
        </article>
        <article class="stat-card">
            <div class="stat-icon stat-icon--green"><i data-lucide="badge-check"></i></div>
            <p>Confirmed</p>
            <h2><?php echo e($officerKpis[0]['confirmed'] ?? 0); ?></h2>
            <small><?php echo e($officerKpis[0]['conversion_rate'] ?? 0); ?>% conversion</small>
        </article>
        <article class="stat-card">
            <div class="stat-icon stat-icon--orange"><i data-lucide="clock"></i></div>
            <p>Pending Evaluation</p>
            <h2><?php echo e($evaluationKpis['pending']); ?></h2>
        </article>
        <article class="stat-card">
            <div class="stat-icon stat-icon--purple"><i data-lucide="landmark"></i></div>
            <p>Missing Invoices</p>
            <h2 class="negative-money"><?php echo e($evaluationKpis['missing_invoices']); ?></h2>
        </article>
        <article class="stat-card">
            <div class="stat-icon stat-icon--red"><i data-lucide="alert-triangle"></i></div>
            <p>Overdue Payments</p>
            <h2 class="negative-money"><?php echo e($evaluationKpis['overdue_payments']); ?></h2>
        </article>
    </section>

    
    <?php $__currentLoopData = $officerKpis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kpi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <details class="ops-panel" open>
        <summary class="ops-panel-title" style="cursor:pointer">
            <div>
                <h2><?php echo e($kpi['name']); ?> <span class="ops-pill <?php echo e($kpi['ratings']['label'] === 'Excellent' ? 'ops-pill--green' : ($kpi['ratings']['label'] === 'Poor' ? 'ops-pill--red' : 'ops-pill--blue')); ?>"><?php echo e($kpi['ratings']['label']); ?></span></h2>
                <p>Performance score: <?php echo e($kpi['ratings']['score']); ?>%</p>
            </div>
            <i data-lucide="chevron-down"></i>
        </summary>
        <div class="kpi-metrics-grid">
            <article><small>Picked proposals (7d)</small><strong><?php echo e($kpi['picked_proposals_7d']); ?></strong></article>
            <article><small>Confirmed (7d)</small><strong><?php echo e($kpi['confirmed_proposals_7d']); ?></strong></article>
            <article><small>Handling</small><strong><?php echo e($kpi['handling_proposals']); ?></strong></article>
            <article><small>Pending >2d</small><strong class="<?php echo e($kpi['pending_2d'] > 3 ? 'negative-money' : ''); ?>"><?php echo e($kpi['pending_2d']); ?></strong></article>
            <article><small>Pending >7d</small><strong class="<?php echo e($kpi['pending_7d'] > 0 ? 'negative-money' : ''); ?>"><?php echo e($kpi['pending_7d']); ?></strong></article>
            <article><small>Longest pending</small><strong><?php echo e($kpi['longest_pending_days']); ?> days</strong></article>
            <article><small>Cancelled</small><strong class="negative-money"><?php echo e($kpi['cancelled']); ?></strong></article>
            <article><small>Expired quotations</small><strong class="negative-money"><?php echo e($kpi['expired']); ?></strong></article>
            <article><small>Avg confirmation</small><strong><?php echo e($kpi['avg_confirm_days']); ?> days</strong></article>
            <article><small>Avg quotation time</small><strong><?php echo e($kpi['avg_quote_days']); ?> days</strong></article>
            <article><small>Conversion rate</small><strong class="<?php echo e($kpi['conversion_rate'] >= 50 ? 'positive-money' : 'negative-money'); ?>"><?php echo e($kpi['conversion_rate']); ?>%</strong></article>
            <article><small>Reservation perf</small><strong class="<?php echo e($kpi['reservation_performance'] >= 70 ? 'positive-money' : 'negative-money'); ?>"><?php echo e($kpi['reservation_performance']); ?>%</strong></article>
        </div>

        <div class="kpi-sub-grid">
            <article>
                <small>Weekly</small><strong><?php echo e($kpi['weekly_perf']); ?></strong>
                <div class="mini-bar"><span style="width: min(<?php echo e($kpi['weekly_perf'] > 0 ? 100 : 0); ?>%, 100%)"></span></div>
            </article>
            <article>
                <small>Monthly</small><strong><?php echo e($kpi['monthly_perf']); ?></strong>
                <div class="mini-bar"><span style="width: min(<?php echo e($kpi['monthly_perf'] > 0 ? 100 : 0); ?>%, 100%)"></span></div>
            </article>
            <article>
                <small>Quarter</small><strong><?php echo e($kpi['quarter_perf']); ?></strong>
                <div class="mini-bar"><span style="width: min(<?php echo e($kpi['quarter_perf'] > 0 ? 100 : 0); ?>%, 100%)"></span></div>
            </article>
            <article>
                <small>Year</small><strong><?php echo e($kpi['year_perf']); ?></strong>
                <div class="mini-bar"><span style="width: min(<?php echo e($kpi['year_perf'] > 0 ? 100 : 0); ?>%, 100%)"></span></div>
            </article>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-top:1rem">
            <article class="ops-panel" style="padding:1rem">
                <h3 style="font-size:0.9rem;margin-bottom:0.5rem">Company Production</h3>
                <strong style="font-size:1.5rem">$<?php echo e(number_format($kpi['company_production'], 0)); ?></strong>
            </article>
            <article class="ops-panel" style="padding:1rem">
                <h3 style="font-size:0.9rem;margin-bottom:0.5rem">Reservations Production</h3>
                <strong style="font-size:1.5rem">$<?php echo e(number_format($kpi['reservation_production'], 0)); ?></strong>
            </article>
        </div>
    </details>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    
    <div class="dashboard-grid">
        <article class="panel panel--wide">
            <div class="panel-heading">
                <div><h3>Weekly Trend</h3><p>Proposals & confirmations over 7 weeks</p></div>
            </div>
            <div class="chart-wrap">
                <div class="bar-chart" style="display:grid;grid-template-columns:repeat(<?php echo e($weeklyTrend->count()); ?>, 1fr)">
                    <?php $__currentLoopData = $weeklyTrend; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bar-column">
                        <div class="bar bar--blue" style="height: min(<?php echo e($w['proposals'] > 0 ? ($w['proposals'] / $weeklyTrend->max('proposals')) * 100 : 0); ?>%, 100%)"><small><?php echo e($w['proposals']); ?></small></div>
                        <div class="bar bar--green" style="height: min(<?php echo e($w['confirmed'] > 0 ? ($w['confirmed'] / $weeklyTrend->max('confirmed')) * 100 : 0); ?>%, 100%)"><small><?php echo e($w['confirmed']); ?></small></div>
                        <small><?php echo e($w['label']); ?></small>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </article>

        <article class="panel">
            <div class="panel-heading">
                <div><h3>Status Distribution</h3><p>Proposal pipeline</p></div>
            </div>
            <div class="kpi-donut">
                <?php $__currentLoopData = $statusDistribution; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="kpi-donut-item">
                    <span class="ops-pill <?php echo e($s['label'] === 'confirmed' ? 'ops-pill--green' : ($s['label'] === 'cancelled' ? 'ops-pill--red' : 'ops-pill--blue')); ?>"><?php echo e($s['label']); ?></span>
                    <strong><?php echo e($s['value']); ?></strong>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </article>
    </div>

    
    <article class="panel">
        <div class="panel-heading">
            <div><h3>Monthly Revenue Trend</h3><p>Confirmed proposal revenue over 12 months</p></div>
        </div>
        <div class="table-wrap">
            <table class="ops-table">
                <thead><tr><th>Month</th><th>Proposals</th><th>Confirmed</th><th>Revenue</th></tr></thead>
                <tbody>
                <?php $__currentLoopData = $monthlyTrend; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr><td><strong><?php echo e($m['label']); ?></strong></td><td><?php echo e($m['proposals']); ?></td><td><?php echo e($m['confirmed']); ?></td><td><strong>$<?php echo e(number_format($m['revenue'], 0)); ?></strong></td></tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </article>

    
    <details class="ops-panel" open>
        <summary class="ops-panel-title" style="cursor:pointer">
            <div><h2>Evaluation Department</h2><p>Invoice verification and payment tracking</p></div>
            <i data-lucide="chevron-down"></i>
        </summary>
        <div class="kpi-metrics-grid">
            <article><small>Today's Evaluations</small><strong><?php echo e($evaluationKpis['today']); ?></strong></article>
            <article><small>Pending</small><strong><?php echo e($evaluationKpis['pending']); ?></strong></article>
            <article><small>Approved</small><strong class="positive-money"><?php echo e($evaluationKpis['approved']); ?></strong></article>
            <article><small>Total Invoices</small><strong><?php echo e($evaluationKpis['total_invoices']); ?></strong></article>
            <article><small>Missing Invoices</small><strong class="negative-money"><?php echo e($evaluationKpis['missing_invoices']); ?></strong></article>
            <article><small>Invoices Waiting</small><strong><?php echo e($evaluationKpis['invoices_waiting']); ?></strong></article>
            <article><small>Overdue Payments</small><strong class="negative-money"><?php echo e($evaluationKpis['overdue_payments']); ?></strong></article>
            <article><small>Largest Variance</small><strong class="<?php echo e($evaluationKpis['largest_variance'] > 0 ? 'negative-money' : 'positive-money'); ?>">$<?php echo e(number_format($evaluationKpis['largest_variance'], 2)); ?></strong></article>
        </div>
    </details>

    
    <div class="dashboard-grid">
        <article class="panel">
            <div class="panel-heading">
                <div><h3>Recent Activity</h3><p>Latest evaluation actions</p></div>
            </div>
            <div class="task-list">
                <?php $__empty_1 = true; $__currentLoopData = $evaluationKpis['recent_activity']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="task-item">
                    <div><strong><?php echo e($activity->title); ?></strong><small><?php echo e($activity->item_type); ?> - <?php echo e(\Carbon\Carbon::parse($activity->evaluated_at)->diffForHumans()); ?></small></div>
                    <span class="ops-pill <?php echo e($activity->status === 'matched' ? 'ops-pill--green' : 'ops-pill--blue'); ?>"><?php echo e($activity->status); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="empty-cell">No recent evaluation activity.</div>
                <?php endif; ?>
            </div>
        </article>

        <article class="panel">
            <div class="panel-heading">
                <div><h3>Upcoming Payments</h3><p>Invoices due for payment</p></div>
            </div>
            <div class="task-list">
                <?php $__empty_1 = true; $__currentLoopData = $evaluationKpis['upcoming_payments']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="task-item">
                    <div><strong><?php echo e($payment->company_name); ?></strong><small><?php echo e($payment->invoice_number); ?> - Due <?php echo e(\Carbon\Carbon::parse($payment->payment_deadline)->format('d M Y')); ?></small></div>
                    <strong>$<?php echo e(number_format($payment->amount, 2)); ?></strong>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="empty-cell">No upcoming payments.</div>
                <?php endif; ?>
            </div>
        </article>
    </div>

    
    <div class="dashboard-grid">
        <article class="panel">
            <div class="panel-heading">
                <div><h3>Reservation Leaderboard</h3><p>By approved evaluations</p></div>
            </div>
            <div class="table-wrap">
                <table class="ops-table">
                    <thead><tr><th>Officer</th><th>Evaluations</th><th>Approved</th></tr></thead>
                    <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $evaluationKpis['reservation_leaderboard']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr><td><strong><?php echo e($r['name']); ?></strong></td><td><?php echo e($r['evaluations']); ?></td><td><?php echo e($r['approved']); ?></td></tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="3" class="empty-cell">No data</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </article>

        <article class="panel">
            <div class="panel-heading">
                <div><h3>Supplier Leaderboard</h3><p>By invoice volume</p></div>
            </div>
            <div class="table-wrap">
                <table class="ops-table">
                    <thead><tr><th>Supplier</th><th>Invoices</th><th>Total Amount</th></tr></thead>
                    <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $evaluationKpis['supplier_leaderboard']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr><td><strong><?php echo e($s->company_name); ?></strong></td><td><?php echo e($s->total); ?></td><td>$<?php echo e(number_format($s->total_amount, 2)); ?></td></tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="3" class="empty-cell">No data</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </article>
    </div>

    
    <details class="ops-panel">
        <summary class="ops-panel-title" style="cursor:pointer">
            <div><h2>Proposal Aging</h2><p>Open proposals by age</p></div>
            <i data-lucide="chevron-down"></i>
        </summary>
        <div class="table-wrap">
            <table class="ops-table">
                <thead><tr><th>Reference</th><th>Title</th><th>Status</th><th>Age (days)</th></tr></thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $proposalAging; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><strong><?php echo e($p['reference']); ?></strong></td>
                    <td><?php echo e($p['title']); ?></td>
                    <td><span class="ops-pill"><?php echo e($p['status']); ?></span></td>
                    <td><strong class="<?php echo e($p['age_days'] > 7 ? 'negative-money' : ''); ?>"><?php echo e($p['age_days']); ?> days</strong></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="4" class="empty-cell">No open proposals.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </details>

    
    <article class="ops-panel">
        <div class="ops-panel-title"><h2>Invoice Status Overview</h2></div>
        <div class="kpi-metrics-grid">
            <?php $__currentLoopData = $invoiceStatus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $is): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <article>
                <small><?php echo e(ucwords(str_replace('_', ' ', $is['label']))); ?></small>
                <strong><?php echo e($is['value']); ?></strong>
            </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </article>
</div>

<style>
.kpi-dashboard { display:flex; flex-direction:column; gap:1.5rem; }
.kpi-metrics-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(160px,1fr)); gap:0.75rem; margin-top:0.75rem; }
.kpi-metrics-grid article { background: var(--surface); padding:0.75rem 1rem; border-radius:8px; }
.kpi-metrics-grid article small { display:block; font-size:0.75rem; color:var(--muted); margin-bottom:0.25rem; }
.kpi-metrics-grid article strong { font-size:1.2rem; }
.kpi-sub-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; margin-top:0.75rem; }
.kpi-sub-grid article { background: var(--surface); padding:0.75rem; border-radius:8px; }
.kpi-sub-grid article small { display:block; font-size:0.75rem; color:var(--muted); }
.kpi-sub-grid article strong { font-size:1.1rem; }
.mini-bar { height:4px; background:var(--border); border-radius:2px; margin-top:0.5rem; overflow:hidden; }
.mini-bar span { display:block; height:100%; background:var(--accent); border-radius:2px; transition:width 0.3s; }
.kpi-donut { display:flex; flex-direction:column; gap:0.5rem; }
.kpi-donut-item { display:flex; justify-content:space-between; align-items:center; }
.kpi-donut-item strong { font-size:1.1rem; }
@media print {
    .sidebar, .topbar, .heading-actions, summary { display:none !important; }
    .kpi-dashboard { break-inside:avoid; }
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\reports\kpi.blade.php ENDPATH**/ ?>