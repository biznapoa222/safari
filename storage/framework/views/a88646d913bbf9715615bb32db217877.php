<?php $__env->startSection('title', $activity->name); ?>
<?php $__env->startSection('content'); ?>
<section style="padding:4rem 2rem;max-width:900px;margin:0 auto;">
    <a href="<?php echo e(route('admin.activities.edit', $activity)); ?>" class="button button-secondary">&larr; Back to Edit</a>
    <h1 style="font-size:2.5rem;margin:1rem 0 0.5rem;"><?php echo e($activity->translations->where('locale', 'en')->first()?->title ?? $activity->name); ?></h1>
    <p style="color:var(--text-muted);margin-bottom:2rem;"><?php echo e($activity->location); ?>, <?php echo e($activity->country); ?></p>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:2rem;">
        <div><strong>Duration:</strong> <?php echo e($activity->duration_hours ? $activity->duration_hours.' hours' : 'N/A'); ?></div>
        <div><strong>Min Pax:</strong> <?php echo e($activity->min_pax ?? 'N/A'); ?></div>
        <div><strong>Min Age:</strong> <?php echo e($activity->min_age ?? 'N/A'); ?></div>
        <div><strong>Pickup Time:</strong> <?php echo e($activity->pickup_time ?? 'N/A'); ?></div>
        <div><strong>Category:</strong> <?php echo e($activity->category?->name ?? 'N/A'); ?></div>
        <div><strong>Currency:</strong> <?php echo e($activity->currency); ?></div>
    </div>

    <?php if($activity->description): ?>
        <div style="margin-bottom:2rem;">
            <h3>Description</h3>
            <p><?php echo e($activity->description); ?></p>
        </div>
    <?php endif; ?>

    <?php if($activity->prices->count()): ?>
        <h3>Pricing</h3>
        <div class="table-wrap">
            <table class="ops-table">
                <thead><tr><th>Type</th><th>Season</th><th>Year</th><th>Price</th></tr></thead>
                <tbody>
                    <?php $__currentLoopData = $activity->prices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $price): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e(ucwords(str_replace('_', ' ', $price->type))); ?></td>
                            <td><?php echo e(ucfirst($price->season)); ?></td>
                            <td><?php echo e($price->year); ?></td>
                            <td><strong><?php echo e($price->currency); ?> <?php echo e(number_format($price->price, 2)); ?></strong></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    
    <?php
        $priceByType = $activity->prices->groupBy('type');
    ?>
    <?php if($priceByType->count()): ?>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1rem;margin-top:2rem;">
            <?php $__currentLoopData = $priceByType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $prices): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div style="border:1px solid var(--border);padding:1rem;border-radius:0.5rem;">
                    <h4><?php echo e(ucwords(str_replace('_', ' ', $type))); ?></h4>
                    <?php $__currentLoopData = $prices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div><small><?php echo e(ucfirst($p->season)); ?> <?php echo e($p->year); ?></small><br><strong><?php echo e($p->currency); ?> <?php echo e(number_format($p->price, 2)); ?></strong></div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\activities\v2\preview.blade.php ENDPATH**/ ?>