<?php $__env->startSection('title', 'Quotations'); ?>
<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginalad67f78bf768badc17b2fc4005a4f8bf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad67f78bf768badc17b2fc4005a4f8bf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.top-bar','data' => ['title' => 'Quotations','description' => 'Proposals','addLabel' => 'New Quotation','addRoute' => ''.e(route('admin.quotations.create')).'','searchPlaceholder' => 'Search quotations...']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.top-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Quotations','description' => 'Proposals','addLabel' => 'New Quotation','addRoute' => ''.e(route('admin.quotations.create')).'','searchPlaceholder' => 'Search quotations...']); ?>
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
<section class="ops-panel">
    <form class="ops-filters" method="GET"><label class="ops-search"><i data-lucide="search"></i><input name="search" value="<?php echo e(request('search')); ?>" placeholder="Reference, client or safari"></label><select name="status"><option value="">All statuses</option><?php $__currentLoopData = ['draft','active','sent','accepted','confirmed','in_progress','completed','cancelled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($status); ?>" <?php if(request('status') === $status): echo 'selected'; endif; ?>><?php echo e(ucwords(str_replace('_', ' ', $status))); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select><button class="button button-primary">Filter</button></form>
    <div class="table-wrap"><table class="ops-table"><thead><tr><th>Quotation</th><th>Client</th><th>Trip</th><th>Buy-in</th><th>Selling</th><th>Margin</th><th>Status</th><th>Actions</th></tr></thead><tbody>
    <?php $__empty_1 = true; $__currentLoopData = $quotations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quote): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><tr><td><strong><?php echo e($quote->reference); ?></strong><small><?php echo e($quote->title); ?></small></td><td><?php echo e($quote->client_name); ?></td><td><?php echo e($quote->duration_days); ?> days · <?php echo e($quote->guest_count); ?> guests<small><?php echo e(\Carbon\Carbon::parse($quote->start_date)->format('d M Y')); ?></small></td><td><span class="buy-price"><?php echo e($quote->currency); ?> <?php echo e(number_format($quote->buy_total, 2)); ?></span></td><td><strong><?php echo e($quote->currency); ?> <?php echo e(number_format($quote->sell_total, 2)); ?></strong></td><td><span class="ops-pill <?php echo e($quote->margin_total >= 0 ? 'ops-pill--green' : 'ops-pill--red'); ?>"><?php echo e($quote->currency); ?> <?php echo e(number_format($quote->margin_total, 2)); ?></span></td><td><span class="ops-pill ops-pill--blue"><?php echo e(ucwords(str_replace('_', ' ', $quote->status))); ?></span></td><td><div class="ops-actions"><a href="<?php echo e(route('admin.quotations.show', $quote->id)); ?>"><i data-lucide="square-pen"></i></a><form method="POST" action="<?php echo e(route('admin.quotations.destroy', $quote->id)); ?>" onsubmit="return confirm('Delete quotation?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button><i data-lucide="trash-2"></i></button></form></div></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="8" class="empty-cell">No quotations found.</td></tr><?php endif; ?>
    </tbody></table></div><div class="ops-pagination"><?php echo e($quotations->links()); ?></div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\quotations\index.blade.php ENDPATH**/ ?>