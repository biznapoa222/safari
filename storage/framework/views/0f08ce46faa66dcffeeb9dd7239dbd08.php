<?php $__env->startSection('title', 'Destinations | Shishi Footsteps'); ?>
<?php $__env->startSection('description', 'Explore luxury safari destinations across Kenya, Tanzania, Uganda, Rwanda, South Africa, Namibia and Botswana.'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $cms = fn($key,$fallback='') => \App\Models\CmsContentBlock::value('destinations',$key,$fallback);
    $hero = \App\Support\MediaPath::publicUrl($cms('hero_image', 'https://images.unsplash.com/photo-1489392191049-fc10c97e64b6?auto=format&fit=crop&w=1800&q=82&fm=webp'));
    $countryImages = [
        'Kenya' => 'https://images.unsplash.com/photo-1534177616072-ef7dc120449d?auto=format&fit=crop&w=900&q=82&fm=webp',
        'Tanzania' => 'https://images.unsplash.com/photo-1523805009345-7448845a9e53?auto=format&fit=crop&w=900&q=82&fm=webp',
        'Uganda' => 'https://images.unsplash.com/photo-1540573133985-87b6da6d54a9?auto=format&fit=crop&w=900&q=82&fm=webp',
        'Rwanda' => 'https://images.unsplash.com/photo-1517853782856-d7cc5de7a7fc?auto=format&fit=crop&w=900&q=82&fm=webp',
        'South Africa' => 'https://images.unsplash.com/photo-1484318571209-661cf29a69c3?auto=format&fit=crop&w=900&q=82&fm=webp',
        'Namibia' => 'https://images.unsplash.com/photo-1516483638261-f4dbaf036963?auto=format&fit=crop&w=900&q=82&fm=webp',
        'Botswana' => 'https://images.unsplash.com/photo-1509099836639-18ba1795216d?auto=format&fit=crop&w=900&q=82&fm=webp',
    ];
    $copy = [
        'Kenya' => 'Big cat country, private conservancies, the Maasai Mara river crossings, Laikipia wildlife and a warm coastal ending.',
        'Tanzania' => 'Endless Serengeti plains, the Ngorongoro Crater, wild southern parks and the slopes of Kilimanjaro.',
        'Uganda' => 'Gorilla trekking in misty forests, chimpanzee encounters, crater lake country and deeply moving wildlife encounters.',
        'Rwanda' => 'Mountain gorilla trekking, rolling hills, golden monkey tracking and intimate luxury lodges with volcano views.',
        'South Africa' => 'Private Big Five reserves, refined lodges, Cape Town icons, wine country and family-friendly safari routes.',
        'Namibia' => 'Desert-adapted wildlife, sculptural dunes, remote lodges beneath huge star-filled skies and Sossusvlei.',
        'Botswana' => 'Okavango Delta waterways, Chobe elephant herds, mokoro channels and pristine private concessions.',
    ];
    $cards = $destinations->isNotEmpty() ? $destinations : collect(array_keys($countryImages))->map(fn ($name) => (object) ['name' => $name]);
?>

<?php if (isset($component)) { $__componentOriginal7667e390c55120bda1fc27c0189959e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7667e390c55120bda1fc27c0189959e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.page-hero','data' => ['label' => 'Destinations','title' => 'The wild, chosen with intention.','subtitle' => 'We specialize in Kenya, Tanzania, Uganda, Rwanda, South Africa, Namibia and Botswana — destinations offering extraordinary diversity, from wildlife-rich plains to gorilla forests, desertscapes and coastal escapes.','image' => $hero]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.page-hero'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Destinations','title' => 'The wild, chosen with intention.','subtitle' => 'We specialize in Kenya, Tanzania, Uganda, Rwanda, South Africa, Namibia and Botswana — destinations offering extraordinary diversity, from wildlife-rich plains to gorilla forests, desertscapes and coastal escapes.','image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($hero)]); ?>
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
    <div class="section-heading">
        <div><?php if (isset($component)) { $__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.section-label','data' => ['label' => 'Where To Go']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.section-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Where To Go']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4)): ?>
<?php $attributes = $__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4; ?>
<?php unset($__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4)): ?>
<?php $component = $__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4; ?>
<?php unset($__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4); ?>
<?php endif; ?><h2>Signature safari countries</h2></div>
        <a href="<?php echo e(route('public.booking')); ?>">Ask a specialist<i data-lucide="arrow-right"></i></a>
    </div>
    <div class="destination-grid">
        <?php $__currentLoopData = $cards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if (isset($component)) { $__componentOriginal0252cd7f3e25a2949455cf3fcecf6f6f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0252cd7f3e25a2949455cf3fcecf6f6f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.destination-card','data' => ['title' => $country->name,'description' => $copy[$country->name] ?? 'A Shishi Footsteps safari destination selected around season, wildlife and comfort.','image' => $countryImages[$country->name] ?? reset($countryImages)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.destination-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($country->name),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($copy[$country->name] ?? 'A Shishi Footsteps safari destination selected around season, wildlife and comfort.'),'image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($countryImages[$country->name] ?? reset($countryImages))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0252cd7f3e25a2949455cf3fcecf6f6f)): ?>
<?php $attributes = $__attributesOriginal0252cd7f3e25a2949455cf3fcecf6f6f; ?>
<?php unset($__attributesOriginal0252cd7f3e25a2949455cf3fcecf6f6f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0252cd7f3e25a2949455cf3fcecf6f6f)): ?>
<?php $component = $__componentOriginal0252cd7f3e25a2949455cf3fcecf6f6f; ?>
<?php unset($__componentOriginal0252cd7f3e25a2949455cf3fcecf6f6f); ?>
<?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</section>

<?php if (isset($component)) { $__componentOriginala13c86f470d2a58cc232c97c825cd90e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala13c86f470d2a58cc232c97c825cd90e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.cta-section','data' => ['label' => 'Tailor Your Route','title' => 'Not sure which country fits?','text' => 'Tell us your travel dates, style and wildlife wishlist. We focus on regions where we have trusted partnerships, deep operational knowledge and strong logistical support — ensuring the strongest route for the season.','image' => 'https://images.unsplash.com/photo-1504432842672-1a79f78e4084?auto=format&fit=crop&w=1800&q=82&fm=webp']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.cta-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Tailor Your Route','title' => 'Not sure which country fits?','text' => 'Tell us your travel dates, style and wildlife wishlist. We focus on regions where we have trusted partnerships, deep operational knowledge and strong logistical support — ensuring the strongest route for the season.','image' => 'https://images.unsplash.com/photo-1504432842672-1a79f78e4084?auto=format&fit=crop&w=1800&q=82&fm=webp']); ?>
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

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\public\destinations.blade.php ENDPATH**/ ?>