<?php $__env->startSection('title', $settings->seo_title ?? 'Shishi Footsteps | Luxury African Safaris'); ?>
<?php $__env->startSection('description', $settings->seo_description ?? 'Premium tailor-made safari journeys across East Africa.'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $cms = fn($key, $fallback = '') => \App\Models\CmsContentBlock::value('home', $key, $fallback);
    $images = [
        'hero' => \App\Support\MediaPath::publicUrl($cms('hero_image', $settings->hero_image)) ?: asset('images/itineraries/kenya-family-cover.webp'),
        'adventure' => asset('images/itineraries/tanzania-classic-cover.webp'),
        'luxury' => asset('images/itineraries/botswana-luxury-cover.webp'),
        'culture' => asset('images/itineraries/kenya-coast-day.webp'),
        'accommodation' => asset('images/itineraries/botswana-luxury-cover.webp'),
        'cta' => \App\Support\MediaPath::publicUrl($cms('cta_image', 'images/itineraries/tanzania-crater-day.webp')),
    ];
    $youtubeId = $cms('youtube_id') ?: '1CYVG70ZbyQ';

    $countryImages = collect(['Kenya','Tanzania','Uganda','Rwanda','South Africa','Namibia','Botswana'])->mapWithKeys(function ($country) use ($settings) {
        $media = $settings->mediaFor(\Illuminate\Support\Str::slug($country));
        return [$country => \App\Support\MediaPath::publicUrl($media['hero'])];
    })->all();

    $destinationCopy = [
        'Kenya' => 'Big cat country, private conservancies, dramatic migration crossings and warm coastal endings.',
        'Tanzania' => 'Serengeti plains, Ngorongoro drama and wild southern parks made for unrushed safaris.',
        'Uganda' => 'Gorilla trekking, chimpanzee forests, lake country and deeply moving wildlife encounters.',
        'Rwanda' => 'Gorilla trekking, rolling hills, golden monkeys and intimate luxury lodges with volcano views.',
        'South Africa' => 'Private reserves, refined lodges, wine country and effortless family-friendly safari routes.',
        'Namibia' => 'Desert-adapted wildlife, sculptural dunes and remote lodges beneath huge star-filled skies.',
        'Botswana' => 'Okavango waterways, mobile camps, elephant-rich landscapes and pristine wilderness.',
    ];

    $erpCountries = $destinations->keyBy(fn ($country) => strtolower($country->name));
    $countryCards = collect(array_keys($countryImages))->map(
        fn ($name) => $erpCountries->get(strtolower($name)) ?? (object) ['name' => $name]
    );

    $experienceDefaults = [
        ['Game Drives', 'Private morning and golden-hour drives with guides who read the land with patience.', 'binoculars', 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=900&q=82&fm=webp'],
        ['Balloon Safaris', 'Float above open plains before a celebratory bush breakfast in the soft early light.', 'sunrise', 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=900&q=82&fm=webp'],
        ['Cultural Visits', 'Meet communities through respectful, locally guided encounters that add meaning.', 'users', 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=900&q=82&fm=webp'],
        ['Beach Extensions', 'Ease from the bush to island air with handpicked coast and barefoot luxury stays.', 'waves', 'https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&w=900&q=82&fm=webp'],
        ['Gorilla Trekking', 'A rare forest journey to spend quiet, unforgettable time with mountain gorillas.', 'leaf', 'https://images.unsplash.com/photo-1540573133985-87b6da6d54a9?auto=format&fit=crop&w=900&q=82&fm=webp'],
        ['Honeymoon Safaris', 'Romantic camps, private decks, candlelit dinners and journeys paced around you.', 'heart', 'https://images.unsplash.com/photo-1491555103944-7c647fd857e6?auto=format&fit=crop&w=900&q=82&fm=webp'],
    ];

    $packageImages = [
        asset('images/itineraries/kenya-family-cover.webp'),
        asset('images/itineraries/tanzania-classic-cover.webp'),
        asset('images/itineraries/botswana-luxury-cover.webp'),
    ];

    $featuredAccommodation = $featuredAccommodations->first();
?>

<section class="reference-home-hero lively-home-hero" id="start" data-safari-hero>
    <img src="<?php echo e($images['hero']); ?>" alt="Private Shishi Footsteps safari">
    <div class="hero-youtube-container">
        <iframe
            class="hero-youtube-bg"
            src="https://www.youtube.com/embed/<?php echo e($youtubeId); ?>?autoplay=1&mute=1&loop=1&playlist=<?php echo e($youtubeId); ?>&controls=0&showinfo=0&rel=0&modestbranding=1&playsinline=1&enablejsapi=1"
            title="Shishi Footsteps safari background video"
            allow="autoplay; encrypted-media; picture-in-picture"
            referrerpolicy="strict-origin-when-cross-origin"
            aria-hidden="true"
            tabindex="-1"></iframe>
    </div>
    <button type="button" class="home-hero-image-link" data-trip-planner-open aria-label="Plan a trip inspired by this safari"></button>
    <div class="hero-ambient" aria-hidden="true"><span></span><span></span><span></span></div>
    <div class="reference-hero-copy">
        <span><?php echo e($cms('hero_label', 'Private African journeys')); ?></span>
        <h1><?php echo nl2br(e($cms('hero_title', 'Travel Africa, your way.'))); ?></h1>
        <p><?php echo e($cms('hero_text', 'Tailor-made safaris, trusted local expertise and thoughtful details from the first idea to the journey home.')); ?></p>
        <div class="reference-hero-actions">
            <button type="button" class="hero-plan-button" data-trip-planner-open><?php echo e(__('ui.plan_your_safari')); ?><i data-lucide="arrow-up-right"></i></button>
            <a href="<?php echo e(route('public.destinations')); ?>">Explore destinations<i data-lucide="compass"></i></a>
        </div>
    </div>
    <div class="hero-journey-points" aria-label="Shishi Footsteps benefits">
        <span><i data-lucide="route"></i><b>100%</b> tailor-made</span>
        <span><i data-lucide="users"></i><b>Local</b> specialists</span>
        <span><i data-lucide="shield-check"></i><b>Trusted</b> support</span>
    </div>
</section>

<dialog class="trip-planner-dialog" data-trip-planner aria-labelledby="trip-planner-title">
    <div class="trip-planner-shell">
        <header>
            <a href="<?php echo e(route('home')); ?>"><img src="<?php echo e(asset('images/brand/shishi-paw-white.png')); ?>" alt=""><span>Shishi Footsteps</span></a>
            <button type="button" data-trip-planner-close aria-label="Close trip planner"><i data-lucide="x"></i></button>
        </header>
        <form method="GET" action="<?php echo e(route('public.booking')); ?>">
            <div class="trip-planner-intro">
                <span>Start with the essentials</span>
                <h2 id="trip-planner-title">Let’s plan your dream trip.</h2>
                <p>Share what you know now. A safari specialist will refine every detail with you.</p>
            </div>
            <div class="trip-planner-grid">
                <label class="span-2">Where would you like to go?
                    <select name="destination">
                        <option value="">Help me choose</option>
                        <?php $__currentLoopData = ['Kenya','Tanzania','Uganda','Rwanda','South Africa','Namibia','Botswana','Multi-country safari','Golf Safari']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option><?php echo e($country); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </label>
                <label>Adults<input type="number" name="adults" min="1" max="60" value="2"></label>
                <label>Children<input type="number" name="children" min="0" max="60" value="0"></label>
                <label class="span-2">Estimated arrival date<input type="date" name="travel_date" value="<?php echo e(now()->addMonths(3)->toDateString()); ?>"></label>
                <label>Travel style
                    <select name="safari_type"><option>Tailor-made safari</option><option>Family safari</option><option>Honeymoon safari</option><option>Luxury lodge safari</option><option>Golf safari</option><option>Beach and safari</option></select>
                </label>
                <label>Budget per person
                    <select name="budget"><option value="">Not decided</option><option>$3,000 - $5,000 per person</option><option>$5,000 - $8,000 per person</option><option>$8,000 - $12,000 per person</option><option>$12,000+ per person</option></select>
                </label>
            </div>
            <button class="trip-planner-submit">Continue planning<i data-lucide="arrow-right"></i></button>
            <small><i data-lucide="lock-keyhole"></i>No obligation and no booking fees.</small>
        </form>
    </div>
</dialog>

<section class="intro-editorial" id="start">
    <div>
        <?php if (isset($component)) { $__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.section-label','data' => ['label' => $cms('intro_label', 'Tailor-made African Safaris')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.section-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($cms('intro_label', 'Tailor-made African Safaris'))]); ?>
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
        <h2><?php echo e($cms('intro_title', 'Premium journeys crafted around you')); ?></h2>
    </div>
    <p><?php echo e($cms('intro_text', 'Shishi Footsteps is a curated travel design company specializing in premium, tailor-made safaris across East Africa. Rather than selling fixed packages, we build every itinerary from scratch based on your interests, pace, comfort level, and travel goals.')); ?></p>
</section>

<section class="feature-story-grid">
    <article>
        <a href="<?php echo e(route('public.safaris')); ?>" class="story-image-link"><img src="<?php echo e($images['adventure']); ?>" alt="Safari adventure" loading="lazy"></a>
        <div><?php if (isset($component)) { $__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.section-label','data' => ['label' => '01','class' => 'light']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.section-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => '01','class' => 'light']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4)): ?>
<?php $attributes = $__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4; ?>
<?php unset($__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4)): ?>
<?php $component = $__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4; ?>
<?php unset($__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4); ?>
<?php endif; ?><h3>Safari</h3><p>Private wildlife journeys with expert guides, from the Maasai Mara to the Serengeti plains, gorilla trekking to Big Five encounters. Every safari is shaped around your pace, interests and comfort.</p></div>
    </article>
    <article>
        <a href="<?php echo e(route('public.accommodations')); ?>" class="story-image-link"><img src="<?php echo e($images['luxury']); ?>" alt="Luxury lodge" loading="lazy"></a>
        <div><?php if (isset($component)) { $__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.section-label','data' => ['label' => '02','class' => 'light']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.section-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => '02','class' => 'light']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4)): ?>
<?php $attributes = $__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4; ?>
<?php unset($__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4)): ?>
<?php $component = $__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4; ?>
<?php unset($__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4); ?>
<?php endif; ?><h3>Luxury</h3><p>Beautiful lodges, private decks, considered service, wellness retreats and comfort after every wild day in Africa's finest destinations. We select stays for location, guiding, atmosphere and how they make you feel.</p></div>
    </article>
    <article>
        <a href="<?php echo e(route('public.experiences')); ?>" class="story-image-link"><img src="<?php echo e($images['culture']); ?>" alt="Cultural safari experience" loading="lazy"></a>
        <div><?php if (isset($component)) { $__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.section-label','data' => ['label' => '03','class' => 'light']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.section-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => '03','class' => 'light']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4)): ?>
<?php $attributes = $__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4; ?>
<?php unset($__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4)): ?>
<?php $component = $__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4; ?>
<?php unset($__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4); ?>
<?php endif; ?><h3>Culture</h3><p>Respectful local encounters, community visits and cultural experiences that make the journey richer than wildlife alone. These moments add meaning to every African safari.</p></div>
    </article>
</section>

<section class="content-band destinations-band">
    <div class="section-heading">
        <div>
            <?php if (isset($component)) { $__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.section-label','data' => ['label' => 'Destinations']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.section-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Destinations']); ?>
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
            <h2><?php echo e($cms('destinations_title', 'Kenya, Tanzania, Uganda, Rwanda, South Africa, Namibia, Botswana')); ?></h2>
        </div>
        <a href="<?php echo e(route('public.destinations')); ?>">View all destinations<i data-lucide="arrow-right"></i></a>
    </div>
    <div class="destination-grid">
        <?php $__currentLoopData = $countryCards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $name = $country->name; ?>
            <?php if (isset($component)) { $__componentOriginal0252cd7f3e25a2949455cf3fcecf6f6f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0252cd7f3e25a2949455cf3fcecf6f6f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.destination-card','data' => ['title' => $name,'description' => $destinationCopy[$name] ?? 'Handpicked safari country shaped around wildlife, season, comfort and your travel style.','image' => $countryImages[$name] ?? reset($countryImages),'url' => route('public.destinations.show', \Illuminate\Support\Str::slug($name))]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.destination-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($name),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($destinationCopy[$name] ?? 'Handpicked safari country shaped around wildlife, season, comfort and your travel style.'),'image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($countryImages[$name] ?? reset($countryImages)),'url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('public.destinations.show', \Illuminate\Support\Str::slug($name)))]); ?>
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

<section class="content-band accommodation-band">
    <?php if (isset($component)) { $__componentOriginalbd252e7290190f40c7d31aeb60fc5688 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd252e7290190f40c7d31aeb60fc5688 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.accommodation-card','data' => ['title' => $featuredAccommodation?->name ?? 'Private camps and lodges with a sense of place','description' => $featuredAccommodation?->description ?? 'Stay in intimate safari camps, refined lodges and private retreats chosen for location, guiding, service and atmosphere. Every stay is matched to the kind of journey you want to feel.','meta' => $featuredAccommodation ? trim(($featuredAccommodation->country ?? '').' / '.($featuredAccommodation->region ?? ''), ' /') : 'Luxury lodges / Tented camps / Private retreats','image' => $featuredAccommodation && !empty($featuredAccommodation->images) ? (is_array($featuredAccommodation->images) ? $featuredAccommodation->images[0] : $featuredAccommodation->images) : $images['accommodation'],'slug' => $featuredAccommodation?->slug ?? null]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.accommodation-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($featuredAccommodation?->name ?? 'Private camps and lodges with a sense of place'),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($featuredAccommodation?->description ?? 'Stay in intimate safari camps, refined lodges and private retreats chosen for location, guiding, service and atmosphere. Every stay is matched to the kind of journey you want to feel.'),'meta' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($featuredAccommodation ? trim(($featuredAccommodation->country ?? '').' / '.($featuredAccommodation->region ?? ''), ' /') : 'Luxury lodges / Tented camps / Private retreats'),'image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($featuredAccommodation && !empty($featuredAccommodation->images) ? (is_array($featuredAccommodation->images) ? $featuredAccommodation->images[0] : $featuredAccommodation->images) : $images['accommodation']),'slug' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($featuredAccommodation?->slug ?? null)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbd252e7290190f40c7d31aeb60fc5688)): ?>
<?php $attributes = $__attributesOriginalbd252e7290190f40c7d31aeb60fc5688; ?>
<?php unset($__attributesOriginalbd252e7290190f40c7d31aeb60fc5688); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbd252e7290190f40c7d31aeb60fc5688)): ?>
<?php $component = $__componentOriginalbd252e7290190f40c7d31aeb60fc5688; ?>
<?php unset($__componentOriginalbd252e7290190f40c7d31aeb60fc5688); ?>
<?php endif; ?>
</section>

<section class="content-band experiences-band">
    <div class="section-heading centered">
        <div>
            <?php if (isset($component)) { $__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.section-label','data' => ['label' => 'Experiences']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.section-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Experiences']); ?>
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
            <h2><?php echo e($cms('experiences_title', 'Shape each day around what moves you.')); ?></h2>
        </div>
    </div>
    <div class="experience-grid">
        <?php $__empty_1 = true; $__currentLoopData = $activities->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php $translation = $activity->translation(); ?>
            <?php if (isset($component)) { $__componentOriginalc5caa5ee4c66d6bc5574f0c0a58180ea = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc5caa5ee4c66d6bc5574f0c0a58180ea = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.experience-card','data' => ['title' => $translation?->title ?? $activity->name,'description' => $translation?->short_description ?? $activity->description ?? 'A carefully guided safari experience designed around place, season and your travel style.','image' => is_array($activity->images ?? null) && count($activity->images) ? $activity->images[0] : $experienceDefaults[$loop->index % count($experienceDefaults)][3],'icon' => 'sparkles','url' => $activity->slug ? route('public.experiences.show', $activity->slug) : null]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.experience-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($translation?->title ?? $activity->name),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($translation?->short_description ?? $activity->description ?? 'A carefully guided safari experience designed around place, season and your travel style.'),'image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(is_array($activity->images ?? null) && count($activity->images) ? $activity->images[0] : $experienceDefaults[$loop->index % count($experienceDefaults)][3]),'icon' => 'sparkles','url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($activity->slug ? route('public.experiences.show', $activity->slug) : null)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc5caa5ee4c66d6bc5574f0c0a58180ea)): ?>
<?php $attributes = $__attributesOriginalc5caa5ee4c66d6bc5574f0c0a58180ea; ?>
<?php unset($__attributesOriginalc5caa5ee4c66d6bc5574f0c0a58180ea); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc5caa5ee4c66d6bc5574f0c0a58180ea)): ?>
<?php $component = $__componentOriginalc5caa5ee4c66d6bc5574f0c0a58180ea; ?>
<?php unset($__componentOriginalc5caa5ee4c66d6bc5574f0c0a58180ea); ?>
<?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <?php $__currentLoopData = $experienceDefaults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $experience): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if (isset($component)) { $__componentOriginalc5caa5ee4c66d6bc5574f0c0a58180ea = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc5caa5ee4c66d6bc5574f0c0a58180ea = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.experience-card','data' => ['title' => $experience[0],'description' => $experience[1],'icon' => $experience[2],'image' => $experience[3]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.experience-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($experience[0]),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($experience[1]),'icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($experience[2]),'image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($experience[3])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc5caa5ee4c66d6bc5574f0c0a58180ea)): ?>
<?php $attributes = $__attributesOriginalc5caa5ee4c66d6bc5574f0c0a58180ea; ?>
<?php unset($__attributesOriginalc5caa5ee4c66d6bc5574f0c0a58180ea); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc5caa5ee4c66d6bc5574f0c0a58180ea)): ?>
<?php $component = $__componentOriginalc5caa5ee4c66d6bc5574f0c0a58180ea; ?>
<?php unset($__componentOriginalc5caa5ee4c66d6bc5574f0c0a58180ea); ?>
<?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
    </div>
