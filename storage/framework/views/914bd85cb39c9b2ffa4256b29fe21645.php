<?php $__env->startSection('title', 'Mail Settings · Shishi Footsteps ERP'); ?>
<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginalad67f78bf768badc17b2fc4005a4f8bf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad67f78bf768badc17b2fc4005a4f8bf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.top-bar','data' => ['title' => 'Mail Settings','description' => 'Configure SMTP and outgoing email','search' => false,'addButton' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.top-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Mail Settings','description' => 'Configure SMTP and outgoing email','search' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'addButton' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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
        <div class="ops-panel-title"><h2>SMTP &amp; Sender</h2></div>
        <div style="padding:16px">
            <form method="POST" action="<?php echo e(route('admin.mail.settings.update')); ?>">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Mailer</label>
                        <select name="mailer" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text)">
                            <?php $__currentLoopData = ['smtp','sendmail','log','array']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($m); ?>" <?php if($setting->mailer === $m): echo 'selected'; endif; ?>><?php echo e(strtoupper($m)); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">SMTP Host</label>
                        <input type="text" name="host" value="<?php echo e($setting->host); ?>" placeholder="smtp.example.com" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Port</label>
                        <input type="number" name="port" value="<?php echo e($setting->port); ?>" placeholder="587" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Encryption</label>
                        <select name="encryption" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                            <option value="">None</option>
                            <option value="tls" <?php if($setting->encryption === 'tls'): echo 'selected'; endif; ?>>TLS</option>
                            <option value="ssl" <?php if($setting->encryption === 'ssl'): echo 'selected'; endif; ?>>SSL</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Username</label>
                        <input type="text" name="username" value="<?php echo e($setting->username); ?>" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Password</label>
                        <input type="password" name="password" value="" placeholder="••••••••" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">From email</label>
                        <input type="email" name="from_address" value="<?php echo e($setting->from_address); ?>" required style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">From name</label>
                        <input type="text" name="from_name" value="<?php echo e($setting->from_name); ?>" required style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Reply-to email</label>
                        <input type="email" name="reply_to_address" value="<?php echo e($setting->reply_to_address); ?>" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Reply-to name</label>
                        <input type="text" name="reply_to_name" value="<?php echo e($setting->reply_to_name); ?>" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                    </div>
                </div>
                <div style="margin-top:14px;display:flex;gap:10px;align-items:center">
                    <label style="display:flex;align-items:center;gap:8px;font-size:9px;color:var(--text);cursor:pointer">
                        <input type="checkbox" name="is_active" value="1" <?php if($setting->is_active): echo 'checked'; endif; ?> style="width:14px;height:14px;accent-color:var(--primary)">
                        Enable SMTP (when unchecked, emails are written to laravel.log instead of sent)
                    </label>
                </div>
                <div style="margin-top:16px;display:flex;gap:10px">
                    <button type="submit" class="button button-primary">Save</button>
                </div>
            </form>
        </div>
    </section>

    <section class="ops-panel">
        <div class="ops-panel-title"><h2>Send test email</h2></div>
        <div style="padding:16px">
            <form method="POST" action="<?php echo e(route('admin.mail.settings.test')); ?>">
                <?php echo csrf_field(); ?>
                <div style="display:grid;gap:8px">
                    <input type="email" name="to" required placeholder="Recipient email" style="height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                    <input type="text" name="subject" placeholder="Subject (optional)" style="height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                    <textarea name="body" rows="4" placeholder="Body (optional)" style="padding:9px 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF"></textarea>
                    <button type="submit" class="button button-secondary">Send test</button>
                </div>
            </form>
        </div>
    </section>
</div>

<section class="ops-panel" style="margin-top:16px">
    <div class="ops-panel-title"><h2>Recent emails</h2><span><?php echo e($stats['total']); ?> total · <?php echo e($stats['sent']); ?> sent · <?php echo e($stats['failed']); ?> failed</span></div>
    <div class="table-wrap">
        <table class="ops-table">
            <thead>
                <tr>
                    <th>When</th>
                    <th>Category</th>
                    <th>Subject</th>
                    <th>To</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $recent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="font-size:8px"><?php echo e($mail->sent_at?->format('d M Y H:i') ?? $mail->created_at->format('d M Y H:i')); ?></td>
                    <td><span class="ops-pill"><?php echo e($mail->category); ?></span></td>
                    <td><?php echo e($mail->subject); ?></td>
                    <td style="font-size:9px"><?php echo e($mail->to_email); ?><br><small style="color:var(--text-muted)"><?php echo e($mail->to_name); ?></small></td>
                    <td>
                        <?php if($mail->status === 'sent'): ?>
                            <span class="ops-pill" style="background:#f0fdf4;color:#16a34a">Sent</span>
                        <?php elseif($mail->status === 'failed'): ?>
                            <span class="ops-pill" style="background:#fef2f2;color:#dc2626">Failed</span>
                        <?php else: ?>
                            <span class="ops-pill"><?php echo e(ucfirst($mail->status)); ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="5" style="text-align:center;padding:32px;color:var(--text-muted)">No emails sent yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\mail\settings.blade.php ENDPATH**/ ?>