<?php $__env->startSection('title', 'Missing Invoices — ' . $record->reference); ?>
<?php $__env->startSection('content'); ?>
<div class="proposal-toolbar">
    <div><span><?php echo e($record->reference); ?></span><strong>Missing invoices</strong><small><?php echo e($record->client_name); ?></small></div>
    <div><a href="<?php echo e(route('admin.evaluations.show', $record->id)); ?>"><i data-lucide="arrow-left"></i>Back to evaluation</a></div>
</div>
<?php echo $__env->make('admin.partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="ops-panel">
    <div class="ops-panel-title"><h2>Missing Invoice Engine</h2><p>Automatically detected itinerary items without assigned invoices.</p></div>

    <?php $__currentLoopData = ['accommodation', 'activities', 'transport', 'jeep', 'guide', 'supplements', 'park_fees', 'misc']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($missing[$group]['total'] > 0): ?>
        <details class="ops-panel" style="margin-top:0.5rem" open>
            <summary class="ops-panel-title" style="cursor:pointer">
                <div><h3><?php echo e(ucfirst($group)); ?> <span class="ops-pill ops-pill--red"><?php echo e($missing[$group]['total']); ?> missing</span></h3></div>
                <i data-lucide="chevron-down"></i>
            </summary>
            <div class="table-wrap">
                <table class="ops-table">
                    <thead><tr><th>Item</th><th>Type</th><th>Supplier</th><th>Date</th><th>System Rate</th></tr></thead>
                    <tbody>
                    <?php $__currentLoopData = $missing[$group]['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><strong><?php echo e($item->title); ?></strong></td>
                        <td><span class="item-type item-type--<?php echo e($item->item_type); ?>"><?php echo e($item->item_type); ?></span></td>
                        <td><?php echo e($item->supplier ?: 'N/A'); ?></td>
                        <td><?php echo e($item->service_date ? \Carbon\Carbon::parse($item->service_date)->format('d M Y') : '-'); ?></td>
                        <td>$<?php echo e(number_format($item->system_rate, 2)); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody></table>
            </div>
        </details>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <?php if(collect($missing)->sum('total') === 0): ?>
    <div class="empty-cell" style="padding:2rem;text-align:center">
        <i data-lucide="check-circle-2" style="width:2rem;height:2rem;color:var(--success);margin-bottom:0.5rem"></i>
        <p><strong>All invoices have been assigned.</strong></p>
        <p>No missing invoices detected for this proposal.</p>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\evaluations\missing.blade.php ENDPATH**/ ?>