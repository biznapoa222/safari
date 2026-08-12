<?php $__env->startSection('title', 'Experiences | Shishi Footsteps'); ?>
<?php $__env->startSection('description', 'Game drives, balloon safaris, gorilla trekking, cultural visits, beach extensions and honeymoon safari experiences.'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $cms = fn($key,$fallback='') => \App\Models\CmsContentBlock::value('experiences',$key,$fallback);
    $hero = 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1800&q=82&fm=webp';
    $fallback = [
        'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=900&q=82&fm=webp',
        'https://images.unsplash.com/photo-1540573133985-87b6da6d54a9?auto=format&fit=crop&w=900&q=82&fm=webp',
        'https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&w=900&q=82&fm=webp',
    ];
?>

<?php if (isset($component)) { $__componentOriginal7667e390c55120bda1fc27c0189959e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7667e390c55120bda1fc27c0189959e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.page-hero','data' => ['label' => 'Experiences','title' => $cms('hero_title'),'subtitle' => $cms('hero_subtitle'),'image' => \App\Support\MediaPath::publicUrl($cms('hero_image',$hero))]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.page-hero'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Experiences','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($cms('hero_title')),'subtitle' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($cms('hero_subtitle')),'image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(\App\Support\MediaPath::publicUrl($cms('hero_image',$hero)))]); ?>
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

<section class="content-band">
    <div class="experience-grid">
        <?php $__empty_1 = true; $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php $translation = $activity->translation(); ?>
            <?php $image = is_array($activity->images ?? null) && count($activity->images) ? $activity->images[0] : $fallback[$loop->index % count($fallback)]; ?>
            <?php if (isset($component)) { $__componentOriginalc5caa5ee4c66d6bc5574f0c0a58180ea = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc5caa5ee4c66d6bc5574f0c0a58180ea = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.experience-card','data' => ['title' => $translation?->title ?? $activity->name,'description' => $translation?->short_description ?? $activity->description ?? 'A handpicked safari experience designed around place, season and your travel style.','image' => $image,'icon' => 'sparkles','url' => $activity->slug ? route('public.experiences.show', $activity->slug) : null]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.experience-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($translation?->title ?? $activity->name),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($translation?->short_description ?? $activity->description ?? 'A handpicked safari experience designed around place, season and your travel style.'),'image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($image),'icon' => 'sparkles','url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($activity->slug ? route('public.experiences.show', $activity->slug) : null)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc5caa5ee4c66d6bc5574f0c0a58180ea)): ?>
<?php $attributes = $__attributesOriginalc5caa5ee4c66d6bc5574f0c0a58180ea; ?>
<?php unset($__attributesOriginalc5caa5ee4c66d6bc5574f0c0a58180ea); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc5caa5ee4c66d6bc5574f0c0a58180ea)): ?>
<?php $component = $__componentOriginalc5caa5ee4c66d6bc5574f0c0a58180ea; ?>
<?php unset($__componentOriginalc5caa5ee4c66d6bc5574f0c0a58180ea); ?>
<?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <?php if (isset($component)) { $__componentOriginalc5caa5ee4c66d6bc5574f0c0a58180ea = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc5caa5ee4c66d6bc5574f0c0a58180ea = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.experience-card','data' => ['title' => 'Game Drives','description' => 'Private guiding in the strongest wildlife areas for the season.','image' => $fallback[0],'icon' => 'binoculars']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.experience-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Game Drives','description' => 'Private guiding in the strongest wildlife areas for the season.','image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($fallback[0]),'icon' => 'binoculars']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc5caa5ee4c66d6bc5574f0c0a58180ea)): ?>
<?php $attributes = $__attributesOriginalc5caa5ee4c66d6bc5574f0c0a58180ea; ?>
<?php unset($__attributesOriginalc5caa5ee4c66d6bc5574f0c0a58180ea); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc5caa5ee4c66d6bc5574f0c0a58180ea)): ?>
<?php $component = $__componentOriginalc5caa5ee4c66d6bc5574f0c0a58180ea; ?>
<?php unset($__componentOriginalc5caa5ee4c66d6bc5574f0c0a58180ea); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginalc5caa5ee4c66d6bc5574f0c0a58180ea = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc5caa5ee4c66d6bc5574f0c0a58180ea = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.experience-card','data' => ['title' => 'Gorilla Trekking','description' => 'A rare forest encounter shaped with care and good timing.','image' => $fallback[1],'icon' => 'leaf']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.experience-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Gorilla Trekking','description' => 'A rare forest encounter shaped with care and good timing.','image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($fallback[1]),'icon' => 'leaf']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc5caa5ee4c66d6bc5574f0c0a58180ea)): ?>
<?php $attributes = $__attributesOriginalc5caa5ee4c66d6bc5574f0c0a58180ea; ?>
<?php unset($__attributesOriginalc5caa5ee4c66d6bc5574f0c0a58180ea); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc5caa5ee4c66d6bc5574f0c0a58180ea)): ?>
<?php $component = $__componentOriginalc5caa5ee4c66d6bc5574f0c0a58180ea; ?>
<?php unset($__componentOriginalc5caa5ee4c66d6bc5574f0c0a58180ea); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginalc5caa5ee4c66d6bc5574f0c0a58180ea = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc5caa5ee4c66d6bc5574f0c0a58180ea = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.experience-card','data' => ['title' => 'Beach Extensions','description' => 'Soft landings after the bush, from Zanzibar to the Indian Ocean.','image' => $fallback[2],'icon' => 'waves']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.experience-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Beach Extensions','description' => 'Soft landings after the bush, from Zanzibar to the Indian Ocean.','image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($fallback[2]),'icon' => 'waves']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc5caa5ee4c66d6bc5574f0c0a58180ea)): ?>
<?php $attributes = $__attributesOriginalc5caa5ee4c66d6bc5574f0c0a58180ea; ?>
<?php unset($__attributesOriginalc5caa5ee4c66d6bc5574f0c0a58180ea); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc5caa5ee4c66d6bc5574f0c0a58180ea)): ?>
<?php $component = $__componentOriginalc5caa5ee4c66d6bc5574f0c0a58180ea; ?>
<?php unset($__componentOriginalc5caa5ee4c66d6bc5574f0c0a58180ea); ?>
<?php endif; ?>
        <?php endif; ?>
    </div>
    <div class="pagination-wrap"><?php echo e($activities->links()); ?></div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\public\experiences.blade.php ENDPATH**/ ?>