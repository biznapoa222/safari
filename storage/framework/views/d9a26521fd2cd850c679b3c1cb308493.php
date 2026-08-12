<?php $__env->startSection('title', 'Activity Categories'); ?>
<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginalad67f78bf768badc17b2fc4005a4f8bf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad67f78bf768badc17b2fc4005a4f8bf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.top-bar','data' => ['title' => 'Activity Categories','description' => 'Activities','addLabel' => 'New Category','addRoute' => ''.e(route('admin.activity-categories.create')).'','search' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.top-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Activity Categories','description' => 'Activities','addLabel' => 'New Category','addRoute' => ''.e(route('admin.activity-categories.create')).'','search' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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
<?php echo $__env->make('admin.partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<div class="table-wrap">
    <table class="ops-table">
        <thead><tr><th>Name</th><th>Slug</th><th>Activities</th><th>Actions</th></tr></thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><strong><?php echo e($cat->name); ?></strong></td>
                <td><?php echo e($cat->slug); ?></td>
                <td><?php echo e($cat->activities->count()); ?></td>
                <td>
                    <a href="<?php echo e(route('admin.activity-categories.edit', $cat)); ?>" class="button button-sm button-secondary">Edit</a>
                    <form method="POST" action="<?php echo e(route('admin.activity-categories.destroy', $cat)); ?>" style="display:inline;" onsubmit="return confirm('Delete?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="button button-sm button-danger">Delete</button></form>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="4" class="text-center text-muted">No categories.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<div class="ops-pagination"><?php echo e($categories->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\activity-categories\index.blade.php ENDPATH**/ ?>