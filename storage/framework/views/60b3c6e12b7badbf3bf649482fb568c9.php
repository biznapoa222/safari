<?php $__env->startSection('title', 'Accommodations'); ?>
<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginalad67f78bf768badc17b2fc4005a4f8bf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad67f78bf768badc17b2fc4005a4f8bf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.top-bar','data' => ['title' => 'Accommodations','description' => 'Accommodation directory','addLabel' => 'New Accommodation','addRoute' => ''.e(route('admin.accommodations-v2.create')).'','searchPlaceholder' => 'Search accommodations...']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.top-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Accommodations','description' => 'Accommodation directory','addLabel' => 'New Accommodation','addRoute' => ''.e(route('admin.accommodations-v2.create')).'','searchPlaceholder' => 'Search accommodations...']); ?>
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
<form class="ops-filters" method="GET">
    <label class="ops-search"><i data-lucide="search"></i><input name="search" value="<?php echo e(request('search')); ?>" placeholder="Search accommodations..."></label>
    <select name="country" onchange="this.form.submit()">
        <option value="">All Countries</option>
        <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($c); ?>" <?php if(request('country') === $c): echo 'selected'; endif; ?>><?php echo e($c); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    <select name="type" onchange="this.form.submit()">
        <option value="">All Types</option>
        <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($k); ?>" <?php if(request('type') === $k): echo 'selected'; endif; ?>><?php echo e($v); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    <button class="button button-primary">Filter</button>
</form>
<div class="table-wrap">
    <table class="ops-table">
        <thead><tr><th>Name</th><th>Type</th><th>Country</th><th>Region</th><th>Level</th><th>Rooms</th><th>Published</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $accommodations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><strong><?php echo e($a->name); ?></strong></td>
                <td><?php echo e(Accommodation::$types[$a->type] ?? $a->type); ?></td>
                <td><?php echo e($a->country); ?></td>
                <td><?php echo e($a->region ?? '-'); ?></td>
                <td><?php echo e($a->luxury_level ? ucwords(str_replace('_', ' ', $a->luxury_level)) : '-'); ?></td>
                <td><?php echo e($a->rooms_count); ?></td>
                <td><?php if($a->published): ?><i data-lucide="check-circle" class="text-green"><?php else: ?><i data-lucide="x-circle" class="text-red"><?php endif; ?></td>
                <td><span class="status status--<?php echo e($a->status); ?>"><?php echo e(ucfirst($a->status)); ?></span></td>
                <td>
                    <div class="ops-actions">
                        <a href="<?php echo e(route('admin.accommodations-v2.edit', $a)); ?>"><i data-lucide="square-pen"></i></a>
                        <form method="POST" action="<?php echo e(route('admin.accommodations-v2.destroy', $a)); ?>" onsubmit="return confirm('Delete?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button><i data-lucide="trash-2"></i></button></form>
                    </div>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="9" class="text-center text-muted">No accommodations found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<div class="ops-pagination"><?php echo e($accommodations->links()); ?></div>
<?php $__env->startPush('styles'); ?>
<style>
.text-green { color: #22c55e; width: 16px; height: 16px; }
.text-red { color: #ef4444; width: 16px; height: 16px; }
.ops-actions { display: flex; gap: 0.25rem; }
.ops-actions a, .ops-actions button { padding: 0.25rem; border: none; background: none; cursor: pointer; color: var(--text-muted); }
.ops-actions a:hover, .ops-actions button:hover { color: var(--primary); }
</style>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\accommodations-v2\index.blade.php ENDPATH**/ ?>