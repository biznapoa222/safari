<?php $__env->startSection('title', 'Contact | Shishi Footsteps'); ?>
<?php $__env->startSection('description', 'Contact Shishi Footsteps to plan a private luxury safari.'); ?>

<?php $__env->startSection('content'); ?>
<?php ($cms = fn($key, $fallback='') => \App\Models\CmsContentBlock::value('contact', $key, $fallback)); ?>
<?php ($global = fn($key, $fallback='') => \App\Models\CmsContentBlock::value('global', $key, $fallback)); ?>
<?php if (isset($component)) { $__componentOriginal7667e390c55120bda1fc27c0189959e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7667e390c55120bda1fc27c0189959e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.page-hero','data' => ['label' => 'Contact','title' => $cms('hero_title','Let us begin with a conversation.'),'subtitle' => $cms('hero_subtitle'),'image' => \App\Support\MediaPath::publicUrl($cms('hero_image'))]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.page-hero'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Contact','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($cms('hero_title','Let us begin with a conversation.')),'subtitle' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($cms('hero_subtitle')),'image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(\App\Support\MediaPath::publicUrl($cms('hero_image')))]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.section-label','data' => ['label' => 'Get In Touch']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.section-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Get In Touch']); ?>
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
        <h2><?php echo e($cms('intro_title')); ?></h2><p><?php echo e($cms('intro_text')); ?></p>
        <div class="contact-detail"><span><i data-lucide="phone"></i></span><div><small>Call or WhatsApp</small><strong><?php echo e($global('phone','+254 725 346 022')); ?></strong></div></div>
        <div class="contact-detail"><span><i data-lucide="mail"></i></span><div><small>General Inquiries</small><strong><?php echo e($global('email','info@shishifootsteps.com')); ?></strong></div></div>
        <div class="contact-detail"><span><i data-lucide="mail"></i></span><div><small>Bookings</small><strong><?php echo e($global('bookings_email','bookings@shishifootsteps.com')); ?></strong></div></div>
        <div class="contact-detail"><span><i data-lucide="map-pin"></i></span><div><small>Office</small><strong><?php echo e($global('address','Nairobi, Kenya')); ?></strong></div></div>
    </div>
    <?php if (isset($component)) { $__componentOriginal771fbdc5784701249b7145c5a5a7483c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal771fbdc5784701249b7145c5a5a7483c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.inquiry-form','data' => ['destinations' => $destinations]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.inquiry-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['destinations' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($destinations)]); ?>
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

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\public\contact.blade.php ENDPATH**/ ?>