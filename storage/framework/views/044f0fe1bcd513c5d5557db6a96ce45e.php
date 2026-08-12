<?php $__env->startSection('title', 'Evaluation Department Dashboard'); ?>
<?php $__env->startSection('content'); ?>
<div class="ops-page-heading">
    <div>
        <p class="eyebrow">Evaluation department</p>
        <h1>Evaluation Dashboard</h1>
        <p>Invoice verification, missing invoice detection and payment tracking.</p>
    </div>
    <div class="heading-actions">
        <a class="button button-secondary" href="<?php echo e(route('admin.evaluations.index')); ?>"><i data-lucide="clipboard-check"></i>Evaluation queue</a>
        <a class="button button-primary" href="<?php echo e(route('admin.evaluations.invoices')); ?>"><i data-lucide="upload"></i>Upload invoices</a>
    </div>
</div>

<?php echo $__env->make('admin.partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="kpi-dashboard">
    <section class="stats-grid">
        <article class="stat-card">
            <div class="stat-icon stat-icon--blue"><i data-lucide="clipboard-list"></i></div>
            <p>Today's Evaluations</p>
            <h2><?php echo e($kpiData['today']); ?></h2>
        </article>
        <article class="stat-card">
            <div class="stat-icon stat-icon--orange"><i data-lucide="hourglass"></i></div>
            <p>Pending</p>
            <h2><?php echo e($kpiData['pending']); ?></h2>
        </article>
        <article class="stat-card">
            <div class="stat-icon stat-icon--green"><i data-lucide="badge-check"></i></div>
            <p>Approved</p>
            <h2><?php echo e($kpiData['approved']); ?></h2>
        </article>
        <article class="stat-card">
            <div class="stat-icon stat-icon--purple"><i data-lucide="receipt-text"></i></div>
            <p>Total Invoices</p>
            <h2><?php echo e($kpiData['total_invoices']); ?></h2>
        </article>
        <article class="stat-card">
            <div class="stat-icon stat-icon--red"><i data-lucide="file-x"></i></div>
            <p>Missing Invoices</p>
            <h2 class="negative-money"><?php echo e($kpiData['missing_invoices']); ?></h2>
        </article>
        <article class="stat-card">
            <div class="stat-icon stat-icon--yellow"><i data-lucide="clock"></i></div>
            <p>Overdue Payments</p>
            <h2 class="negative-money"><?php echo e($kpiData['overdue_payments']); ?></h2>
        </article>
    </section>

    <?php if($kpiData['largest_variance'] > 0): ?>
    <div class="ops-panel" style="padding:1rem;border-left:4px solid var(--danger)">
        <strong>Largest variance detected:</strong> $<?php echo e(number_format($kpiData['largest_variance'], 2)); ?>

        <?php if($kpiData['largest_variance_entry']): ?>
        — <?php echo e($kpiData['largest_variance_entry']->title); ?> (<?php echo e($kpiData['largest_variance_entry']->item_type); ?>)
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="dashboard-grid">
        <article class="panel panel--wide">
            <div class="panel-heading">
                <div><h3>Weekly Trend</h3><p>Proposals, confirmations & invoices</p></div>
            </div>
            <div class="chart-wrap">
                <div class="bar-chart" style="display:grid;grid-template-columns:repeat(<?php echo e($weeklyTrend->count()); ?>,1fr)">
                    <?php $__currentLoopData = $weeklyTrend; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bar-column">
                        <div class="bar bar--blue" style="height:min(<?php echo e($w['proposals'] > 0 ? ($w['proposals'] / $weeklyTrend->max('proposals')) * 100 : 0); ?>%,100%)"><small><?php echo e($w['proposals']); ?></small></div>
                        <div class="bar bar--green" style="height:min(<?php echo e($w['confirmed'] > 0 ? ($w['confirmed'] / $weeklyTrend->max('confirmed')) * 100 : 0); ?>%,100%)"><small><?php echo e($w['confirmed']); ?></small></div>
                        <div class="bar bar--orange" style="height:min(<?php echo e($w['invoices'] > 0 ? ($w['invoices'] / $weeklyTrend->max('invoices')) * 100 : 0); ?>%,100%)"><small><?php echo e($w['invoices']); ?></small></div>
                        <small><?php echo e($w['label']); ?></small>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </article>
        <article class="panel">
            <div class="panel-heading"><div><h3>Invoice Status</h3><p>Distribution</p></div></div>
            <div class="task-list">
                <?php $__currentLoopData = $invoiceStatus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $is): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="task-item" style="justify-content:space-between">
                    <span class="ops-pill <?php echo e($is['label'] === 'approved' || $is['label'] === 'paid' ? 'ops-pill--green' : ($is['label'] === 'requires_amendment' ? 'ops-pill--red' : 'ops-pill--blue')); ?>"><?php echo e(ucwords(str_replace('_', ' ', $is['label']))); ?></span>
                    <strong><?php echo e($is['value']); ?></strong>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </article>
    </div>

    <div class="dashboard-grid">
        <article class="panel">
            <div class="panel-heading"><div><h3>Recent Activity</h3></div></div>
            <div class="task-list">
                <?php $__empty_1 = true; $__currentLoopData = $recentEntries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="task-item">
                    <div><strong><?php echo e($entry->title); ?></strong><small><?php echo e($entry->item_type); ?> — <?php echo e($entry->evaluated_at?->diffForHumans()); ?></small></div>
                    <span class="ops-pill <?php echo e($entry->status === 'matched' ? 'ops-pill--green' : 'ops-pill--blue'); ?>"><?php echo e($entry->status); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="empty-cell">No recent evaluation activity.</div>
                <?php endif; ?>
            </div>
        </article>
        <article class="panel">
            <div class="panel-heading"><div><h3>Upcoming Payments</h3></div></div>
            <div class="task-list">
                <?php $__empty_1 = true; $__currentLoopData = $kpiData['upcoming_payments']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="task-item">
                    <div><strong><?php echo e($payment->company_name); ?></strong><small><?php echo e($payment->invoice_number); ?> — Due <?php echo e($payment->payment_deadline?->format('d M Y')); ?></small></div>
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
            <div class="panel-heading"><div><h3>Reservation Leaderboard</h3></div></div>
            <div class="table-wrap">
                <table class="ops-table">
                    <thead><tr><th>Officer</th><th>Evaluations</th><th>Approved</th></tr></thead>
                    <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $kpiData['reservation_leaderboard']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr><td><strong><?php echo e($r['name']); ?></strong></td><td><?php echo e($r['evaluations']); ?></td><td class="<?php echo e($r['approved'] > 0 ? 'positive-money' : ''); ?>"><?php echo e($r['approved']); ?></td></tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="3" class="empty-cell">No data</td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </article>
        <article class="panel">
            <div class="panel-heading"><div><h3>Supplier Leaderboard</h3></div></div>
            <div class="table-wrap">
                <table class="ops-table">
                    <thead><tr><th>Supplier</th><th>Invoices</th><th>Total</th></tr></thead>
                    <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $kpiData['supplier_leaderboard']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr><td><strong><?php echo e($s->company_name); ?></strong></td><td><?php echo e($s->total); ?></td><td><strong>$<?php echo e(number_format($s->total_amount, 2)); ?></strong></td></tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="3" class="empty-cell">No data</td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </article>
    </div>
</div>
<style>
.kpi-dashboard { display:flex; flex-direction:column; gap:1.5rem; }
.stat-card .stat-icon--yellow { background:#fef3c7;color:#d97706; }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\evaluations\dashboard.blade.php ENDPATH**/ ?>