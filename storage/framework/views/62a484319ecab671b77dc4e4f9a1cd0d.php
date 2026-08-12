<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #333; line-height: 1.5; }
        .cover-page { text-align: center; padding-top: 40%; }
        .cover-page h1 { font-size: 28px; color: #234A36; font-weight: 800; margin-bottom: 8px; }
        .cover-page h2 { font-size: 18px; color: #C8A96A; margin-bottom: 16px; }
        .cover-page p { font-size: 11px; color: #555; margin: 4px 0; }
        .section-title { font-size: 16px; font-weight: bold; color: #234A36; margin: 24px 0 12px; padding-bottom: 6px; border-bottom: 1px solid #ddd; }
        .day-section { page-break-inside: avoid; margin-bottom: 20px; border-bottom: 1px solid #ddd; padding-bottom: 15px; }
        .day-number { font-size: 14px; font-weight: bold; color: #234A36; margin-bottom: 6px; }
        .label { font-weight: bold; color: #234A36; font-size: 9px; }
        .value { font-size: 9px; color: #555; margin-bottom: 3px; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { padding: 6px 8px; text-align: left; border: 1px solid #ddd; font-size: 9px; }
        th { background: #234A36; color: #fff; font-size: 9px; font-weight: bold; }
        .terms { font-size: 8px; color: #666; margin-top: 30px; }
        .terms h3 { font-size: 10px; color: #234A36; margin-bottom: 4px; margin-top: 12px; }
        .terms p { margin: 2px 0 8px; white-space: pre-wrap; }
        .footer { text-align: center; font-size: 8px; color: #999; margin-top: 40px; padding-top: 15px; border-top: 1px solid #ddd; }
        .page-break { page-break-before: always; }
        .activity-badge { display: inline; padding: 1px 5px; font-size: 8px; background: #ede8df; border-radius: 2px; margin-right: 3px; }
    </style>
</head>
<body>
    
    <div class="cover-page">
        <h1><?php echo e($template->trip_name ?? $template->name); ?></h1>
        <h2>Luxury Safari Proposal</h2>
        <p><strong>Duration:</strong> <?php echo e($template->duration_days); ?> Days</p>
        <p><strong>Category:</strong> <?php echo e($categories[$template->category] ?? $template->category ?? 'Custom'); ?></p>
        <?php if($template->destination): ?><p><strong>Destination:</strong> <?php echo e($template->destination->name); ?></p><?php endif; ?>
    </div>

    
    <div class="page-break"></div>
    <?php if($template->overview): ?>
    <h2 class="section-title">Overview</h2>
    <p style="font-size:9px;color:#555;white-space:pre-wrap"><?php echo e($template->overview); ?></p>
    <?php endif; ?>

    <?php if($template->highlights): ?>
    <h2 class="section-title">Highlights</h2>
    <p style="font-size:9px;color:#555;white-space:pre-wrap"><?php echo e($template->highlights); ?></p>
    <?php endif; ?>

    <?php if($template->includes): ?>
    <h2 class="section-title">Includes</h2>
    <p style="font-size:9px;color:#555;white-space:pre-wrap"><?php echo e($template->includes); ?></p>
    <?php endif; ?>

    <?php if($template->excludes): ?>
    <h2 class="section-title">Excludes</h2>
    <p style="font-size:9px;color:#555;white-space:pre-wrap"><?php echo e($template->excludes); ?></p>
    <?php endif; ?>

    
    <div class="page-break"></div>
    <h2 class="section-title">Your Safari Itinerary</h2>
    <?php $__currentLoopData = $template->days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="day-section">
        <div class="day-number">Day <?php echo e($day->day_number); ?>: <?php echo e($day->title ?? ''); ?></div>
        <?php if($day->destination): ?>
        <p class="value"><span class="label">Destination:</span> <?php echo e($day->destination->name); ?></p>
        <?php endif; ?>
        <?php if($day->hotel || $day->hotel_name): ?>
        <p class="value"><span class="label">Accommodation:</span> <?php echo e($day->hotel->name ?? $day->hotel_name); ?><?php if($day->room_type): ?> (<?php echo e($day->room_type); ?>)<?php endif; ?></p>
        <?php endif; ?>
        <?php if($day->meal_plan): ?>
        <p class="value"><span class="label">Meal Plan:</span> <?php echo e($day->meal_plan); ?></p>
        <?php endif; ?>
        <?php if($day->description): ?>
        <p class="value" style="white-space:pre-wrap;margin-top:4px"><?php echo e($day->description); ?></p>
        <?php endif; ?>
        <?php if($day->morning_activity): ?>
        <p class="value"><span class="label">Morning:</span> <?php echo e($day->morning_activity); ?></p>
        <?php endif; ?>
        <?php if($day->afternoon_activity): ?>
        <p class="value"><span class="label">Afternoon:</span> <?php echo e($day->afternoon_activity); ?></p>
        <?php endif; ?>
        <?php if($day->evening_activity): ?>
        <p class="value"><span class="label">Evening:</span> <?php echo e($day->evening_activity); ?></p>
        <?php endif; ?>
        <?php if($day->included_services): ?>
        <p class="value"><span class="label">Included:</span> <?php echo e($day->included_services); ?></p>
        <?php endif; ?>
        <?php if($day->optional_activities): ?>
        <p class="value"><span class="label">Optional:</span> <?php echo e($day->optional_activities); ?></p>
        <?php endif; ?>
        <?php if($day->activities->count()): ?>
        <p class="value">
            <?php $__currentLoopData = $day->activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $act): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <span class="activity-badge"><?php echo e($act->activity_name ?? $act->activity->name ?? 'Activity'); ?></span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </p>
        <?php endif; ?>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    
    <?php if($template->pricing->count()): ?>
    <div class="page-break"></div>
    <h2 class="section-title">Investment</h2>
    <table>
        <thead>
            <tr>
                <th>Currency</th>
                <th>Per Person</th>
                <th>Single Supplement</th>
                <th>Total Cost</th>
                <?php if($template->pricing->first()->notes): ?><th>Notes</th><?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $template->pricing; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $price): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($price->currency); ?></td>
                <td><?php echo e(number_format($price->price_per_person, 2)); ?></td>
                <td><?php echo e(number_format($price->single_supplement, 2)); ?></td>
                <td><?php echo e(number_format($price->total_cost, 2)); ?></td>
                <?php if($template->pricing->first()->notes): ?><td><?php echo e($price->notes ?? ''); ?></td><?php endif; ?>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <?php endif; ?>

    
    <div class="page-break"></div>
    <h2 class="section-title">Terms & Conditions</h2>
    <div class="terms">
        <?php if($template->booking_terms): ?>
        <h3>Booking Terms</h3>
        <p><?php echo e($template->booking_terms); ?></p>
        <?php endif; ?>
        <?php if($template->cancellation_policy): ?>
        <h3>Cancellation Policy</h3>
        <p><?php echo e($template->cancellation_policy); ?></p>
        <?php endif; ?>
        <?php if($template->payment_schedule): ?>
        <h3>Payment Schedule</h3>
        <p><?php echo e($template->payment_schedule); ?></p>
        <?php endif; ?>
        <?php if($template->refund_policy): ?>
        <h3>Refund Policy</h3>
        <p><?php echo e($template->refund_policy); ?></p>
        <?php endif; ?>
        <?php if($template->important_notes): ?>
        <h3>Important Notes</h3>
        <p><?php echo e($template->important_notes); ?></p>
        <?php endif; ?>
        <?php if($template->terms): ?>
        <h3>Terms</h3>
        <p><?php echo e($template->terms); ?></p>
        <?php endif; ?>
    </div>

    
    <div class="footer">
        <p>Shishi Footsteps · Call or WhatsApp: +254 725 346 022</p>
        <p>info@shishifootsteps.com · bookings@shishifootsteps.com · Nairobi, Kenya</p>
    </div>
</body>
</html>
<?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\pdf\itinerary.blade.php ENDPATH**/ ?>