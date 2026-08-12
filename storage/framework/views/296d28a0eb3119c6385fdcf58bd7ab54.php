<?php $__empty_1 = true; $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<tr>
    <td>
        <a href="<?php echo e(route('admin.itinerary-templates.show', $template)); ?>" style="color:var(--primary);text-decoration:none;font-weight:600;font-size:9px">
            <?php echo e($template->name); ?>

        </a>
        <?php if($template->trip_name): ?>
            <small style="display:block;color:var(--text-muted);font-size:8px"><?php echo e($template->trip_name); ?></small>
        <?php endif; ?>
    </td>
    <td style="font-size:9px;color:var(--text)"><?php echo e($template->destination->name ?? '—'); ?></td>
    <td style="font-size:9px;color:var(--text)"><?php echo e($template->duration_days); ?> days</td>
    <td>
        <?php if($template->category): ?>
        <span style="display:inline-flex;padding:3px 7px;border-radius:4px;font-size:7px;font-weight:700;background:#ede8df;color:#3a3530">
            <?php echo e(\App\Models\ItineraryTemplate::categories()[$template->category] ?? $template->category); ?>

        </span>
        <?php else: ?>
        <span style="color:var(--text-muted);font-size:9px">—</span>
        <?php endif; ?>
    </td>
    <td>
        <?php if($template->status === 'active'): ?>
        <span style="display:inline-flex;padding:3px 7px;border-radius:4px;font-size:7px;font-weight:700;color:#16a34a;background:#f0fdf4">Active</span>
        <?php elseif($template->status === 'inactive'): ?>
        <span style="display:inline-flex;padding:3px 7px;border-radius:4px;font-size:7px;font-weight:700;color:#dc2626;background:#fef2f2">Inactive</span>
        <?php else: ?>
        <span style="display:inline-flex;padding:3px 7px;border-radius:4px;font-size:7px;font-weight:700;color:#6b7280;background:#f3f4f6">Archived</span>
        <?php endif; ?>
    </td>
    <td style="font-size:9px;color:var(--text)"><?php echo e($template->days_count); ?></td>
    <td>
        <div style="display:flex;gap:6px">
            <a href="<?php echo e(route('admin.itinerary-templates.show', $template)); ?>" class="icon-button" title="View">
                <i data-lucide="eye" style="width:13px;height:13px"></i>
            </a>
            <a href="<?php echo e(route('admin.itinerary-templates.edit', $template)); ?>" class="icon-button" title="Edit">
                <i data-lucide="square-pen" style="width:13px;height:13px"></i>
            </a>
            <form method="POST" action="<?php echo e(route('admin.itinerary-templates.duplicate', $template)); ?>" style="display:inline" onsubmit="return confirm('Duplicate this template?')">
                <?php echo csrf_field(); ?>
                <button type="submit" class="icon-button" title="Duplicate">
                    <i data-lucide="copy" style="width:13px;height:13px"></i>
                </button>
            </form>
            <form method="POST" action="<?php echo e(route('admin.itinerary-templates.destroy', $template)); ?>" style="display:inline" onsubmit="return confirm('Delete this template?')">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button type="submit" class="icon-button" title="Delete">
                    <i data-lucide="trash-2" style="width:13px;height:13px"></i>
                </button>
            </form>
        </div>
    </td>
</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<tr>
    <td colspan="7" style="text-align:center;padding:32px 16px;color:var(--text-muted);font-size:9px">No itinerary templates match the current filters.</td>
</tr>
<?php endif; ?>
<?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\itinerary-templates\partials\_table_rows.blade.php ENDPATH**/ ?>