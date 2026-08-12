<?php $__env->startSection('title', 'Executive Dashboard'); ?>
<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginalad67f78bf768badc17b2fc4005a4f8bf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad67f78bf768badc17b2fc4005a4f8bf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.top-bar','data' => ['title' => 'Executive Dashboard','description' => now()->format('l, F j, Y'),'addButton' => false,'search' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.top-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Executive Dashboard','description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(now()->format('l, F j, Y')),'addButton' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'search' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
    <p style="margin:0.25rem 0 0;color:var(--text-muted);font-size:0.85rem;">Real-time KPIs and performance metrics.</p>
     <?php $__env->slot('actions', null, []); ?> 
        <a href="<?php echo e(route('admin.reports.weekly')); ?>" class="button button-secondary button-sm"><i data-lucide="calendar"></i>Weekly Report</a>
        <a href="<?php echo e(route('admin.reports.kpi')); ?>" class="button button-secondary button-sm"><i data-lucide="chart-no-axes-combined"></i>Full KPIs</a>
     <?php $__env->endSlot(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalad67f78bf768badc17b2fc4005a4f8bf)): ?>
<?php $attributes = $__attributesOriginalad67f78bf768badc17b2fc4005a4f8bf; ?>
<?php unset($__attributesOriginalad67f78bf768badc17b2fc4005a4f8bf); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalad67f78bf768badc17b2fc4005a4f8bf)): ?>
<?php $component = $__componentOriginalad67f78bf768badc17b2fc4005a4f8bf; ?>
<?php unset($__componentOriginalad67f78bf768badc17b2fc4005a4f8bf); ?>
<?php endif; ?>


<h3 style="margin-bottom:0.75rem;">Today</h3>
<section class="stats-grid" style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:2rem;">
    <article class="stat-card">
        <div class="stat-icon stat-icon--orange"><i data-lucide="mail-question"></i></div>
        <p>New Leads</p>
        <h2><?php echo e($stats['today_leads']); ?></h2>
    </article>
    <article class="stat-card">
        <div class="stat-icon stat-icon--blue"><i data-lucide="calendar-check"></i></div>
        <p>Bookings</p>
        <h2><?php echo e($stats['today_bookings']); ?></h2>
    </article>
    <article class="stat-card">
        <div class="stat-icon stat-icon--green"><i data-lucide="circle-dollar-sign"></i></div>
        <p>Revenue</p>
        <h2>$<?php echo e(number_format($stats['today_revenue'])); ?></h2>
    </article>
    <article class="stat-card">
        <div class="stat-icon stat-icon--purple"><i data-lucide="trending-up"></i></div>
        <p>Conversion Rate</p>
        <h2><?php echo e($stats['conversion_rate']); ?>%</h2>
    </article>
</section>


<h3 style="margin-bottom:0.75rem;">This Month</h3>
<section class="stats-grid" style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:2rem;">
    <article class="stat-card">
        <div class="stat-icon stat-icon--orange"><i data-lucide="mail-question"></i></div>
        <p>Leads</p><h2><?php echo e($stats['month_leads']); ?></h2>
    </article>
    <article class="stat-card">
        <div class="stat-icon stat-icon--blue"><i data-lucide="files"></i></div>
        <p>Bookings</p><h2><?php echo e($stats['month_bookings']); ?></h2>
    </article>
    <article class="stat-card">
        <div class="stat-icon stat-icon--green"><i data-lucide="circle-dollar-sign"></i></div>
        <p>Revenue (Total)</p><h2>$<?php echo e(number_format($stats['month_revenue'])); ?></h2>
    </article>
    <article class="stat-card">
        <div class="stat-icon stat-icon--purple"><i data-lucide="wallet"></i></div>
        <p>Collected</p><h2>$<?php echo e(number_format($stats['month_collected'])); ?></h2>
    </article>
</section>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;">
    
    <section class="ops-panel">
        <div class="ops-panel-title"><h2>Top Consultants</h2></div>
        <div class="table-wrap">
            <table class="ops-table">
                <thead><tr><th>Name</th><th>Leads Assigned</th><th>Converted</th><th>Conversion Rate</th></tr></thead>
                <tbody>
                    <?php $__currentLoopData = $topConsultants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><strong><?php echo e($c['name']); ?></strong></td>
                        <td><?php echo e($c['leads_assigned']); ?></td>
                        <td><?php echo e($c['leads_converted']); ?></td>
                        <td><?php echo e($c['conversion_rate']); ?>%</td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </section>

    
    <section class="ops-panel">
        <div class="ops-panel-title"><h2>Top Activities</h2></div>
        <div class="table-wrap">
            <table class="ops-table">
                <thead><tr><th>Activity</th><th>Bookings</th></tr></thead>
                <tbody>
                    <?php $__currentLoopData = $topActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr><td><?php echo e($a->name); ?></td><td><?php echo e($a->total); ?></td></tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\dashboard-v2.blade.php ENDPATH**/ ?>