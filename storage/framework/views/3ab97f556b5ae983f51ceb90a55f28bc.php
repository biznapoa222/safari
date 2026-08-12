<?php $__env->startSection('title', $country ? 'Edit Country' : 'New Country'); ?>
<?php $__env->startSection('content'); ?>
<div class="page-heading">
    <div><p class="eyebrow">Location Management</p><h1><?php echo e($country ? 'Edit: '.$country->name : 'New Country'); ?></h1></div>
</div>
<?php echo $__env->make('admin.partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<form method="POST" action="<?php echo e($country ? route('admin.countries.update', $country) : route('admin.countries.store')); ?>" class="ops-panel" style="max-width:500px;">
    <?php echo csrf_field(); ?> <?php if($country): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
    <div class="ops-form-grid" style="display:flex;flex-direction:column;gap:1rem;">
        <label>Country Code (3 letters)<input name="code" value="<?php echo e(old('code', $country->code ?? '')); ?>" maxlength="3" required></label>
        <label>Country Name<input name="name" value="<?php echo e(old('name', $country->name ?? '')); ?>" required></label>
    </div>
    <div class="ops-form-footer">
        <a href="<?php echo e(route('admin.countries.index')); ?>" class="button button-secondary">Cancel</a>
        <button class="button button-primary"><i data-lucide="save"></i><?php echo e($country ? 'Update' : 'Create'); ?></button>
    </div>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\locations\country-form.blade.php ENDPATH**/ ?>