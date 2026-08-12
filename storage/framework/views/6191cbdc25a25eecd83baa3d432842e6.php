<?php $__env->startSection('title', 'Booking: '.$booking->reference); ?>
<?php $__env->startSection('content'); ?>
<div class="page-heading">
    <div><p class="eyebrow">Booking Details</p><h1><?php echo e($booking->reference); ?></h1><p><?php echo e($booking->lead?->name ?? 'No client'); ?></p></div>
    <div class="heading-actions">
        <a href="<?php echo e(route('admin.bookings.edit', $booking)); ?>" class="button button-primary"><i data-lucide="square-pen"></i>Edit</a>
    </div>
</div>
<?php echo $__env->make('admin.partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<section class="ops-panel">
    <div class="ops-panel-title"><h2>Booking Summary</h2></div>
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;text-align:center;">
        <div><small class="text-muted">Status</small><div><span class="status status--<?php echo e($booking->status); ?>"><?php echo e(\App\Models\Booking::$statuses[$booking->status] ?? $booking->status); ?></span></div></div>
        <div><small class="text-muted">Total</small><h3><?php echo e($booking->currency); ?> <?php echo e(number_format($booking->total_amount,2)); ?></h3></div>
        <div><small class="text-muted">Paid</small><h3 class="text-green"><?php echo e($booking->currency); ?> <?php echo e(number_format($booking->amount_paid,2)); ?></h3></div>
        <div><small class="text-muted">Balance</small><h3 style="color:<?php echo e($booking->balance > 0 ? '#ef4444' : '#22c55e'); ?>"><?php echo e($booking->currency); ?> <?php echo e(number_format($booking->balance,2)); ?></h3></div>
    </div>
</section>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-top:1rem;">
    <section class="ops-panel">
        <div class="ops-panel-title"><h2>Payment History</h2></div>
        <?php $__empty_1 = true; $__currentLoopData = $booking->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div style="display:flex;justify-content:space-between;padding:0.5rem 0;border-bottom:1px solid var(--border);">
            <div><strong><?php echo e($p->reference); ?></strong><br><small><?php echo e(\App\Models\Payment::$methods[$p->method] ?? $p->method); ?> · <?php echo e(ucfirst($p->type)); ?></small></div>
            <div style="text-align:right;"><strong><?php echo e($p->currency); ?> <?php echo e(number_format($p->amount,2)); ?></strong><br><small><?php echo e($p->paid_at?->format('d/m/Y')); ?></small></div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p class="text-muted">No payments recorded.</p>
        <?php endif; ?>
    </section>
    <section class="ops-panel">
        <div class="ops-panel-title"><h2>Payment Links</h2></div>
        <?php $__empty_1 = true; $__currentLoopData = $booking->paymentLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div style="padding:0.5rem 0;border-bottom:1px solid var(--border);">
            <div><strong><?php echo e(ucfirst($pl->type)); ?></strong> · <?php echo e($pl->currency); ?> <?php echo e(number_format($pl->amount,2)); ?></div>
            <small><?php echo e($pl->is_used ? 'Used '.($pl->used_at?->format('d/m/Y H:i') ?? '') : 'Active'); ?></small>
            <br><small><a href="<?php echo e(route('admin.payments.links.show', $pl->token)); ?>" target="_blank"><?php echo e(route('admin.payments.links.show', $pl->token)); ?></a></small>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p class="text-muted">No payment links generated.</p>
        <?php endif; ?>
    </section>
</div>
<style>.text-green { color: #22c55e; }</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\bookings\show.blade.php ENDPATH**/ ?>