<?php $__env->startSection('title', $name.' Safari Tours | Shishi Footsteps'); ?>
<?php $__env->startSection('description', 'Tailor-made '.$name.' safari tours, private itineraries, trusted lodges and expert local planning.'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $countryMedia = $settings->mediaFor($slug);
    $heroImage = \App\Support\MediaPath::publicUrl($countryMedia['hero']);
    $galleryImages = collect($countryMedia['gallery'])->map(fn ($path) => \App\Support\MediaPath::publicUrl($path))->filter()->values();
    $copy = [
        'kenya' => 'Big cats, private conservancies, the Great Migration and warm Indian Ocean endings.',
        'tanzania' => 'Serengeti plains, Ngorongoro drama, Kilimanjaro and wild southern parks.',
        'uganda' => 'Gorilla forests, chimpanzees, the Nile and deeply moving wildlife encounters.',
        'rwanda' => 'Volcanoes, mountain gorillas, golden monkeys and refined highland lodges.',
        'south-africa' => 'Private reserves, Cape landscapes, wine country and exceptional golf.',
        'namibia' => 'Sculptural dunes, desert-adapted wildlife and immense starlit wilderness.',
        'botswana' => 'Okavango waterways, elephant-rich landscapes and pristine mobile safaris.',
    ];
?>

<section class="country-tour-hero">
    <a href="<?php echo e(route('public.booking', ['destination' => $name])); ?>" class="country-hero-image-link" aria-label="Plan a <?php echo e($name); ?> safari"><img src="<?php echo e($heroImage); ?>" alt="<?php echo e($name); ?> safari"></a>
    <div><span>Tailor-made journeys</span><h1><?php echo e(strtoupper($name)); ?> TOURS</h1><p><?php echo e($copy[$slug]); ?></p></div>
</section>

<nav class="country-breadcrumb">
    <a href="<?php echo e(route('home')); ?>">Home</a><span>&rsaquo;</span>
    <a href="<?php echo e(route('public.destinations')); ?>">Countries</a><span>&rsaquo;</span><b><?php echo e($name); ?></b>
</nav>

<section class="tour-catalogue">
    <aside>
        <strong>Types</strong>
        <label><input type="checkbox"> Safari</label>
        <label><input type="checkbox"> Family travel</label>
        <label><input type="checkbox"> Luxury</label>
        <label><input type="checkbox"> Golf</label>
        <strong>Travel time</strong>
        <p>All journeys are tailor-made around your preferred dates.</p>
    </aside>
    <main>
        <div class="tour-catalogue-head">
            <div><span><?php echo e($safaris->count() ?: 'Private'); ?> journeys</span><h2><?php echo e($name); ?> safari ideas</h2></div>
            <p>These are starting points. We refine every route, lodge and experience around you.</p>
        </div>
        <div class="reference-tour-list">
            <?php $__empty_1 = true; $__currentLoopData = $safaris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $safari): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <article>
                    <div class="tour-image">
                        <a href="<?php echo e(route('public.safaris.show', $safari->slug)); ?>" class="tour-image-link"><img src="<?php echo e(is_array($safari->images) && count($safari->images) ? \App\Support\MediaPath::publicUrl($safari->images[0]) : ($galleryImages[$loop->index % max(1, $galleryImages->count())] ?? $heroImage)); ?>" alt="<?php echo e($safari->title); ?>"></a>
                        <button type="button" aria-label="Save <?php echo e($safari->title); ?>"><i data-lucide="heart"></i></button>
                        <span><?php echo e($name); ?></span>
                    </div>
                    <div>
                        <small><?php echo e($safari->duration_days); ?> days</small><h3><?php echo e($safari->title); ?></h3>
                        <p><?php echo e(\Illuminate\Support\Str::limit($safari->summary, 170)); ?></p>
                        <ul>
                            <?php $__currentLoopData = $safari->days->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($day->title ?: 'Day '.$day->day_number); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                        <a href="<?php echo e(route('public.safaris.show', $safari->slug)); ?>">View this trip</a>
                    </div>
                </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <?php
                    $ideas = [
                        ['Classic '.$name.' Safari', 'Private guiding, signature wildlife areas and handpicked camps.'],
                        [$name.' Family Adventure', 'A flexible journey with child-friendly pacing and memorable experiences.'],
                        ['Luxury '.$name.' & Golf', 'Championship fairways paired with beautiful landscapes and private safari days.'],
                    ];
                ?>
                <?php $__currentLoopData = $ideas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idea): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <article>
                        <div class="tour-image"><a href="<?php echo e(route('public.booking', ['destination' => $name, 'message' => $idea[0]])); ?>" class="tour-image-link"><img src="<?php echo e($galleryImages[$loop->index % max(1, $galleryImages->count())] ?? $heroImage); ?>" alt="<?php echo e($idea[0]); ?>"></a><span><?php echo e($name); ?></span></div>
                        <div><small>Tailor-made</small><h3><?php echo e($idea[0]); ?></h3><p><?php echo e($idea[1]); ?></p><a href="<?php echo e(route('public.booking', ['destination' => $name])); ?>">Request this trip</a></div>
                    </article>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>
    </main>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\public\destination-show.blade.php ENDPATH**/ ?>