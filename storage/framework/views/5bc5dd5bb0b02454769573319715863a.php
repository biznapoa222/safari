<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'label' => 'Welcome to Shishi Footsteps',
    'title',
    'subtitle' => null,
    'image',
    'primaryText' => 'Plan Your Safari',
    'primaryUrl' => null,
    'secondaryText' => null,
    'secondaryUrl' => null,
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
    'label' => 'Welcome to Shishi Footsteps',
    'title',
    'subtitle' => null,
    'image',
    'primaryText' => 'Plan Your Safari',
    'primaryUrl' => null,
    'secondaryText' => null,
    'secondaryUrl' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<section class="luxury-hero">
    <a href="<?php echo e($primaryUrl ?? route('public.booking')); ?>" class="luxury-hero-image-link" aria-label="<?php echo e($primaryText); ?>"><picture class="luxury-hero-media">
        <source srcset="<?php echo e($image); ?>" type="image/webp">
        <img src="<?php echo e($image); ?>" alt="<?php echo e($title); ?>" fetchpriority="high">
    </picture></a>
    <div class="luxury-hero-shade"></div>
    <div class="luxury-hero-content">
        <?php if (isset($component)) { $__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.section-label','data' => ['label' => $label,'class' => 'light']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.section-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($label),'class' => 'light']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4)): ?>
<?php $attributes = $__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4; ?>
<?php unset($__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4)): ?>
<?php $component = $__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4; ?>
<?php unset($__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4); ?>
<?php endif; ?>
        <h1><?php echo e($title); ?></h1>
        <?php if($subtitle): ?><p><?php echo e($subtitle); ?></p><?php endif; ?>
        <div class="hero-actions">
            <a href="<?php echo e($primaryUrl ?? route('public.booking')); ?>" class="button hero-primary"><?php echo e($primaryText); ?><i data-lucide="arrow-up-right"></i></a>
            <?php if($secondaryText): ?>
                <a href="<?php echo e($secondaryUrl ?? route('public.destinations')); ?>" class="button hero-secondary"><?php echo e($secondaryText); ?></a>
            <?php endif; ?>
        </div>
    </div>
    <a href="#start" class="scroll-cue"><span>Scroll</span><i data-lucide="arrow-down"></i></a>
</section>
<?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\components\public\hero-section.blade.php ENDPATH**/ ?>