<?php $__env->startSection('title', 'Reservations'); ?>
<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginalad67f78bf768badc17b2fc4005a4f8bf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad67f78bf768badc17b2fc4005a4f8bf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.top-bar','data' => ['title' => 'Reservations','description' => 'Operations','addLabel' => 'New Reservation','addRoute' => ''.e(route('admin.reservations.store')).'','searchPlaceholder' => 'Search reservations...']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.top-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Reservations','description' => 'Operations','addLabel' => 'New Reservation','addRoute' => ''.e(route('admin.reservations.store')).'','searchPlaceholder' => 'Search reservations...']); ?>
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
<div class="ops-actions-bar"><a class="button button-secondary" href="<?php echo e(route('admin.evaluations.invoices')); ?>"><i data-lucide="upload-cloud"></i>Supplier invoices</a><a class="button button-secondary" href="<?php echo e(route('admin.evaluations.index')); ?>"><i data-lucide="clipboard-check"></i>Evaluations</a></div>
<?php echo $__env->make('admin.partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<section class="ops-panel">
<form class="ops-filters" method="GET"><select name="status"><option value="">All statuses</option><?php $__currentLoopData = ['pending','requested','confirmed','rejected','cancelled','completed']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($status); ?>" <?php if(request('status') === $status): echo 'selected'; endif; ?>><?php echo e(ucfirst($status)); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select><button class="button button-primary">Filter</button></form>
<div class="table-wrap"><table class="ops-table"><thead><tr><th>Client / quotation</th><th>Reservation</th><th>Schedule</th><th>Assigned</th><th>Actual cost</th><th>Paid</th><th>Deadline</th><th>Status</th><th></th></tr></thead><tbody>
<?php $__empty_1 = true; $__currentLoopData = $reservations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reservation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><tr><td><strong><?php echo e($reservation->client_name); ?></strong><small><a href="<?php echo e(route('admin.quotations.show', $reservation->quotation_id)); ?>"><?php echo e($reservation->quotation_reference); ?> · <?php echo e($reservation->quotation_title); ?></a></small></td><td><?php echo e(ucfirst($reservation->reservation_type)); ?><small><?php echo e($reservation->supplier); ?> · Qty <?php echo e($reservation->quantity); ?></small></td><td><?php echo e(\Carbon\Carbon::parse($reservation->starts_at)->format('d M Y H:i')); ?><small><?php echo e(\Carbon\Carbon::parse($reservation->ends_at)->format('d M Y H:i')); ?></small></td><td><?php echo e($reservation->assigned_person ?: 'Unassigned'); ?><small><?php echo e($reservation->number_plate); ?></small></td><td><?php echo e(number_format($reservation->actual_cost, 2)); ?></td><td><?php echo e(number_format($reservation->paid_amount, 2)); ?></td><td><?php echo e($reservation->payment_deadline ? \Carbon\Carbon::parse($reservation->payment_deadline)->format('d M Y') : '—'); ?></td><td><span class="ops-pill <?php echo e($reservation->status === 'confirmed' ? 'ops-pill--green' : 'ops-pill--blue'); ?>"><?php echo e(ucfirst($reservation->status)); ?></span></td><td><a class="ops-icon-link" href="<?php echo e(route('admin.quotations.show', $reservation->quotation_id).'#reservations'); ?>"><i data-lucide="square-pen"></i></a></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="9" class="empty-cell">No reservations match this filter.</td></tr><?php endif; ?>
</tbody></table></div><div class="ops-pagination"><?php echo e($reservations->links()); ?></div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\reservations\index.blade.php ENDPATH**/ ?>