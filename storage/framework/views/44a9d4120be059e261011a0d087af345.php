<?php $__env->startSection('title', 'Booking Report'); ?>
<?php $__env->startSection('content'); ?>

<?php if (isset($component)) { $__componentOriginalad67f78bf768badc17b2fc4005a4f8bf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad67f78bf768badc17b2fc4005a4f8bf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.top-bar','data' => ['title' => 'Booking Report','description' => 'Reports','addButton' => false,'search' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.top-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Booking Report','description' => 'Reports','addButton' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'search' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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

<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem;">
    <div class="ops-panel" style="text-align:center;padding:1rem;">
        <small class="text-muted">Total</small><h3><?php echo e($summary['total']); ?></h3>
    </div>
    <div class="ops-panel" style="text-align:center;padding:1rem;">
        <small class="text-muted">Confirmed</small><h3 class="text-green"><?php echo e($summary['confirmed']); ?></h3>
    </div>
    <div class="ops-panel" style="text-align:center;padding:1rem;">
        <small class="text-muted">Pending</small><h3 style="color:#f59e0b;"><?php echo e($summary['pending']); ?></h3>
    </div>
    <div class="ops-panel" style="text-align:center;padding:1rem;">
        <small class="text-muted">Cancelled</small><h3 style="color:#ef4444;"><?php echo e($summary['cancelled']); ?></h3>
    </div>
</div>

<section class="ops-panel">
    <div class="ops-panel-title"><h2>All Bookings</h2></div>
    <div class="table-wrap">
        <table class="ops-table">
            <thead><tr><th>Reference</th><th>Client</th><th>Status</th><th>Total</th><th>Paid</th><th>Date</th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><strong><?php echo e($b->reference); ?></strong></td>
                    <td><?php echo e($b->lead?->name ?? 'N/A'); ?></td>
                    <td><span class="status status--<?php echo e($b->status); ?>"><?php echo e(\App\Models\Booking::$statuses[$b->status] ?? $b->status); ?></span></td>
                    <td><?php echo e($b->currency); ?> <?php echo e(number_format($b->total_amount, 2)); ?></td>
                    <td><?php echo e(number_format($b->amount_paid, 2)); ?></td>
                    <td><?php echo e($b->created_at->format('d/m/Y')); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="text-center text-muted">No bookings found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="ops-pagination"><?php echo e($bookings->links()); ?></div>
</section>

<style>.text-green { color: #22c55e; }</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\reports\bookings.blade.php ENDPATH**/ ?>