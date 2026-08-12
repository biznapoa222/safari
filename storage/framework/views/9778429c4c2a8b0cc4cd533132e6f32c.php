<?php $__env->startSection('title', 'Evaluations'); ?>
<?php $__env->startSection('content'); ?>
<div class="ops-page-heading">
    <div>
        <p class="eyebrow">Confirmed proposals</p>
        <h1>Evaluations</h1>
        <p>Verify supplier invoices against the confirmed itinerary before finance pays them.</p>
    </div>
    <div class="heading-actions">
        <a class="button button-secondary" href="<?php echo e(route('admin.evaluations.invoices')); ?>"><i data-lucide="files"></i>Reservation invoices</a>
    </div>
</div>
<?php echo $__env->make('admin.partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<section class="evaluation-guide">
    <?php $__currentLoopData = [
        ['files', '1. Verify invoices', 'Confirm every supplier document has been uploaded.'],
        ['list-checks', '2. Match itinerary', 'Check rates, dates, meal plans and room details.'],
        ['link-2', '3. Assign invoices', 'Attach each invoice to its correct confirmed service.'],
        ['landmark', '4. Finance handoff', 'Approve deadlines and send payable invoices to accounts.'],
    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$icon, $title, $copy]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <article><i data-lucide="<?php echo e($icon); ?>"></i><span><strong><?php echo e($title); ?></strong><small><?php echo e($copy); ?></small></span></article>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</section>

<section class="ops-panel">
    <form class="ops-filters" method="GET">
        <label class="ops-search"><i data-lucide="search"></i><input name="search" value="<?php echo e(request('search')); ?>" placeholder="Proposal, client or reference"></label>
        <select name="status">
            <option value="">All evaluation states</option>
            <?php $__currentLoopData = ['pending', 'in_progress', 'approved']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($status); ?>" <?php if(request('status') === $status): echo 'selected'; endif; ?>><?php echo e(ucwords(str_replace('_', ' ', $status))); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <button class="button button-primary">Filter</button>
    </form>
    <div class="table-wrap">
        <table class="ops-table evaluation-queue-table">
            <thead><tr><th>Proposal / client</th><th>Travel</th><th>Supplier invoices</th><th>Verification progress</th><th>Evaluation</th><th></th></tr></thead>
            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $evaluations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php $progress = $item->entry_count > 0 ? round(($item->matched_count / $item->entry_count) * 100) : 0; ?>
                <tr>
                    <td><strong><?php echo e($item->reference); ?> - <?php echo e($item->title); ?></strong><small><?php echo e($item->client_name); ?></small></td>
                    <td><?php echo e(\Carbon\Carbon::parse($item->start_date)->format('d M Y')); ?><small><?php echo e($item->duration_days); ?> days - <?php echo e($item->guest_count); ?> guests</small></td>
                    <td><strong><?php echo e($item->invoice_count); ?></strong><small>documents received</small></td>
                    <td>
                        <div class="evaluation-progress"><span style="width: <?php echo e($progress); ?>%"></span></div>
                        <small><?php echo e($item->matched_count); ?> of <?php echo e($item->entry_count); ?> services matched</small>
                    </td>
                    <td><span class="ops-pill <?php echo e($item->evaluation_status === 'approved' ? 'ops-pill--green' : 'ops-pill--blue'); ?>"><?php echo e(ucwords(str_replace('_', ' ', $item->evaluation_status))); ?></span></td>
                    <td><a class="button button-secondary button-compact" href="<?php echo e(route('admin.evaluations.show', $item->id)); ?>">Open evaluation<i data-lucide="arrow-right"></i></a></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="empty-cell">No confirmed proposals are waiting for evaluation.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="ops-pagination"><?php echo e($evaluations->links()); ?></div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\evaluations\index.blade.php ENDPATH**/ ?>