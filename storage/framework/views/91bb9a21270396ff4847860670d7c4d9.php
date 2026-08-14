<?php
    $navCountries = [['Kenya','kenya'],['Tanzania','tanzania'],['Uganda','uganda'],['Rwanda','rwanda'],['South Africa','south-africa'],['Namibia','namibia'],['Botswana','botswana']];
    $websiteSettings = \App\Models\WebsiteSetting::home();
    $golfCountries = ['kenya','tanzania','uganda','rwanda','south-africa'];
?>

<?php if(false): ?>
<div class="trust-strip">
    <div>
        <img class="trust-paw" src="<?php echo e(asset('images/brand/shishi-paw-white.png')); ?>" alt="">
        <?php if (isset($component)) { $__componentOriginal8d3bff7d7383a45350f7495fc470d934 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8d3bff7d7383a45350f7495fc470d934 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.language-switcher','data' => ['variant' => 'public']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('language-switcher'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'public']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8d3bff7d7383a45350f7495fc470d934)): ?>
<?php $attributes = $__attributesOriginal8d3bff7d7383a45350f7495fc470d934; ?>
<?php unset($__attributesOriginal8d3bff7d7383a45350f7495fc470d934); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8d3bff7d7383a45350f7495fc470d934)): ?>
<?php $component = $__componentOriginal8d3bff7d7383a45350f7495fc470d934; ?>
<?php unset($__componentOriginal8d3bff7d7383a45350f7495fc470d934); ?>
<?php endif; ?>
        <span class="currency-choice"><b>$</b> U.S. Dollar <i data-lucide="chevron-down"></i></span>
    </div>
    <div class="review-proof">
        <span class="proof-mark"><i data-lucide="badge-check"></i></span><b>Travelers’ Choice</b>
        <span><strong>4.9/5</strong> ★★★★★<small>Based on verified reviews</small></span>
        <span><strong>4.8/5</strong> ★★★★★<small>Guest-rated journeys</small></span>
    </div>
    <nav><a href="<?php echo e(route('public.golf')); ?>">Golf</a><a href="<?php echo e(route('public.about')); ?>">About us</a></nav>
</div>

<?php endif; ?>

