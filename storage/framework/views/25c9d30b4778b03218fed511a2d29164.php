<?php
    $cms = fn($key, $fallback='') => \App\Models\CmsContentBlock::value('golf', $key, $fallback);
    $countryName = $countryName ?? null;
    $countrySlug = $countrySlug ?? null;
    $hero = $cms('hero_image') ? \App\Support\MediaPath::publicUrl($cms('hero_image')) : '';
    if (!$hero) {
        $hero = 'https://images.unsplash.com/photo-1592919505780-303950717480?auto=format&fit=crop&w=2000&q=88&fm=webp';
    }
    $countryHeroImages = [
        'kenya' => 'https://images.unsplash.com/photo-1535131749006-b7f58c99034b?auto=format&fit=crop&w=2000&q=88&fm=webp',
        'tanzania' => 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=2000&q=88&fm=webp',
        'uganda' => 'https://images.unsplash.com/photo-1540573133985-87b6da6d54a9?auto=format&fit=crop&w=2000&q=88&fm=webp',
        'rwanda' => 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=2000&q=88&fm=webp',
        'south-africa' => 'https://images.unsplash.com/photo-1580060839134-75a5edca2e99?auto=format&fit=crop&w=2000&q=88&fm=webp',
    ];
    if ($countrySlug && isset($countryHeroImages[$countrySlug])) {
        $hero = $countryHeroImages[$countrySlug];
    }
    $courses = [
        ['name'=>'Muthaiga Golf Club','country'=>'Kenya','location'=>'Nairobi','description'=>'Kenya\'s storied championship parkland course and a regular home of the Magical Kenya Open.','image'=>'https://images.unsplash.com/photo-1535131749006-b7f58c99034b?auto=format&fit=crop&w=1200&q=86&fm=webp','url'=>'https://www.muthaigagolfclub.com/'],
        ['name'=>'Karen Country Club','country'=>'Kenya','location'=>'Nairobi','description'=>'An established 18-hole course with mature trees, strategic bunkering and a celebrated tournament heritage.','image'=>'https://images.unsplash.com/photo-1530028828-25e8270793c5?auto=format&fit=crop&w=1200&q=86&fm=webp','url'=>'https://www.karencountryclub.org/Golf/Course_Photos'],
        ['name'=>'Kigali Golf Resort & Villas','country'=>'Rwanda','location'=>'Kigali','description'=>'A modern 18-hole, par-72 championship layout shaped through Kigali\'s rolling green landscape.','image'=>'https://images.unsplash.com/photo-1593111774240-d529f12cf4bb?auto=format&fit=crop&w=1200&q=86&fm=webp','url'=>'https://www.kigaligolf.rw/'],
        ['name'=>'Vipingo Ridge PGA Baobab Course','country'=>'Kenya','location'=>'Kilifi','description'=>'A coastal par-72 championship course with dramatic ridge views, native baobabs and PGA-accredited facilities.','image'=>'https://images.unsplash.com/photo-1592919505780-303950717480?auto=format&fit=crop&w=1200&q=86&fm=webp','url'=>'https://www.vipingoridge.com/golf'],
    ];
    $packages = [
        ['title'=>'The Great Rift Valley Golf Safari Circuit','country'=>'Kenya','duration'=>'8 Days','price'=>'On request','summary'=>'A Kenya golf circuit with Rift Valley fairways, wildlife landscapes and relaxed safari pacing.','image'=>'https://images.unsplash.com/photo-1592919505780-303950717480?auto=format&fit=crop&w=1200&q=86&fm=webp','slug'=>'the-great-rift-valley-golf-safari-circuit'],
        ['title'=>'The Coastal Golf and Beach Safari Circuit','country'=>'Kenya','duration'=>'6 Days','price'=>'On request','summary'=>'A coastal golf escape with Indian Ocean calm, beach time and carefully arranged rounds.','image'=>'https://images.unsplash.com/photo-1593111774240-d529f12cf4bb?auto=format&fit=crop&w=1200&q=86&fm=webp','slug'=>'the-coastal-golf-and-beach-safari-circuit'],
        ['title'=>'Rwanda Championship Golf Week','country'=>'Rwanda','duration'=>'7 Days','price'=>'From USD 6,000','summary'=>'Kigali golf with gorilla trekking and elegant highland travel in Rwanda.','image'=>'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=1200&q=86&fm=webp','slug'=>'rwanda-championship-golf-week'],
        ['title'=>'Queen Elizabeth & Tooro Golf Safari','country'=>'Uganda','duration'=>'Tailor-made','price'=>'On request','summary'=>'Uganda golf paired with highland landscapes, wildlife and warm local hosting.','image'=>'https://images.unsplash.com/photo-1540573133985-87b6da6d54a9?auto=format&fit=crop&w=1200&q=86&fm=webp','slug'=>'queen-elizabeth-tooro-golf-safari'],
        ['title'=>'South Africa Golf Travel','country'=>'South Africa','duration'=>'7 Days','price'=>'On request','summary'=>'Cape golf, wine country, coastal beauty and polished private travel.','image'=>'https://images.unsplash.com/photo-1580060839134-75a5edca2e99?auto=format&fit=crop&w=1200&q=86&fm=webp','slug'=>'7-days-6-nights-south-africa-golf-travel'],
    ];
    // Prefer live published Rwanda/country golf safaris from DB when available
    $dbPackages = \App\Models\ItineraryV2::where('published', true)
        ->when($countryName, fn ($q) => $q->where('country', $countryName))
        ->where(function ($q) {
            $q->where('title', 'like', '%golf%')->orWhere('slug', 'like', '%golf%')->orWhere('summary', 'like', '%golf%');
        })
        ->orderByDesc('featured')
        ->get()
        ->map(fn ($s) => [
            'title' => $s->title,
            'country' => $s->country,
            'duration' => $s->duration_days ? $s->duration_days.' Days' : 'Tailor-made',
            'price' => $s->price_from ? ('USD '.number_format((float) $s->price_from)) : 'On request',
            'summary' => \Illuminate\Support\Str::limit($s->summary, 140),
            'image' => is_array($s->images) && count($s->images) ? \App\Support\MediaPath::publicUrl($s->images[0]) : $hero,
            'slug' => $s->slug,
        ])->all();
    if ($countryName && count($dbPackages)) {
        $packages = $dbPackages;
    }
    if (! empty($countryName)) {
        $courses = collect($courses)->where('country', $countryName)->values()->all() ?: $courses;
        $packages = collect($packages)->where('country', $countryName)->values()->all() ?: [[
            'title' => $countryName.' Private Golf Safari',
            'country' => $countryName,
            'duration' => 'Tailor-made',
            'price' => 'On request',
            'summary' => 'A custom '.$countryName.' golf safari with tee times, private transfers, stays and safari or coast extensions arranged around you.',
            'image' => $hero,
        ]];
    }
