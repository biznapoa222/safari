<?php $__env->startSection('title', 'Itinerary Builder'); ?>
<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginalad67f78bf768badc17b2fc4005a4f8bf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad67f78bf768badc17b2fc4005a4f8bf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.top-bar','data' => ['title' => 'Itinerary Builder','description' => 'Safari Programs','addLabel' => 'New Itinerary','addRoute' => ''.e(route('admin.itinerary-builder.create')).'','searchPlaceholder' => 'Search itineraries...']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.top-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Itinerary Builder','description' => 'Safari Programs','addLabel' => 'New Itinerary','addRoute' => ''.e(route('admin.itinerary-builder.create')).'','searchPlaceholder' => 'Search itineraries...']); ?>
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
    <label class="ops-search"><i data-lucide="search"></i><input name="search" value="<?php echo e(request('search')); ?>" placeholder="Search itineraries..."></label>
    <button class="button button-primary">Search</button>
</form>
<div class="table-wrap">
    <table class="ops-table">
        <thead><tr><th>Title</th><th>Days</th><th>Country</th><th>Price From</th><th>Published</th><th>Actions</th></tr></thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $itineraries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><strong><?php echo e($i->title); ?></strong></td>
                <td><?php echo e($i->duration_days); ?> days</td>
                <td><?php echo e($i->country ?? '-'); ?></td>
                <td><?php echo e($i->currency); ?> <?php echo e(number_format($i->price_from ?? 0)); ?></td>
                <td><?php if($i->published): ?><i data-lucide="check-circle" class="text-green"><?php else: ?><i data-lucide="x-circle" class="text-red"><?php endif; ?></td>
                <td>
                    <div class="ops-actions">
                        <a href="<?php echo e(route('admin.itinerary-builder.edit', $i)); ?>"><i data-lucide="square-pen"></i></a>
                        <form method="POST" action="<?php echo e(route('admin.itinerary-builder.destroy', $i)); ?>" onsubmit="return confirm('Delete?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button><i data-lucide="trash-2"></i></button></form>
                    </div>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="6" class="text-center text-muted">No itineraries.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<div class="ops-pagination"><?php echo e($itineraries->links()); ?></div>
<style>.text-green { color: #22c55e; width:16px; height:16px; } .text-red { color: #ef4444; width:16px; height:16px; } .ops-actions { display: flex; gap: 0.25rem; } .ops-actions a, .ops-actions button { padding: 0.25rem; border: none; background: none; cursor: pointer; color: var(--text-muted); } .ops-actions a:hover, .ops-actions button:hover { color: var(--primary); }</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\itinerary-builder\index.blade.php ENDPATH**/ ?>