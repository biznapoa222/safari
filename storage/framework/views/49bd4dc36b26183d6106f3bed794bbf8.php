<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['title', 'description', 'image', 'url' => null]));

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

foreach (array_filter((['title', 'description', 'image', 'url' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<article class="destination-card">
    <a href="<?php echo e($url ?? route('public.safaris')); ?>" class="destination-card-media" aria-label="Explore <?php echo e($title); ?>"><img src="<?php echo e($image); ?>" alt="<?php echo e($title); ?>" loading="lazy"></a>
    <div class="card-shade"></div>
    <div>
        <h3><?php echo e($title); ?></h3>
        <p><?php echo e($description); ?></p>
        <a href="<?php echo e($url ?? route('public.safaris')); ?>">View Safaris<i data-lucide="arrow-up-right"></i></a>
    </div>
</article>
<?php /**PATH C:\shishifootsteps\safari\resources\views/components/public/destination-card.blade.php ENDPATH**/ ?>