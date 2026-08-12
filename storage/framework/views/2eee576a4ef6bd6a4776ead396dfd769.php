<?php $__env->startSection('title', 'Plan Your Safari | Shishi Footsteps'); ?>
<?php $__env->startSection('description', 'Plan a tailor-made African safari with Shishi Footsteps.'); ?>

<?php $__env->startSection('content'); ?>
<?php $hero = 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=1800&q=82&fm=webp'; $cms=fn($key,$fallback='')=>\App\Models\CmsContentBlock::value('booking',$key,$fallback); $global=fn($key,$fallback='')=>\App\Models\CmsContentBlock::value('global',$key,$fallback); ?>

<?php if (isset($component)) { $__componentOriginal7667e390c55120bda1fc27c0189959e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7667e390c55120bda1fc27c0189959e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.page-hero','data' => ['label' => 'Private Safari Planning','title' => $cms('hero_title'),'subtitle' => $cms('hero_subtitle'),'image' => \App\Support\MediaPath::publicUrl($cms('hero_image',$hero))]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.page-hero'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Private Safari Planning','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($cms('hero_title')),'subtitle' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($cms('hero_subtitle')),'image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(\App\Support\MediaPath::publicUrl($cms('hero_image',$hero)))]); ?>
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

<section class="inquiry-section">
    <div class="inquiry-copy">
        <?php if (isset($component)) { $__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.section-label','data' => ['label' => 'Speak With A Specialist']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.section-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Speak With A Specialist']); ?>
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
        <h2>Tell us about your journey.</h2>
        <p>Complete the short form and a Shishi Footsteps specialist will contact you to refine the route, stays and experiences.</p>
        <div class="contact-detail"><span><i data-lucide="phone"></i></span><div><small>Call or WhatsApp</small><strong><?php echo e($global('phone','+254 725 346 022')); ?></strong></div></div>
        <div class="contact-detail"><span><i data-lucide="mail"></i></span><div><small>Email</small><strong><?php echo e($global('email','info@shishifootsteps.com')); ?></strong></div></div>
    </div>
    <?php if (isset($component)) { $__componentOriginal771fbdc5784701249b7145c5a5a7483c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal771fbdc5784701249b7145c5a5a7483c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.inquiry-form','data' => ['destinations' => $destinations,'selectedItinerary' => $selectedItinerary,'prefillDestination' => $prefillDestination]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.inquiry-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['destinations' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($destinations),'selected-itinerary' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($selectedItinerary),'prefill-destination' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($prefillDestination)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal771fbdc5784701249b7145c5a5a7483c)): ?>
<?php $attributes = $__attributesOriginal771fbdc5784701249b7145c5a5a7483c; ?>
<?php unset($__attributesOriginal771fbdc5784701249b7145c5a5a7483c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal771fbdc5784701249b7145c5a5a7483c)): ?>
<?php $component = $__componentOriginal771fbdc5784701249b7145c5a5a7483c; ?>
<?php unset($__componentOriginal771fbdc5784701249b7145c5a5a7483c); ?>
<?php endif; ?>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\public\booking.blade.php ENDPATH**/ ?>