</section>

<section class="content-band packages-band">
    <div class="section-heading">
        <div>
            <?php if (isset($component)) { $__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.section-label','data' => ['label' => 'Featured Safaris']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.section-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Featured Safaris']); ?>
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
            <h2><?php echo e($cms('safaris_title', 'Safari packages ready to become personal.')); ?></h2>
        </div>
        <a href="<?php echo e(route('public.safaris')); ?>">Explore safaris<i data-lucide="arrow-right"></i></a>
    </div>
    <div class="safari-grid">
        <?php $__empty_1 = true; $__currentLoopData = $packages->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $_pkg = $package;
                $_isContent = $_pkg instanceof \App\Models\ContentItem;
                $_trans = $_isContent ? $_pkg->translation() : null;
                if ($_isContent) {
                    $_pTitle = $_trans?->title ?? $_pkg->name ?? 'Safari Package';
                    $_pSummary = $_trans?->short_description ?? 'A curated safari package from the Shishi Footsteps collection.';
                } else {
                    $_pTitle = $_pkg->title ?? 'Safari Package';
                    $_pSummary = $_pkg->summary ?? 'A curated safari package from the Shishi Footsteps collection.';
                }
                $_pDuration = ($_pkg->duration_days ?? null) ? $_pkg->duration_days.' days' : null;
                $_pPrice = ($_pkg->price_from ?? null) ? '$'.number_format((float) $_pkg->price_from) : null;
                $_pImg = !$_isContent && is_array($_pkg->images ?? null) && count($_pkg->images) ? \App\Support\MediaPath::publicUrl($_pkg->images[0]) : $packageImages[$loop->index % count($packageImages)];
            ?>
            <?php if (isset($component)) { $__componentOriginalf66f29700c8192c024c08f591d4c62ab = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf66f29700c8192c024c08f591d4c62ab = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.safari-package-card','data' => ['title' => $_pTitle,'summary' => $_pSummary,'image' => $_pImg,'duration' => $_pDuration,'country' => $_pkg->country ?? null,'price' => $_pPrice,'slug' => $_isContent ? null : ($_pkg->slug ?? null)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.safari-package-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($_pTitle),'summary' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($_pSummary),'image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($_pImg),'duration' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($_pDuration),'country' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($_pkg->country ?? null),'price' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($_pPrice),'slug' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($_isContent ? null : ($_pkg->slug ?? null))]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.safari-package-card','data' => ['title' => 'Great Migration Private Safari','summary' => 'A classic East African wildlife journey with elegant camps, private guiding and flexible pacing.','image' => $packageImages[0],'duration' => '8 days','country' => 'Kenya','price' => '$5,800']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.safari-package-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Great Migration Private Safari','summary' => 'A classic East African wildlife journey with elegant camps, private guiding and flexible pacing.','image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($packageImages[0]),'duration' => '8 days','country' => 'Kenya','price' => '$5,800']); ?>
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
            <?php if (isset($component)) { $__componentOriginalf66f29700c8192c024c08f591d4c62ab = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf66f29700c8192c024c08f591d4c62ab = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.safari-package-card','data' => ['title' => 'Gorillas and Savannahs','summary' => 'A moving combination of forest trekking, lakeside calm and big game encounters.','image' => $packageImages[1],'duration' => '9 days','country' => 'Uganda','price' => '$7,200']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.safari-package-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Gorillas and Savannahs','summary' => 'A moving combination of forest trekking, lakeside calm and big game encounters.','image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($packageImages[1]),'duration' => '9 days','country' => 'Uganda','price' => '$7,200']); ?>
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
            <?php if (isset($component)) { $__componentOriginalf66f29700c8192c024c08f591d4c62ab = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf66f29700c8192c024c08f591d4c62ab = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.safari-package-card','data' => ['title' => 'Desert and Delta Escape','summary' => 'Remote landscapes, water safaris, silent skies and refined wilderness lodges.','image' => $packageImages[2],'duration' => '10 days','country' => 'Namibia / Botswana','price' => '$8,900']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.safari-package-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Desert and Delta Escape','summary' => 'Remote landscapes, water safaris, silent skies and refined wilderness lodges.','image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($packageImages[2]),'duration' => '10 days','country' => 'Namibia / Botswana','price' => '$8,900']); ?>
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
</section>

<section class="home-golf-showcase">
    <div class="section-heading">
        <div><?php if (isset($component)) { $__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.section-label','data' => ['label' => 'African Golf Holidays']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.section-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'African Golf Holidays']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4)): ?>
<?php $attributes = $__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4; ?>
<?php unset($__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4)): ?>
<?php $component = $__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4; ?>
<?php unset($__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4); ?>
<?php endif; ?><h2>Play Africa’s most memorable fairways.</h2></div>
        <a href="<?php echo e(route('public.golf')); ?>">Explore golf holidays<i data-lucide="arrow-right"></i></a>
    </div>
    <p class="home-golf-intro">From a focused championship week to a multi-country golf circuit, we arrange tee times, caddies, club hire, private transfers and course-friendly stays around the way you want to play.</p>
    <div class="home-golf-grid">
        <article class="home-golf-card home-golf-card--wide" data-tilt-card>
            <a href="<?php echo e(route('public.golf')); ?>#golf-courses"><img src="https://images.unsplash.com/photo-1535131749006-b7f58c99034b?auto=format&fit=crop&w=1400&q=86&fm=webp" alt="Golfer driving from a championship tee" loading="lazy"><span><small>Championship play</small><strong>Explore premier courses</strong><i data-lucide="arrow-up-right"></i></span></a>
        </article>
        <article class="home-golf-card" data-tilt-card>
            <a href="<?php echo e(route('public.golf')); ?>#golf-packages"><img src="https://images.unsplash.com/photo-1592919505780-303950717480?auto=format&fit=crop&w=1100&q=86&fm=webp" alt="Championship golf course with rolling fairways" loading="lazy"><span><small>Tailor-made routes</small><strong>View golf holidays</strong><i data-lucide="arrow-up-right"></i></span></a>
        </article>
        <article class="home-golf-card" data-tilt-card>
            <a href="<?php echo e(route('public.booking', ['safari_type'=>'Golf safari'])); ?>"><img src="https://images.unsplash.com/photo-1530028828-25e8270793c5?auto=format&fit=crop&w=1100&q=86&fm=webp" alt="Professional golf clubs ready for a round" loading="lazy"><span><small>Your game, your pace</small><strong>Plan your golf trip</strong><i data-lucide="arrow-up-right"></i></span></a>
        </article>
    </div>
</section>

<?php if($blogPosts->isNotEmpty()): ?>
<section class="content-band" style="background:#f1eadb;">
    <div class="section-heading">
        <div>
            <?php if (isset($component)) { $__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.section-label','data' => ['label' => 'Journal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.section-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Journal']); ?>
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
            <h2>Field notes for better journeys</h2>
        </div>
        <a href="<?php echo e(route('public.blog')); ?>">All articles<i data-lucide="arrow-right"></i></a>
    </div>
    <div class="blog-grid">
        <?php $__currentLoopData = $blogPosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <article class="blog-card">
                <a href="<?php echo e(route('public.blog.post', $post->slug)); ?>" class="blog-image-link"><img src="<?php echo e($post->cover_image ?: 'https://images.unsplash.com/photo-1489392191049-fc10c97e64b6?auto=format&fit=crop&w=900&q=82&fm=webp'); ?>" alt="<?php echo e($post->title); ?>" loading="lazy"></a>
                <div>
                    <?php if (isset($component)) { $__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.section-label','data' => ['label' => $post->published_at?->format('M d, Y') ?? 'Travel Guide']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.section-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($post->published_at?->format('M d, Y') ?? 'Travel Guide')]); ?>
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
                    <h2><?php echo e($post->title); ?></h2>
                    <p><?php echo e($post->seo_description ?? Str::limit(strip_tags($post->content), 140)); ?></p>
                    <a href="<?php echo e(route('public.blog.post', $post->slug)); ?>">Read more<i data-lucide="arrow-up-right"></i></a>
                </div>
            </article>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</section>
<?php endif; ?>

<section class="responsible-section">
    <div>
        <?php if (isset($component)) { $__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.section-label','data' => ['label' => 'Responsible Travel','class' => 'light']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.section-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Responsible Travel','class' => 'light']); ?>
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
        <h2>Travel that leaves more than footprints.</h2>
    </div>
    <article><i data-lucide="hand-heart"></i><h3>Community support</h3><p>We favour partners who invest in local employment, fair opportunity and meaningful community programs across East Africa.</p></article>
    <article><i data-lucide="shield-check"></i><h3>Conservation</h3><p>Safari choices can protect habitat, fund rangers and keep wildlife corridors alive for the future. We support conservation-conscious travel.</p></article>
    <article><i data-lucide="leaf"></i><h3>Eco-conscious travel</h3><p>We prioritise thoughtful routing, lower-impact stays and operators who take sustainability seriously across every journey we design.</p></article>
</section>

<?php if (isset($component)) { $__componentOriginala13c86f470d2a58cc232c97c825cd90e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala13c86f470d2a58cc232c97c825cd90e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.cta-section','data' => ['label' => 'Your Private Safari','title' => 'Let Us Design Your Safari Journey','text' => 'Tell us your preferences and our specialists will shape a tailor-made itinerary with the right destinations, pace, guides and lodges — built from scratch around you.','image' => $images['cta'],'buttonText' => 'Start Planning']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.cta-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Your Private Safari','title' => 'Let Us Design Your Safari Journey','text' => 'Tell us your preferences and our specialists will shape a tailor-made itinerary with the right destinations, pace, guides and lodges — built from scratch around you.','image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($images['cta']),'buttonText' => 'Start Planning']); ?>
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

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\shishifootsteps\safari\resources\views/public/home.blade.php ENDPATH**/ ?>