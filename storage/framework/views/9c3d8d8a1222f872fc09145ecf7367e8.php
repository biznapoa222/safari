<?php $__env->startSection('title', 'Duplicate Invoice Detection'); ?>
<?php $__env->startSection('content'); ?>
<div class="ops-page-heading">
    <div><p class="eyebrow">Validation</p><h1>Duplicate Invoice Detection</h1><p>System-wide search for duplicate invoice numbers within the same proposal.</p></div>
    <div class="heading-actions"><a class="button button-secondary" href="<?php echo e(route('admin.evaluations.overview')); ?>"><i data-lucide="arrow-left"></i>Dashboard</a></div>
</div>
<?php echo $__env->make('admin.partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<section class="ops-panel">
    <div class="ops-panel-title"><h2>Potential Duplicates</h2></div>
    <?php $__empty_1 = true; $__currentLoopData = $duplicates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="ops-panel" style="margin-top:0.5rem;border-left:4px solid var(--danger)">
        <div style="display:flex;justify-content:space-between;align-items:center">
            <div>
                <strong><?php echo e($dup->invoice_number); ?></strong> — <?php echo e($dup->company_name); ?>

                <small style="display:block;color:var(--muted)">Duplicate: <?php echo e($dup->duplicate_company); ?> (Invoice #<?php echo e($dup->duplicate_id); ?>)</small>
            </div>
            <span class="ops-pill ops-pill--red">DUPLICATE</span>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="empty-cell" style="padding:2rem;text-align:center">
        <i data-lucide="check-circle-2" style="width:2rem;height:2rem;color:var(--success)"></i>
        <p><strong>No duplicate invoices found.</strong></p>
    </div>
    <?php endif; ?>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\evaluations\duplicates.blade.php ENDPATH**/ ?>