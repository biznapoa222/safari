<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['placeholder' => 'Search templates...', 'endpoint' => route('admin.itinerary-templates.search')]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['placeholder' => 'Search templates...', 'endpoint' => route('admin.itinerary-templates.search')]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="search-dropdown search-dropdown--admin" data-endpoint="<?php echo e($endpoint); ?>" data-show-all="0">
    <label class="ops-search" style="flex:1;min-width:240px">
        <i data-lucide="search"></i>
        <input
            type="text"
            class="search-dropdown-input"
            placeholder="<?php echo e($placeholder); ?>"
            autocomplete="off"
        />
    </label>
    <div class="search-dropdown-results hidden" role="listbox" aria-label="Quick results" style="position:absolute;left:0;right:0;top:100%;margin-top:6px;background:#fff;border:1px solid #d9d0c1;border-radius:10px;box-shadow:0 12px 28px rgba(0,0,0,.1);z-index:999;max-height:340px;overflow-y:auto"></div>
</div>

<?php if (! $__env->hasRenderedOnce('c23fcc8b-9242-4dcd-8fa7-6638ecb04487')): $__env->markAsRenderedOnce('c23fcc8b-9242-4dcd-8fa7-6638ecb04487'); ?>
<?php $__env->startPush('styles'); ?>
<style>
.search-dropdown--admin .search-dropdown-results .sdr-item { display:flex;gap:10px;align-items:center;padding:9px 12px;border-bottom:1px solid #ede8df;cursor:pointer;font-size:10px;color:#3a3530;text-decoration:none; }
.search-dropdown--admin .search-dropdown-results .sdr-item:hover, .search-dropdown--admin .search-dropdown-results .sdr-item.is-active { background:#faf6ec; }
.search-dropdown--admin .search-dropdown-results .sdr-item strong { color:#234A36; }
.search-dropdown--admin .search-dropdown-results .sdr-meta { padding:8px 12px;font-size:7px;color:#6b6b6b;text-transform:uppercase;letter-spacing:.4px;background:#faf6ec;border-bottom:1px solid #ede8df; }
.search-dropdown--admin .search-dropdown-results .sdr-hint { padding:14px;color:#6b6b6b;font-size:9px;text-align:center; }
</style>
<?php $__env->stopPush(); ?>
<?php endif; ?>
<?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\components\admin\search-with-dropdown.blade.php ENDPATH**/ ?>