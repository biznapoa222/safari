<?php $__env->startSection('title', $activity ? 'Edit Activity' : 'Create Activity'); ?>
<?php $__env->startSection('content'); ?>
<div class="page-heading">
    <div>
        <p class="eyebrow">Activity Management</p>
        <h1><?php echo e($activity ? 'Edit: '.$activity->name : 'New Activity'); ?></h1>
    </div>
    <div class="heading-actions">
        <?php if($activity): ?>
            <a href="<?php echo e(route('admin.activities.preview', $activity)); ?>" class="button button-secondary"><i data-lucide="eye"></i>Preview</a>
        <?php endif; ?>
    </div>
</div>

<?php echo $__env->make('admin.partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<form method="POST" action="<?php echo e($activity ? route('admin.activities.update', $activity) : route('admin.activities.store')); ?>" class="ops-form">
    <?php echo csrf_field(); ?> <?php if($activity): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

    
    <section class="ops-panel">
        <div class="ops-panel-title"><h2>Basic Information</h2></div>
        <div class="ops-form-grid">
            <label class="span-2">Activity Name (English)<input name="name" value="<?php echo e(old('name', $activity->name ?? '')); ?>" required></label>
            <label>Country
                <select name="country">
                    <?php $__currentLoopData = ['Kenya','Tanzania','Uganda','South Africa','Namibia','Botswana']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($c); ?>" <?php if(old('country', $activity->country ?? '') === $c): echo 'selected'; endif; ?>><?php echo e($c); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </label>
            <label>Region<input name="region" value="<?php echo e(old('region', $activity->region ?? '')); ?>"></label>
            <label>Location<input name="location" value="<?php echo e(old('location', $activity->location ?? '')); ?>" required></label>
            <label>Category
                <select name="activity_category_id">
                    <option value="">-- Select --</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cat->id); ?>" <?php if(old('activity_category_id', $activity->activity_category_id ?? '') == $cat->id): echo 'selected'; endif; ?>><?php echo e($cat->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </label>
            <label>Activity Type
                <select name="activity_status">
                    <option value="active" <?php if(old('activity_status', $activity->activity_status ?? 'active') === 'active'): echo 'selected'; endif; ?>>Active</option>
                    <option value="inactive" <?php if(old('activity_status', $activity->activity_status ?? '') === 'inactive'): echo 'selected'; endif; ?>>Inactive</option>
                </select>
            </label>
            <label>Min Pax<input type="number" name="min_pax" value="<?php echo e(old('min_pax', $activity->min_pax ?? '')); ?>" min="1"></label>
            <label>Min Age<input type="number" name="min_age" value="<?php echo e(old('min_age', $activity->min_age ?? '')); ?>" min="0"></label>
            <label>Duration (hours)<input type="number" name="duration_hours" value="<?php echo e(old('duration_hours', $activity->duration_hours ?? '')); ?>" min="1"></label>
            <label>Pickup Time<input name="pickup_time" value="<?php echo e(old('pickup_time', $activity->pickup_time ?? '')); ?>" placeholder="e.g. 07:00"></label>
            <label>Currency
                <select name="currency">
                    <?php $__currentLoopData = ['USD','EUR','GBP','KES','AUD','CAD']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cur); ?>" <?php if(old('currency', $activity->currency ?? 'USD') === $cur): echo 'selected'; endif; ?>><?php echo e($cur); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </label>
        </div>
    </section>

    
    <section class="ops-panel">
        <div class="ops-panel-title"><h2>Description</h2></div>
        <div class="ops-form-grid">
            <label class="span-2">Description<textarea name="description" rows="6"><?php echo e(old('description', $activity->description ?? '')); ?></textarea></label>
            <label class="span-2">Keywords<textarea name="keywords" rows="2" placeholder="Comma-separated"><?php echo e(old('keywords', $activity->keywords ?? '')); ?></textarea></label>
            <label class="span-2">Tags<textarea name="tags" rows="2" placeholder="Comma-separated"><?php echo e(old('tags', $activity->tags ?? '')); ?></textarea></label>
        </div>
    </section>

    
    <?php if($activity): ?>
    <section class="ops-panel">
        <div class="ops-panel-title"><h2>Status & Publishing</h2></div>
        <div class="ops-form-grid">
            <label class="checkbox-label"><input type="checkbox" name="published_on_website" value="1" <?php if(old('published_on_website', $activity->published_on_website)): echo 'checked'; endif; ?>> Published on Website</label>
            <label class="checkbox-label"><input type="checkbox" name="show_on_mobile_app" value="1" <?php if(old('show_on_mobile_app', $activity->show_on_mobile_app)): echo 'checked'; endif; ?>> Show on Mobile App</label>
            <label>Price Status Current Year<input name="price_status_current_year" value="<?php echo e(old('price_status_current_year', $activity->price_status_current_year ?? '')); ?>"></label>
            <label>Price Status Next Year<input name="price_status_next_year" value="<?php echo e(old('price_status_next_year', $activity->price_status_next_year ?? '')); ?>"></label>
            <label>Payment Scheme Status<input name="payment_scheme_status" value="<?php echo e(old('payment_scheme_status', $activity->payment_scheme_status ?? '')); ?>"></label>
        </div>
    </section>
    <?php endif; ?>

    
    <section class="ops-panel">
        <div class="ops-panel-title"><h2>Suppliers</h2></div>
        <div class="ops-form-grid">
            <div class="span-2">
                <select name="suppliers[]" multiple class="tag-select">
                    <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($supplier->id); ?>"
                            <?php if($activity && $activity->suppliers->contains($supplier->id)): echo 'selected'; endif; ?>>
                            <?php echo e($supplier->name); ?> (<?php echo e($supplier->type); ?>)
                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
    </section>

    <div class="ops-form-footer">
        <a href="<?php echo e(route('admin.activities.index')); ?>" class="button button-secondary">Cancel</a>
        <button class="button button-primary"><i data-lucide="save"></i><?php echo e($activity ? 'Update Activity' : 'Create Activity'); ?></button>
    </div>
