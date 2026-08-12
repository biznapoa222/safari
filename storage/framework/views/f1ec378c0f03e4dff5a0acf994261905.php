<?php $__env->startSection('title', 'Activities & Pricing'); ?>
<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginalad67f78bf768badc17b2fc4005a4f8bf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad67f78bf768badc17b2fc4005a4f8bf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.top-bar','data' => ['title' => 'Activities & Pricing','description' => 'Experience catalogue','addLabel' => 'New Activity','addRoute' => ''.e(route('admin.activities.create')).'','searchPlaceholder' => 'Search activities...']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.top-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Activities & Pricing','description' => 'Experience catalogue','addLabel' => 'New Activity','addRoute' => ''.e(route('admin.activities.create')).'','searchPlaceholder' => 'Search activities...']); ?>
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
<div class="ops-two-column">
    <section class="ops-panel ops-form-panel">
        <div class="ops-panel-title"><div><h2><?php echo e($editing ? 'Edit activity' : 'New activity'); ?></h2><p>Selling price is calculated from buy-in plus markup.</p></div></div>
        <form method="POST" action="<?php echo e($editing ? route('admin.legacy-activities.update', $editing->id) : route('admin.legacy-activities.store')); ?>">
            <?php echo csrf_field(); ?> <?php if($editing): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
            <div class="ops-form-grid">
                <label class="span-2">Activity name<input name="name" value="<?php echo e(old('name', $editing->name ?? '')); ?>" required></label>
                <label>Category<input name="category" value="<?php echo e(old('category', $editing->category ?? 'Hiking')); ?>" required></label>
                <label>Supplier<input name="supplier" value="<?php echo e(old('supplier', $editing->supplier ?? '')); ?>"></label>
                <label>Country<input name="country" value="<?php echo e(old('country', $editing->country ?? 'Kenya')); ?>" required></label>
                <label>Location<input name="location" value="<?php echo e(old('location', $editing->location ?? '')); ?>" required></label>
                <label>Calculation<select name="calculation_type"><?php $__currentLoopData = ['per_person','per_vehicle','per_group']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($option); ?>" <?php if(old('calculation_type', $editing->calculation_type ?? 'per_person') === $option): echo 'selected'; endif; ?>><?php echo e(ucwords(str_replace('_', ' ', $option))); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></label>
                <label>Buy-in rate<input type="number" step="0.01" name="buy_rate" value="<?php echo e(old('buy_rate', $editing->buy_rate ?? '')); ?>" required></label>
                <label>Markup %<input type="number" step="0.01" name="markup_percent" value="<?php echo e(old('markup_percent', $editing->markup_percent ?? 20)); ?>" required></label>
                <label>Currency<input name="currency" maxlength="3" value="<?php echo e(old('currency', $editing->currency ?? 'USD')); ?>" required></label>
                <label>Daily capacity<input type="number" name="daily_capacity" value="<?php echo e(old('daily_capacity', $editing->daily_capacity ?? '')); ?>"></label>
                <label>Duration hours<input type="number" name="duration_hours" min="1" max="24" value="<?php echo e(old('duration_hours', $editing->duration_hours ?? 3)); ?>" required></label>
                <label>Status<select name="status"><option value="active" <?php if(old('status', $editing->status ?? 'active') === 'active'): echo 'selected'; endif; ?>>Active</option><option value="inactive" <?php if(old('status', $editing->status ?? '') === 'inactive'): echo 'selected'; endif; ?>>Inactive</option></select></label>
                <label class="span-2">Cost inclusions and notes<textarea name="notes" rows="4"><?php echo e(old('notes', $editing->notes ?? '')); ?></textarea></label>
            </div>
            <div class="ops-form-footer"><?php if($editing): ?><a class="button button-secondary" href="<?php echo e(route('admin.legacy-activities.index')); ?>">Cancel</a><?php endif; ?><button class="button button-primary"><i data-lucide="save"></i><?php echo e($editing ? 'Update activity' : 'Create activity'); ?></button></div>
        </form>
    </section>
    <section class="ops-panel">
        <form class="ops-filters" method="GET"><label class="ops-search"><i data-lucide="search"></i><input name="search" value="<?php echo e(request('search')); ?>" placeholder="Search activities"></label><button class="button button-primary">Search</button></form>
        <div class="table-wrap"><table class="ops-table"><thead><tr><th>Activity</th><th>Location</th><th>Basis</th><th>Buy-in</th><th>Markup</th><th>Selling</th><th>Capacity</th><th>Actions</th></tr></thead>
        <tbody><?php $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><tr><td><strong><?php echo e($activity->name); ?></strong><small><?php echo e($activity->category); ?> - <?php echo e($activity->supplier); ?></small></td><td><?php echo e($activity->location); ?><small><?php echo e($activity->country); ?></small></td><td><?php echo e(ucwords(str_replace('_', ' ', $activity->calculation_type))); ?></td><td><span class="buy-price"><?php echo e($activity->currency); ?> <?php echo e(number_format($activity->buy_rate, 2)); ?></span></td><td><?php echo e(number_format($activity->markup_percent, 1)); ?>%</td><td><strong class="sell-price"><?php echo e($activity->currency); ?> <?php echo e(number_format($activity->sell_rate, 2)); ?></strong></td><td><?php echo e($activity->daily_capacity ?: 'Unlimited'); ?></td><td><div class="ops-actions"><a href="<?php echo e(route('admin.legacy-activities.index', ['edit' => $activity->id])); ?>"><i data-lucide="square-pen"></i></a><form method="POST" action="<?php echo e(route('admin.legacy-activities.destroy', $activity->id)); ?>" onsubmit="return confirm('Delete this activity?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button><i data-lucide="trash-2"></i></button></form></div></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></tbody></table></div>
        <div class="ops-pagination"><?php echo e($activities->links()); ?></div>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\activities\index.blade.php ENDPATH**/ ?>