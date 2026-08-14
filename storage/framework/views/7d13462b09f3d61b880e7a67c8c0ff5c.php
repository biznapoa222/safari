<?php $__env->startSection('title', 'Frequently Asked Questions | Shishi Footsteps'); ?>
<?php $__env->startSection('description', 'Answers on safari planning, Kenya, Tanzania, gorilla trekking, South Africa, Namibia, Botswana, golf, lodges, visas, health and how to request a Shishi Footsteps proposal.'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $cms = fn ($key, $fallback = '') => \App\Models\CmsContentBlock::value('faqs', $key, $fallback);
    $hero = $cms('hero_image') ?: 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=2200&q=84&fm=webp';
?>

<?php if (isset($component)) { $__componentOriginal7667e390c55120bda1fc27c0189959e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7667e390c55120bda1fc27c0189959e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.page-hero','data' => ['class' => 'faqs-hero','label' => 'Travel notes','title' => $cms('hero_title', 'Questions, before the journey'),'subtitle' => $cms('hero_subtitle', 'Countries, seasons, permits, golf, lodges and how a private proposal comes together.'),'image' => \App\Support\MediaPath::publicUrl($hero)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.page-hero'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'faqs-hero','label' => 'Travel notes','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($cms('hero_title', 'Questions, before the journey')),'subtitle' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($cms('hero_subtitle', 'Countries, seasons, permits, golf, lodges and how a private proposal comes together.')),'image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(\App\Support\MediaPath::publicUrl($hero))]); ?>
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

<section class="faqs-editorial" id="start">
    <div>
        <?php if (isset($component)) { $__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.section-label','data' => ['label' => 'How can we help?']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.section-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'How can we help?']); ?>
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
        <h2><?php echo e($cms('editorial_title', 'Ask us anything')); ?></h2>
    </div>
    <p><?php echo e($cms('editorial_text', 'From first enquiry to the last sundowner: destinations, wildlife seasons, camps, golf, families and the practical details of travelling with Shishi Footsteps.')); ?></p>
</section>

<nav class="faqs-index" aria-label="FAQ topics">
    <?php $__currentLoopData = $faqGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="#<?php echo e($group['id']); ?>"><?php echo e($group['label']); ?></a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</nav>

<section class="faqs-content-band" id="faqs">
    <?php $__currentLoopData = $faqGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <article class="faqs-chapter">
            <?php if(!empty($group['image'])): ?>
                <figure class="faqs-scene<?php echo e($loop->even ? ' faqs-scene--end' : ''); ?>">
                    <img src="<?php echo e(\App\Support\MediaPath::publicUrl($group['image'])); ?>" alt="<?php echo e($group['image_alt'] ?? $group['label']); ?>" loading="lazy">
                    <figcaption>
                        <small>Field notes</small>
                        <?php echo e($group['display'] ?? $group['label']); ?>

                    </figcaption>
                </figure>
            <?php endif; ?>
            <div class="faqs-category" id="<?php echo e($group['id']); ?>">
                <h3 class="<?php echo e(!empty($group['image']) ? 'visually-hidden' : 'faqs-category-label'); ?>"><?php echo e($group['label']); ?></h3>
                <?php $__currentLoopData = $group['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <details class="faq-item" <?php if($loop->parent->first && $loop->first): ?> open <?php endif; ?>>
                        <summary class="faq-question"><span><?php echo e($item['q']); ?></span><i data-lucide="chevron-down"></i></summary>
                        <div class="faq-answer"><p><?php echo e($item['a']); ?></p></div>
                    </details>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </article>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</section>

<?php if (isset($component)) { $__componentOriginala13c86f470d2a58cc232c97c825cd90e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala13c86f470d2a58cc232c97c825cd90e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.cta-section','data' => ['label' => 'Still have a question','title' => 'Ask a trip advisor instead.','text' => 'If the answer depends on your dates, your family or a particular lodge, a private proposal is the cleaner next step.','image' => 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=1800&q=82&fm=webp','buttonText' => 'Request a proposal','url' => route('public.booking')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.cta-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Still have a question','title' => 'Ask a trip advisor instead.','text' => 'If the answer depends on your dates, your family or a particular lodge, a private proposal is the cleaner next step.','image' => 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=1800&q=82&fm=webp','buttonText' => 'Request a proposal','url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('public.booking'))]); ?>
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

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\shishifootsteps\safari\resources\views/public/faqs.blade.php ENDPATH**/ ?>