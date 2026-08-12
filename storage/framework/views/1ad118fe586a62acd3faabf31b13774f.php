<?php $__env->startSection('title', $activity->name.' | Shishi Footsteps'); ?>
<?php $__env->startSection('description', $activity->description ?? $activity->name); ?>
<?php $__env->startSection('og_image', is_array($activity->images) && count($activity->images) ? $activity->images[0] : 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=1800&q=82&fm=webp'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $translation = $activity->translation();
    $pName = $translation?->title ?? $activity->name;
    $pDesc = $translation?->description ?? $activity->description ?? '';
    $hero = is_array($activity->images) && count($activity->images) ? $activity->images[0] : 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=1800&q=82&fm=webp';
?>

<?php if (isset($component)) { $__componentOriginal7667e390c55120bda1fc27c0189959e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7667e390c55120bda1fc27c0189959e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.page-hero','data' => ['label' => $activity->category?->name ?? 'Experience','title' => $pName,'subtitle' => ($activity->country ?? '').($activity->region ? ' / '.$activity->region : ''),'image' => $hero]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.page-hero'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($activity->category?->name ?? 'Experience'),'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($pName),'subtitle' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(($activity->country ?? '').($activity->region ? ' / '.$activity->region : '')),'image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($hero)]); ?>
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
    <div class="experience-detail-layout">
        <div class="experience-detail-main">
            <?php if(is_array($activity->images) && count($activity->images) > 1): ?>
                <section class="detail-block">
                    <h2 class="detail-section-title">Gallery</h2>
                    <div class="experience-gallery">
                        <?php $__currentLoopData = $activity->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="gallery-item">
                                <img src="<?php echo e($img); ?>" alt="<?php echo e($pName); ?>" loading="lazy">
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </section>
            <?php endif; ?>

            <?php if($pDesc): ?>
                <section class="detail-block">
                    <h2 class="detail-section-title">About This Experience</h2>
                    <div class="detail-description"><?php echo e($pDesc); ?></div>
                </section>
            <?php endif; ?>

            <?php if($activity->seasons->isNotEmpty()): ?>
                <section class="detail-block">
                    <h2 class="detail-section-title">Available Seasons</h2>
                    <div class="season-list">
                        <?php $__currentLoopData = $activity->seasons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $season): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="season-item">
                                <i data-lucide="calendar"></i>
                                <div>
                                    <strong><?php echo e($season->name); ?></strong>
                                    <span><?php echo e(\Carbon\Carbon::parse($season->start_date)->format('M d')); ?> - <?php echo e(\Carbon\Carbon::parse($season->end_date)->format('M d, Y')); ?></span>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </section>
            <?php endif; ?>

            <?php if($activity->prices->isNotEmpty()): ?>
                <section class="detail-block">
                    <h2 class="detail-section-title">Pricing</h2>
                    <div class="price-grid">
                        <?php $__currentLoopData = $activity->prices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $price): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="price-card">
                                <span class="price-type"><?php echo e($price->type ?? 'Standard'); ?></span>
                                <?php if($price->season): ?><span class="price-season"><?php echo e($price->season); ?></span><?php endif; ?>
                                <?php if($price->year): ?><span class="price-year"><?php echo e($price->year); ?></span><?php endif; ?>
                                <span class="price-amount">$<?php echo e(number_format((float) $price->price, 2)); ?></span>
                                <span class="price-currency"><?php echo e($price->currency ?? 'USD'); ?></span>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </section>
            <?php endif; ?>

            <?php if($activity->keywords): ?>
                <section class="detail-block">
                    <h2 class="detail-section-title">Tags</h2>
                    <div class="detail-tags">
                        <?php $__currentLoopData = explode(',', $activity->keywords); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keyword): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="tag"><?php echo e(trim($keyword)); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </section>
            <?php endif; ?>
        </div>

        <aside class="experience-detail-sidebar">
            <div class="detail-sidebar-card">
                <div class="detail-quick-facts">
                    <?php if($activity->country): ?>
                        <div class="quick-fact">
                            <i data-lucide="map-pin"></i>
                            <div><strong><?php echo e($activity->country); ?></strong><small>Country</small></div>
                        </div>
                    <?php endif; ?>
                    <?php if($activity->region): ?>
                        <div class="quick-fact">
                            <i data-lucide="compass"></i>
                            <div><strong><?php echo e($activity->region); ?></strong><small>Region</small></div>
                        </div>
                    <?php endif; ?>
                    <?php if($activity->duration_hours): ?>
                        <div class="quick-fact">
                            <i data-lucide="clock"></i>
                            <div><strong><?php echo e($activity->duration_hours); ?> hours</strong><small>Duration</small></div>
                        </div>
                    <?php endif; ?>
                    <?php if($activity->min_pax): ?>
                        <div class="quick-fact">
                            <i data-lucide="users"></i>
                            <div><strong>Min <?php echo e($activity->min_pax); ?> pax</strong><small>Group Size</small></div>
                        </div>
                    <?php endif; ?>
                    <?php if($activity->min_age): ?>
                        <div class="quick-fact">
                            <i data-lucide="baby"></i>
                            <div><strong>Min age <?php echo e($activity->min_age); ?>+</strong><small>Age Requirement</small></div>
                        </div>
                    <?php endif; ?>
                    <?php if($activity->location): ?>
                        <div class="quick-fact">
                            <i data-lucide="map"></i>
                            <div><strong><?php echo e($activity->location); ?></strong><small>Meeting Point</small></div>
                        </div>
                    <?php endif; ?>
                    <?php if($activity->pickup_time): ?>
                        <div class="quick-fact">
                            <i data-lucide="clock"></i>
                            <div><strong><?php echo e($activity->pickup_time); ?></strong><small>Pickup Time</small></div>
                        </div>
                    <?php endif; ?>
                </div>

                <a href="<?php echo e(route('public.booking')); ?>" class="button dark-button" style="width:100%;justify-content:center;">
                    Add to My Safari<i data-lucide="arrow-up-right"></i>
                </a>
            </div>

            <div class="detail-sidebar-categories">
                <h3>Experience Categories</h3>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('public.experiences').'#'.$cat->slug); ?>" class="category-link <?php echo e($cat->id === $activity->activity_category_id ? 'is-active' : ''); ?>">
                        <?php echo e($cat->name); ?>

                        <span><?php echo e($cat->activities_count); ?></span>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </aside>
    </div>
</section>

<?php if (isset($component)) { $__componentOriginala13c86f470d2a58cc232c97c825cd90e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala13c86f470d2a58cc232c97c825cd90e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.cta-section','data' => ['label' => $activity->category?->name ?? 'Experience','title' => 'Experience '.$pName,'text' => 'Our specialists can include this experience in your custom safari itinerary at the best seasonal rates.','image' => $hero,'buttonText' => 'Inquire About This Experience']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.cta-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($activity->category?->name ?? 'Experience'),'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('Experience '.$pName),'text' => 'Our specialists can include this experience in your custom safari itinerary at the best seasonal rates.','image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($hero),'buttonText' => 'Inquire About This Experience']); ?>
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

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\public\experience-show.blade.php ENDPATH**/ ?>