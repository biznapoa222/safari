<?php $__env->startSection('title', 'Accommodation | Shishi Footsteps'); ?>
<?php $__env->startSection('description', 'Luxury lodges, tented camps and private retreats selected for Shishi Footsteps safari journeys.'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $cms = fn($key,$fallback='') => \App\Models\CmsContentBlock::value('accommodations',$key,$fallback);
    $hero = 'https://images.unsplash.com/photo-1491555103944-7c647fd857e6?auto=format&fit=crop&w=1800&q=82&fm=webp';
    $fallback = 'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?auto=format&fit=crop&w=1200&q=82&fm=webp';
?>

<?php if (isset($component)) { $__componentOriginal7667e390c55120bda1fc27c0189959e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7667e390c55120bda1fc27c0189959e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.page-hero','data' => ['label' => 'Accommodation','title' => $cms('hero_title'),'subtitle' => $cms('hero_subtitle'),'image' => \App\Support\MediaPath::publicUrl($cms('hero_image',$hero))]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.page-hero'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Accommodation','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($cms('hero_title')),'subtitle' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($cms('hero_subtitle')),'image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(\App\Support\MediaPath::publicUrl($cms('hero_image',$hero)))]); ?>
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

<section class="content-band accommodation-list">
        <?php $__empty_1 = true; $__currentLoopData = $accommodations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $accommodation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php $image = is_array($accommodation->images ?? null) && count($accommodation->images) ? $accommodation->images[0] : $fallback; ?>
            <?php if (isset($component)) { $__componentOriginalbd252e7290190f40c7d31aeb60fc5688 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd252e7290190f40c7d31aeb60fc5688 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.accommodation-card','data' => ['title' => $accommodation->name,'description' => $accommodation->description ?? 'A published Shishi Footsteps accommodation partner selected for comfort, service and safari access.','meta' => trim(($accommodation->country ?? '').' / '.($accommodation->region ?? '').' / '.($accommodation->type ?? ''), ' /'),'image' => $image,'reverse' => $loop->even,'slug' => $accommodation->slug]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.accommodation-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($accommodation->name),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($accommodation->description ?? 'A published Shishi Footsteps accommodation partner selected for comfort, service and safari access.'),'meta' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trim(($accommodation->country ?? '').' / '.($accommodation->region ?? '').' / '.($accommodation->type ?? ''), ' /')),'image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($image),'reverse' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($loop->even),'slug' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($accommodation->slug)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbd252e7290190f40c7d31aeb60fc5688)): ?>
<?php $attributes = $__attributesOriginalbd252e7290190f40c7d31aeb60fc5688; ?>
<?php unset($__attributesOriginalbd252e7290190f40c7d31aeb60fc5688); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbd252e7290190f40c7d31aeb60fc5688)): ?>
<?php $component = $__componentOriginalbd252e7290190f40c7d31aeb60fc5688; ?>
<?php unset($__componentOriginalbd252e7290190f40c7d31aeb60fc5688); ?>
<?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <?php if (isset($component)) { $__componentOriginalbd252e7290190f40c7d31aeb60fc5688 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd252e7290190f40c7d31aeb60fc5688 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.accommodation-card','data' => ['title' => 'Luxury lodges and private safari camps','description' => 'Our accommodation collection is being updated and will appear here as lodges and camps are published.','image' => $fallback,'meta' => 'Coming soon from our collection']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.accommodation-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Luxury lodges and private safari camps','description' => 'Our accommodation collection is being updated and will appear here as lodges and camps are published.','image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($fallback),'meta' => 'Coming soon from our collection']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbd252e7290190f40c7d31aeb60fc5688)): ?>
<?php $attributes = $__attributesOriginalbd252e7290190f40c7d31aeb60fc5688; ?>
<?php unset($__attributesOriginalbd252e7290190f40c7d31aeb60fc5688); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbd252e7290190f40c7d31aeb60fc5688)): ?>
<?php $component = $__componentOriginalbd252e7290190f40c7d31aeb60fc5688; ?>
<?php unset($__componentOriginalbd252e7290190f40c7d31aeb60fc5688); ?>
<?php endif; ?>
    <?php endif; ?>
    <div class="pagination-wrap"><?php echo e($accommodations->links()); ?></div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\public\accommodations.blade.php ENDPATH**/ ?>