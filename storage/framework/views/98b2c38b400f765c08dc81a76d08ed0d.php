<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
@page { margin: 28px 34px 42px; }
* { box-sizing: border-box; }
body { margin: 0; color: #283a33; font-family: DejaVu Sans, sans-serif; font-size: 9px; line-height: 1.55; }
.cover { position: relative; height: 690px; margin: -28px -34px 0; color: white; background: #173e32; page-break-after: always; overflow: hidden; }
.cover img { width: 100%; height: 690px; object-fit: cover; opacity: .62; }
.cover-copy { position: absolute; left: 45px; right: 45px; bottom: 62px; }
.kicker { color: #d9bb6b; font-size: 9px; font-weight: bold; letter-spacing: 1.5px; text-transform: uppercase; }
h1 { margin: 12px 0 14px; font-family: DejaVu Serif, serif; font-size: 38px; line-height: 1.12; }
.cover p { width: 82%; margin: 0; font-size: 12px; line-height: 1.7; }
.facts { width: 100%; margin: 0 0 25px; border-collapse: collapse; background: #f2f5ef; }
.facts td { width: 25%; padding: 13px; border-right: 1px solid #dce4dc; vertical-align: top; }
.facts small, .meta small { display: block; color: #87938e; font-size: 7px; text-transform: uppercase; }
.facts strong { display: block; margin-top: 4px; font-size: 9px; }
h2 { margin: 0 0 8px; color: #173e32; font-family: DejaVu Serif, serif; font-size: 21px; }
.intro { margin: 0 0 28px; }
.intro p { font-size: 10px; }
.day { margin: 0 0 22px; padding-bottom: 20px; border-bottom: 1px solid #dce3df; page-break-inside: avoid; }
.day-number { width: 58px; padding-top: 3px; float: left; color: #9a7434; font-size: 8px; text-transform: uppercase; }
.day-number b { display: block; color: #173e32; font-family: DejaVu Serif, serif; font-size: 27px; }
.day-body { margin-left: 68px; }
.day-body > span { color: #9a7434; font-size: 7px; font-weight: bold; letter-spacing: 1px; text-transform: uppercase; }
.day h3 { margin: 4px 0 8px; color: #173e32; font-family: DejaVu Serif, serif; font-size: 17px; }
.day-image { width: 100%; height: 220px; margin: 4px 0 11px; object-fit: cover; }
.lead { font-weight: bold; }
.activities { margin: 9px 0; padding: 8px 11px; background: #f2f5ef; }
.activities span { display: block; }
.meta { width: 100%; margin-top: 10px; border-collapse: collapse; }
.meta td { padding: 7px 8px; border: 1px solid #dce3df; vertical-align: top; }
.clear { clear: both; }
.two-columns { width: 100%; margin-top: 20px; border-collapse: collapse; page-break-inside: avoid; }
.two-columns td { width: 50%; padding: 16px; vertical-align: top; border: 1px solid #dce3df; }
.two-columns p { margin: 5px 0; }
.notes { margin-top: 18px; padding: 14px; color: white; background: #173e32; page-break-inside: avoid; }
.footer { position: fixed; left: 0; right: 0; bottom: -27px; color: #87938e; font-size: 7px; text-align: center; }
</style>
</head>
<body>
<div class="footer">Shishi Footsteps · <?php echo e($itinerary->code); ?> · Tailor-made East Africa journeys</div>
<section class="cover">
    <?php if($cover = $imageData($itinerary->cover_image)): ?><img src="<?php echo e($cover); ?>"><?php endif; ?>
    <div class="cover-copy"><div class="kicker"><?php echo e($itinerary->countries); ?> · <?php echo e($itinerary->duration_days); ?> days</div><h1><?php echo e($itinerary->title); ?></h1><p><?php echo e($itinerary->summary); ?></p></div>
</section>
<table class="facts"><tr>
    <td><small>Duration</small><strong><?php echo e($itinerary->duration_days); ?> days / <?php echo e($itinerary->nights); ?> nights</strong></td>
    <td><small>Route</small><strong><?php echo e($itinerary->start_location); ?> to <?php echo e($itinerary->end_location); ?></strong></td>
    <td><small>Best time</small><strong><?php echo e($itinerary->best_time ?: 'Year-round'); ?></strong></td>
    <td><small>Price from</small><strong><?php echo e($itinerary->currency); ?> <?php echo e(number_format($itinerary->price_from)); ?> per person</strong></td>
</tr></table>
<section class="intro"><div class="kicker">Your journey</div><h2><?php echo e($itinerary->title); ?></h2><p><?php echo nl2br(e($itinerary->description ?: $itinerary->summary)); ?></p></section>
<?php $__currentLoopData = $itinerary->days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<section class="day">
    <div class="day-number">Day<b><?php echo e(str_pad($day->day_number, 2, '0', STR_PAD_LEFT)); ?></b></div>
    <div class="day-body">
        <span><?php echo e($day->location); ?></span><h3><?php echo e($day->title); ?></h3>
        <?php if($src = $imageData($day->primary_image)): ?><img class="day-image" src="<?php echo e($src); ?>"><?php endif; ?>
        <?php if($day->summary): ?><p class="lead"><?php echo e($day->summary); ?></p><?php endif; ?>
        <p><?php echo nl2br(e($day->description)); ?></p>
        <?php if($day->activities): ?><div class="activities"><?php $__currentLoopData = $day->activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><span>✓ <?php echo e($activity); ?></span><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div><?php endif; ?>
        <table class="meta"><tr>
            <td><small>Accommodation</small><?php echo e($day->accommodation ?: 'To be advised'); ?></td>
            <td><small>Meals</small><?php echo e($day->meal_plan ?: 'As indicated'); ?></td>
            <td><small>Journey</small><?php echo e($day->distance_km ? $day->distance_km.' km · '.number_format($day->driving_hours, 1).' hrs' : 'At leisure'); ?></td>
        </tr></table>
    </div><div class="clear"></div>
</section>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<table class="two-columns"><tr>
    <td><div class="kicker">Included</div><h2>What is covered</h2><?php $__currentLoopData = $itinerary->inclusions ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><p>✓ <?php echo e($item); ?></p><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></td>
    <td><div class="kicker">Not included</div><h2>Plan separately</h2><?php $__currentLoopData = $itinerary->exclusions ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><p>× <?php echo e($item); ?></p><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></td>
</tr></table>
<?php if($itinerary->important_notes): ?><div class="notes"><strong>Important journey notes</strong><br><?php echo e($itinerary->important_notes); ?></div><?php endif; ?>
</body>
</html>
<?php /**PATH C:\shishifootsteps\safari\resources\views/admin/itineraries/pdf.blade.php ENDPATH**/ ?>