</form>


<?php if($activity): ?>
<section class="ops-panel" style="margin-top: 2rem;">
    <div class="ops-panel-title"><h2>Translations</h2></div>
    <div class="ops-form-grid">
        <?php $locales = ['en'=>'English','nl'=>'Dutch','fr'=>'French','de'=>'German','es'=>'Spanish','sv'=>'Swedish','no'=>'Norwegian','da'=>'Danish','it'=>'Italian','pl'=>'Polish','pt'=>'Portuguese']; ?>
        <?php $__currentLoopData = $locales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="translation-card">
            <strong><?php echo e($label); ?> (<?php echo e($code); ?>)</strong>
            <?php $t = $activity->translations->where('locale', $code)->first(); ?>
            <form method="POST" action="<?php echo e(route('admin.activities.translations.store', $activity)); ?>" style="display:contents;">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="locale" value="<?php echo e($code); ?>">
                <input name="title" value="<?php echo e(old("titles.$code", $t->title ?? '')); ?>" placeholder="Title in <?php echo e($label); ?>">
                <textarea name="description" rows="2" placeholder="Description"><?php echo e(old("descriptions.$code", $t->description ?? '')); ?></textarea>
                <button class="button button-sm button-primary">Save</button>
            </form>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</section>


<section class="ops-panel" style="margin-top: 2rem;">
    <div class="ops-panel-title"><h2>Pricing</h2>
        <button class="button button-sm button-secondary" onclick="document.getElementById('price-form').classList.toggle('hidden')">Add Price</button>
    </div>
    <form id="price-form" method="POST" action="<?php echo e(route('admin.activities.prices.store', $activity)); ?>" class="hidden" style="margin-bottom:1rem;">
        <?php echo csrf_field(); ?>
        <div class="ops-form-grid">
            <label>Type
                <select name="type">
                    <?php $__currentLoopData = ['standard','resident','non_resident','child','group']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($pt); ?>"><?php echo e(ucwords(str_replace('_', ' ', $pt))); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </label>
            <label>Season
                <select name="season">
                    <?php $__currentLoopData = ['high','low','peak']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s); ?>"><?php echo e(ucfirst($s)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </label>
            <label>Year<input type="number" name="year" value="<?php echo e(date('Y')); ?>" min="2024" max="2099"></label>
            <label>Price<input type="number" step="0.01" name="price" required></label>
            <label>Currency<input name="currency" value="USD" maxlength="3"></label>
            <label>Valid From<input type="date" name="valid_from"></label>
            <label>Valid To<input type="date" name="valid_to"></label>
            <div class="span-2"><button class="button button-primary">Save Price</button></div>
        </div>
    </form>
    <?php if($activity->prices->count()): ?>
    <div class="table-wrap">
        <table class="ops-table">
            <thead><tr><th>Type</th><th>Season</th><th>Year</th><th>Price</th><th>Currency</th><th>Valid</th><th></th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $activity->prices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $price): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($price->type); ?></td>
                    <td><?php echo e(ucfirst($price->season)); ?></td>
                    <td><?php echo e($price->year); ?></td>
                    <td><strong><?php echo e(number_format($price->price, 2)); ?></strong></td>
                    <td><?php echo e($price->currency); ?></td>
                    <td><?php echo e($price->valid_from?->format('d/m/Y')); ?> - <?php echo e($price->valid_to?->format('d/m/Y')); ?></td>
                    <td>
                        <form method="POST" action="<?php echo e(route('admin.activities.prices.destroy', [$activity, $price])); ?>" onsubmit="return confirm('Delete?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="row-action"><i data-lucide="trash-2"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</section>


