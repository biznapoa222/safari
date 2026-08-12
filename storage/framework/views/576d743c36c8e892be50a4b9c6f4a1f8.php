<?php $__env->startSection('title', $activity->name.' | Activity'); ?>
<?php $__env->startSection('content'); ?>
<div class="page-heading">
    <div>
        <p class="eyebrow"><a href="<?php echo e(route('admin.activities.index')); ?>">Activities</a></p>
        <h1><?php echo e($activity->name); ?></h1>
        <p><?php echo e($activity->category?->name ?? 'Uncategorized'); ?> <?php if($activity->country): ?>/ <?php echo e($activity->country); ?> <?php endif; ?></p>
    </div>
    <div class="heading-actions">
        <a href="<?php echo e(route('admin.activities.edit', $activity->id)); ?>" class="button button-secondary"><i data-lucide="pencil"></i>Edit</a>
        <a href="<?php echo e(route('admin.activities.index')); ?>" class="button button-ghost">Back</a>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 320px;gap:18px;align-items:start;">
    <section class="ops-panel">
        <div class="ops-panel-title"><h2>Details</h2></div>
        <div style="padding:15px;display:grid;gap:14px;">
            <?php if($activity->description): ?><div><strong style="font-size:7px;letter-spacing:1px;text-transform:uppercase;color:#6a7b73;">Description</strong><p style="margin-top:6px;font-size:12px;line-height:1.9;"><?php echo e($activity->description); ?></p></div><?php endif; ?>
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;">
                <?php if($activity->duration_hours): ?><div><strong style="font-size:7px;letter-spacing:1px;text-transform:uppercase;color:#6a7b73;">Duration</strong><p style="margin-top:4px;font-size:11px;"><?php echo e($activity->duration_hours); ?> hours</p></div><?php endif; ?>
                <?php if($activity->min_pax): ?><div><strong style="font-size:7px;letter-spacing:1px;text-transform:uppercase;color:#6a7b73;">Min Pax</strong><p style="margin-top:4px;font-size:11px;"><?php echo e($activity->min_pax); ?></p></div><?php endif; ?>
                <?php if($activity->min_age): ?><div><strong style="font-size:7px;letter-spacing:1px;text-transform:uppercase;color:#6a7b73;">Min Age</strong><p style="margin-top:4px;font-size:11px;"><?php echo e($activity->min_age); ?>+</p></div><?php endif; ?>
                <?php if($activity->location): ?><div><strong style="font-size:7px;letter-spacing:1px;text-transform:uppercase;color:#6a7b73;">Location</strong><p style="margin-top:4px;font-size:11px;"><?php echo e($activity->location); ?></p></div><?php endif; ?>
                <?php if($activity->pickup_time): ?><div><strong style="font-size:7px;letter-spacing:1px;text-transform:uppercase;color:#6a7b73;">Pickup</strong><p style="margin-top:4px;font-size:11px;"><?php echo e($activity->pickup_time); ?></p></div><?php endif; ?>
                <?php if($activity->currency): ?><div><strong style="font-size:7px;letter-spacing:1px;text-transform:uppercase;color:#6a7b73;">Currency</strong><p style="margin-top:4px;font-size:11px;"><?php echo e($activity->currency); ?></p></div><?php endif; ?>
            </div>
        </div>
    </section>
    <aside style="display:grid;gap:14px;">
        <section class="ops-panel">
            <div class="ops-panel-title"><h2>Status</h2></div>
            <div style="padding:15px;display:grid;gap:10px;">
                <div><strong style="font-size:7px;letter-spacing:1px;text-transform:uppercase;color:#6a7b73;">Website</strong><p style="margin-top:4px;"><span class="ops-pill ops-pill--<?php echo e($activity->published_on_website ? 'success' : 'muted'); ?>"><?php echo e($activity->published_on_website ? 'Published' : 'Draft'); ?></span></p></div>
                <div><strong style="font-size:7px;letter-spacing:1px;text-transform:uppercase;color:#6a7b73;">Activity Status</strong><p style="margin-top:4px;font-size:11px;"><?php echo e($activity->activity_status ?? 'Active'); ?></p></div>
            </div>
        </section>
        <?php if($activity->prices->isNotEmpty()): ?>
        <section class="ops-panel">
            <div class="ops-panel-title"><h2>Prices</h2></div>
            <div style="padding:15px;display:grid;gap:8px;">
                <?php $__currentLoopData = $activity->prices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $price): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div style="display:flex;justify-content:space-between;font-size:11px;padding:6px 0;border-bottom:1px solid var(--line);">
                        <span><?php echo e($price->type ?? 'Standard'); ?> <?php if($price->season): ?>(<?php echo e($price->season); ?>)<?php endif; ?></span>
                        <strong>$<?php echo e(number_format((float) $price->price, 2)); ?></strong>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </section>
        <?php endif; ?>
    </aside>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\activities\v2\show.blade.php ENDPATH**/ ?>