<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title' => '',
    'description' => '',
    'search' => true,
    'searchPlaceholder' => 'Search...',
    'searchValue' => '',
    'addRoute' => null,
    'addLabel' => 'Add New',
    'addIcon' => 'plus',
    'addButton' => true,
    'addOnclick' => null,
    'filters' => false,
    'extraActions' => '',
]));

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

foreach (array_filter(([
    'title' => '',
    'description' => '',
    'search' => true,
    'searchPlaceholder' => 'Search...',
    'searchValue' => '',
    'addRoute' => null,
    'addLabel' => 'Add New',
    'addIcon' => 'plus',
    'addButton' => true,
    'addOnclick' => null,
    'filters' => false,
    'extraActions' => '',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="page-heading">
    <div>
        <?php if($description): ?><p class="eyebrow"><?php echo e($description); ?></p><?php endif; ?>
        <h1><?php echo e($title); ?></h1>
        <?php echo e($slot ?? ''); ?>

    </div>
    <div class="heading-actions">
        <?php echo e($extraActions); ?>

        <?php if(isset($actions)): ?><?php echo e($actions); ?><?php endif; ?>
        <?php if($search): ?>
        <form method="GET" class="inline-search" style="display:contents;">
            <?php $__currentLoopData = request()->except('search', 'page'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <input type="hidden" name="<?php echo e($k); ?>" value="<?php echo e($v); ?>">
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <label class="ops-search">
                <i data-lucide="search"></i>
                <input name="search" value="<?php echo e($searchValue ?: request('search')); ?>" placeholder="<?php echo e($searchPlaceholder); ?>">
            </label>
            <button class="button button-secondary button-sm" style="display:none;">Go</button>
        </form>
        <?php endif; ?>
        <?php if($filters && !($filters instanceof \Illuminate\Support\HtmlString)): ?>
            <?php echo e($filters); ?>

        <?php endif; ?>
        <?php if($addButton): ?>
            <?php if($addOnclick): ?>
            <button class="button button-primary" onclick="<?php echo e($addOnclick); ?>">
                <i data-lucide="<?php echo e($addIcon); ?>"></i><?php echo e($addLabel); ?>

            </button>
            <?php elseif($addRoute): ?>
            <a href="<?php echo e($addRoute); ?>" class="button button-primary">
                <i data-lucide="<?php echo e($addIcon); ?>"></i><?php echo e($addLabel); ?>

            </a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
<style>
.page-heading {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}
.page-heading h1 {
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0;
}
.page-heading .eyebrow {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--text-muted);
    margin-bottom: 0.25rem;
}
.heading-actions {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
    flex-shrink: 0;
}
.inline-search { display: flex; align-items: center; gap: 0.5rem; }
.ops-search {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.4rem 0.75rem;
    border: 1px solid var(--border);
    border-radius: 0.5rem;
    background: var(--bg);
}
.ops-search input {
    border: none;
    background: none;
    outline: none;
    font-size: 0.85rem;
    min-width: 180px;
}
.ops-search i { color: var(--text-muted); width: 16px; height: 16px; }
.button { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border-radius: 0.5rem; font-weight: 600; font-size: 0.85rem; cursor: pointer; border: none; text-decoration: none; transition: all 0.15s; }
.button-primary { background: #234A36; color: #fff; }
.button-primary:hover { background: #1a3829; }
.button-primary:active { background: #142d20; }
.button-secondary { background: #F8F5EF; color: #3a3530; border: 1px solid #c9c0b2; }
.button-secondary:hover { background: #ede8df; border-color: #b5aa99; }
.button-sm { padding: 0.35rem 0.75rem; font-size: 0.8rem; }
@media (max-width: 768px) {
    .page-heading { flex-direction: column; }
    .heading-actions { width: 100%; }
    .ops-search { flex: 1; }
}
</style>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\components\admin\top-bar.blade.php ENDPATH**/ ?>