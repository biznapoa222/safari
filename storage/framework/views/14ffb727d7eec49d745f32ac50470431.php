<?php $__env->startSection('title', $sectionData['title'].' | Shishi Footsteps'); ?>
<?php $__env->startSection('description', $sectionData['summary']); ?>

<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginal7667e390c55120bda1fc27c0189959e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7667e390c55120bda1fc27c0189959e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.page-hero','data' => ['label' => $sectionData['eyebrow'],'title' => $sectionData['title'],'subtitle' => $sectionData['summary'],'image' => $sectionData['image'],'url' => route('public.booking', ['destination' => $name, 'interest' => $sectionData['nav']])]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.page-hero'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sectionData['eyebrow']),'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sectionData['title']),'subtitle' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sectionData['summary']),'image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sectionData['image']),'url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('public.booking', ['destination' => $name, 'interest' => $sectionData['nav']]))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7667e390c55120bda1fc27c0189959e9)): ?>
<?php $attributes = $__attributesOriginal7667e390c55120bda1fc27c0189959e9; ?>
<?php unset($__attributesOriginal7667e390c55120bda1fc27c0189959e9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7667e390c55120bda1fc27c0189959e9)): ?>
<?php $component = $__componentOriginal7667e390c55120bda1fc27c0189959e9; ?>
<?php unset($__componentOriginal7667e390c55120bda1fc27c0189959e9); ?>
<?php endif; ?>

<nav class="country-breadcrumb">
    <a href="<?php echo e(route('home')); ?>">Home</a><span>&rsaquo;</span>
    <a href="<?php echo e(route('public.destinations')); ?>">Countries</a><span>&rsaquo;</span>
    <a href="<?php echo e(route('public.destinations.show', $slug)); ?>"><?php echo e($name); ?></a><span>&rsaquo;</span>
    <b><?php echo e($sectionData['nav']); ?></b>
</nav>

<section class="destination-section-page">
    <aside class="destination-section-menu">
        <strong><?php echo e($name); ?> guide</strong>
        <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('public.destinations.section', [$slug, $key])); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['active' => $key === $section]); ?>">
                <?php echo e($item['nav']); ?>

            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php if(in_array($slug, ['kenya', 'tanzania', 'uganda', 'rwanda', 'south-africa'], true)): ?>
            <a href="<?php echo e(route('public.tee-off.country', $slug)); ?>">Golf safari</a>
        <?php else: ?>
            <a href="<?php echo e(route('public.golf')); ?>">Golf safari</a>
        <?php endif; ?>
    </aside>

    <article class="destination-section-story">
        <?php if (isset($component)) { $__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.section-label','data' => ['label' => $sectionData['eyebrow']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.section-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sectionData['eyebrow'])]); ?>
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
        <h2><?php echo e($sectionData['heading']); ?></h2>
        <?php $__currentLoopData = $sectionData['paragraphs']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $paragraph): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <p><?php echo e($paragraph); ?></p>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <div class="destination-section-points">
            <?php $__currentLoopData = $sectionData['bullets']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bullet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <span><i data-lucide="check"></i><?php echo e($bullet); ?></span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="destination-section-actions">
            <a href="<?php echo e(route('public.booking', ['destination' => $name, 'interest' => $sectionData['nav']])); ?>" class="button hero-primary">Plan your <?php echo e($name); ?> trip<i data-lucide="arrow-up-right"></i></a>
            <a href="<?php echo e(route('public.destinations.show', $slug)); ?>" class="button hero-secondary">View full <?php echo e($name); ?> overview<i data-lucide="arrow-up-right"></i></a>
        </div>
    </article>
</section>

<?php if($safaris->isNotEmpty() || $activities->isNotEmpty() || $accommodations->isNotEmpty()): ?>
    <section class="content-band destination-related">
        <div class="section-heading">
            <div>
                <?php if (isset($component)) { $__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.section-label','data' => ['label' => 'Related ideas']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.section-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Related ideas']); ?>
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
                <h2><?php echo e($name); ?> experiences to explore</h2>
            </div>
        </div>
        <div class="destination-related-grid">
            <?php $__currentLoopData = $safaris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $safari): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('public.safaris.show', $safari->slug)); ?>" class="destination-related-card">
                    <img src="<?php echo e(is_array($safari->images ?? null) && count($safari->images) ? \App\Support\MediaPath::publicUrl($safari->images[0]) : asset('images/itineraries/kenya-family-cover.webp')); ?>" alt="<?php echo e($safari->title); ?>" loading="lazy">
                    <small>Safari</small>
                    <strong><?php echo e($safari->title); ?></strong>
                    <span><?php echo e($safari->duration_days); ?> days</span>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php $__currentLoopData = $accommodations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $accommodation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e($accommodation->slug ? route('public.accommodations.show', $accommodation->slug) : route('public.accommodations')); ?>" class="destination-related-card">
                    <img src="<?php echo e(is_array($accommodation->images ?? null) && count($accommodation->images) ? \App\Support\MediaPath::publicUrl($accommodation->images[0]) : asset('images/itineraries/botswana-luxury-cover.webp')); ?>" alt="<?php echo e($accommodation->name); ?>" loading="lazy">
                    <small>Accommodation</small>
                    <strong><?php echo e($accommodation->name); ?></strong>
                    <span><?php echo e(trim(($accommodation->region ?? '').' / '.($accommodation->type ?? ''), ' /') ?: $name); ?></span>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $actImg = is_array($activity->images ?? null) && count($activity->images) ? $activity->images[0] : ($activity->image ?? null); ?>
                <a href="<?php echo e($activity->slug ? route('public.experiences.show', $activity->slug) : route('public.experiences')); ?>" class="destination-related-card">
                    <img src="<?php echo e($actImg ? \App\Support\MediaPath::publicUrl($actImg) : asset('images/itineraries/kenya-coast-day.webp')); ?>" alt="<?php echo e($activity->name); ?>" loading="lazy">
                    <small>Activity</small>
                    <strong><?php echo e($activity->name); ?></strong>
                    <span><?php echo e($activity->location ?: $name); ?></span>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </section>
<?php endif; ?>

<?php if (isset($component)) { $__componentOriginala13c86f470d2a58cc232c97c825cd90e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala13c86f470d2a58cc232c97c825cd90e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.cta-section','data' => ['label' => ''.e($name).' Safari','title' => 'Ready to shape this into a real itinerary?','text' => 'Tell us your dates, travellers and travel style. We will turn the right '.e($name).' ideas into a smooth private journey.','image' => $sectionData['image'],'buttonText' => 'Plan your trip','url' => route('public.booking', ['destination' => $name, 'interest' => $sectionData['nav']])]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.cta-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => ''.e($name).' Safari','title' => 'Ready to shape this into a real itinerary?','text' => 'Tell us your dates, travellers and travel style. We will turn the right '.e($name).' ideas into a smooth private journey.','image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sectionData['image']),'buttonText' => 'Plan your trip','url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('public.booking', ['destination' => $name, 'interest' => $sectionData['nav']]))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala13c86f470d2a58cc232c97c825cd90e)): ?>
<?php $attributes = $__attributesOriginala13c86f470d2a58cc232c97c825cd90e; ?>
<?php unset($__attributesOriginala13c86f470d2a58cc232c97c825cd90e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala13c86f470d2a58cc232c97c825cd90e)): ?>
<?php $component = $__componentOriginala13c86f470d2a58cc232c97c825cd90e; ?>
<?php unset($__componentOriginala13c86f470d2a58cc232c97c825cd90e); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\public\destination-section.blade.php ENDPATH**/ ?>