<header class="public-header safari-reference-header" data-public-header>
    <a href="<?php echo e(route('home')); ?>" class="reference-brand"><img src="<?php echo e(asset('images/brand/shishi-footsteps-green.png')); ?>" alt="Shishi Footsteps"></a>
    <nav class="public-nav reference-nav" aria-label="Primary navigation">
        <?php $__currentLoopData = $navCountries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$name, $slug]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $countryMedia = $websiteSettings->mediaFor($slug);
                $image = \App\Support\MediaPath::publicUrl($countryMedia['hero']);
                $menuTiles = $websiteSettings->menuTilesFor($slug);
                $golfUrl = in_array($slug, $golfCountries, true) ? route('public.tee-off.country', $slug) : route('public.golf');
                $tiles = [
                    ['Safaris and tours', 'binoculars', route('public.destinations.section', [$slug, 'safaris-and-tours'])],
                    ['Discover '.$name, 'compass', route('public.destinations.section', [$slug, 'discover'])],
                    ['National parks', 'trees', route('public.destinations.section', [$slug, 'national-parks'])],
                    ['Accommodation', 'bed-double', route('public.destinations.section', [$slug, 'accommodation'])],
                    ['Highlights', 'sparkles', route('public.destinations.section', [$slug, 'highlights'])],
                    ['Activities', 'footprints', route('public.destinations.section', [$slug, 'activities'])],
                    ['Wildlife', 'paw-print', route('public.destinations.section', [$slug, 'wildlife'])],
                    ['Golf safaris', 'flag', $golfUrl],
                    ['Journal', 'book-open', route('public.destinations.section', [$slug, 'journal'])],
                    ['Travellers reviews', 'quote', route('public.destinations.section', [$slug, 'reviews'])],
                ];
            ?>
            <div class="country-nav">
                <a href="<?php echo e(route('public.destinations.show', $slug)); ?>"><?php echo e($name); ?> <i data-lucide="chevron-down"></i></a>
                <div class="country-mega">
                    <div class="country-mega-main">
                        <h2>VIEW <?php echo e(strtoupper($name)); ?> SAFARI IDEAS</h2>
                        <div class="country-mega-grid">
                            <?php $__currentLoopData = $tiles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $tile): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e($tile[2]); ?>">
                                    <img src="<?php echo e($menuTiles[$i] ?? $image); ?>" alt="<?php echo e($tile[0]); ?> in <?php echo e($name); ?>" loading="lazy"><span><i data-lucide="<?php echo e($tile[1]); ?>"></i><?php echo e($tile[0]); ?></span>
                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <aside>
                        <h3>START THE ADVENTURE!</h3><a href="<?php echo e(route('public.destinations.show', $slug)); ?>" class="mega-feature-image"><img src="<?php echo e($image); ?>" alt="<?php echo e($name); ?>"></a>
                        <p>Tell us what you love and our specialists will build your private <?php echo e($name); ?> journey.</p>
                        <a href="<?php echo e(route('public.booking', ['destination' => $name])); ?>"><?php echo e(__('ui.plan_your_safari')); ?></a>
                    </aside>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <div class="country-nav golf-nav">
            <a href="<?php echo e(route('public.golf')); ?>">Golf <i data-lucide="chevron-down"></i></a>
            <div class="country-mega">
                <div class="country-mega-main">
                    <h2>GOLF SAFARI</h2>
                    <div class="country-mega-grid">
                        <a href="<?php echo e(route('public.golf')); ?>"><img src="https://images.unsplash.com/photo-1592919505780-303950717480?auto=format&fit=crop&w=900&q=84&fm=webp" alt="All golf safaris"><span><i data-lucide="flag"></i>All Golf Safaris</span></a>
                        <a href="<?php echo e(route('public.tee-off.country', 'kenya')); ?>"><img src="https://images.unsplash.com/photo-1535131749006-b7f58c99034b?auto=format&fit=crop&w=900&q=84&fm=webp" alt="Kenya golf"><span><i data-lucide="flag"></i>Kenya Golf</span></a>
                        <a href="<?php echo e(route('public.tee-off.country', 'rwanda')); ?>"><img src="https://images.unsplash.com/photo-1593111774240-d529f12cf4bb?auto=format&fit=crop&w=900&q=84&fm=webp" alt="Rwanda golf"><span><i data-lucide="flag"></i>Rwanda Golf</span></a>
                        <a href="<?php echo e(route('public.tee-off.country', 'south-africa')); ?>"><img src="https://images.unsplash.com/photo-1580060839134-75a5edca2e99?auto=format&fit=crop&w=900&q=84&fm=webp" alt="South Africa golf"><span><i data-lucide="flag"></i>South Africa Golf</span></a>
                    </div>
                </div>
                <aside>
                    <h3>Start the adventure!</h3>
                    <a href="<?php echo e(route('public.golf')); ?>" class="mega-feature-image"><img src="https://images.unsplash.com/photo-1592919505780-303950717480?auto=format&fit=crop&w=1200&q=84&fm=webp" alt="Golf"></a>
                    <p>Championship golf, carefully timed tee sheets and smooth travel between Africa's most rewarding courses.</p>
                    <a href="<?php echo e(route('public.golf')); ?>">Explore golf</a>
                </aside>
            </div>
        </div>
    </nav>
    <div class="reference-actions">
        <a href="<?php echo e(route('public.booking')); ?>" class="reference-request"><?php echo e(__('ui.plan_your_safari')); ?></a>
        <button class="public-menu-button" type="button" data-public-menu aria-label="Open navigation"><i data-lucide="menu"></i></button>
    </div>
</header>
<?php /**PATH C:\shishifootsteps\safari\resources\views/components/public/header.blade.php ENDPATH**/ ?>