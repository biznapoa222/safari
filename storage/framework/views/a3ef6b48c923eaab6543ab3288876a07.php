<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['title', 'description', 'image', 'icon' => 'sparkles', 'url' => null]));

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

foreach (array_filter((['title', 'description', 'image', 'icon' => 'sparkles', 'url' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<article class="experience-card">
    <a href="<?php echo e($url ?? route('public.experiences')); ?>" class="experience-card-link">
        <img src="<?php echo e($image); ?>" alt="<?php echo e($title); ?>" loading="lazy">
        <div>
            <span><i data-lucide="<?php echo e($icon); ?>"></i></span>
            <h3><?php echo e($title); ?></h3>
            <p><?php echo e($description); ?></p>
        </div>
    </a>
</article>
<?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\components\public\experience-card.blade.php ENDPATH**/ ?>