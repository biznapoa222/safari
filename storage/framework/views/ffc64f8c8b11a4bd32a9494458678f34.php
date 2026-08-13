<?php $__env->startSection('title', 'Accommodations'); ?>

<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginalad67f78bf768badc17b2fc4005a4f8bf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad67f78bf768badc17b2fc4005a4f8bf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.top-bar','data' => ['title' => 'Accommodations','description' => 'Accommodation directory','addLabel' => 'New Accommodation','addRoute' => ''.e(route('admin.accommodations.create')).'','searchPlaceholder' => 'Search accommodations...']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.top-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Accommodations','description' => 'Accommodation directory','addLabel' => 'New Accommodation','addRoute' => ''.e(route('admin.accommodations.create')).'','searchPlaceholder' => 'Search accommodations...']); ?>
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

<section class="ops-panel">
    <form class="ops-filters" method="GET">
        <label class="ops-search"><i data-lucide="search"></i><input name="search" value="<?php echo e(request('search')); ?>" placeholder="Search hotel, destination or country"></label>
        <select name="country"><option value="">All countries</option><?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option <?php if(request('country') === $country): echo 'selected'; endif; ?>><?php echo e($country); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select>
        <button class="button button-primary">Search</button>
        <a class="button button-secondary" href="<?php echo e(route('admin.accommodations.index')); ?>">Reset</a>
    </form>
    <div class="country-tabs">
        <a class="<?php echo e(!request('country') ? 'is-active' : ''); ?>" href="<?php echo e(route('admin.accommodations.index')); ?>">All</a>
        <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><a class="<?php echo e(request('country') === $country ? 'is-active' : ''); ?>" href="<?php echo e(route('admin.accommodations.index', ['country' => $country])); ?>"><?php echo e($country); ?></a><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <div class="table-wrap ops-table-wrap">
        <table class="ops-table">
            <thead><tr><th>Accommodation</th><th>Destination</th><th>Translations</th><th>Tier</th><th>Stars</th><th>Rooms</th><th>Rates</th><th>Status</th><th>Website</th><th>Actions</th></tr></thead>
            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $hotels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hotel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><strong><?php echo e($hotel->name); ?></strong><small><?php echo e($hotel->reservation_email); ?></small></td>
                    <td><?php echo e($hotel->destination_name); ?><small><?php echo e($hotel->country); ?></small></td>
                    <td><div class="translation-badges compact"><?php $__currentLoopData = config('safari.languages'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><span class="<?php echo e(in_array($code, $hotel->translations) ? 'complete' : ''); ?>"><?php echo e($lang['badge']); ?></span><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div></td>
                    <td><span class="ops-pill ops-pill--blue"><?php echo e($hotel->tier ? ucfirst($hotel->tier) : 'Standard'); ?></span></td>
                    <td><?php echo e($hotel->star_rating ? $hotel->star_rating.' star' : '—'); ?></td>
                    <td><strong><?php echo e($hotel->room_count); ?></strong><small>room types</small></td>
                    <td><span class="ops-pill <?php echo e($hotel->rate_count ? 'ops-pill--green' : 'ops-pill--red'); ?>"><?php echo e($hotel->rate_count ? 'Complete' : 'Missing'); ?></span></td>
                    <td><span class="ops-pill <?php echo e($hotel->status ? 'ops-pill--green' : 'ops-pill--red'); ?>"><?php echo e($hotel->status ? 'Active' : 'Inactive'); ?></span></td>
                    <td><?php if($hotel->website): ?><a href="<?php echo e($hotel->website); ?>" target="_blank" rel="noopener">Open site</a><?php else: ?> — <?php endif; ?></td>
                    <td><div class="ops-actions"><a title="Edit" href="<?php echo e(route('admin.accommodations.edit', $hotel->id)); ?>"><i data-lucide="square-pen"></i></a><form method="POST" action="<?php echo e(route('admin.accommodations.destroy', $hotel->id)); ?>" onsubmit="return confirm('Delete this accommodation and all its rates?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button title="Delete"><i data-lucide="trash-2"></i></button></form></div></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="10" class="empty-cell">No accommodations match this filter.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="ops-pagination"><?php echo e($hotels->links()); ?></div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\shishifootsteps\safari\resources\views/admin/accommodations/index.blade.php ENDPATH**/ ?>