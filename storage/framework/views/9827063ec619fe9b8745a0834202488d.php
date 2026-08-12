<?php
    $fallbackImages = [
        asset('images/itineraries/kenya-family-cover.webp'),
        asset('images/itineraries/tanzania-classic-cover.webp'),
        asset('images/itineraries/botswana-luxury-cover.webp'),
    ];
?>
<div class="safari-grid">
    <?php $__empty_1 = true; $__currentLoopData = $safaris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $safari): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php
            $image = is_array($safari->images ?? null) && count($safari->images)
                ? \App\Support\MediaPath::publicUrl($safari->images[0])
                : $fallbackImages[$loop->index % count($fallbackImages)];
        ?>
        <?php if (isset($component)) { $__componentOriginalf66f29700c8192c024c08f591d4c62ab = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf66f29700c8192c024c08f591d4c62ab = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.safari-package-card','data' => ['title' => $safari->title,'summary' => $safari->summary ?? 'A published Shishi Footsteps itinerary ready to be tailored by our safari team.','image' => $image,'duration' => $safari->duration_days ? $safari->duration_days.' days' : null,'country' => $safari->country,'price' => $safari->price_from ? '$'.number_format((float) $safari->price_from) : null,'slug' => $safari->slug]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.safari-package-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($safari->title),'summary' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($safari->summary ?? 'A published Shishi Footsteps itinerary ready to be tailored by our safari team.'),'image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($image),'duration' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($safari->duration_days ? $safari->duration_days.' days' : null),'country' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($safari->country),'price' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($safari->price_from ? '$'.number_format((float) $safari->price_from) : null),'slug' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($safari->slug)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf66f29700c8192c024c08f591d4c62ab)): ?>
<?php $attributes = $__attributesOriginalf66f29700c8192c024c08f591d4c62ab; ?>
<?php unset($__attributesOriginalf66f29700c8192c024c08f591d4c62ab); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf66f29700c8192c024c08f591d4c62ab)): ?>
<?php $component = $__componentOriginalf66f29700c8192c024c08f591d4c62ab; ?>
<?php unset($__componentOriginalf66f29700c8192c024c08f591d4c62ab); ?>
<?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <?php if (isset($component)) { $__componentOriginalf66f29700c8192c024c08f591d4c62ab = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf66f29700c8192c024c08f591d4c62ab = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.safari-package-card','data' => ['title' => 'Tailor-Made Private Safari','summary' => 'No matching safaris yet. Tell us your dates, interests and comfort level, and we will build the right safari from scratch.','image' => $fallbackImages[0],'duration' => 'Flexible','country' => 'East and Southern Africa']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.safari-package-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Tailor-Made Private Safari','summary' => 'No matching safaris yet. Tell us your dates, interests and comfort level, and we will build the right safari from scratch.','image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($fallbackImages[0]),'duration' => 'Flexible','country' => 'East and Southern Africa']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf66f29700c8192c024c08f591d4c62ab)): ?>
<?php $attributes = $__attributesOriginalf66f29700c8192c024c08f591d4c62ab; ?>
<?php unset($__attributesOriginalf66f29700c8192c024c08f591d4c62ab); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf66f29700c8192c024c08f591d4c62ab)): ?>
<?php $component = $__componentOriginalf66f29700c8192c024c08f591d4c62ab; ?>
<?php unset($__componentOriginalf66f29700c8192c024c08f591d4c62ab); ?>
<?php endif; ?>
    <?php endif; ?>
</div>
<div class="pagination-wrap"><?php echo e($safaris->links()); ?></div>
<?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\public\partials\_safari_grid.blade.php ENDPATH**/ ?>