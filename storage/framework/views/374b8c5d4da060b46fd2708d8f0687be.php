<?php $__env->startSection('title', $itinerary->title.' Itinerary | Shishi Footsteps'); ?>
<?php $__env->startSection('description', $itinerary->summary ?? $itinerary->title); ?>
<?php $__env->startSection('og_image', is_array($itinerary->images) && count($itinerary->images) ? \App\Support\MediaPath::publicUrl($itinerary->images[0]) : asset('images/itineraries/kenya-family-cover.webp')); ?>

<?php $__env->startSection('content'); ?>
<?php
    $hero = is_array($itinerary->images) && count($itinerary->images) ? \App\Support\MediaPath::publicUrl($itinerary->images[0]) : asset('images/itineraries/kenya-family-cover.webp');
?>

<?php if (isset($component)) { $__componentOriginal7667e390c55120bda1fc27c0189959e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7667e390c55120bda1fc27c0189959e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.page-hero','data' => ['label' => $itinerary->country ?? 'Itinerary','title' => $itinerary->title,'subtitle' => $itinerary->summary ?? 'A thoughtfully paced safari itinerary.','image' => $hero]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.page-hero'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($itinerary->country ?? 'Itinerary'),'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($itinerary->title),'subtitle' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($itinerary->summary ?? 'A thoughtfully paced safari itinerary.'),'image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($hero)]); ?>
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
    <div class="itinerary-detail-layout">
        <div class="itinerary-detail-main">
            <div class="itinerary-stats-bar">
                <?php if($itinerary->duration_days): ?>
                    <div class="stat-badge"><i data-lucide="calendar-days"></i><?php echo e($itinerary->duration_days); ?> Days</div>
                <?php endif; ?>
                <?php if($itinerary->country): ?>
                    <div class="stat-badge"><i data-lucide="map-pin"></i><?php echo e($itinerary->country); ?></div>
                <?php endif; ?>
                <?php if($itinerary->region): ?>
                    <div class="stat-badge"><i data-lucide="compass"></i><?php echo e($itinerary->region); ?></div>
                <?php endif; ?>
                <?php if($itinerary->price_from): ?>
                    <div class="stat-badge highlight"><i data-lucide="dollar-sign"></i>From $<?php echo e(number_format((float) $itinerary->price_from)); ?></div>
                <?php endif; ?>
            </div>

            <?php if($itinerary->days->isNotEmpty()): ?>
                <section class="detail-block">
                    <h2 class="detail-section-title">Full Itinerary</h2>
                    <div class="itinerary-timeline">
                        <?php $__currentLoopData = $itinerary->days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="timeline-entry">
                                <div class="timeline-marker">
                                    <span><?php echo e($day->day_number); ?></span>
                                </div>
                                <div class="timeline-content">
                                    <h3><?php echo e($day->title ?? 'Day '.$day->day_number); ?></h3>
                                    <?php if($day->location): ?>
                                        <p class="timeline-location"><i data-lucide="map-pin"></i><?php echo e($day->location); ?></p>
                                    <?php endif; ?>
                                    <?php if($day->activities): ?>
                                        <p class="timeline-activities"><?php echo e($day->activities); ?></p>
                                    <?php endif; ?>
                                    <div class="timeline-meta">
                                        <?php if($day->meal_plan): ?><span><i data-lucide="utensils-crossed"></i><?php echo e($day->meal_plan); ?></span><?php endif; ?>
                                        <?php if($day->transfers): ?><span><i data-lucide="car"></i><?php echo e($day->transfers); ?></span><?php endif; ?>
                                        <?php if($day->notes): ?><span><i data-lucide="info"></i><?php echo e($day->notes); ?></span><?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </section>
            <?php endif; ?>

            <?php if(!empty($itinerary->inclusions)): ?>
                <section class="detail-block">
                    <h2 class="detail-section-title">Inclusions</h2>
                    <ul class="detail-checklist">
                        <?php $__currentLoopData = $itinerary->inclusions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inclusion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><i data-lucide="check-circle"></i><?php echo e($inclusion); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </section>
            <?php endif; ?>

            <?php if(!empty($itinerary->exclusions)): ?>
                <section class="detail-block">
                    <h2 class="detail-section-title">Exclusions</h2>
                    <ul class="detail-checklist muted">
                        <?php $__currentLoopData = $itinerary->exclusions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exclusion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><i data-lucide="x-circle"></i><?php echo e($exclusion); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </section>
            <?php endif; ?>
        </div>

        <aside class="itinerary-detail-sidebar">
            <div class="detail-sidebar-card">
                <div class="detail-quick-facts">
                    <?php if($itinerary->duration_days): ?>
                        <div class="quick-fact">
                            <i data-lucide="calendar-days"></i>
                            <div><strong><?php echo e($itinerary->duration_days); ?> days</strong><small>Duration</small></div>
                        </div>
                    <?php endif; ?>
                    <?php if($itinerary->price_from): ?>
                        <div class="quick-fact">
                            <i data-lucide="dollar-sign"></i>
                            <div><strong>$<?php echo e(number_format((float) $itinerary->price_from)); ?></strong><small>From per person</small></div>
                        </div>
                    <?php endif; ?>
                    <?php if($itinerary->country): ?>
                        <div class="quick-fact">
                            <i data-lucide="map-pin"></i>
                            <div><strong><?php echo e($itinerary->country); ?></strong><small>Destination</small></div>
                        </div>
                    <?php endif; ?>
                </div>

                <a href="<?php echo e(route('public.booking', array_filter(['itinerary_id' => $itinerary->id ?? null, 'itinerary_slug' => $itinerary->slug ?? null, 'itinerary_title' => $itinerary->title ?? null, 'itinerary_url' => url()->current()]))); ?>" class="button dark-button" style="width:100%;justify-content:center;">
                    Request This Itinerary<i data-lucide="arrow-up-right"></i>
                </a>
            </div>

            <?php if($related->isNotEmpty()): ?>
                <div class="detail-sidebar-related">
                    <h3>More Itineraries</h3>
                    <?php $__currentLoopData = $related; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $relImg = is_array($rel->images) && count($rel->images) ? \App\Support\MediaPath::publicUrl($rel->images[0]) : asset('images/itineraries/kenya-family-cover.webp'); ?>
                        <a href="<?php echo e(route('public.itineraries.show', $rel->slug)); ?>" class="related-item">
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.cta-section','data' => ['label' => 'Your Safari','title' => 'Turn This Itinerary Into Your Journey','text' => 'This itinerary is a starting point. Share your preferences and our specialists will refine every detail to match your style, budget and travel dates.','image' => $hero,'buttonText' => 'Start Planning This Route']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.cta-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Your Safari','title' => 'Turn This Itinerary Into Your Journey','text' => 'This itinerary is a starting point. Share your preferences and our specialists will refine every detail to match your style, budget and travel dates.','image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($hero),'buttonText' => 'Start Planning This Route']); ?>
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

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\public\itinerary-show.blade.php ENDPATH**/ ?>