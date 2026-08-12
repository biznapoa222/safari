<?php $__env->startSection('title', 'Evaluation Audit Log'); ?>
<?php $__env->startSection('content'); ?>
<div class="ops-page-heading">
    <div><p class="eyebrow">Audit trail</p><h1>Evaluation Audit Log</h1></div>
    <div class="heading-actions"><a class="button button-secondary" href="<?php echo e(route('admin.evaluations.show', $quotation)); ?>"><i data-lucide="arrow-left"></i>Back</a></div>
</div>
<?php echo $__env->make('admin.partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<section class="ops-panel">
    <div class="table-wrap">
        <table class="ops-table">
            <thead><tr><th>Time</th><th>User</th><th>Action</th><th>Description</th><th>Changes</th></tr></thead>
            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><small><?php echo e(\Carbon\Carbon::parse($log->created_at)->format('d M Y H:i')); ?></small></td>
                <td><?php echo e($log->user_name ?: 'System'); ?></td>
                <td><span class="ops-pill"><?php echo e(ucwords(str_replace('_', ' ', $log->action))); ?></span></td>
                <td><?php echo e($log->description); ?></td>
                <td>
                    <?php if($log->old_values || $log->new_values): ?>
                    <details style="font-size:0.8rem">
                        <summary>View changes</summary>
                        <pre style="background:var(--surface);padding:0.5rem;border-radius:4px;margin-top:0.25rem;max-height:100px;overflow:auto"><?php if($log->old_values): ?>Old: <?php echo e(json_encode(json_decode($log->old_values), JSON_PRETTY_PRINT)); ?><?php endif; ?>
<?php if($log->new_values): ?>New: <?php echo e(json_encode(json_decode($log->new_values), JSON_PRETTY_PRINT)); ?><?php endif; ?></pre>
                    </details>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="5" class="empty-cell">No audit log entries for this evaluation.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="ops-pagination"><?php echo e($logs->links()); ?></div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\evaluations\audit.blade.php ENDPATH**/ ?>