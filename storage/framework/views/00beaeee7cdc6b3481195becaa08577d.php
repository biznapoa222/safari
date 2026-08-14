<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['title', 'summary', 'image', 'duration' => null, 'country' => null, 'price' => null, 'url' => null, 'slug' => null]));

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

foreach (array_filter((['title', 'summary', 'image', 'duration' => null, 'country' => null, 'price' => null, 'url' => null, 'slug' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<article class="safari-card">
    <a href="<?php echo e($url ?? ($slug ? route('public.safaris.show', $slug) : route('public.booking'))); ?>" class="safari-card-link">
        <img src="<?php echo e($image); ?>" alt="<?php echo e($title); ?>" loading="lazy">
        <div class="safari-card-body">
            <div class="safari-meta">
                <?php if($duration): ?><span><i data-lucide="calendar-days"></i><?php echo e($duration); ?></span><?php endif; ?>
                <?php if($country): ?><span><i data-lucide="map-pin"></i><?php echo e($country); ?></span><?php endif; ?>
            </div>
            <h3><?php echo e($title); ?></h3>
            <p><?php echo e($summary); ?></p>
            <div class="safari-card-footer">
                <span><?php echo e($price ? 'From '.$price : 'Tailor-made pricing'); ?></span>
                <span class="safari-card-cta">View itinerary<i data-lucide="arrow-up-right"></i></span>
            </div>
        </div>
    </a>
</article>
<?php /**PATH C:\shishifootsteps\safari\resources\views/components/public/safari-package-card.blade.php ENDPATH**/ ?>