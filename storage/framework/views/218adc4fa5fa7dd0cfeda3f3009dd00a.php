<?php $__env->startSection('title', 'CRM - Leads'); ?>
<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginalad67f78bf768badc17b2fc4005a4f8bf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad67f78bf768badc17b2fc4005a4f8bf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.top-bar','data' => ['title' => 'Leads & Enquiries','description' => 'Customer Relationship Management','addButton' => false,'searchPlaceholder' => 'Search by name, email, phone...']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.top-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Leads & Enquiries','description' => 'Customer Relationship Management','addButton' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'searchPlaceholder' => 'Search by name, email, phone...']); ?>
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
<form class="ops-filters" method="GET">
    <label class="ops-search"><i data-lucide="search"></i><input name="search" value="<?php echo e(request('search')); ?>" placeholder="Search by name, email, phone..."></label>
    <select name="status" onchange="this.form.submit()">
        <option value="">All Statuses</option>
        <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($k); ?>" <?php if(request('status') === $k): echo 'selected'; endif; ?>><?php echo e($v); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    <select name="source" onchange="this.form.submit()">
        <option value="">All Sources</option>
        <?php $__currentLoopData = $sources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($k); ?>" <?php if(request('source') === $k): echo 'selected'; endif; ?>><?php echo e($v); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    <select name="consultant" onchange="this.form.submit()">
        <option value="">All Consultants</option>
        <?php $__currentLoopData = $consultants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($u->id); ?>" <?php if(request('consultant') == $u->id): echo 'selected'; endif; ?>><?php echo e($u->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    <button class="button button-primary">Filter</button>
</form>
<div class="table-wrap">
    <table class="ops-table">
        <thead><tr><th>Name</th><th>Contact</th><th>Source</th><th>Status</th><th>Destination</th><th>Travel Date</th><th>Guests</th><th>Value</th><th>Consultant</th><th>Created</th><th>Actions</th></tr></thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $leads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lead): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><strong><?php echo e($lead->name); ?></strong></td>
                <td><small><?php echo e($lead->email); ?><br><?php echo e($lead->phone ?? ''); ?></small></td>
                <td><span class="status status--source"><?php echo e($sources[$lead->source] ?? $lead->source); ?></span></td>
                <td><span class="status status--<?php echo e($lead->status); ?>"><?php echo e($statuses[$lead->status] ?? $lead->status); ?></span></td>
                <td><?php echo e($lead->destination ?? '-'); ?></td>
                <td><?php echo e($lead->travel_date?->format('d/m/Y') ?? '-'); ?></td>
                <td><?php echo e($lead->travelers); ?></td>
                <td><?php echo e($lead->currency); ?> <?php echo e(number_format($lead->estimated_value ?? 0)); ?></td>
                <td><?php echo e($lead->consultant?->name ?? 'Unassigned'); ?></td>
                <td><small><?php echo e($lead->created_at->format('d/m/Y')); ?></small></td>
                <td>
                    <div class="ops-actions">
                        <a href="<?php echo e(route('admin.leads.show', $lead)); ?>"><i data-lucide="eye"></i></a>
                        <a href="<?php echo e(route('admin.leads.show', $lead)); ?>#conversations"><i data-lucide="message-square"></i></a>
                    </div>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="11" class="text-center text-muted">No leads found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<div class="ops-pagination"><?php echo e($leads->links()); ?></div>
<style>
.ops-actions { display: flex; gap: 0.25rem; }
.ops-actions a { padding: 0.25rem; color: var(--text-muted); }
.ops-actions a:hover { color: var(--primary); }
.status--source { background: var(--bg-subtle); color: var(--text); }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\shishifootsteps\safari\resources\views/admin/leads/v2/index.blade.php ENDPATH**/ ?>