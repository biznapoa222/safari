<?php $__env->startSection('title', 'Conversations | '.$lead->name); ?>
<?php $__env->startSection('content'); ?>
<div class="page-heading">
    <div>
        <p class="eyebrow"><a href="<?php echo e(route('admin.leads.index')); ?>">Leads</a> / <a href="<?php echo e(route('admin.leads.show', $lead->id)); ?>"><?php echo e($lead->name); ?></a></p>
        <h1>Conversations</h1>
        <p><?php echo e($lead->name); ?> · <?php echo e($lead->email); ?></p>
    </div>
    <div class="heading-actions">
        <a href="<?php echo e(route('admin.leads.show', $lead->id)); ?>" class="button button-ghost"><i data-lucide="arrow-left"></i>Back to lead</a>
    </div>
</div>

<section class="ops-panel">
    <div class="ops-panel-title"><h2>Conversation History</h2></div>
    <div style="padding:15px;display:grid;gap:12px;">
        <?php $__empty_1 = true; $__currentLoopData = $lead->conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conversation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div style="padding:14px;background:var(--bg-subtle);border:1px solid var(--line);border-radius:8px;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                    <strong style="font-size:11px;"><?php echo e($conversation->user?->name ?? 'System'); ?></strong>
                    <small style="color:#7d8b84;font-size:9px;"><?php echo e($conversation->created_at->format('M d, Y H:i')); ?></small>
                </div>
                <p style="font-size:12px;line-height:1.8;margin:0;"><?php echo e($conversation->message); ?></p>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div style="text-align:center;padding:40px;color:#7d8b84;">
                <i data-lucide="message-circle" style="width:32px;margin-bottom:12px;"></i>
                <p>No conversations recorded yet.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="ops-panel" style="margin-top:18px;">
    <div class="ops-panel-title"><h2>Add Note</h2></div>
    <form method="POST" action="<?php echo e(route('admin.leads.conversations.store', $lead->id)); ?>" style="padding:15px;">
        <?php echo csrf_field(); ?>
        <label style="display:block;margin-bottom:10px;">
            <span style="display:block;margin-bottom:6px;font-size:7px;font-weight:800;letter-spacing:1px;text-transform:uppercase;color:#6a7b73;">Message</span>
            <textarea name="message" rows="4" style="width:100%;padding:10px;border:1px solid var(--line);border-radius:6px;font-size:12px;" required></textarea>
        </label>
        <button class="button button-primary">Save note</button>
    </form>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\leads\v2\conversations.blade.php ENDPATH**/ ?>