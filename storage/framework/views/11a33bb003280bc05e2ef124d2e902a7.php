<?php $__env->startSection('title', 'Compare Accommodation Costs'); ?>
<?php $__env->startSection('content'); ?>
<div class="ops-page-heading">
    <div><p class="eyebrow">Rate intelligence</p><h1>Compare accommodation costs</h1><p>Compare room-specific buy-in and selling rates before selecting a hotel for a client.</p></div>
    <a class="button button-secondary" href="<?php echo e(route('admin.accommodations.index')); ?>"><i data-lucide="arrow-left"></i>Accommodations</a>
</div>
<section class="ops-panel">
    <form class="ops-filters" method="GET">
        <select name="location"><option value="">All locations</option><?php $__currentLoopData = $locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option <?php if(request('location') === $location): echo 'selected'; endif; ?>><?php echo e($location); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select>
        <input type="date" name="date" value="<?php echo e(request('date')); ?>">
        <button class="button button-primary">Compare available rates</button>
    </form>
    <div class="table-wrap ops-table-wrap">
        <table class="ops-table comparison-table"><thead><tr><th>Hotel</th><th>Location</th><th>Supplier</th><th>Guests</th><th>Room category</th><th>Season</th><th>Buy-in</th><th>Markup</th><th>Selling</th><th>Notes</th></tr></thead>
        <tbody><?php $__empty_1 = true; $__currentLoopData = $rates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><tr><td><strong><?php echo e($rate->hotel_name); ?></strong></td><td><?php echo e($rate->location); ?><small><?php echo e($rate->country); ?></small></td><td><?php echo e(ucfirst($rate->supplier_type)); ?></td><td><span class="guest-icons"><i data-lucide="users"></i><?php echo e($rate->max_adults + $rate->max_children); ?></span></td><td><?php echo e($rate->room_name); ?><?php if($rate->is_interconnecting): ?><small>Interconnecting</small><?php endif; ?></td><td><?php echo e($rate->season_name); ?><small><?php echo e(\Carbon\Carbon::parse($rate->valid_from)->format('d M')); ?> – <?php echo e(\Carbon\Carbon::parse($rate->valid_to)->format('d M Y')); ?></small></td><td><span class="buy-price"><?php echo e($rate->currency); ?> <?php echo e(number_format($rate->buy_rate, 2)); ?></span></td><td><?php echo e(number_format($rate->markup_percent, 1)); ?>%</td><td><strong class="sell-price"><?php echo e($rate->currency); ?> <?php echo e(number_format($rate->sell_rate, 2)); ?></strong></td><td class="notes-cell"><?php echo e($rate->notes ?: '—'); ?></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="10" class="empty-cell">No rates match the selected date and location.</td></tr><?php endif; ?></tbody></table>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\accommodations\compare.blade.php ENDPATH**/ ?>