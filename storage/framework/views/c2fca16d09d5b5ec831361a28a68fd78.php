<?php $__env->startSection('title', $itinerary ? 'Edit Itinerary' : 'New Itinerary'); ?>
<?php $__env->startSection('content'); ?>
<div class="page-heading">
    <div><p class="eyebrow">Itinerary Builder</p><h1><?php echo e($itinerary ? 'Edit: '.$itinerary->title : 'New Itinerary'); ?></h1></div>
</div>
<?php echo $__env->make('admin.partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<form method="POST" action="<?php echo e($itinerary ? route('admin.itinerary-builder.update', $itinerary) : route('admin.itinerary-builder.store')); ?>" class="ops-panel">
    <?php echo csrf_field(); ?> <?php if($itinerary): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
    <div class="ops-panel-title"><h2>Itinerary Details</h2></div>
    <div class="ops-form-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
        <label class="span-2">Title<input name="title" value="<?php echo e(old('title', $itinerary->title ?? '')); ?>" required></label>
        <label>Duration (days)<input type="number" name="duration_days" value="<?php echo e(old('duration_days', $itinerary->duration_days ?? 1)); ?>" min="1"></label>
        <label>Country<input name="country" value="<?php echo e(old('country', $itinerary->country ?? '')); ?>"></label>
        <label>Region<input name="region" value="<?php echo e(old('region', $itinerary->region ?? '')); ?>"></label>
        <label>Price From<input type="number" step="0.01" name="price_from" value="<?php echo e(old('price_from', $itinerary->price_from ?? '')); ?>"></label>
        <label>Currency
            <select name="currency">
                <?php $__currentLoopData = ['USD','EUR','GBP','KES','AUD','CAD']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($cur); ?>" <?php if(old('currency', $itinerary->currency ?? 'USD') === $cur): echo 'selected'; endif; ?>><?php echo e($cur); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </label>
        <label class="span-2">Summary<textarea name="summary" rows="4"><?php echo e(old('summary', $itinerary->summary ?? '')); ?></textarea></label>
        <label class="span-2">Inclusions (one per line)<textarea name="inclusions" rows="4"><?php echo e(old('inclusions', $itinerary ? (is_array($itinerary->inclusions) ? implode("\n", $itinerary->inclusions) : '') : '')); ?></textarea></label>
        <label class="span-2">Exclusions (one per line)<textarea name="exclusions" rows="4"><?php echo e(old('exclusions', $itinerary ? (is_array($itinerary->exclusions) ? implode("\n", $itinerary->exclusions) : '') : '')); ?></textarea></label>
        <label class="span-2">Notes<textarea name="notes" rows="3"><?php echo e(old('notes', $itinerary->notes ?? '')); ?></textarea></label>
        <?php if($itinerary): ?>
        <label class="checkbox-label"><input type="checkbox" name="published" value="1" <?php if(old('published', $itinerary->published)): echo 'checked'; endif; ?>> Published</label>
        <label class="checkbox-label"><input type="checkbox" name="featured" value="1" <?php if(old('featured', $itinerary->featured)): echo 'checked'; endif; ?>> Featured</label>
        <?php endif; ?>
    </div>
    <div class="ops-form-footer">
        <a href="<?php echo e(route('admin.itinerary-builder.index')); ?>" class="button button-secondary">Cancel</a>
        <button class="button button-primary"><i data-lucide="save"></i><?php echo e($itinerary ? 'Update' : 'Create'); ?></button>
    </div>
</form>

<?php if($itinerary): ?>
<h3 style="margin:2rem 0 1rem;">Day-by-Day Plan</h3>
<div id="days-container" class="day-list">
    <?php $__currentLoopData = $itinerary->days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="ops-panel day-card" data-day-id="<?php echo e($day->id); ?>" style="margin-bottom:0.75rem;">
        <div class="day-header" style="display:flex;justify-content:space-between;align-items:center;cursor:pointer;">
            <h4 style="margin:0;">Day <?php echo e($day->day_number); ?>: <?php echo e($day->title); ?></h4>
            <div style="display:flex;gap:0.5rem;">
                <button class="button button-sm button-secondary" onclick="this.closest('.day-card').querySelector('.day-body').classList.toggle('hidden')">Edit</button>
                <form method="POST" action="<?php echo e(route('admin.itinerary-builder.days.destroy', [$itinerary, $day])); ?>" onsubmit="return confirm('Delete day?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="button button-sm button-danger">Del</button></form>
            </div>
        </div>
        <form method="POST" action="<?php echo e(route('admin.itinerary-builder.days.update', [$itinerary, $day])); ?>" class="day-body hidden" style="margin-top:1rem;">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <div class="ops-form-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;">
                <label>Title<input name="title" value="<?php echo e($day->title); ?>"></label>
                <label>Location<input name="location" value="<?php echo e($day->location ?? ''); ?>"></label>
                <label>Accommodation ID<input type="number" name="accommodation_id" value="<?php echo e($day->accommodation_id ?? ''); ?>"></label>
                <label>Meal Plan<input name="meal_plan" value="<?php echo e($day->meal_plan ?? ''); ?>"></label>
                <label>Activities<textarea name="activities" rows="2"><?php echo e($day->activities ?? ''); ?></textarea></label>
                <label>Transfers<textarea name="transfers" rows="2"><?php echo e($day->transfers ?? ''); ?></textarea></label>
                <label class="span-2">Notes<textarea name="notes" rows="2"><?php echo e($day->notes ?? ''); ?></textarea></label>
                <div class="span-2"><button class="button button-primary">Save Day</button></div>
            </div>
        </form>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>


<form method="POST" action="<?php echo e(route('admin.itinerary-builder.days.store', $itinerary)); ?>" class="ops-panel" style="margin-top:1rem;">
    <?php echo csrf_field(); ?>
    <div class="ops-panel-title"><h2>Add Day</h2></div>
    <div class="ops-form-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;">
        <label>Day Number<input type="number" name="day_number" value="<?php echo e($itinerary->days->count() + 1); ?>" min="1"></label>
        <label>Title<input name="title" placeholder="Day <?php echo e($itinerary->days->count() + 1); ?>"></label>
        <label>Location<input name="location"></label>
        <label>Meal Plan<input name="meal_plan"></label>
        <label class="span-2">Activities<textarea name="activities" rows="2"></textarea></label>
        <label class="span-2">Transfers<textarea name="transfers" rows="2"></textarea></label>
        <label class="span-2">Notes<textarea name="notes" rows="2"></textarea></label>
        <div class="span-2"><button class="button button-primary">Add Day</button></div>
    </div>
</form>
<?php endif; ?>

<?php $__env->startPush('styles'); ?>
<style>
.checkbox-label { display: flex; align-items: center; gap: 0.5rem; cursor: pointer; }
.checkbox-label input { width: auto; }
.hidden { display: none; }
.day-card { border-left: 3px solid var(--primary); }
.day-header h4 { font-size: 1rem; }
</style>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\itinerary-builder\form.blade.php ENDPATH**/ ?>