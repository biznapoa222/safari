<?php $__env->startSection('title', 'Weekly Management Report'); ?>
<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginalad67f78bf768badc17b2fc4005a4f8bf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad67f78bf768badc17b2fc4005a4f8bf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.top-bar','data' => ['title' => 'Weekly Management Report','description' => 'Reports','addButton' => false,'search' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.top-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Weekly Management Report','description' => 'Reports','addButton' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'search' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
    <p style="margin:0.25rem 0 0;color:var(--text-muted);font-size:0.85rem;"><?php echo e($data['week']); ?></p>
     <?php $__env->slot('actions', null, []); ?> 
        <a href="<?php echo e(route('admin.reports.weekly.export', 'pdf')); ?>" class="button button-secondary button-sm"><i data-lucide="file-text"></i>Export PDF</a>
        <a href="<?php echo e(route('admin.reports.weekly.export', 'csv')); ?>" class="button button-secondary button-sm"><i data-lucide="file-spreadsheet"></i>Export CSV</a>
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
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:1.5rem;">
    <div class="ops-panel" style="text-align:center;padding:1.5rem;">
        <small class="text-muted">New Leads</small>
        <h2><?php echo e($data['new_leads']); ?></h2>
    </div>
    <div class="ops-panel" style="text-align:center;padding:1.5rem;">
        <small class="text-muted">Converted Leads</small>
        <h2><?php echo e($data['converted_leads']); ?></h2>
    </div>
    <div class="ops-panel" style="text-align:center;padding:1.5rem;">
        <small class="text-muted">New Bookings</small>
        <h2><?php echo e($data['new_bookings']); ?></h2>
    </div>
    <div class="ops-panel" style="text-align:center;padding:1.5rem;">
        <small class="text-muted">Revenue</small>
        <h2>$<?php echo e(number_format($data['revenue'])); ?></h2>
    </div>
    <div class="ops-panel" style="text-align:center;padding:1.5rem;">
        <small class="text-muted">Collected</small>
        <h2 class="text-green">$<?php echo e(number_format($data['collected'])); ?></h2>
    </div>
    <div class="ops-panel" style="text-align:center;padding:1.5rem;">
        <small class="text-muted">Outstanding</small>
        <h2 style="color:#ef4444;">$<?php echo e(number_format($data['outstanding'])); ?></h2>
    </div>
</div>
<section class="ops-panel">
    <div class="ops-panel-title"><h2>Consultant KPIs (This Week)</h2></div>
    <div class="table-wrap">
        <table class="ops-table">
            <thead><tr><th>Consultant</th><th>Leads</th><th>Converted</th><th>Revenue</th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $data['consultant_kpis']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr><td><strong><?php echo e($c['name']); ?></strong></td><td><?php echo e($c['leads']); ?></td><td><?php echo e($c['converted']); ?></td><td>$<?php echo e(number_format($c['revenue'],2)); ?></td></tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</section>
<style>.text-green { color: #22c55e; }</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\reports\weekly.blade.php ENDPATH**/ ?>