<?php $__env->startSection('title', __('ui.dashboard')); ?>

<?php $__env->startSection('content'); ?>
    <div class="page-heading">
        <div>
            <p class="eyebrow"><?php echo e(now()->format('l, F j')); ?></p>
            <h1><?php echo e(__('ui.good_afternoon')); ?>, Amara</h1>
            <p><?php echo e(__('ui.dashboard_intro')); ?></p>
        </div>
        <div class="heading-actions">
            <button class="button button-secondary"><i data-lucide="calendar-days"></i><?php echo e(__('ui.this_month')); ?></button>
            <a href="<?php echo e(route('admin.quotations.create')); ?>" class="button button-primary"><i data-lucide="plus"></i><?php echo e(__('ui.new_proposal')); ?></a>
        </div>
    </div>

    <section class="stats-grid">
        <article class="stat-card">
            <div class="stat-icon stat-icon--green"><i data-lucide="circle-dollar-sign"></i></div>
            <div class="stat-change positive"><i data-lucide="trending-up"></i>12.8%</div>
            <p><?php echo e(__('ui.confirmed_revenue')); ?></p>
            <h2>$<?php echo e(number_format($stats['revenue'])); ?></h2>
            <small><?php echo e(__('ui.vs_last_month')); ?></small>
        </article>
        <article class="stat-card">
            <div class="stat-icon stat-icon--orange"><i data-lucide="mail-question"></i></div>
            <div class="stat-change positive"><i data-lucide="trending-up"></i>8.4%</div>
            <p><?php echo e(__('ui.new_enquiries')); ?></p>
            <h2><?php echo e($stats['enquiries']); ?></h2>
            <small><?php echo e(__('ui.this_month')); ?></small>
        </article>
        <article class="stat-card">
            <div class="stat-icon stat-icon--blue"><i data-lucide="files"></i></div>
            <div class="stat-change neutral"><?php echo e(__('ui.in_progress')); ?></div>
            <p><?php echo e(__('ui.active_proposals')); ?></p>
            <h2><?php echo e($stats['proposals']); ?></h2>
            <small><?php echo e(__('ui.awaiting_decision')); ?></small>
        </article>
        <article class="stat-card">
            <div class="stat-icon stat-icon--purple"><i data-lucide="plane-takeoff"></i></div>
            <div class="stat-change positive"><i data-lucide="arrow-up-right"></i><?php echo e(__('ui.upcoming')); ?></div>
            <p><?php echo e(__('ui.departures')); ?></p>
            <h2><?php echo e($stats['departures']); ?></h2>
            <small><?php echo e(__('ui.next_30_days')); ?></small>
        </article>
    </section>

    <section class="dashboard-grid">
        <article class="panel panel--wide">
            <div class="panel-heading">
                <div><h3><?php echo e(__('ui.revenue_overview')); ?></h3><p><?php echo e(__('ui.sales_performance')); ?></p></div>
                <div class="chart-legend"><span></span>2026</div>
            </div>
            <div class="chart-wrap">
                <div class="chart-y"><span>$150k</span><span>$100k</span><span>$50k</span><span>$0</span></div>
                <div class="bar-chart">
                    <?php $__currentLoopData = $monthlySales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bar-column">
                            <div class="bar" style="height: <?php echo e(($sale / 150) * 100); ?>%"><span>$<?php echo e($sale); ?>k</span></div>
                            <small><?php echo e(now()->startOfYear()->addMonths($index)->format('M')); ?></small>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </article>

        <article class="panel task-panel">
            <div class="panel-heading">
                <div><h3><?php echo e(__('ui.todays_tasks')); ?></h3><p><?php echo e($tasks->count()); ?> <?php echo e(__('ui.items_due')); ?></p></div>
                <a href="<?php echo e(route('admin.records.index', 'proposal-tasks')); ?>"><?php echo e(__('ui.view_all')); ?></a>
            </div>
            <div class="task-list">
                <?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="task-item">
                        <button class="task-check" aria-label="Complete task"></button>
                        <div><strong><?php echo e($task->title); ?></strong><small><?php echo e($task->category); ?> · <?php echo e(\Carbon\Carbon::parse($task->due_at)->diffForHumans()); ?></small></div>
                        <span class="priority priority--<?php echo e($task->priority); ?>"><?php echo e(ucfirst($task->priority)); ?></span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <button class="add-task"><i data-lucide="plus"></i><?php echo e(__('ui.add_task')); ?></button>
        </article>
    </section>

    <section class="dashboard-grid dashboard-grid--bottom">
        <article class="panel panel--wide">
            <div class="panel-heading">
                <div><h3><?php echo e(__('ui.recent_proposals')); ?></h3><p><?php echo e(__('ui.latest_client_quotes')); ?></p></div>
                <a href="<?php echo e(route('admin.quotations.index')); ?>"><?php echo e(__('ui.view_all')); ?> <i data-lucide="arrow-right"></i></a>
            </div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th><?php echo e(__('ui.proposal')); ?></th><th><?php echo e(__('ui.client')); ?></th><th><?php echo e(__('ui.destination')); ?></th><th><?php echo e(__('ui.value')); ?></th><th><?php echo e(__('ui.status')); ?></th><th></th></tr></thead>
                    <tbody>
                    <?php $__currentLoopData = $proposals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proposal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><strong><?php echo e($proposal->reference); ?></strong><small><?php echo e($proposal->title); ?></small></td>
                            <td><?php echo e($proposal->client_name); ?></td>
                            <td><?php echo e($proposal->destination); ?></td>
                            <td><strong>$<?php echo e(number_format($proposal->quoted_amount)); ?></strong></td>
                            <td><span class="status status--<?php echo e($proposal->status); ?>"><?php echo e(ucfirst($proposal->status)); ?></span></td>
                            <td><button class="row-action"><i data-lucide="more-horizontal"></i></button></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </article>

        <article class="panel departure-panel">
            <div class="panel-heading">
                <div><h3><?php echo e(__('ui.upcoming_departures')); ?></h3><p><?php echo e(__('ui.next_30_days')); ?></p></div>
                <a href="<?php echo e(route('admin.records.index', 'operations-calendar')); ?>"><i data-lucide="calendar"></i></a>
            </div>
            <div class="departure-list">
                <?php $__currentLoopData = $departures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $departure): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="departure-item">
                        <div class="date-tile"><strong><?php echo e(\Carbon\Carbon::parse($departure->start_date)->format('d')); ?></strong><small><?php echo e(\Carbon\Carbon::parse($departure->start_date)->format('M')); ?></small></div>
                        <div><strong><?php echo e($departure->title); ?></strong><small><?php echo e($departure->lead_guest); ?> · <?php echo e($departure->travelers); ?> guests</small></div>
                        <i data-lucide="chevron-right"></i>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </article>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\dashboard.blade.php ENDPATH**/ ?>