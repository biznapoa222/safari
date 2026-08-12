<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <?php $settings = $settings ?? \App\Models\WebsiteSetting::home(); ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="description" content="<?php echo $__env->yieldContent('description', $settings->seo_description ?? 'Tailor-made luxury safari journeys across East and Southern Africa.'); ?>">
    <meta property="og:title" content="<?php echo $__env->yieldContent('title', $settings->seo_title ?? 'Shishi Footsteps | Luxury African Safaris'); ?>">
    <meta property="og:description" content="<?php echo $__env->yieldContent('description', $settings->seo_description ?? 'Plan a private luxury safari with Shishi Footsteps.'); ?>">
    <meta property="og:image" content="<?php echo $__env->yieldContent('og_image', $settings->open_graph_image ?? $settings->hero_image); ?>">
    <title><?php echo $__env->yieldContent('title', $settings->seo_title ?? 'Shishi Footsteps | Luxury African Safaris'); ?></title>
    <link rel="icon" href="<?php echo e(asset('favicon.ico')); ?>" sizes="any">
    <link rel="icon" type="image/png" sizes="512x512" href="<?php echo e(asset('images/brand/favicon-512.png')); ?>">
    <link rel="apple-touch-icon" href="<?php echo e(asset('images/brand/apple-touch-icon.png')); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Great+Vibes&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="public-body">
    <?php if (isset($component)) { $__componentOriginalb146cbf8306c95b172d2591af732a390 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb146cbf8306c95b172d2591af732a390 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.header','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb146cbf8306c95b172d2591af732a390)): ?>
<?php $attributes = $__attributesOriginalb146cbf8306c95b172d2591af732a390; ?>
<?php unset($__attributesOriginalb146cbf8306c95b172d2591af732a390); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb146cbf8306c95b172d2591af732a390)): ?>
<?php $component = $__componentOriginalb146cbf8306c95b172d2591af732a390; ?>
<?php unset($__componentOriginalb146cbf8306c95b172d2591af732a390); ?>
<?php endif; ?>

    <?php echo $__env->yieldContent('content'); ?>

    <?php if (isset($component)) { $__componentOriginalbb84be681bbe94cc31d6257779433433 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbb84be681bbe94cc31d6257779433433 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.footer','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbb84be681bbe94cc31d6257779433433)): ?>
<?php $attributes = $__attributesOriginalbb84be681bbe94cc31d6257779433433; ?>
<?php unset($__attributesOriginalbb84be681bbe94cc31d6257779433433); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbb84be681bbe94cc31d6257779433433)): ?>
<?php $component = $__componentOriginalbb84be681bbe94cc31d6257779433433; ?>
<?php unset($__componentOriginalbb84be681bbe94cc31d6257779433433); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginal9262a24b454ab77beba8f5dbdedb24c8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9262a24b454ab77beba8f5dbdedb24c8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.chat-widget','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.chat-widget'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9262a24b454ab77beba8f5dbdedb24c8)): ?>
<?php $attributes = $__attributesOriginal9262a24b454ab77beba8f5dbdedb24c8; ?>
<?php unset($__attributesOriginal9262a24b454ab77beba8f5dbdedb24c8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9262a24b454ab77beba8f5dbdedb24c8)): ?>
<?php $component = $__componentOriginal9262a24b454ab77beba8f5dbdedb24c8; ?>
<?php unset($__componentOriginal9262a24b454ab77beba8f5dbdedb24c8); ?>
<?php endif; ?>
    <script src="https://unpkg.com/lucide@0.468.0/dist/umd/lucide.min.js"></script>
</body>
</html>
<?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\layouts\public.blade.php ENDPATH**/ ?>