<?php $__env->startSection('title', $safari->title.' | Shishi Footsteps'); ?>
<?php $__env->startSection('description', $safari->summary ?? $safari->title); ?>
<?php $__env->startSection('og_image', is_array($safari->images) && count($safari->images) ? \App\Support\MediaPath::publicUrl($safari->images[0]) : asset('images/itineraries/kenya-family-cover.webp')); ?>

<?php $__env->startSection('content'); ?>
<?php
    $hero = is_array($safari->images) && count($safari->images) ? \App\Support\MediaPath::publicUrl($safari->images[0]) : asset('images/itineraries/kenya-family-cover.webp');
?>

<?php if (isset($component)) { $__componentOriginal7667e390c55120bda1fc27c0189959e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7667e390c55120bda1fc27c0189959e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.page-hero','data' => ['label' => $safari->country ?? 'Safari','title' => $safari->title,'subtitle' => $safari->summary ?? 'Experience the journey of a lifetime.','image' => $hero]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.page-hero'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($safari->country ?? 'Safari'),'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($safari->title),'subtitle' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($safari->summary ?? 'Experience the journey of a lifetime.'),'image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($hero)]); ?>
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
    <div class="safari-detail-layout">
        <div class="safari-detail-main">
            <?php if($safari->days->isNotEmpty()): ?>
                <h2 class="detail-section-title">Day-by-Day Itinerary</h2>
                <?php $__currentLoopData = $safari->days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <article class="itinerary-day-card">
                        <div class="itinerary-day-number">
                            <span>Day</span>
                            <strong><?php echo e($day->day_number); ?></strong>
                        </div>
                        <div class="itinerary-day-body">
                            <h3><?php echo e($day->title ?? 'Day '.$day->day_number); ?></h3>
                            <?php if($day->location): ?><p class="itinerary-day-location"><i data-lucide="map-pin"></i><?php echo e($day->location); ?></p><?php endif; ?>
                            <?php if($day->activities): ?><p class="itinerary-day-activities"><?php echo e($day->activities); ?></p><?php endif; ?>
                            <div class="itinerary-day-meta">
                                <?php if($day->meal_plan): ?><span><i data-lucide="utensils-crossed"></i><?php echo e($day->meal_plan); ?></span><?php endif; ?>
                                <?php if($day->transfers): ?><span><i data-lucide="car"></i><?php echo e($day->transfers); ?></span><?php endif; ?>
                                <?php if($day->notes): ?><span><i data-lucide="info"></i><?php echo e($day->notes); ?></span><?php endif; ?>
                            </div>
                        </div>
                    </article>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>

            <?php if(!empty($safari->inclusions)): ?>
                <section class="detail-block">
                    <h2 class="detail-section-title">Inclusions</h2>
                    <ul class="detail-checklist">
                        <?php $__currentLoopData = $safari->inclusions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inclusion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><i data-lucide="check-circle"></i><?php echo e($inclusion); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </section>
            <?php endif; ?>

            <?php if(!empty($safari->exclusions)): ?>
                <section class="detail-block">
                    <h2 class="detail-section-title">Exclusions</h2>
                    <ul class="detail-checklist muted">
                        <?php $__currentLoopData = $safari->exclusions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exclusion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><i data-lucide="x-circle"></i><?php echo e($exclusion); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </section>
            <?php endif; ?>
        </div>

        <aside class="safari-detail-sidebar">
            <div class="detail-sidebar-card">
                <div class="detail-price">
                    <?php if($safari->price_from): ?>
                        <span class="detail-price-label">From</span>
                        <span class="detail-price-value">$<?php echo e(number_format((float) $safari->price_from)); ?></span>
                        <span class="detail-price-note">per person sharing</span>
                    <?php else: ?>
                        <span class="detail-price-label">Tailor-Made Pricing</span>
                        <span class="detail-price-value">Custom</span>
                    <?php endif; ?>
                </div>

                <div class="detail-quick-facts">
                    <?php if($safari->duration_days): ?>
                        <div class="quick-fact">
                            <i data-lucide="calendar-days"></i>
                            <div><strong><?php echo e($safari->duration_days); ?> days</strong><small>Duration</small></div>
                        </div>
                    <?php endif; ?>
                    <?php if($safari->country): ?>
                        <div class="quick-fact">
                            <i data-lucide="map-pin"></i>
                            <div><strong><?php echo e($safari->country); ?></strong><small>Destination</small></div>
                        </div>
                    <?php endif; ?>
                    <?php if($safari->region): ?>
                        <div class="quick-fact">
                            <i data-lucide="compass"></i>
                            <div><strong><?php echo e($safari->region); ?></strong><small>Region</small></div>
                        </div>
                    <?php endif; ?>
                    <div class="quick-fact">
                        <i data-lucide="users"></i>
                        <div><strong>Private</strong><small>Exclusively yours</small></div>
                    </div>
                </div>

                <a href="<?php echo e(route('public.booking', array_filter(['itinerary_id' => $safari->id ?? null, 'itinerary_slug' => $safari->slug ?? null, 'itinerary_title' => $safari->title ?? null, 'itinerary_url' => url()->current()]))); ?>" class="button dark-button" style="width:100%;justify-content:center;">
                    Plan This Safari<i data-lucide="arrow-up-right"></i>
                </a>
            </div>

            <?php if($related->isNotEmpty()): ?>
                <div class="detail-sidebar-related">
                    <h3>Other Safaris</h3>
                    <?php $__currentLoopData = $related; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $relImg = is_array($rel->images) && count($rel->images) ? \App\Support\MediaPath::publicUrl($rel->images[0]) : asset('images/itineraries/kenya-family-cover.webp'); ?>
                        <a href="<?php echo e(route('public.safaris.show', $rel->slug)); ?>" class="related-item">
                            <img src="<?php echo e($relImg); ?>" alt="<?php echo e($rel->title); ?>" loading="lazy">
                            <div>
                                <strong><?php echo e($rel->title); ?></strong>
                                <span><?php echo e($rel->duration_days ?? 'Custom'); ?> days</span>
                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </aside>
    </div>
</section>

<?php if (isset($component)) { $__componentOriginala13c86f470d2a58cc232c97c825cd90e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala13c86f470d2a58cc232c97c825cd90e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.cta-section','data' => ['label' => 'Your Private Safari','title' => 'Let Us Shape This Journey Around You','text' => 'Tell us your preferred dates and travel style, and our specialists will refine this itinerary to match exactly what you envision.','image' => $hero,'buttonText' => 'Start Planning']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.cta-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Your Private Safari','title' => 'Let Us Shape This Journey Around You','text' => 'Tell us your preferred dates and travel style, and our specialists will refine this itinerary to match exactly what you envision.','image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($hero),'buttonText' => 'Start Planning']); ?>
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

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\shishifootsteps\safari\resources\views/public/safari-show.blade.php ENDPATH**/ ?>