<?php $__env->startSection('title', 'Countries & Regions'); ?>
<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginalad67f78bf768badc17b2fc4005a4f8bf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad67f78bf768badc17b2fc4005a4f8bf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.top-bar','data' => ['title' => 'Countries & Regions','description' => 'Location Management','addLabel' => 'New Country','addRoute' => ''.e(route('admin.countries.create')).'','search' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.top-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Countries & Regions','description' => 'Location Management','addLabel' => 'New Country','addRoute' => ''.e(route('admin.countries.create')).'','search' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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
<?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<section class="ops-panel" style="margin-bottom:1rem;">
    <div class="ops-panel-title">
        <div><h2><?php echo e($country->name); ?> <span class="badge"><?php echo e($country->code); ?></span></h2></div>
        <div style="display:flex;gap:0.5rem;">
            <a href="<?php echo e(route('admin.countries.edit', $country)); ?>" class="button button-sm button-secondary">Edit</a>
            <form method="POST" action="<?php echo e(route('admin.countries.destroy', $country)); ?>" onsubmit="return confirm('Delete country?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="button button-sm button-danger">Delete</button></form>
        </div>
    </div>
    <div class="regions-list">
        <?php $__currentLoopData = $country->regions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $region): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="region-item">
            <span><?php echo e($region->name); ?></span>
            <div style="display:flex;gap:0.25rem;">
                <form method="POST" action="<?php echo e(route('admin.countries.regions.update', [$country, $region])); ?>" style="display:contents;">
                    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                    <input name="name" value="<?php echo e($region->name); ?>" class="region-input" style="display:none;" onblur="this.form.submit()">
                </form>
                <form method="POST" action="<?php echo e(route('admin.countries.regions.destroy', [$country, $region])); ?>" onsubmit="return confirm('Delete region?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="row-action"><i data-lucide="trash-2" style="width:14px;height:14px;"></i></button></form>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <form method="POST" action="<?php echo e(route('admin.countries.regions.store', $country)); ?>" class="add-region-form">
            <?php echo csrf_field(); ?>
            <input name="name" placeholder="Add region..." required>
            <button class="button button-sm button-primary">Add</button>
        </form>
    </div>
</section>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<style>
.badge { background: var(--primary-light); color: var(--primary); padding: 0.15rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 600; }
.regions-list { display: flex; flex-direction: column; gap: 0.25rem; }
.region-item { display: flex; justify-content: space-between; align-items: center; padding: 0.4rem 0.75rem; background: var(--bg-subtle); border-radius: 0.375rem; font-size: 0.9rem; }
.add-region-form { display: flex; gap: 0.5rem; margin-top: 0.5rem; }
.add-region-form input { flex: 1; padding: 0.4rem 0.75rem; border: 1px solid var(--border); border-radius: 0.375rem; font-size: 0.85rem; }
.row-action { border: none; background: none; cursor: pointer; color: var(--text-muted); padding: 0.25rem; }
.row-action:hover { color: #ef4444; }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\locations\index.blade.php ENDPATH**/ ?>