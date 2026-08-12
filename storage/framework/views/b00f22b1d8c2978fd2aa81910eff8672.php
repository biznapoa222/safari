<?php $__env->startSection('title', $accommodation->name.' | Shishi Footsteps'); ?>
<?php $__env->startSection('description', $accommodation->description ?? $accommodation->name); ?>
<?php $__env->startSection('og_image', is_array($accommodation->images) && count($accommodation->images) ? $accommodation->images[0] : 'https://images.unsplash.com/photo-1491555103944-7c647fd857e6?auto=format&fit=crop&w=1800&q=82&fm=webp'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $hero = is_array($accommodation->images) && count($accommodation->images) ? $accommodation->images[0] : 'https://images.unsplash.com/photo-1491555103944-7c647fd857e6?auto=format&fit=crop&w=1800&q=82&fm=webp';
    $typeLabel = \App\Models\Accommodation::$types[$accommodation->type] ?? $accommodation->type ?? 'Accommodation';
?>

<?php if (isset($component)) { $__componentOriginal7667e390c55120bda1fc27c0189959e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7667e390c55120bda1fc27c0189959e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.page-hero','data' => ['label' => $typeLabel,'title' => $accommodation->name,'subtitle' => trim(($accommodation->country ?? '').' / '.($accommodation->region ?? ''), ' /'),'image' => $hero]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.page-hero'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($typeLabel),'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($accommodation->name),'subtitle' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trim(($accommodation->country ?? '').' / '.($accommodation->region ?? ''), ' /')),'image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($hero)]); ?>
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
    <div class="accommodation-detail-layout">
        <div class="accommodation-detail-main">
            <?php if($accommodation->description): ?>
                <section class="detail-block">
                    <h2 class="detail-section-title">About <?php echo e($accommodation->name); ?></h2>
                    <div class="detail-description"><?php echo e($accommodation->description); ?></div>
                </section>
            <?php endif; ?>

            <?php if($accommodation->rooms->isNotEmpty()): ?>
                <section class="detail-block">
                    <h2 class="detail-section-title">Rooms & Suites</h2>
                    <div class="room-grid">
                        <?php $__currentLoopData = $accommodation->rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <article class="room-card">
                                <div class="room-card-header">
                                    <h3><?php echo e($room->name); ?></h3>
                                    <?php if($room->capacity): ?><span class="room-capacity"><i data-lucide="users"></i>Up to <?php echo e($room->capacity); ?> guests</span><?php endif; ?>
                                </div>
                                <?php if($room->max_adults || $room->max_children): ?>
                                    <div class="room-occupancy">
                                        <?php if($room->max_adults): ?><span><i data-lucide="user"></i><?php echo e($room->max_adults); ?> adults max</span><?php endif; ?>
                                        <?php if($room->max_children): ?><span><i data-lucide="baby"></i><?php echo e($room->max_children); ?> children max</span><?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if($room->inventory): ?>
                                    <p class="room-inventory"><?php echo e($room->inventory); ?> room(s) available</p>
                                <?php endif; ?>
                                <?php if($room->rates->isNotEmpty()): ?>
                                    <div class="room-rates">
                                        <span class="rates-label">Seasonal Rates</span>
                                        <table class="rates-table">
                                            <thead>
                                                <tr><th>Season</th><th>Valid</th><th>Rate/Night</th><th>Meal Plan</th></tr>
                                            </thead>
                                            <tbody>
                                                <?php $__currentLoopData = $room->rates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td><strong><?php echo e($rate->season_name); ?></strong></td>
                                                        <td><?php echo e($rate->valid_from?->format('M d')); ?> - <?php echo e($rate->valid_to?->format('M d, Y')); ?></td>
                                                        <td class="rate-amount">$<?php echo e(number_format((float) $rate->rate, 2)); ?></td>
                                                        <td><?php echo e($rate->meal_plan ?? '—'); ?></td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                        <?php if($room->rates->first()?->currency): ?>
                                            <span class="rates-currency">All rates in <?php echo e($room->rates->first()->currency); ?></span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </article>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </section>
            <?php endif; ?>

            <?php if($accommodation->notes): ?>
                <section class="detail-block">
                    <h2 class="detail-section-title">Additional Notes</h2>
                    <div class="detail-description"><?php echo e($accommodation->notes); ?></div>
                </section>
            <?php endif; ?>
        </div>

        <aside class="accommodation-detail-sidebar">
            <div class="detail-sidebar-card">
                <div class="detail-quick-facts">
                    <?php if($accommodation->country): ?>
                        <div class="quick-fact">
                            <i data-lucide="map-pin"></i>
                            <div><strong><?php echo e($accommodation->country); ?></strong><small>Country</small></div>
                        </div>
                    <?php endif; ?>
                    <?php if($accommodation->region): ?>
                        <div class="quick-fact">
                            <i data-lucide="compass"></i>
                            <div><strong><?php echo e($accommodation->region); ?></strong><small>Region</small></div>
                        </div>
                    <?php endif; ?>
                    <?php if($accommodation->type): ?>
                        <div class="quick-fact">
                            <i data-lucide="building-2"></i>
                            <div><strong><?php echo e($typeLabel); ?></strong><small>Type</small></div>
                        </div>
                    <?php endif; ?>
                    <?php if($accommodation->luxury_level): ?>
                        <div class="quick-fact">
                            <i data-lucide="star"></i>
                            <div><strong><?php echo e($accommodation->luxury_level); ?></strong><small>Luxury Level</small></div>
                        </div>
                    <?php endif; ?>
                    <?php if($accommodation->category): ?>
                        <div class="quick-fact">
                            <i data-lucide="tag"></i>
                            <div><strong><?php echo e($accommodation->category); ?></strong><small>Category</small></div>
                        </div>
                    <?php endif; ?>
                </div>

                <a href="<?php echo e(route('public.booking')); ?>" class="button dark-button" style="width:100%;justify-content:center;">
                    Include in My Safari<i data-lucide="arrow-up-right"></i>
                </a>

                <?php if($accommodation->website || $accommodation->phone || $accommodation->email): ?>
                    <div class="detail-contact-info">
                        <h4>Contact</h4>
                        <?php if($accommodation->website): ?><a href="<?php echo e($accommodation->website); ?>" target="_blank" rel="noopener"><i data-lucide="globe"></i><?php echo e($accommodation->website); ?></a><?php endif; ?>
                        <?php if($accommodation->phone): ?><span><i data-lucide="phone"></i><?php echo e($accommodation->phone); ?></span><?php endif; ?>
                        <?php if($accommodation->email): ?><span><i data-lucide="mail"></i><?php echo e($accommodation->email); ?></span><?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php if($related->isNotEmpty()): ?>
                <div class="detail-sidebar-related">
                    <h3>More Accommodations</h3>
                    <?php $__currentLoopData = $related; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $relImg = is_array($rel->images) && count($rel->images) ? $rel->images[0] : 'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?auto=format&fit=crop&w=1200&q=82&fm=webp'; ?>
                        <a href="<?php echo e(route('public.accommodations.show', $rel->slug)); ?>" class="related-item">
                            <img src="<?php echo e($relImg); ?>" alt="<?php echo e($rel->name); ?>" loading="lazy">
                            <div>
                                <strong><?php echo e($rel->name); ?></strong>
                                <span><?php echo e($rel->country ?? ''); ?><?php if($rel->region): ?> / <?php echo e($rel->region); ?><?php endif; ?></span>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.cta-section','data' => ['label' => 'Accommodation','title' => 'Book This Stay As Part of Your Journey','text' => 'Our specialists will include this accommodation in your custom safari itinerary, matching room types and seasons to your travel dates.','image' => $hero,'buttonText' => 'Inquire Now']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.cta-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Accommodation','title' => 'Book This Stay As Part of Your Journey','text' => 'Our specialists will include this accommodation in your custom safari itinerary, matching room types and seasons to your travel dates.','image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($hero),'buttonText' => 'Inquire Now']); ?>
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

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\public\accommodation-show.blade.php ENDPATH**/ ?>