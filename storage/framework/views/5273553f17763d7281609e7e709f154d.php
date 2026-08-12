<?php $__env->startSection('title', 'Sales Report'); ?>
<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginalad67f78bf768badc17b2fc4005a4f8bf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad67f78bf768badc17b2fc4005a4f8bf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.top-bar','data' => ['title' => 'Sales Report','description' => 'Reports','addButton' => false,'search' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.top-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Sales Report','description' => 'Reports','addButton' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'search' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
    <section class="ops-panel">
        <div class="ops-panel-title"><h2>Monthly Sales</h2></div>
        <div class="table-wrap">
            <table class="ops-table">
                <thead><tr><th>Month</th><th>Bookings</th><th>Revenue</th><th>Collected</th></tr></thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $salesData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr><td><?php echo e($s->month); ?></td><td><?php echo e($s->total_bookings); ?></td><td><strong>$<?php echo e(number_format($s->revenue,2)); ?></strong></td><td>$<?php echo e(number_format($s->collected,2)); ?></td></tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="4" class="text-muted text-center">No data</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
    <section class="ops-panel">
        <div class="ops-panel-title"><h2>Revenue by Currency</h2></div>
        <div class="table-wrap">
            <table class="ops-table">
                <thead><tr><th>Currency</th><th>Revenue</th></tr></thead>
                <tbody>
                    <?php $__currentLoopData = $byCountry; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr><td><?php echo e($c->currency); ?></td><td><strong><?php echo e(number_format($c->revenue,2)); ?></strong></td></tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
<section class="ops-panel" style="margin-top:1.5rem;">
    <div class="ops-panel-title"><h2>Revenue by Consultant</h2></div>
    <div class="table-wrap">
        <table class="ops-table">
            <thead><tr><th>Consultant</th><th>Revenue</th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $byConsultant; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr><td><?php echo e($c['name']); ?></td><td><strong>$<?php echo e(number_format($c['revenue'],2)); ?></strong></td></tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\reports\sales.blade.php ENDPATH**/ ?>