<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title' => 'No records yet.',
    'description' => '',
    'addRoute' => null,
    'addLabel' => 'Add First Record',
    'addIcon' => 'plus',
    'action' => null,
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
    'title' => 'No records yet.',
    'description' => '',
    'addRoute' => null,
    'addLabel' => 'Add First Record',
    'addIcon' => 'plus',
    'action' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<section class="module-placeholder">
    <div class="placeholder-art"><i data-lucide="<?php echo e($addIcon); ?>"></i></div>
    <h2><?php echo e($title); ?></h2>
    <?php if($description): ?><p><?php echo e($description); ?></p><?php endif; ?>
    <div class="placeholder-actions">
        <?php if($action): ?>
            <button class="button button-primary" onclick="<?php echo e($action); ?>">
                <i data-lucide="<?php echo e($addIcon); ?>"></i><?php echo e($addLabel); ?>

            </button>
        <?php elseif($addRoute): ?>
            <a href="<?php echo e($addRoute); ?>" class="button button-primary">
                <i data-lucide="<?php echo e($addIcon); ?>"></i><?php echo e($addLabel); ?>

            </a>
        <?php endif; ?>
    </div>
</section>

<?php $__env->startPush('styles'); ?>
<style>
.module-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 4rem 2rem;
    text-align: center;
    min-height: 300px;
    border: 2px dashed var(--border);
    border-radius: 1rem;
    background: var(--bg-subtle);
}
.placeholder-art {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: var(--primary-light);
    color: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.5rem;
}
.placeholder-art i { width: 32px; height: 32px; }
.module-placeholder h2 { font-size: 1.25rem; font-weight: 600; margin: 0 0 0.5rem; }
.module-placeholder p { color: var(--text-muted); font-size: 0.9rem; margin: 0 0 1.5rem; max-width: 400px; }
.placeholder-actions { display: flex; gap: 0.75rem; }
</style>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\components\admin\empty-state.blade.php ENDPATH**/ ?>