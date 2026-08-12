<?php $__env->startSection('title', 'Payment Scheme - '.$activity->name); ?>
<?php $__env->startSection('content'); ?>
<div class="page-heading">
    <div>
        <p class="eyebrow">Activity Payment Scheme</p>
        <h1><?php echo e($activity->name); ?></h1>
    </div>
    <div class="heading-actions">
        <a href="<?php echo e(route('admin.activities.edit', $activity)); ?>" class="button button-secondary">Back to Activity</a>
    </div>
</div>

<?php echo $__env->make('admin.partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<form method="POST" action="<?php echo e(route('admin.activities.payment-scheme.update', $activity)); ?>" class="ops-panel">
    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
    <div class="ops-panel-title"><h2>Payment Scheme Settings</h2></div>
    <div class="ops-form-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;max-width:600px;">
        <label>Deposit Percentage (%)<input type="number" step="0.01" min="0" max="100" name="deposit_percent" value="<?php echo e(old('deposit_percent', $scheme->deposit_percent ?? 50)); ?>" required></label>
        <label class="span-2">Full Payment Rules<textarea name="full_payment_rules" rows="3"><?php echo e(old('full_payment_rules', $scheme->full_payment_rules ?? '')); ?></textarea></label>
        <label class="span-2">Cancellation Rules<textarea name="cancellation_rules" rows="4"><?php echo e(old('cancellation_rules', $scheme->cancellation_rules ?? '')); ?></textarea></label>
        <div class="span-2"><button class="button button-primary">Save Payment Scheme</button></div>
    </div>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\activities\v2\payment-scheme.blade.php ENDPATH**/ ?>