<?php $__env->startSection('title', $accommodation ? 'Edit Accommodation' : 'New Accommodation'); ?>
<?php $__env->startSection('content'); ?>
<div class="page-heading">
    <div>
        <p class="eyebrow">Accommodation</p>
        <h1><?php echo e($accommodation ? 'Edit: '.$accommodation->name : 'New Accommodation'); ?></h1>
    </div>
</div>
<?php echo $__env->make('admin.partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<form method="POST" action="<?php echo e($accommodation ? route('admin.accommodations-v2.update', $accommodation) : route('admin.accommodations-v2.store')); ?>" class="ops-panel">
    <?php echo csrf_field(); ?> <?php if($accommodation): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
    <div class="ops-panel-title"><h2>Details</h2></div>
    <div class="ops-form-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
        <label class="span-2">Name<input name="name" value="<?php echo e(old('name', $accommodation->name ?? '')); ?>" required></label>
        <label>Type
            <select name="type">
                <?php $__currentLoopData = \App\Models\Accommodation::$types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($k); ?>" <?php if(old('type', $accommodation->type ?? '') === $k): echo 'selected'; endif; ?>><?php echo e($v); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </label>
        <label>Country
            <select name="country">
                <?php $__currentLoopData = ['Kenya','Tanzania','Uganda','South Africa','Namibia','Botswana']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($c); ?>" <?php if(old('country', $accommodation->country ?? '') === $c): echo 'selected'; endif; ?>><?php echo e($c); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </label>
        <label>Region<input name="region" value="<?php echo e(old('region', $accommodation->region ?? '')); ?>"></label>
        <label>Category<input name="category" value="<?php echo e(old('category', $accommodation->category ?? '')); ?>"></label>
        <label>Luxury Level
            <select name="luxury_level">
                <option value="">-- Select --</option>
                <?php $__currentLoopData = ['luxury'=>'Luxury','premium'=>'Premium','mid_range'=>'Mid Range','budget'=>'Budget']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($k); ?>" <?php if(old('luxury_level', $accommodation->luxury_level ?? '') === $k): echo 'selected'; endif; ?>><?php echo e($v); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </label>
        <label>Currency
            <select name="currency">
                <?php $__currentLoopData = ['USD','EUR','GBP','KES','AUD','CAD']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($cur); ?>" <?php if(old('currency', $accommodation->currency ?? 'USD') === $cur): echo 'selected'; endif; ?>><?php echo e($cur); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </label>
        <label>Phone<input name="phone" value="<?php echo e(old('phone', $accommodation->phone ?? '')); ?>"></label>
        <label>Email<input type="email" name="email" value="<?php echo e(old('email', $accommodation->email ?? '')); ?>"></label>
        <label class="span-2">Website<input name="website" value="<?php echo e(old('website', $accommodation->website ?? '')); ?>"></label>
        <label class="span-2">Description<textarea name="description" rows="5"><?php echo e(old('description', $accommodation->description ?? '')); ?></textarea></label>
        <label class="span-2">Notes<textarea name="notes" rows="3"><?php echo e(old('notes', $accommodation->notes ?? '')); ?></textarea></label>

        <?php if($accommodation): ?>
        <label class="checkbox-label"><input type="checkbox" name="published" value="1" <?php if(old('published', $accommodation->published)): echo 'checked'; endif; ?>> Published</label>
        <label class="checkbox-label"><input type="checkbox" name="featured" value="1" <?php if(old('featured', $accommodation->featured)): echo 'checked'; endif; ?>> Featured</label>
        <label>Status
            <select name="status">
                <option value="active" <?php if(old('status', $accommodation->status) === 'active'): echo 'selected'; endif; ?>>Active</option>
                <option value="inactive" <?php if(old('status', $accommodation->status) === 'inactive'): echo 'selected'; endif; ?>>Inactive</option>
            </select>
        </label>
        <?php endif; ?>
    </div>
    <div class="ops-form-footer">
        <a href="<?php echo e(route('admin.accommodations-v2.index')); ?>" class="button button-secondary">Cancel</a>
        <button class="button button-primary"><i data-lucide="save"></i><?php echo e($accommodation ? 'Update' : 'Create'); ?></button>
    </div>
</form>

<?php if($accommodation): ?>

<section class="ops-panel" style="margin-top:2rem;">
    <div class="ops-panel-title"><h2>Room Types</h2>
        <button class="button button-sm button-secondary" onclick="document.getElementById('room-form').classList.toggle('hidden')">Add Room</button>
    </div>
    <form id="room-form" method="POST" action="<?php echo e(route('admin.accommodations-v2.rooms.store', $accommodation)); ?>" class="hidden" style="margin-bottom:1rem;">
        <?php echo csrf_field(); ?>
        <div class="ops-form-grid" style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0.75rem;">
            <label>Room Name<input name="name" required></label>
            <label>Capacity<input type="number" name="capacity" value="2" min="1"></label>
            <label>Max Adults<input type="number" name="max_adults" value="2" min="1"></label>
            <label>Max Children<input type="number" name="max_children" value="0" min="0"></label>
            <label>Baby Max Age<input type="number" name="baby_max_age" value="2" min="0" max="17" required></label>
            <label>Child Min Age<input type="number" name="child_min_age" value="3" min="0" max="17" required></label>
            <label>Child Max Age<input type="number" name="child_max_age" value="11" min="0" max="17" required></label>
            <label>Adult Min Age<input type="number" name="adult_min_age" value="12" min="1" max="30" required></label>
            <label>Inventory<input type="number" name="inventory" value="1" min="1"></label>
            <label>Child Policy<textarea name="child_policy" rows="2"></textarea></label>
            <div><button class="button button-primary" style="margin-top:1.5rem;">Add</button></div>
        </div>
    </form>
    <?php if($accommodation->rooms->count()): ?>
    <div class="table-wrap">
        <table class="ops-table">
            <thead><tr><th>Room Name</th><th>Capacity</th><th>Adults</th><th>Children</th><th>Age rules</th><th>Inventory</th><th>Rates</th><th></th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $accommodation->rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><strong><?php echo e($room->name); ?></strong></td>
                    <td><?php echo e($room->capacity); ?></td>
                    <td><?php echo e($room->max_adults); ?></td>
                    <td><?php echo e($room->max_children); ?></td>
                    <td><small>Baby 0-<?php echo e($room->baby_max_age ?? 2); ?> · Child <?php echo e($room->child_min_age ?? 3); ?>-<?php echo e($room->child_max_age ?? 11); ?> · Adult <?php echo e($room->adult_min_age ?? 12); ?>+</small></td>
                    <td><?php echo e($room->inventory); ?></td>
                    <td><?php echo e($room->rates->count()); ?></td>
                    <td>
                        <form method="POST" action="<?php echo e(route('admin.accommodations-v2.rooms.destroy', [$accommodation, $room])); ?>" onsubmit="return confirm('Delete?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="row-action"><i data-lucide="trash-2"></i></button>
                        </form>
                    </td>
                </tr>
                
                <?php $__currentLoopData = $room->rates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr style="background:var(--bg-subtle);">
                    <td colspan="7" style="padding-left:2rem;">
                        <small><?php echo e($rate->season_name); ?>: <?php echo e($rate->currency); ?> <?php echo e(number_format($rate->rate,2)); ?> (<?php echo e($rate->meal_plan); ?>, <?php echo e($rate->valid_from->format('d/m/Y')); ?> - <?php echo e($rate->valid_to->format('d/m/Y')); ?>)</small>
                    </td>
                    <td>
                        <form method="POST" action="<?php echo e(route('admin.accommodations-v2.rates.destroy', [$accommodation, $room, $rate])); ?>" onsubmit="return confirm('Delete?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="row-action"><i data-lucide="x"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                
                <tr>
                    <td colspan="8" style="padding:0.5rem 1rem;">
                        <form method="POST" action="<?php echo e(route('admin.accommodations-v2.rates.store', [$accommodation, $room])); ?>" style="display:flex;gap:0.5rem;align-items:end;flex-wrap:wrap;">
                            <?php echo csrf_field(); ?>
                            <input name="season_name" placeholder="Season name" required style="width:120px;">
                            <input type="date" name="valid_from" required style="width:130px;">
                            <input type="date" name="valid_to" required style="width:130px;">
                            <input name="meal_plan" placeholder="Meal plan" value="Full Board" style="width:110px;">
                            <input type="number" step="0.01" name="rate" placeholder="Rate" required style="width:100px;">
                            <input name="currency" value="USD" style="width:60px;">
                            <button class="button button-sm button-primary">Add Rate</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</section>
<?php endif; ?>

<?php $__env->startPush('styles'); ?>
<style>
.checkbox-label { display: flex; align-items: center; gap: 0.5rem; cursor: pointer; }
.checkbox-label input { width: auto; }
.hidden { display: none; }
.row-action { border: none; background: none; cursor: pointer; color: var(--text-muted); padding: 0.25rem; }
.row-action:hover { color: #ef4444; }
</style>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\accommodations-v2\form.blade.php ENDPATH**/ ?>