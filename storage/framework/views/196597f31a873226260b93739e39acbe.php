<?php $__env->startSection('title', 'Itinerary List'); ?>

<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginalad67f78bf768badc17b2fc4005a4f8bf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad67f78bf768badc17b2fc4005a4f8bf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.top-bar','data' => ['title' => 'Itineraries','description' => 'Legacy Itineraries','addLabel' => 'New Itinerary','addRoute' => ''.e(route('admin.itineraries.create')).'','searchPlaceholder' => 'Search itineraries...']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.top-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Itineraries','description' => 'Legacy Itineraries','addLabel' => 'New Itinerary','addRoute' => ''.e(route('admin.itineraries.create')).'','searchPlaceholder' => 'Search itineraries...']); ?>
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
        <label class="ops-search"><i data-lucide="search"></i><input name="search" value="<?php echo e(request('search')); ?>" placeholder="Search title, code or country"></label>
        <select name="status"><option value="">All statuses</option><?php $__currentLoopData = ['draft','published','archived']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($status); ?>" <?php if(request('status') === $status): echo 'selected'; endif; ?>><?php echo e(ucfirst($status)); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select>
        <button class="button button-primary">Search</button>
        <a class="button button-secondary" href="<?php echo e(route('admin.itineraries.index')); ?>">Reset</a>
    </form>

    <div class="itinerary-grid itinerary-grid--list">
        <?php $__empty_1 = true; $__currentLoopData = $itineraries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itinerary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <article class="itinerary-card">
                <a class="itinerary-card-image" href="<?php echo e(route('admin.itineraries.show', $itinerary)); ?>">
                    <?php if($itinerary->cover_image): ?>
                        <img src="<?php echo e($itinerary->cover_image_url); ?>" alt="<?php echo e($itinerary->title); ?>" loading="lazy">
                    <?php else: ?>
                        <span><i data-lucide="image-plus"></i>Add cover image</span>
                    <?php endif; ?>
                    <div class="itinerary-image-shade"></div>
                    <span class="itinerary-duration"><i data-lucide="calendar-days"></i><?php echo e($itinerary->duration_days); ?> days · <?php echo e($itinerary->nights); ?> nights</span>
                </a>
                <div class="itinerary-card-body">
                    <div class="itinerary-card-meta"><span><?php echo e($itinerary->code); ?></span><span class="itinerary-status itinerary-status--<?php echo e($itinerary->status); ?>"><i></i><?php echo e(ucfirst($itinerary->status)); ?></span></div>
                    <h2><a href="<?php echo e(route('admin.itineraries.show', $itinerary)); ?>"><?php echo e($itinerary->title); ?></a></h2>
                    <p><?php echo e(\Illuminate\Support\Str::limit($itinerary->summary, 145)); ?></p>
                    <div class="itinerary-card-stats">
                        <span><i data-lucide="map-pin"></i><?php echo e($itinerary->countries); ?></span>
                        <span><i data-lucide="calendar-range"></i><?php echo e($itinerary->days_count); ?> planned days</span>
                        <span><i data-lucide="images"></i><?php echo e($itinerary->images_count); ?> images</span>
                    </div>
                    <div class="itinerary-card-footer">
                        <div class="itinerary-price"><small>Starting from</small><strong><?php echo e($itinerary->currency); ?> <?php echo e(number_format($itinerary->price_from)); ?></strong><span>per person</span></div>
                        <div class="itinerary-card-actions">
                            <a class="itinerary-action itinerary-action--primary" href="<?php echo e(route('admin.itineraries.edit', $itinerary)); ?>"><i data-lucide="square-pen"></i>Edit itinerary</a>
                            <a class="itinerary-action" title="Preview" href="<?php echo e(route('admin.itineraries.show', $itinerary)); ?>"><i data-lucide="eye"></i></a>
                            <a class="itinerary-action" title="Download PDF" href="<?php echo e(route('admin.itineraries.pdf', $itinerary)); ?>"><i data-lucide="file-down"></i></a>
                            <form method="POST" action="<?php echo e(route('admin.itineraries.duplicate', $itinerary)); ?>"><?php echo csrf_field(); ?><button class="itinerary-action" title="Duplicate"><i data-lucide="copy"></i></button></form>
                            <form method="POST" action="<?php echo e(route('admin.itineraries.destroy', $itinerary)); ?>" onsubmit="return confirm('Delete this itinerary and all its days and images?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="itinerary-action itinerary-action--danger" title="Delete"><i data-lucide="trash-2"></i></button></form>
                        </div>
                    </div>
                </div>
            </article>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="ops-empty itinerary-empty"><i data-lucide="map"></i><h2>No itineraries found</h2><p>Create the first detailed safari program and add a cover image and daily plan.</p></div>
        <?php endif; ?>
    </div>
    <div class="ops-pagination"><?php echo e($itineraries->links()); ?></div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\itineraries\index.blade.php ENDPATH**/ ?>