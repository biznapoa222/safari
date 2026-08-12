<?php $__env->startSection('title', 'Bookings'); ?>
<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginalad67f78bf768badc17b2fc4005a4f8bf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad67f78bf768badc17b2fc4005a4f8bf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.top-bar','data' => ['title' => 'Bookings','description' => 'Bookings Manager','addLabel' => 'New Booking','addRoute' => ''.e(route('admin.bookings.create')).'','searchPlaceholder' => 'Search by reference or client...']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.top-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Bookings','description' => 'Bookings Manager','addLabel' => 'New Booking','addRoute' => ''.e(route('admin.bookings.create')).'','searchPlaceholder' => 'Search by reference or client...']); ?>
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
<form class="ops-filters" method="GET">
    <label class="ops-search"><i data-lucide="search"></i><input name="search" value="<?php echo e(request('search')); ?>" placeholder="Search by reference or client..."></label>
    <select name="status" onchange="this.form.submit()">
        <option value="">All Statuses</option>
        <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($k); ?>" <?php if(request('status') === $k): echo 'selected'; endif; ?>><?php echo e($v); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    <select name="payment_status" onchange="this.form.submit()">
        <option value="">All Payments</option>
        <option value="unpaid" <?php if(request('payment_status') === 'unpaid'): echo 'selected'; endif; ?>>Unpaid</option>
        <option value="partial" <?php if(request('payment_status') === 'partial'): echo 'selected'; endif; ?>>Partial</option>
        <option value="paid" <?php if(request('payment_status') === 'paid'): echo 'selected'; endif; ?>>Paid</option>
    </select>
    <button class="button button-primary">Filter</button>
</form>
<div class="table-wrap">
    <table class="ops-table">
        <thead><tr><th>Reference</th><th>Client</th><th>Status</th><th>Dates</th><th>Guests</th><th>Total</th><th>Paid</th><th>Balance</th><th>Payment</th><th>Consultant</th><th>Created</th><th>Actions</th></tr></thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><strong><?php echo e($b->reference); ?></strong></td>
                <td><?php echo e($b->lead?->name ?? 'N/A'); ?></td>
                <td><span class="status status--<?php echo e($b->status); ?>"><?php echo e($statuses[$b->status] ?? $b->status); ?></span></td>
                <td><small><?php echo e($b->start_date?->format('d/m/Y') ?? '-'); ?> - <?php echo e($b->end_date?->format('d/m/Y') ?? '-'); ?></small></td>
                <td><?php echo e($b->guests); ?></td>
                <td><strong><?php echo e($b->currency); ?> <?php echo e(number_format($b->total_amount, 2)); ?></strong></td>
                <td class="text-green"><?php echo e(number_format($b->amount_paid, 2)); ?></td>
                <td><?php echo e(number_format($b->balance, 2)); ?></td>
                <td><span class="status status--<?php echo e($b->payment_status); ?>"><?php echo e(ucfirst($b->payment_status)); ?></span></td>
                <td><?php echo e($b->consultant?->name ?? '-'); ?></td>
                <td><small><?php echo e($b->created_at->format('d/m/Y')); ?></small></td>
                <td>
                    <div class="ops-actions">
                        <a href="<?php echo e(route('admin.bookings.edit', $b)); ?>"><i data-lucide="square-pen"></i></a>
                        <a href="<?php echo e(route('admin.bookings.show', $b)); ?>"><i data-lucide="eye"></i></a>
                    </div>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="12" class="text-center text-muted">No bookings found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<div class="ops-pagination"><?php echo e($bookings->links()); ?></div>
<style>
.ops-actions { display: flex; gap: 0.25rem; }
.ops-actions a { padding: 0.25rem; color: var(--text-muted); }
.ops-actions a:hover { color: var(--primary); }
.text-green { color: #22c55e; }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\bookings\index.blade.php ENDPATH**/ ?>