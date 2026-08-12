<?php $__env->startSection('title', $email->subject ?: 'Email · Shishi Footsteps ERP'); ?>
<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginalad67f78bf768badc17b2fc4005a4f8bf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad67f78bf768badc17b2fc4005a4f8bf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.top-bar','data' => ['title' => $email->subject ?: '(no subject)','description' => $email->from_email,'search' => false,'addButton' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.top-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($email->subject ?: '(no subject)'),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($email->from_email),'search' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'addButton' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
     <?php $__env->slot('actions', null, []); ?> 
        <a href="<?php echo e(route('admin.mail.inbox')); ?>" class="button button-secondary"><i data-lucide="arrow-left"></i>Back to inbox</a>
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

<div style="display:grid;grid-template-columns:2fr 1fr;gap:16px;align-items:start">
    <section class="ops-panel">
        <div class="ops-panel-title"><h2>Message</h2></div>
        <div style="padding:16px;font-size:11px;line-height:1.6">
            <p><strong>From:</strong> <?php echo e($email->from_name); ?> &lt;<?php echo e($email->from_email); ?>&gt;</p>
            <p><strong>To:</strong> <?php echo e($email->to_email); ?></p>
            <p><strong>Received:</strong> <?php echo e($email->received_at?->toDateTimeString()); ?></p>
            <p><strong>Account:</strong> <?php echo e($email->account?->label); ?></p>
            <hr>
            <?php if($email->body_html): ?>
                <iframe sandbox srcdoc="<?php echo e($email->body_html); ?>" style="width:100%;min-height:420px;border:1px solid #ede8df;border-radius:8px;background:#fff"></iframe>
                <details style="margin-top:8px"><summary>Show plain text</summary>
                    <pre style="white-space:pre-wrap;background:#faf6ec;padding:12px;border-radius:8px;margin-top:8px"><?php echo e($email->body_text); ?></pre>
                </details>
            <?php else: ?>
                <pre style="white-space:pre-wrap;background:#faf6ec;padding:12px;border-radius:8px"><?php echo e($email->body_text); ?></pre>
            <?php endif; ?>
        </div>
    </section>

    <section class="ops-panel">
        <div class="ops-panel-title"><h2>Convert</h2></div>
        <div style="padding:16px">
            <p style="font-size:9px;margin-bottom:8px;color:var(--text-muted)">Choose where this email should land in the CRM.</p>
            <?php if($email->isConvertable()): ?>
                <form method="POST" action="<?php echo e(route('admin.mail.inbox.convert', $email)); ?>" style="display:flex;flex-direction:column;gap:8px">
                    <?php echo csrf_field(); ?>
                    <label style="display:flex;gap:8px;align-items:center;font-size:10px"><input type="radio" name="as" value="request" checked> Create as <strong>Request</strong></label>
                    <label style="display:flex;gap:8px;align-items:center;font-size:10px"><input type="radio" name="as" value="lead"> Create as <strong>Lead</strong></label>
                    <button type="submit" class="button button-primary" style="height:38px">Convert</button>
                </form>
                <hr>
                <form method="POST" action="<?php echo e(route('admin.mail.inbox.ignore', $email)); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="button button-secondary" style="width:100%" onclick="return confirm('Ignore this email?')">Ignore</button>
                </form>
            <?php else: ?>
                <p style="font-size:9px;color:var(--text-muted)">Already converted to <strong><?php echo e($email->request_id ? 'request' : 'lead'); ?></strong>#<?php echo e($email->request_id ?? $email->lead_id); ?>.</p>
                <a href="<?php echo e($email->request_id ? route('admin.requests.show', $email->request_id) : route('admin.leads.v2.show', $email->lead_id)); ?>" class="button button-primary">Open <?php echo e($email->request_id ? 'request' : 'lead'); ?></a>
            <?php endif; ?>
        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\mail\inbox-show.blade.php ENDPATH**/ ?>