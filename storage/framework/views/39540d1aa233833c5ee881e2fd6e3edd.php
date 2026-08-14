<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['label', 'title', 'subtitle' => null, 'image', 'url' => null, 'youtubeId' => null]));

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

foreach (array_filter((['label', 'title', 'subtitle' => null, 'image', 'url' => null, 'youtubeId' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<section <?php echo e($attributes->merge(['class' => 'page-hero'])); ?>>
    <?php if($url): ?>
        <a href="<?php echo e($url); ?>" class="page-hero-image-link" aria-label="Explore <?php echo e($title); ?>"><img src="<?php echo e($image); ?>" alt="<?php echo e($title); ?>" loading="eager"></a>
    <?php else: ?>
        <div class="page-hero-image-link"><img src="<?php echo e($image); ?>" alt="<?php echo e($title); ?>" loading="eager"></div>
    <?php endif; ?>
    <?php if($youtubeId): ?>
        <div class="hero-youtube-container" data-hero-video>
            <iframe
                class="hero-youtube-bg"
                src="https://www.youtube.com/embed/<?php echo e($youtubeId); ?>?autoplay=1&mute=1&loop=1&playlist=<?php echo e($youtubeId); ?>&controls=0&showinfo=0&rel=0&modestbranding=1&playsinline=1&enablejsapi=1"
                title="<?php echo e($title); ?> background video"
                allow="autoplay; encrypted-media; picture-in-picture"
                referrerpolicy="strict-origin-when-cross-origin"
                aria-hidden="true"
                tabindex="-1"></iframe>
        </div>
    <?php endif; ?>
    <div></div>
    <article>
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
    </article>
</section>
<?php /**PATH C:\shishifootsteps\safari\resources\views/components/public/page-hero.blade.php ENDPATH**/ ?>