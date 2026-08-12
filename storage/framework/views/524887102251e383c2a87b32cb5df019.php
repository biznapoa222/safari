<?php $__env->startSection('title', 'IMAP Accounts · Shishi Footsteps ERP'); ?>
<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginalad67f78bf768badc17b2fc4005a4f8bf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad67f78bf768badc17b2fc4005a4f8bf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.top-bar','data' => ['title' => 'IMAP Accounts','description' => 'Connect mailboxes to import enquiries into Requests','search' => false,'addButton' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.top-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'IMAP Accounts','description' => 'Connect mailboxes to import enquiries into Requests','search' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'addButton' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
     <?php $__env->slot('actions', null, []); ?> 
        <form method="POST" action="<?php echo e(route('admin.mail.incoming.fetch')); ?>" style="display:inline">
            <?php echo csrf_field(); ?>
            <button type="submit" class="button button-primary"><i data-lucide="refresh-cw"></i>Fetch now</button>
        </form>
        <a href="<?php echo e(route('admin.mail.inbox')); ?>" class="button button-secondary"><i data-lucide="inbox"></i>Open inbox</a>
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
        <div class="ops-panel-title"><h2>Connected accounts</h2></div>
        <div class="table-wrap">
            <table class="ops-table">
                <thead>
                    <tr>
                        <th>Label</th>
                        <th>Host</th>
                        <th>Username</th>
                        <th>Folder</th>
                        <th>Last fetched</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><strong><?php echo e($account->label); ?></strong><br><small style="color:var(--text-muted)"><?php echo e(strtoupper($account->protocol)); ?> · <?php echo e($account->encryption); ?> · port <?php echo e($account->port); ?></small></td>
                        <td style="font-size:9px"><?php echo e($account->host); ?></td>
                        <td style="font-size:9px"><?php echo e($account->username); ?></td>
                        <td style="font-size:9px"><?php echo e($account->folder); ?></td>
                        <td style="font-size:9px"><?php echo e($account->last_fetched_at ? $account->last_fetched_at->diffForHumans() : '—'); ?><br><small style="color:var(--text-muted)">uid <?php echo e($account->last_uid); ?></small></td>
                        <td>
                            <?php if($account->is_active): ?>
                                <span class="ops-pill" style="background:#f0fdf4;color:#16a34a">Active</span>
                            <?php else: ?>
                                <span class="ops-pill" style="background:#f3f4f6;color:#6b7280">Paused</span>
                            <?php endif; ?>
                            <?php if($account->error): ?>
                                <small style="color:#dc2626;display:block;margin-top:4px;max-width:240px;white-space:normal"><?php echo e($account->error); ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="ops-actions">
                                <form method="POST" action="<?php echo e(route('admin.mail.incoming.destroy', $account)); ?>" onsubmit="return confirm('Delete this IMAP account?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button title="Delete"><i data-lucide="trash-2"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="7" style="text-align:center;padding:32px;color:var(--text-muted)">No IMAP accounts yet. Add one on the right.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section class="ops-panel">
        <div class="ops-panel-title"><h2>Add IMAP account</h2></div>
        <div style="padding:16px">
            <form method="POST" action="<?php echo e(route('admin.mail.incoming.store')); ?>">
                <?php echo csrf_field(); ?>
                <div style="display:grid;gap:8px">
                    <input type="text" name="label" required placeholder="Label (e.g. info@shishifootsteps.com)" style="height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                        <select name="protocol" style="height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                            <option value="imap">IMAP</option>
                            <option value="pop3">POP3</option>
                        </select>
                        <select name="encryption" style="height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                            <option value="ssl">SSL</option>
                            <option value="tls">TLS</option>
                            <option value="none">None</option>
                        </select>
                    </div>
                    <input type="text" name="host" required placeholder="imap.example.com" style="height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                        <input type="number" name="port" value="993" required style="height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                        <input type="text" name="folder" value="INBOX" style="height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                    </div>
                    <input type="text" name="username" required placeholder="username" style="height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                    <input type="password" name="password" required placeholder="Password" style="height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                    <select name="assigned_consultant_id" style="height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                        <option value="">Default assignee (no one)</option>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($u->id); ?>"><?php echo e($u->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;font-size:9px">
                        <label style="display:flex;align-items:center;gap:6px"><input type="checkbox" name="is_active" value="1" checked>Active</label>
                        <label style="display:flex;align-items:center;gap:6px"><input type="checkbox" name="mark_seen" value="1" checked>Mark as seen</label>
                        <label style="display:flex;align-items:center;gap:6px"><input type="checkbox" name="delete_after_fetch" value="1">Delete after fetch</label>
                        <label style="display:flex;align-items:center;gap:6px"><input type="checkbox" name="auto_create_request" value="1">Auto-create request</label>
                    </div>
                    <button type="submit" class="button button-primary" style="height:38px">Save account</button>
                </div>
            </form>
        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\mail\incoming-accounts.blade.php ENDPATH**/ ?>