?>

<?php $__env->startSection('title', ($countryName ?? null) ? ($countryName ?? '').' Golf Tours | Shishi Footsteps' : 'African Golf Holidays | Shishi Footsteps'); ?>
<?php $__env->startSection('description', 'Bespoke luxury golf tours combining world-class African courses with safaris, beaches, culture and exceptional travel experiences.'); ?>

<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginal7667e390c55120bda1fc27c0189959e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7667e390c55120bda1fc27c0189959e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.page-hero','data' => ['label' => ''.e($countryName ? 'Tee Off / '.$countryName : $cms('hero_label', 'African Golf Holidays')).'','title' => ''.e($countryName ? $countryName.' Golf Safaris' : $cms('hero_title', 'Beyond the Fairways')).'','subtitle' => ''.e($countryName ? 'Private '.$countryName.' golf travel with tee times, safari extensions, handpicked stays and smooth local support.' : $cms('hero_subtitle', 'Championship golf, carefully timed tee sheets and smooth travel between Africa\'s most rewarding courses.')).'','image' => $hero,'url' => route('public.booking', $countryName ? ['destination' => $countryName] : ['safari_type' => 'Golf safari']),'youtubeId' => $cms('youtube_id') ?: 'iG5nlWiP9Ro']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.page-hero'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => ''.e($countryName ? 'Tee Off / '.$countryName : $cms('hero_label', 'African Golf Holidays')).'','title' => ''.e($countryName ? $countryName.' Golf Safaris' : $cms('hero_title', 'Beyond the Fairways')).'','subtitle' => ''.e($countryName ? 'Private '.$countryName.' golf travel with tee times, safari extensions, handpicked stays and smooth local support.' : $cms('hero_subtitle', 'Championship golf, carefully timed tee sheets and smooth travel between Africa\'s most rewarding courses.')).'','image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($hero),'url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('public.booking', $countryName ? ['destination' => $countryName] : ['safari_type' => 'Golf safari'])),'youtubeId' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($cms('youtube_id') ?: 'iG5nlWiP9Ro')]); ?>
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

