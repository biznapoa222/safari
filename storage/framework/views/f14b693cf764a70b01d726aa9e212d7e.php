<?php $__env->startSection('title', 'Users & Roles'); ?>

<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginalad67f78bf768badc17b2fc4005a4f8bf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad67f78bf768badc17b2fc4005a4f8bf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.top-bar','data' => ['title' => 'Users & Roles','description' => 'System Settings','addLabel' => 'Add User','addOnclick' => 'document.querySelector(\'[data-user-form]\').hidden = !document.querySelector(\'[data-user-form]\').hidden','search' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.top-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Users & Roles','description' => 'System Settings','addLabel' => 'Add User','addOnclick' => 'document.querySelector(\'[data-user-form]\').hidden = !document.querySelector(\'[data-user-form]\').hidden','search' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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

<section class="user-create-panel ops-panel" data-user-form hidden>
    <div class="ops-panel-title"><div><h2>Create team account</h2><p>Assign the correct department role and a temporary password.</p></div><button class="popover-close user-form-close" type="button" data-user-form-toggle><i data-lucide="x"></i></button></div>
    <form method="POST" action="<?php echo e(route('admin.users.store')); ?>">
        <?php echo csrf_field(); ?>
        <div class="ops-form-grid">
            <label>Full name<input name="name" required></label>
            <label>Email address<input type="email" name="email" required></label>
            <label>Role<select name="role"><?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($value); ?>"><?php echo e($label); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></label>
            <label>Department<input name="department" placeholder="Sales, Operations, Finance..."></label>
            <label>Phone<input name="phone" placeholder="+254 ..."></label>
            <label>Temporary password<input type="password" name="password" minlength="8" required></label>
            <label class="check-label"><input type="checkbox" name="is_active" value="1" checked> Active account</label>
        </div>
        <div class="ops-form-footer"><button class="button button-primary"><i data-lucide="user-plus"></i>Create user</button></div>
    </form>
</section>

<section class="ops-panel">
    <form class="ops-filters" method="GET">
        <label class="ops-search"><i data-lucide="search"></i><input name="search" value="<?php echo e(request('search')); ?>" placeholder="Search name, email or department"></label>
        <select name="role"><option value="">All roles</option><?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($value); ?>" <?php if(request('role') === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select>
        <button class="button button-primary">Search</button><a class="button button-secondary" href="<?php echo e(route('admin.users.index')); ?>">Reset</a>
    </form>
    <div class="user-directory">
        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <article class="user-card">
                <div class="user-avatar"><?php echo e($user->initials()); ?></div>
                <div class="user-identity"><h2><?php echo e($user->name); ?></h2><p><?php echo e($user->email); ?></p><span><?php echo e($user->department ?: 'General administration'); ?></span></div>
                <div class="user-role"><small>Access role</small><strong><?php echo e($roles[$user->role] ?? ucfirst($user->role)); ?></strong></div>
                <div class="user-activity"><small>Last sign in</small><strong><?php echo e($user->last_login_at?->diffForHumans() ?? 'Never'); ?></strong></div>
                <span class="user-state <?php echo e($user->is_active ? 'is-active' : ''); ?>"><i></i><?php echo e($user->is_active ? 'Active' : 'Inactive'); ?></span>
                <details class="edit-popover">
                    <summary><i data-lucide="square-pen"></i>Edit</summary>
                    <div class="edit-popover-panel">
                        <header><div><small>Edit user account</small><strong><?php echo e($user->name); ?></strong></div><button type="button" class="popover-close"><i data-lucide="x"></i></button></header>
                        <form method="POST" action="<?php echo e(route('admin.users.update', $user)); ?>">
                            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                            <input name="name" value="<?php echo e($user->name); ?>" placeholder="Full name" required>
                            <input type="email" name="email" value="<?php echo e($user->email); ?>" placeholder="Email" required>
                            <select name="role"><?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($value); ?>" <?php if($user->role === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select>
                            <input name="department" value="<?php echo e($user->department); ?>" placeholder="Department">
                            <input name="phone" value="<?php echo e($user->phone); ?>" placeholder="Phone">
                            <input type="password" name="password" minlength="8" placeholder="New password (leave blank to keep)">
                            <label class="check-label"><input type="checkbox" name="is_active" value="1" <?php if($user->is_active): echo 'checked'; endif; ?>> Active account</label>
                            <button class="button button-primary">Save changes</button>
                        </form>
                        <?php if(!auth()->user()->is($user)): ?><form class="popover-delete-form" method="POST" action="<?php echo e(route('admin.users.destroy', $user)); ?>" onsubmit="return confirm('Delete this user account?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="button danger-button">Delete user</button></form><?php endif; ?>
                    </div>
                </details>
            </article>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <div class="ops-pagination"><?php echo e($users->links()); ?></div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\shishifootsteps\safari\resources\views/admin/users/index.blade.php ENDPATH**/ ?>