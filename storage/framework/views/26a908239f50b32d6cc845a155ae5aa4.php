<?php $__env->startSection('title', 'Inbox · Shishi Footsteps ERP'); ?>
<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginalad67f78bf768badc17b2fc4005a4f8bf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad67f78bf768badc17b2fc4005a4f8bf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.top-bar','data' => ['title' => 'Inbox','description' => 'Convert incoming emails into Requests or Leads','search' => false,'addButton' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.top-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Inbox','description' => 'Convert incoming emails into Requests or Leads','search' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'addButton' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
     <?php $__env->slot('actions', null, []); ?> 
        <form method="POST" action="<?php echo e(route('admin.mail.incoming.fetch')); ?>" style="display:inline">
            <?php echo csrf_field(); ?>
            <button type="submit" class="button button-primary"><i data-lucide="refresh-cw"></i>Fetch now</button>
        </form>
        <a href="<?php echo e(route('admin.mail.incoming.accounts')); ?>" class="button button-secondary"><i data-lucide="settings"></i>IMAP accounts</a>
     <?php $__env->endSlot(); ?>
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

<div style="display:flex;gap:8px;margin-bottom:16px;flex-wrap:wrap">
    <?php
        $tabs = ['new' => 'New', 'converted_to_request' => 'Converted to request', 'converted_to_lead' => 'Converted to lead', 'ignored' => 'Ignored', 'all' => 'All'];
    ?>
    <?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('admin.mail.inbox', ['status' => $val])); ?>" class="button <?php if($status === $val): ?> button-primary <?php else: ?> button-secondary <?php endif; ?>" style="font-size:9px"><?php echo e($label); ?></a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<section class="ops-panel">
    <div class="table-wrap">
        <table class="ops-table">
            <thead>
                <tr>
                    <th>Received</th>
                    <th>From</th>
                    <th>Subject</th>
                    <th>Account</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $emails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $email): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="font-size:8px"><?php echo e($email->received_at ? $email->received_at->format('d M Y H:i') : '—'); ?></td>
                    <td><strong><?php echo e($email->from_name ?: $email->from_email); ?></strong><br><small style="color:var(--text-muted)"><?php echo e($email->from_email); ?></small></td>
                    <td><a href="<?php echo e(route('admin.mail.inbox.show', $email)); ?>" style="color:var(--primary);font-weight:600"><?php echo e($email->subject ?: '(no subject)'); ?></a></td>
                    <td style="font-size:9px"><?php echo e($email->account?->label ?? '—'); ?></td>
                    <td>
                        <?php $colors = ['new' => '#2563eb', 'converted_to_request' => '#16a34a', 'converted_to_lead' => '#0d9488', 'ignored' => '#6b7280', 'failed' => '#dc2626']; ?>
                        <span class="ops-pill" style="color:<?php echo e($colors[$email->status] ?? '#3a3530'); ?>"><?php echo e(ucfirst(str_replace('_', ' ', $email->status))); ?></span>
                    </td>
                    <td>
                        <div class="ops-actions">
                            <a href="<?php echo e(route('admin.mail.inbox.show', $email)); ?>" title="Open"><i data-lucide="eye"></i></a>
                            <?php if($email->isConvertable()): ?>
                            <form method="POST" action="<?php echo e(route('admin.mail.inbox.convert', $email)); ?>" style="display:inline">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="as" value="request">
                                <button title="Convert to request" onclick="return confirm('Convert to request?')"><i data-lucide="file-plus"></i></button>
                            </form>
                            <?php endif; ?>
                            <?php if($email->isConvertable()): ?>
                            <form method="POST" action="<?php echo e(route('admin.mail.inbox.ignore', $email)); ?>" style="display:inline">
                                <?php echo csrf_field(); ?>
                                <button title="Ignore" onclick="return confirm('Ignore this email?')"><i data-lucide="eye-off"></i></button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" style="text-align:center;padding:32px;color:var(--text-muted)">No emails for this filter. Click "Fetch now" to pull new messages.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="ops-pagination"><?php echo e($emails->links()); ?></div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\mail\inbox.blade.php ENDPATH**/ ?>