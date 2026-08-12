<?php $__env->startSection('title', $itinerary->title); ?>

<?php $__env->startSection('content'); ?>
<div class="itinerary-preview-toolbar">
    <a class="button button-secondary" href="<?php echo e(route('admin.itineraries.index')); ?>"><i data-lucide="arrow-left"></i>Itinerary list</a>
    <div><a class="button button-secondary" href="<?php echo e(route('admin.itineraries.edit', $itinerary)); ?>"><i data-lucide="square-pen"></i>Edit</a><a class="button button-primary" href="<?php echo e(route('admin.itineraries.pdf', $itinerary)); ?>"><i data-lucide="file-down"></i>Download PDF</a></div>
</div>

<article class="itinerary-preview">
    <header class="itinerary-hero <?php echo e($itinerary->cover_image ? '' : 'no-image'); ?>">
        <?php if($itinerary->cover_image): ?><img src="<?php echo e($itinerary->cover_image_url); ?>" alt="<?php echo e($itinerary->title); ?>"><?php endif; ?>
        <div class="itinerary-hero-overlay"></div>
        <div class="itinerary-hero-content"><span><?php echo e($itinerary->countries); ?> · <?php echo e($itinerary->travel_style); ?></span><h1><?php echo e($itinerary->title); ?></h1><p><?php echo e($itinerary->summary); ?></p></div>
    </header>
    <div class="itinerary-facts">
        <div><small>Duration</small><strong><?php echo e($itinerary->duration_days); ?> days / <?php echo e($itinerary->nights); ?> nights</strong></div>
        <div><small>Route</small><strong><?php echo e($itinerary->start_location); ?> to <?php echo e($itinerary->end_location); ?></strong></div>
        <div><small>Best time</small><strong><?php echo e($itinerary->best_time ?: 'Year-round'); ?></strong></div>
        <div><small>From</small><strong><?php echo e($itinerary->currency); ?> <?php echo e(number_format($itinerary->price_from)); ?> per person</strong></div>
    </div>
    <section class="itinerary-introduction"><p class="eyebrow">Your journey</p><h2>An East African story, thoughtfully paced</h2><div><?php echo nl2br(e($itinerary->description ?: $itinerary->summary)); ?></div></section>
    <section class="itinerary-preview-days">
        <div class="preview-section-heading"><p class="eyebrow">Day by day</p><h2>Your safari program</h2></div>
        <?php $__currentLoopData = $itinerary->days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <article class="preview-day">
                <div class="preview-day-number"><span>Day</span><strong><?php echo e(str_pad($day->day_number, 2, '0', STR_PAD_LEFT)); ?></strong></div>
                <div class="preview-day-content">
                    <span><?php echo e($day->location); ?></span><h3><?php echo e($day->title); ?></h3>
                    <?php if($day->primary_image): ?><img class="preview-day-primary" src="<?php echo e($day->primary_image_url); ?>" alt="<?php echo e($day->title); ?>" loading="lazy"><?php endif; ?>
                    <p class="day-lead"><?php echo e($day->summary); ?></p>
                    <div class="day-description"><?php echo nl2br(e($day->description)); ?></div>
                    <?php $dayActivities = is_string($day->activities) ? json_decode($day->activities, true) ?: [] : ($day->activities ?: []); ?>
                    <?php if($dayActivities): ?><div class="day-activities"><?php $__currentLoopData = $dayActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><span><i data-lucide="check"></i><?php echo e($activity); ?></span><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div><?php endif; ?>
                    <div class="day-logistics">
                        <?php if($day->accommodation): ?><span><i data-lucide="bed-double"></i><b>Stay</b><?php echo e($day->accommodation); ?></span><?php endif; ?>
                        <?php if($day->meal_plan): ?><span><i data-lucide="utensils"></i><b>Meals</b><?php echo e($day->meal_plan); ?></span><?php endif; ?>
                        <?php if($day->distance_km): ?><span><i data-lucide="car-front"></i><b>Journey</b><?php echo e($day->distance_km); ?> km · <?php echo e(number_format($day->driving_hours, 1)); ?> hrs</span><?php endif; ?>
                    </div>
                    <?php if($day->images->isNotEmpty()): ?><div class="preview-day-gallery"><?php $__currentLoopData = $day->images->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><img src="<?php echo e($image->url); ?>" alt="<?php echo e($image->alt_text); ?>" loading="lazy"><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div><?php endif; ?>
                </div>
            </article>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </section>
    <section class="itinerary-inclusions">
        <div><p class="eyebrow">Included</p><h2>What is covered</h2><?php $__currentLoopData = $itinerary->inclusions ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><p><i data-lucide="check-circle-2"></i><?php echo e($item); ?></p><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div>
        <div><p class="eyebrow">Not included</p><h2>Plan separately</h2><?php $__currentLoopData = $itinerary->exclusions ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><p><i data-lucide="x-circle"></i><?php echo e($item); ?></p><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div>
    </section>
    <?php if($itinerary->important_notes): ?><section class="itinerary-notes"><h2>Important journey notes</h2><p><?php echo e($itinerary->important_notes); ?></p></section><?php endif; ?>
</article>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\itineraries\show.blade.php ENDPATH**/ ?>