<?php $__env->startSection('title', $itinerary->title.' | Itinerary'); ?>
<?php $__env->startSection('content'); ?>
<div class="page-heading">
    <div>
        <p class="eyebrow"><a href="<?php echo e(route('admin.itinerary-builder.index')); ?>">Itinerary Builder</a></p>
        <h1><?php echo e($itinerary->title); ?></h1>
        <p><?php echo e($itinerary->duration_days); ?> days <?php if($itinerary->country): ?>/ <?php echo e($itinerary->country); ?> <?php endif; ?> <?php if($itinerary->price_from): ?>· From $<?php echo e(number_format((float) $itinerary->price_from)); ?><?php endif; ?></p>
    </div>
    <div class="heading-actions">
        <a href="<?php echo e(route('admin.itinerary-builder.edit', $itinerary->id)); ?>" class="button button-secondary"><i data-lucide="pencil"></i>Edit</a>
        <a href="<?php echo e(route('admin.itinerary-builder.index')); ?>" class="button button-ghost">Back</a>
    </div>
</div>

<section class="ops-panel">
    <div class="ops-panel-title"><h2>Itinerary Days</h2></div>
    <div style="padding:15px;display:grid;gap:10px;">
        <?php $__empty_1 = true; $__currentLoopData = $itinerary->days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div style="display:grid;grid-template-columns:60px 1fr;gap:16px;padding:16px;background:var(--bg-subtle);border:1px solid var(--line);border-radius:8px;">
                <div style="text-align:center;">
                    <div style="font-size:7px;font-weight:800;letter-spacing:1px;text-transform:uppercase;color:#8a7144;">Day</div>
                    <div style="font-family:var(--font-display);font-size:28px;line-height:1;"><?php echo e($day->day_number); ?></div>
                </div>
                <div>
                    <h3 style="margin:0 0 6px;font-size:16px;"><?php echo e($day->title ?? 'Day '.$day->day_number); ?></h3>
                    <?php if($day->location): ?><p style="margin:0 0 6px;display:flex;align-items:center;gap:5px;font-size:10px;color:#8a6430;font-weight:800;"><i data-lucide="map-pin" style="width:12px;"></i><?php echo e($day->location); ?></p><?php endif; ?>
                    <?php if($day->activities): ?><p style="margin:0 0 6px;font-size:12px;line-height:1.8;color:#54635b;"><?php echo e($day->activities); ?></p><?php endif; ?>
                    <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:6px;">
                        <?php if($day->meal_plan): ?><span style="font-size:8px;font-weight:800;color:#6f8a7a;display:flex;align-items:center;gap:4px;"><i data-lucide="utensils-crossed" style="width:11px;"></i><?php echo e($day->meal_plan); ?></span><?php endif; ?>
                        <?php if($day->transfers): ?><span style="font-size:8px;font-weight:800;color:#6f8a7a;display:flex;align-items:center;gap:4px;"><i data-lucide="car" style="width:11px;"></i><?php echo e($day->transfers); ?></span><?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div style="text-align:center;padding:40px;color:#7d8b84;">
                <i data-lucide="map" style="width:32px;margin-bottom:12px;"></i>
                <p>No days added yet. Edit this itinerary to add days.</p>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\itinerary-builder\show.blade.php ENDPATH**/ ?>