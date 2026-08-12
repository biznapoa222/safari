<?php $__empty_1 = true; $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<?php
    $clientName = trim($request->client_name ?: ($request->client?->name ?? ''));
    $parts = preg_split('/\s+/', $clientName, 2);
    $firstName = $parts[0] ?? 'N/A';
    $lastName = $parts[1] ?? '';
    $company = $request->company ?: 'Tanzania Specialist';
    $value = $request->quote_value ?: $request->budget;
    $language = strtoupper($request->language ?: 'EN');
    $responsible = $request->assignedUser?->name ?: $request->consultant?->name ?: 'NO USER';
    $colors = ['#ef1b1b', '#111111', '#62c7d7', '#12e62b', '#8b31c6', '#ff4aa2'];
    $dotColor = $colors[$loop->index % count($colors)];
?>
<tr>
    <td><?php echo e($loop->iteration + (($requests->currentPage() - 1) * $requests->perPage())); ?></td>
    <td>
        <?php if($request->followups->isNotEmpty()): ?>
            <?php echo e($request->followups->sortBy('followup_date')->first()->followup_date?->format('d-m-Y')); ?>

        <?php endif; ?>
    </td>
    <td><?php echo e($request->arrival_date?->format('d-m-Y')); ?></td>
    <td><?php echo e($request->created_at?->format('d-m-Y')); ?></td>
    <td><?php echo e($firstName); ?></td>
    <td><?php echo e($lastName); ?></td>
    <td><?php echo e($request->status_label ?: 'New'); ?></td>
    <td><?php echo e($value ? (($request->currency ?: '$').number_format($value, 2)) : '$0.00'); ?></td>
    <td>
        <div class="pm-stars star-rating" data-request-id="<?php echo e($request->id); ?>">
            <?php for($i = 1; $i <= 3; $i++): ?>
                <button type="button" data-rating="<?php echo e($i); ?>" class="star <?php echo e($i <= ($request->rating ?? 1) ? 'is-on' : ''); ?>"><i data-lucide="star"></i></button>
            <?php endfor; ?>
        </div>
    </td>
    <td><button type="button" class="pm-flag-button" data-notes-trigger="<?php echo e($request->id); ?>">FLAG THIS</button></td>
    <td><span class="pm-no-pill"><?php echo e($request->is_diamond ? 'Yes' : 'No'); ?></span></td>
    <td><?php echo e(ucwords($request->source ?: 'Manual')); ?></td>
    <td><?php echo e($language); ?></td>
    <td><span><?php echo e($responsible); ?></span><span class="pm-user-dot" style="background:<?php echo e($dotColor); ?>"></span></td>
    <td><?php echo e($company); ?></td>
    <td>
        <details class="pm-row-menu">
            <summary><i data-lucide="menu"></i></summary>
            <div>
                <a href="<?php echo e(route('admin.requests.show', $request->id)); ?>"><i data-lucide="arrow-right"></i> Open Request</a>
                <a href="<?php echo e(route('admin.requests.edit', $request->id)); ?>"><i data-lucide="pencil"></i> Edit request status</a>
                <a href="<?php echo e(route('admin.requests.show', $request->id)); ?>"><i data-lucide="info"></i> View Info</a>
                <a href="<?php echo e(route('admin.requests.show', $request->id)); ?>#timeline"><i data-lucide="clock"></i> View activity log</a>
                <?php if($request->status !== 'converted' && $request->status !== 'cancelled'): ?>
                    <button type="button" data-convert-quote="<?php echo e($request->id); ?>"><i data-lucide="pencil"></i> Force change status</button>
                <?php endif; ?>
            </div>
        </details>
    </td>
</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<tr>
    <td colspan="16" class="empty-cell">No requests found.</td>
</tr>
<?php endif; ?>
<?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views/admin/requests/partials/_table_rows.blade.php ENDPATH**/ ?>