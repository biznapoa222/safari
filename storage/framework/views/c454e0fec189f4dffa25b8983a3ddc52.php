<?php $__env->startSection('title', $category ? 'Edit Category' : 'New Category'); ?>
<?php $__env->startSection('content'); ?>
<div class="page-heading">
    <div><p class="eyebrow">Activity Categories</p><h1><?php echo e($category ? 'Edit: '.$category->name : 'New Category'); ?></h1></div>
</div>
<?php echo $__env->make('admin.partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<form method="POST" action="<?php echo e($category ? route('admin.activity-categories.update', $category) : route('admin.activity-categories.store')); ?>" class="ops-panel" style="max-width:500px;">
    <?php echo csrf_field(); ?> <?php if($category): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
    <div class="ops-form-grid" style="display:flex;flex-direction:column;gap:1rem;">
        <label>Name<input name="name" value="<?php echo e(old('name', $category->name ?? '')); ?>" required></label>
        <label>Description<textarea name="description" rows="3"><?php echo e(old('description', $category->description ?? '')); ?></textarea></label>
    </div>
    <div class="ops-form-footer">
        <a href="<?php echo e(route('admin.activity-categories.index')); ?>" class="button button-secondary">Cancel</a>
        <button class="button button-primary"><i data-lucide="save"></i><?php echo e($category ? 'Update' : 'Create'); ?></button>
    </div>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\activity-categories\form.blade.php ENDPATH**/ ?>