<section class="intro-editorial" id="start">
    <div><?php if (isset($component)) { $__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.section-label','data' => ['label' => $cms('intro_label', 'Tee Off With Shishi Footsteps')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.section-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($cms('intro_label', 'Tee Off With Shishi Footsteps'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4)): ?>
<?php $attributes = $__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4; ?>
<?php unset($__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4)): ?>
<?php $component = $__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4; ?>
<?php unset($__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4); ?>
<?php endif; ?><h2><?php echo e($countryName ? $countryName.' golf, shaped around your pace' : $cms('intro_title', 'A golf safari designed around your game')); ?></h2></div>
    <p><?php echo e($cms('intro_text', 'Shishi Footsteps designs luxury golf safaris across Africa. We combine access to premier golf courses with unforgettable safari adventures, relaxing coastal escapes, premium accommodation and meaningful cultural experiences. Every itinerary is thoughtfully designed around the traveller\'s interests, travel style and preferred pace. From the first tee time to the final sunset, our team manages every detail to create a seamless and memorable African journey.')); ?></p>
</section>

<section class="golf-service-band">
    <div class="section-heading centered"><div><?php if (isset($component)) { $__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.section-label','data' => ['label' => $cms('services_label', 'The Shishi Difference'),'class' => 'light']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.section-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($cms('services_label', 'The Shishi Difference')),'class' => 'light']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4)): ?>
<?php $attributes = $__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4; ?>
<?php unset($__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4)): ?>
<?php $component = $__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4; ?>
<?php unset($__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4); ?>
<?php endif; ?><h2><?php echo e($cms('services_title', 'Everything a travelling golfer needs')); ?></h2></div></div>
    <div class="golf-service-grid">
        <article><i data-lucide="flag"></i><h3>Premier Courses</h3><p>Championship layouts selected for course quality, scenery, conditioning and professional standards.</p></article>
        <article><i data-lucide="calendar-check"></i><h3>Protected Tee Times</h3><p>Rounds arranged around travel time, warm-up needs and the best realistic playing windows.</p></article>
        <article><i data-lucide="briefcase-business"></i><h3>Clubs & Caddies</h3><p>Quality rental sets, carts, caddies, practice facilities and bag transfers reserved in advance.</p></article>
        <article><i data-lucide="sparkles"></i><h3>Tailor-Made</h3><p>Every golf itinerary built around your handicap, companions, preferred courses and budget.</p></article>
    </div>
</section>

<section class="content-band golf-packages-section" id="golf-packages">
    <div class="section-heading"><div><?php if (isset($component)) { $__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.section-label','data' => ['label' => $cms('packages_label', 'Golf Itineraries')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.section-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($cms('packages_label', 'Golf Itineraries'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4)): ?>
<?php $attributes = $__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4; ?>
<?php unset($__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4)): ?>
<?php $component = $__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4; ?>
<?php unset($__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4); ?>
<?php endif; ?><h2><?php echo e($countryName ? $countryName.' golf ideas' : $cms('packages_title', 'Choose a fairway, then make it yours')); ?></h2></div><a href="<?php echo e(route('public.booking', $countryName ? ['destination' => $countryName] : ['safari_type' => 'Golf safari'])); ?>">Plan a golf trip<i data-lucide="arrow-right"></i></a></div>
    <div class="golf-package-grid">
        <?php $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pkg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $packageUrl = !empty($pkg['slug']) && \App\Models\ItineraryV2::where('slug', $pkg['slug'])->where('published', true)->exists() ? route('public.safaris.show', $pkg['slug']) : route('public.booking', ['destination' => $pkg['country']]); ?>
            <article class="golf-package-card">
                <a href="<?php echo e($packageUrl); ?>" class="golf-package-image-link"><img src="<?php echo e($pkg['image']); ?>" alt="<?php echo e($pkg['title']); ?>" loading="lazy"><span>Plan this golf trip</span></a>
                <div class="golf-package-body">
                    <div class="golf-package-meta"><span><i data-lucide="map-pin"></i><?php echo e($pkg['country']); ?></span><span><i data-lucide="calendar-days"></i><?php echo e($pkg['duration']); ?></span></div>
                    <h3><a href="<?php echo e($packageUrl); ?>"><?php echo e($pkg['title']); ?></a></h3><p><?php echo e($pkg['summary']); ?></p>
                    <div class="golf-package-footer"><span>From <?php echo e($pkg['price']); ?></span><a href="<?php echo e($packageUrl); ?>">Plan this trip<i data-lucide="arrow-up-right"></i></a></div>
                </div>
            </article>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</section>

