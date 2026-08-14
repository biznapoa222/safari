<?php $__env->startSection('title', 'Request a Proposal | Shishi Footsteps'); ?>
<?php $__env->startSection('description', 'Request a private safari proposal from a Shishi Footsteps trip advisor. Share your country, dates and travel style for a written itinerary and quote.'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $hero = 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=1800&q=82&fm=webp';
    $cms = fn ($key, $fallback = '') => \App\Models\CmsContentBlock::value('booking', $key, $fallback);
    $global = fn ($key, $fallback = '') => \App\Models\CmsContentBlock::value('global', $key, $fallback);
    $proposalCountries = collect($destinations)->pluck('name')->merge(['Kenya', 'Tanzania', 'Uganda', 'Rwanda', 'South Africa', 'Namibia', 'Botswana'])->unique()->values();
?>

<?php if (isset($component)) { $__componentOriginal7667e390c55120bda1fc27c0189959e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7667e390c55120bda1fc27c0189959e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.page-hero','data' => ['label' => 'Private proposal','title' => 'Request a private proposal','subtitle' => 'A trip advisor will shape the country, the lodges and the pace into a written itinerary and quote.','image' => \App\Support\MediaPath::publicUrl($cms('hero_image', $hero))]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.page-hero'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Private proposal','title' => 'Request a private proposal','subtitle' => 'A trip advisor will shape the country, the lodges and the pace into a written itinerary and quote.','image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(\App\Support\MediaPath::publicUrl($cms('hero_image', $hero)))]); ?>
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

<section class="proposal-rhythm" id="start">
    <?php if (isset($component)) { $__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.section-label','data' => ['label' => 'How a proposal works']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.section-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'How a proposal works']); ?>
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
    <h2>From a conversation to a written journey.</h2>
    <ol>
        <li><span>01</span><strong>Tell us the shape</strong><p>Country or circuit, rough dates, who is travelling, and whether golf, gorillas or a beach ending matter.</p></li>
        <li><span>02</span><strong>A specialist designs</strong><p>Your advisor chooses parks, nights and lodges around season, availability and the way you like to travel.</p></li>
        <li><span>03</span><strong>You receive the quote</strong><p>A clear itinerary, what is included, and a price. Revise it until the route feels like yours, then confirm when you are ready.</p></li>
    </ol>
</section>

<section class="inquiry-section proposal-inquiry" id="proposal">
    <div class="inquiry-copy">
        <?php if (isset($component)) { $__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.section-label','data' => ['label' => 'Speak with a trip advisor']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.section-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Speak with a trip advisor']); ?>
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
        <h2>Begin with the details that matter.</h2>
        <p>Share as much or as little as you know. We will come back with the right questions, then a private proposal — not a generic package.</p>
        <div class="contact-detail"><span><i data-lucide="phone"></i></span><div><small>Call or WhatsApp</small><strong><?php echo e($global('phone', '+254 725 346 022')); ?></strong></div></div>
        <div class="contact-detail"><span><i data-lucide="mail"></i></span><div><small>Email</small><strong><?php echo e($global('bookings_email', $global('email', 'bookings@shishifootsteps.com'))); ?></strong></div></div>
        <p class="proposal-note">Proposals are complimentary. Lodges and gorilla permits are only held once you accept and a deposit is paid.</p>
    </div>
    <?php if (isset($component)) { $__componentOriginal771fbdc5784701249b7145c5a5a7483c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal771fbdc5784701249b7145c5a5a7483c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.inquiry-form','data' => ['destinations' => $destinations,'selectedItinerary' => $selectedItinerary,'prefillDestination' => $prefillDestination,'prefillInterest' => $prefillInterest ?? null,'countryNames' => $proposalCountries,'variant' => 'proposal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.inquiry-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['destinations' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($destinations),'selected-itinerary' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($selectedItinerary),'prefill-destination' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($prefillDestination),'prefill-interest' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($prefillInterest ?? null),'country-names' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($proposalCountries),'variant' => 'proposal']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal771fbdc5784701249b7145c5a5a7483c)): ?>
<?php $attributes = $__attributesOriginal771fbdc5784701249b7145c5a5a7483c; ?>
<?php unset($__attributesOriginal771fbdc5784701249b7145c5a5a7483c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal771fbdc5784701249b7145c5a5a7483c)): ?>
<?php $component = $__componentOriginal771fbdc5784701249b7145c5a5a7483c; ?>
<?php unset($__componentOriginal771fbdc5784701249b7145c5a5a7483c); ?>
<?php endif; ?>
</section>

<?php if (isset($component)) { $__componentOriginala13c86f470d2a58cc232c97c825cd90e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala13c86f470d2a58cc232c97c825cd90e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.cta-section','data' => ['label' => 'Still deciding','title' => 'Read the journal of questions.','text' => 'Seasons, permits, packing, golf and how we travel — answered before you write to us.','image' => 'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?auto=format&fit=crop&w=1800&q=82&fm=webp','buttonText' => 'Browse the FAQs','url' => route('public.faqs')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.cta-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Still deciding','title' => 'Read the journal of questions.','text' => 'Seasons, permits, packing, golf and how we travel — answered before you write to us.','image' => 'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?auto=format&fit=crop&w=1800&q=82&fm=webp','buttonText' => 'Browse the FAQs','url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('public.faqs'))]); ?>
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

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\shishifootsteps\safari\resources\views/public/booking.blade.php ENDPATH**/ ?>