<section class="ops-panel" style="margin-top: 2rem;">
    <div class="ops-panel-title"><h2>Season Dates</h2>
        <button class="button button-sm button-secondary" onclick="document.getElementById('season-form').classList.toggle('hidden')">Add Season</button>
    </div>
    <form id="season-form" method="POST" action="<?php echo e(route('admin.activities.seasons.store', $activity)); ?>" class="hidden" style="margin-bottom:1rem;">
        <?php echo csrf_field(); ?>
        <div class="ops-form-grid">
            <label>Season
                <select name="name">
                    <?php $__currentLoopData = ['high'=>'High Season','low'=>'Low Season','peak'=>'Peak Season']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sk => $sl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($sk); ?>"><?php echo e($sl); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </label>
            <label>Start Date<input type="date" name="start_date" required></label>
            <label>End Date<input type="date" name="end_date" required></label>
            <div><button class="button button-primary">Save</button></div>
        </div>
    </form>
    <?php if($activity->seasons->count()): ?>
    <div class="table-wrap">
        <table class="ops-table">
            <thead><tr><th>Season</th><th>Start</th><th>End</th><th></th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $activity->seasons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $season): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e(ucfirst($season->name)); ?></td>
                    <td><?php echo e($season->start_date->format('d/m/Y')); ?></td>
                    <td><?php echo e($season->end_date->format('d/m/Y')); ?></td>
                    <td>
                        <form method="POST" action="<?php echo e(route('admin.activities.seasons.destroy', [$activity, $season])); ?>" onsubmit="return confirm('Delete?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="row-action"><i data-lucide="trash-2"></i></button>
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
.ops-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.span-2 { grid-column: span 2; }
.checkbox-label { display: flex; align-items: center; gap: 0.5rem; cursor: pointer; }
.checkbox-label input { width: auto; }
.tag-select { min-height: 120px; width: 100%; }
.translation-card { border: 1px solid var(--border); padding: 0.75rem; border-radius: 0.5rem; }
.translation-card input, .translation-card textarea { width: 100%; margin-top: 0.25rem; font-size: 0.85rem; }
.translation-card button { margin-top: 0.25rem; }
.hidden { display: none; }
.row-action { border: none; background: none; cursor: pointer; color: var(--text-muted); padding: 0.25rem; }
.row-action:hover { color: #ef4444; }
</style>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\activities\v2\form.blade.php ENDPATH**/ ?>