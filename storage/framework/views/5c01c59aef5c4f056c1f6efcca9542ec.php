<?php $__env->startSection('title', $supplier ? 'Edit Supplier' : 'New Supplier'); ?>
<?php $__env->startSection('content'); ?>
<div class="page-heading">
    <div><p class="eyebrow">Supplier</p><h1><?php echo e($supplier ? 'Edit: '.$supplier->name : 'New Supplier'); ?></h1></div>
</div>
<?php echo $__env->make('admin.partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<form method="POST" action="<?php echo e($supplier ? route('admin.suppliers.update', $supplier) : route('admin.suppliers.store')); ?>" class="ops-panel">
    <?php echo csrf_field(); ?> <?php if($supplier): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
    <div class="ops-panel-title"><h2>Supplier Information</h2></div>
    <div class="ops-form-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
        <label>Supplier Type
            <select name="type">
                <?php $__currentLoopData = \App\Models\Supplier::$types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($k); ?>" <?php if(old('type', $supplier->type ?? '') === $k): echo 'selected'; endif; ?>><?php echo e($v); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </label>
        <label>Name<input name="name" value="<?php echo e(old('name', $supplier->name ?? '')); ?>" required></label>
        <label>Country
            <select name="country">
                <?php $__currentLoopData = ['Kenya','Tanzania','Uganda','South Africa','Namibia','Botswana']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($c); ?>" <?php if(old('country', $supplier->country ?? '') === $c): echo 'selected'; endif; ?>><?php echo e($c); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </label>
        <label>Region<input name="region" value="<?php echo e(old('region', $supplier->region ?? '')); ?>"></label>
        <label>Contact Person<input name="contact_person" value="<?php echo e(old('contact_person', $supplier->contact_person ?? '')); ?>"></label>
        <label>Phone<input name="phone" value="<?php echo e(old('phone', $supplier->phone ?? '')); ?>"></label>
        <label>Email<input type="email" name="email" value="<?php echo e(old('email', $supplier->email ?? '')); ?>"></label>
        <label>Website<input name="website" value="<?php echo e(old('website', $supplier->website ?? '')); ?>"></label>
        <label>GPS Coordinates<input name="gps_coordinates" value="<?php echo e(old('gps_coordinates', $supplier->gps_coordinates ?? '')); ?>"></label>
        <label>Classification<input name="classification" value="<?php echo e(old('classification', $supplier->classification ?? '')); ?>" placeholder="e.g. luxury, game_drive, land_cruiser"></label>
        <label class="span-2">Notes<textarea name="notes" rows="3"><?php echo e(old('notes', $supplier->notes ?? '')); ?></textarea></label>
    </div>
    <div class="ops-form-footer">
        <a href="<?php echo e(route('admin.suppliers.index')); ?>" class="button button-secondary">Cancel</a>
        <button class="button button-primary"><i data-lucide="save"></i><?php echo e($supplier ? 'Update' : 'Create'); ?></button>
    </div>
</form>
<style>.ops-form-grid label { display: flex; flex-direction: column; gap: 0.25rem; }</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\suppliers\form.blade.php ENDPATH**/ ?>