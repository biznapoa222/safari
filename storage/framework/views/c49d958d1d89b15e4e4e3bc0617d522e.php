<?php $__env->startSection('title', __('catalogue.title')); ?>
<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginalad67f78bf768badc17b2fc4005a4f8bf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad67f78bf768badc17b2fc4005a4f8bf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.top-bar','data' => ['title' => __('catalogue.title'),'description' => __('catalogue.description'),'addLabel' => __('catalogue.new'),'addRoute' => ''.e(route('admin.activities.create')).'','searchPlaceholder' => __('catalogue.search_placeholder')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.top-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('catalogue.title')),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('catalogue.description')),'addLabel' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('catalogue.new')),'addRoute' => ''.e(route('admin.activities.create')).'','searchPlaceholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('catalogue.search_placeholder'))]); ?>
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


<div class="country-tabs">
    <?php $countries = ['Kenya', 'Tanzania', 'Uganda', 'South Africa', 'Namibia', 'Botswana']; ?>
    <a href="<?php echo e(route('admin.activities.index')); ?>" class="<?php echo e(!request('country') ? 'is-active' : ''); ?>"><?php echo e(__('catalogue.all')); ?></a>
    <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('admin.activities.index', array_merge(request()->query(), ['country' => $c]))); ?>"
           class="<?php echo e(request('country') === $c ? 'is-active' : ''); ?>"><?php echo e($c); ?></a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>


<form class="ops-filters" method="GET">
    <label class="ops-search">
        <i data-lucide="search"></i>
        <input name="search" value="<?php echo e(request('search')); ?>" placeholder="<?php echo e(__('catalogue.search_placeholder')); ?>">
    </label>
    <select name="status" onchange="this.form.submit()">
        <option value=""><?php echo e(__('catalogue.all_statuses')); ?></option>
        <option value="active" <?php if(request('status') === 'active'): echo 'selected'; endif; ?>><?php echo e(__('catalogue.active')); ?></option>
        <option value="inactive" <?php if(request('status') === 'inactive'): echo 'selected'; endif; ?>><?php echo e(__('catalogue.inactive')); ?></option>
    </select>
    <button class="button button-primary"><?php echo e(__('catalogue.search')); ?></button>
</form>


<div class="table-wrap">
    <table class="ops-table activity-table">
        <thead>
            <tr>
                <th><?php echo e(__('catalogue.activity_title')); ?> (<?php echo e(strtoupper(app()->getLocale())); ?>)</th>
                <th><?php echo e(__('catalogue.min_pax')); ?></th><th><?php echo e(__('catalogue.min_age')); ?></th><th><?php echo e(__('catalogue.location')); ?></th>
                <th><?php echo e(__('catalogue.price_cy')); ?></th><th><?php echo e(__('catalogue.price_ny')); ?></th><th><?php echo e(__('catalogue.payment_scheme')); ?></th>
                <th><?php echo e(__('catalogue.status')); ?></th><th><?php echo e(__('catalogue.currency')); ?></th><th><?php echo e(__('catalogue.published')); ?></th><th><?php echo e(__('catalogue.mobile_app')); ?></th><th><?php echo e(__('catalogue.actions')); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php $localized=$activity->translation(app()->getLocale()); ?>
            <tr>
                <td><strong><?php echo e($localized?->title ?? $activity->name); ?></strong><?php if($localized?->description): ?><small><?php echo e(\Illuminate\Support\Str::limit($localized->description,70)); ?></small><?php endif; ?></td>
                <td><?php echo e($activity->min_pax ?? '-'); ?></td>
                <td><?php echo e($activity->min_age ?? '-'); ?></td>
                <td><?php echo e($localized?->location ?? $activity->location); ?><small><?php echo e(($localized?->region ?? $activity->region) ? ', '.($localized?->region ?? $activity->region) : ''); ?></small></td>
                <td><?php echo e($activity->price_status_current_year ?? '-'); ?></td>
                <td><?php echo e($activity->price_status_next_year ?? '-'); ?></td>
                <td><?php echo e($activity->payment_scheme_status ?? '-'); ?></td>
                <td><span class="status status--<?php echo e($activity->activity_status); ?>"><?php echo e(__('catalogue.'.$activity->activity_status)); ?></span></td>
                <td><?php echo e($activity->currency); ?></td>
                <td><?php if($activity->published_on_website): ?><i data-lucide="check-circle" class="text-green"></i><?php else: ?><i data-lucide="x-circle" class="text-red"></i><?php endif; ?></td>
                <td><?php if($activity->show_on_mobile_app): ?><i data-lucide="check-circle" class="text-green"></i><?php else: ?><i data-lucide="x-circle" class="text-red"></i><?php endif; ?></td>
                <td>
                    <div class="ops-actions">
                        <a href="<?php echo e(route('admin.activities.edit', $activity)); ?>" title="Edit"><i data-lucide="square-pen"></i></a>
                        <a href="<?php echo e(route('admin.activities.preview', $activity)); ?>" title="Preview"><i data-lucide="eye"></i></a>
                        <a href="<?php echo e(route('admin.activities.payment-scheme.edit', $activity)); ?>" title="Payment Scheme"><i data-lucide="credit-card"></i></a>
                        <form method="POST" action="<?php echo e(route('admin.activities.destroy', $activity)); ?>" onsubmit="return confirm('Soft-delete this activity?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button title="Delete"><i data-lucide="trash-2"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="12" class="text-center text-muted"><?php echo e(__('catalogue.none')); ?></td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<div class="ops-pagination"><?php echo e($activities->links()); ?></div>

<?php $__env->startPush('styles'); ?>
<style>
.country-tabs { display: flex; gap: 0; margin-bottom: 1.5rem; border-bottom: 2px solid var(--border); overflow-x: auto; }
.country-tabs a { padding: 0.6rem 1.2rem; font-size: 0.85rem; font-weight: 500; color: var(--text-muted); border-bottom: 2px solid transparent; margin-bottom: -2px; transition: all 0.2s; white-space: nowrap; text-decoration: none; }
.country-tabs a:hover, .country-tabs a.is-active { color: var(--primary); border-bottom-color: var(--primary); }
.activity-table { font-size: 0.8rem; }
.activity-table th, .activity-table td { padding: 0.5rem 0.4rem; }
.activity-table td small { display: block; font-size: 0.7rem; color: var(--text-muted); }
.text-green { color: #22c55e; width: 16px; height: 16px; }
.text-red { color: #ef4444; width: 16px; height: 16px; }
.ops-actions { display: flex; gap: 0.25rem; align-items: center; }
.ops-actions a, .ops-actions button { padding: 0.25rem; border: none; background: none; cursor: pointer; color: var(--text-muted); }
.ops-actions a:hover, .ops-actions button:hover { color: var(--primary); }
</style>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\shishifootsteps\safari\resources\views/admin/activities/v2/index.blade.php ENDPATH**/ ?>