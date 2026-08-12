<?php $__env->startSection('title', 'About Us | Shishi Footsteps'); ?>
<?php $__env->startSection('description', 'Shishi Footsteps is a curated travel design company specialising in premium, tailor-made safari experiences across East Africa.'); ?>

<?php $__env->startSection('content'); ?>
<?php ($cms = fn($key, $fallback='') => \App\Models\CmsContentBlock::value('about', $key, $fallback)); ?>
<?php if (isset($component)) { $__componentOriginal7667e390c55120bda1fc27c0189959e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7667e390c55120bda1fc27c0189959e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.page-hero','data' => ['label' => $cms('hero_label','About Shishi Footsteps'),'title' => $cms('hero_title','Crafted with care, guided by Africa.'),'subtitle' => $cms('hero_subtitle'),'image' => \App\Support\MediaPath::publicUrl($cms('hero_image'))]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.page-hero'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($cms('hero_label','About Shishi Footsteps')),'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($cms('hero_title','Crafted with care, guided by Africa.')),'subtitle' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($cms('hero_subtitle')),'image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(\App\Support\MediaPath::publicUrl($cms('hero_image')))]); ?>
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

<section class="about-editorial">
    <div>
        <?php if (isset($component)) { $__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.section-label','data' => ['label' => 'Who We Are']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.section-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Who We Are']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4)): ?>
<?php $attributes = $__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4; ?>
<?php unset($__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4)): ?>
<?php $component = $__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4; ?>
<?php unset($__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4); ?>
<?php endif; ?>
        <h2><?php echo e($cms('intro_title','Luxury is personal, not loud.')); ?></h2>
    </div>
    <p><?php echo e($cms('intro_text','Shishi Footsteps is a curated travel design company specialising in premium, tailor-made experiences across East Africa.')); ?></p>
    <p>We specialise in Kenya, Tanzania, Uganda, Rwanda and South Africa. These destinations offer extraordinary diversity, from the wildlife-rich Maasai Mara and Serengeti plains to Rwanda's gorilla trekking experiences, from high-altitude sports training hubs to coastal beach escapes. We focus on regions where we have trusted partnerships, deep operational knowledge, and strong logistical support.</p>
    <p>Unlike companies that sell fixed packages, we create tailor-made journeys shaped around your travel goals, budget, interests, and timing. Our approach combines deep local knowledge with international service standards, ensuring every detail — from wildlife viewing routes to lodge selection — is carefully curated. We focus on immersive experiences, conservation-conscious travel, and seamless logistics so your safari feels effortless and meaningful.</p>

    <div class="principle-grid">
        <article><i data-lucide="compass"></i><h3>Tailor-made design</h3><p>Every route starts with your season, comfort level, interests and travel rhythm. We build from scratch, never from a template.</p></article>
        <article><i data-lucide="award"></i><h3>Specialist expertise</h3><p>Deep knowledge in safari, golf tourism, wellness coordination and sports travel across East Africa's premier destinations.</p></article>
        <article><i data-lucide="handshake"></i><h3>Trusted partnerships</h3><p>We work with vetted guides, lodges and suppliers chosen for quality, service standards and conservation ethos.</p></article>
        <article><i data-lucide="shield-check"></i><h3>Professional coordination</h3><p>Meticulous logistics and on-ground support from the first inquiry to your return home, ensuring a seamless experience.</p></article>
        <article><i data-lucide="leaf"></i><h3>Responsible travel</h3><p>We favour travel that supports conservation, local communities and long-term wilderness value across every journey.</p></article>
        <article><i data-lucide="heart"></i><h3>Personalised service</h3><p>Multilingual support, individual attention and journeys designed around your comfort, pace and travel style.</p></article>
    </div>
</section>

<section class="content-band" style="background:#e9e2d2;">
    <div class="section-heading centered">
        <div>
            <?php if (isset($component)) { $__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.section-label','data' => ['label' => 'Our Mission']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.section-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Our Mission']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4)): ?>
<?php $attributes = $__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4; ?>
<?php unset($__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4)): ?>
<?php $component = $__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4; ?>
<?php unset($__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4); ?>
<?php endif; ?>
            <h2><?php echo e($cms('mission_title','To be the premier provider of luxury safari experiences in Africa')); ?></h2>
        </div>
    </div>
    <p style="max-width:720px;margin:0 auto;text-align:center;color:#54635b;font-size:14px;line-height:2;"><?php echo e($cms('mission_text')); ?></p>
</section>

<section class="content-band" style="background:var(--sf-porcelain);">
    <div class="section-heading centered">
        <div>
            <?php if (isset($component)) { $__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.section-label','data' => ['label' => 'Our Vision']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.section-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Our Vision']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4)): ?>
<?php $attributes = $__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4; ?>
<?php unset($__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4)): ?>
<?php $component = $__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4; ?>
<?php unset($__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4); ?>
<?php endif; ?>
            <h2><?php echo e($cms('vision_title','Travel that leaves more than footprints')); ?></h2>
        </div>
    </div>
    <p style="max-width:720px;margin:0 auto;text-align:center;color:#54635b;font-size:14px;line-height:2;"><?php echo e($cms('vision_text')); ?></p>
</section>

<?php if (isset($component)) { $__componentOriginala13c86f470d2a58cc232c97c825cd90e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala13c86f470d2a58cc232c97c825cd90e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.cta-section','data' => ['label' => 'Your Private Safari','title' => $cms('cta_title', 'Let Us Design Your Journey'),'text' => $cms('cta_text', 'Tell us what you are dreaming of, and we will shape a safari with the right destinations, pace, guides and lodges — built from scratch around you.'),'image' => \App\Support\MediaPath::publicUrl($cms('cta_image')) ?: 'https://images.unsplash.com/photo-1534177616072-ef7dc120449d?auto=format&fit=crop&w=1800&q=82&fm=webp','buttonText' => 'Start Planning']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.cta-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Your Private Safari','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($cms('cta_title', 'Let Us Design Your Journey')),'text' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($cms('cta_text', 'Tell us what you are dreaming of, and we will shape a safari with the right destinations, pace, guides and lodges — built from scratch around you.')),'image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(\App\Support\MediaPath::publicUrl($cms('cta_image')) ?: 'https://images.unsplash.com/photo-1534177616072-ef7dc120449d?auto=format&fit=crop&w=1800&q=82&fm=webp'),'buttonText' => 'Start Planning']); ?>
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

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\public\about.blade.php ENDPATH**/ ?>