<section class="content-band golf-courses-section" id="golf-courses">
    <div class="section-heading centered"><div><?php if (isset($component)) { $__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.section-label','data' => ['label' => $cms('courses_label', 'Premier Courses')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.section-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($cms('courses_label', 'Premier Courses'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4)): ?>
<?php $attributes = $__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4; ?>
<?php unset($__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4)): ?>
<?php $component = $__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4; ?>
<?php unset($__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4); ?>
<?php endif; ?><h2><?php echo e($cms('courses_title', 'Africa\'s standout fairways')); ?></h2></div></div>
    <div class="course-grid">
        <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <article class="course-card">
                <a href="<?php echo e($course['url']); ?>" target="_blank" rel="noopener" class="course-image-link"><img src="<?php echo e($course['image']); ?>" alt="<?php echo e($course['name']); ?>" loading="lazy"><span>Visit course website<i data-lucide="external-link"></i></span></a>
                <div class="course-body"><span class="course-location"><?php echo e($course['country']); ?> / <?php echo e($course['location']); ?></span><h3><?php echo e($course['name']); ?></h3><p><?php echo e($course['description']); ?></p><a class="course-plan-link" href="<?php echo e(route('public.contact',['destination'=>$course['country']])); ?>">Plan a round<i data-lucide="arrow-up-right"></i></a></div>
            </article>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</section>

<section class="golf-complete-service">
    <div class="section-heading centered"><div><?php if (isset($component)) { $__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.section-label','data' => ['label' => $cms('complete_label', 'Complete Golf Service')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.section-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($cms('complete_label', 'Complete Golf Service'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4)): ?>
<?php $attributes = $__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4; ?>
<?php unset($__attributesOriginalef375b1d2be2fadf1abf4fd72c1d16c4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4)): ?>
<?php $component = $__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4; ?>
<?php unset($__componentOriginalef375b1d2be2fadf1abf4fd72c1d16c4); ?>
<?php endif; ?><h2><?php echo e($cms('complete_title', 'Everything around your round, handled')); ?></h2></div></div>
    <div class="beyond-grid">
        <article><i data-lucide="badge-help"></i><h3>PGA Coaching</h3><p>Private lessons, warm-ups and academy sessions arranged to suit your playing goals.</p></article>
        <article><i data-lucide="trophy"></i><h3>Tournament Travel</h3><p>Thoughtful planning for individual players, teams, corporate golf days and spectators.</p></article>
        <article><i data-lucide="car-front"></i><h3>Golf Transfers</h3><p>Private vehicles with proper club space and realistic timings between hotels and courses.</p></article>
        <article><i data-lucide="hotel"></i><h3>Golf Resorts</h3><p>Stay close to the first tee in comfortable properties chosen around your playing schedule.</p></article>
        <article><i data-lucide="utensils"></i><h3>Clubhouse Dining</h3><p>Lunches, prize-givings and nineteenth-hole moments booked around your round.</p></article>
        <article><i data-lucide="shield-check"></i><h3>On-Trip Support</h3><p>Local assistance for schedule changes, equipment needs and course coordination.</p></article>
    </div>
</section>

<?php if (isset($component)) { $__componentOriginala13c86f470d2a58cc232c97c825cd90e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala13c86f470d2a58cc232c97c825cd90e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.cta-section','data' => ['label' => $cms('cta_label', 'Your Golf Safari'),'title' => $cms('cta_title', 'Where Passion for Golf Meets the Spirit of Adventure'),'text' => $cms('cta_text', 'Tell us your preferred courses, travel dates and handicap. Our specialists will create a personalised African golf safari around you.'),'image' => $cms('cta_image') ? \App\Support\MediaPath::publicUrl($cms('cta_image')) : $hero,'buttonText' => 'Start Planning','url' => route('public.contact')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.cta-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($cms('cta_label', 'Your Golf Safari')),'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($cms('cta_title', 'Where Passion for Golf Meets the Spirit of Adventure')),'text' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($cms('cta_text', 'Tell us your preferred courses, travel dates and handicap. Our specialists will create a personalised African golf safari around you.')),'image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($cms('cta_image') ? \App\Support\MediaPath::publicUrl($cms('cta_image')) : $hero),'buttonText' => 'Start Planning','url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('public.contact'))]); ?>
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

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\shishifootsteps\safari\resources\views/public/golf.blade.php ENDPATH**/ ?>