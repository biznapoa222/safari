<?php $__empty_1 = true; $__currentLoopData = $safaris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $safari): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <article class="itinerary-row">
        <span><?php echo e($safari->duration_days ?? 'Custom'); ?> days</span>
        <div>
            <h2><a href="<?php echo e(route('public.itineraries.show', $safari->slug)); ?>" class="itinerary-title-link"><?php echo e($safari->title); ?></a></h2>
            <p><?php echo e($safari->summary ?? 'A thoughtfully designed Shishi Footsteps itinerary with day-by-day routing and expert guiding.'); ?></p>
        </div>
        <a href="<?php echo e(route('public.itineraries.show', $safari->slug)); ?>">View itinerary<i data-lucide="arrow-up-right"></i></a>
    </article>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <article class="itinerary-row">
        <span>Custom</span>
        <div><h2>Tailor-made itinerary planning</h2><p>No itineraries match your filter. Tell us what you have in mind and our team will build a route from your wishlist.</p></div>
        <a href="<?php echo e(route('public.booking')); ?>">Start planning<i data-lucide="arrow-up-right"></i></a>
    </article>
<?php endif; ?>
<div class="pagination-wrap"><?php echo e($safaris->links()); ?></div>
<?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\public\partials\_itinerary_rows.blade.php ENDPATH**/ ?>