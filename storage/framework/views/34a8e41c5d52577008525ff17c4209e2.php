<?php $__env->startSection('title', $itinerary ? 'Edit Itinerary' : 'New Itinerary'); ?>

<?php $__env->startSection('content'); ?>
<div class="ops-page-heading">
    <div><p class="eyebrow">Itinerary studio</p><h1><?php echo e($itinerary?->title ?? 'New itinerary'); ?></h1><p>Build a complete reusable safari with daily descriptions, logistics, accommodation and photography.</p></div>
    <div class="heading-actions">
        <?php if($itinerary): ?>
            <a class="button button-secondary" href="<?php echo e(route('admin.itineraries.show', $itinerary)); ?>"><i data-lucide="eye"></i>Preview</a>
            <a class="button button-primary" href="<?php echo e(route('admin.itineraries.pdf', $itinerary)); ?>"><i data-lucide="file-down"></i>Download PDF</a>
        <?php endif; ?>
        <a class="button button-secondary" href="<?php echo e(route('admin.itineraries.index')); ?>"><i data-lucide="arrow-left"></i>List</a>
    </div>
</div>
<?php echo $__env->make('admin.partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="ops-detail-layout itinerary-editor">
    <nav class="ops-side-tabs">
        <a href="#overview"><i data-lucide="notebook-text"></i>Overview</a>
        <?php if($itinerary): ?>
            <a href="#days"><i data-lucide="route"></i>Day by day</a>
            <a href="#gallery"><i data-lucide="images"></i>Gallery</a>
            <a href="#publishing"><i data-lucide="globe-2"></i>Publishing</a>
        <?php endif; ?>
    </nav>
    <div class="ops-detail-content">
        <section id="overview" class="ops-panel ops-form-panel">
            <div class="ops-panel-title"><div><h2>Program overview</h2><p>Client-facing information, route details, pricing and publishing status.</p></div></div>
            <form method="POST" enctype="multipart/form-data" action="<?php echo e($itinerary ? route('admin.itineraries.update', $itinerary) : route('admin.itineraries.store')); ?>">
                <?php echo csrf_field(); ?> <?php if($itinerary): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
                <div class="ops-form-grid">
                    <label>Reference code<input name="code" value="<?php echo e(old('code', $itinerary?->code)); ?>" placeholder="Generated automatically"></label>
                    <label>Status<select name="status"><?php $__currentLoopData = ['draft','published','archived']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($status); ?>" <?php if(old('status', $itinerary?->status ?? 'draft') === $status): echo 'selected'; endif; ?>><?php echo e(ucfirst($status)); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></label>
                    <label class="span-2">Itinerary title<input name="title" value="<?php echo e(old('title', $itinerary?->title)); ?>" required></label>
                    <label>Countries<input name="countries" value="<?php echo e(old('countries', $itinerary?->countries ?? 'Kenya')); ?>" placeholder="Kenya, Tanzania" required></label>
                    <label>Travel style<input name="travel_style" value="<?php echo e(old('travel_style', $itinerary?->travel_style ?? 'Private tailor-made safari')); ?>" required></label>
                    <label>Days<input type="number" name="duration_days" value="<?php echo e(old('duration_days', $itinerary?->duration_days ?? 7)); ?>" min="1" required></label>
                    <label>Nights<input type="number" name="nights" value="<?php echo e(old('nights', $itinerary?->nights ?? 6)); ?>" min="0" required></label>
                    <label>Minimum guests<input type="number" name="minimum_guests" value="<?php echo e(old('minimum_guests', $itinerary?->minimum_guests ?? 2)); ?>" min="1" required></label>
                    <label>Maximum guests<input type="number" name="maximum_guests" value="<?php echo e(old('maximum_guests', $itinerary?->maximum_guests ?? 12)); ?>" min="1" required></label>
                    <label>Price from<input type="number" step="0.01" name="price_from" value="<?php echo e(old('price_from', $itinerary?->price_from ?? 0)); ?>" min="0" required></label>
                    <label>Currency<input name="currency" maxlength="3" value="<?php echo e(old('currency', $itinerary?->currency ?? 'USD')); ?>" required></label>
                    <label>Start location<input name="start_location" value="<?php echo e(old('start_location', $itinerary?->start_location)); ?>"></label>
                    <label>End location<input name="end_location" value="<?php echo e(old('end_location', $itinerary?->end_location)); ?>"></label>
                    <label>Difficulty<input name="difficulty" value="<?php echo e(old('difficulty', $itinerary?->difficulty ?? 'Easy')); ?>" required></label>
                    <label>Accommodation level<input name="accommodation_level" value="<?php echo e(old('accommodation_level', $itinerary?->accommodation_level ?? 'Luxury lodges and camps')); ?>"></label>
                    <label class="span-2">Best time to travel<input name="best_time" value="<?php echo e(old('best_time', $itinerary?->best_time)); ?>" placeholder="June to October and January to March"></label>
                    <label class="span-2">Short summary<textarea name="summary" rows="3" required><?php echo e(old('summary', $itinerary?->summary)); ?></textarea></label>
                    <label class="span-2">Detailed introduction<textarea name="description" rows="7"><?php echo e(old('description', $itinerary?->description)); ?></textarea></label>
                    <label>Inclusions, one per line<textarea name="inclusions_text" rows="8"><?php echo e(old('inclusions_text', implode("\n", $itinerary?->inclusions ?? []))); ?></textarea></label>
                    <label>Exclusions, one per line<textarea name="exclusions_text" rows="8"><?php echo e(old('exclusions_text', implode("\n", $itinerary?->exclusions ?? []))); ?></textarea></label>
                    <label class="span-2">Important notes<textarea name="important_notes" rows="4"><?php echo e(old('important_notes', $itinerary?->important_notes)); ?></textarea></label>
                    <label class="span-2 itinerary-file-field">Cover image<input type="file" name="cover_image_upload" accept="image/jpeg,image/png,image/webp"><small>JPG, PNG or WebP, maximum 8 MB. Landscape photography works best.</small></label>
                    <?php if($itinerary?->cover_image): ?><div class="span-2 current-cover"><img src="<?php echo e($itinerary->cover_image_url); ?>" alt="<?php echo e($itinerary->title); ?>"></div><?php endif; ?>
                    <label class="check-label"><input type="checkbox" name="featured" value="1" <?php if(old('featured', $itinerary?->featured)): echo 'checked'; endif; ?>> Feature this itinerary</label>
                    <label id="publishing">SEO title<input name="seo_title" value="<?php echo e(old('seo_title', $itinerary?->seo_title)); ?>"></label>
                    <label class="span-2">SEO description<textarea name="seo_description" rows="3"><?php echo e(old('seo_description', $itinerary?->seo_description)); ?></textarea></label>
                </div>
                <div class="ops-form-footer"><button class="button button-primary"><i data-lucide="save"></i><?php echo e($itinerary ? 'Save itinerary' : 'Create itinerary'); ?></button></div>
            </form>
        </section>

        <?php if($itinerary): ?>
        <section id="days" class="ops-panel ops-form-panel">
            <div class="ops-panel-title"><div><h2>Day-by-day program</h2><p>Each day can have detailed copy, route logistics, accommodation, meals and multiple images.</p></div><span class="ops-pill ops-pill--blue"><?php echo e($itinerary->days->count()); ?> of <?php echo e($itinerary->duration_days); ?> days</span></div>
            <details class="itinerary-add-day" open>
                <summary><i data-lucide="plus-circle"></i>Add itinerary day</summary>
                <?php echo $__env->make('admin.itineraries.partials.day-form', ['day' => null], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </details>
            <div class="itinerary-day-editor-list">
                <?php $__currentLoopData = $itinerary->days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <details class="itinerary-day-editor" <?php if($loop->first): ?> open <?php endif; ?>>
                        <summary>
                            <?php if($day->primary_image): ?><img src="<?php echo e($day->primary_image_url); ?>" alt="<?php echo e($day->title); ?>" loading="lazy"><?php else: ?><span class="day-image-placeholder"><i data-lucide="image"></i></span><?php endif; ?>
                            <b>Day <?php echo e($day->day_number); ?></b><strong><?php echo e($day->title); ?></strong><small><?php echo e($day->location); ?> <?php if($day->accommodation): ?> · <?php echo e($day->accommodation); ?> <?php endif; ?></small><i data-lucide="chevron-down"></i>
                        </summary>
                        <?php echo $__env->make('admin.itineraries.partials.day-form', ['day' => $day], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php if($day->images->isNotEmpty()): ?>
                            <div class="day-mini-gallery">
                                <?php $__currentLoopData = $day->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div><img src="<?php echo e($image->url); ?>" alt="<?php echo e($image->alt_text); ?>" loading="lazy"><form method="POST" action="<?php echo e(route('admin.itineraries.images.destroy', [$itinerary, $image])); ?>"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button title="Remove"><i data-lucide="x"></i></button></form></div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>
                        <form class="day-delete-form" method="POST" action="<?php echo e(route('admin.itineraries.days.destroy', [$itinerary, $day])); ?>" onsubmit="return confirm('Delete this complete day?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="button danger-button"><i data-lucide="trash-2"></i>Delete day</button></form>
                    </details>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </section>

        <section id="gallery" class="ops-panel ops-form-panel">
            <div class="ops-panel-title"><div><h2>Itinerary gallery</h2><p>Upload multiple images, add credits and choose a new cover from the gallery.</p></div></div>
            <form class="itinerary-gallery-upload" method="POST" enctype="multipart/form-data" action="<?php echo e(route('admin.itineraries.images.store', $itinerary)); ?>">
                <?php echo csrf_field(); ?>
                <label>Images<input type="file" name="images[]" accept="image/jpeg,image/png,image/webp" multiple required></label>
                <label>Shared caption<input name="caption" placeholder="Optional image caption"></label>
                <label>Photo credit<input name="credit" placeholder="Optional photographer or supplier"></label>
                <button class="button button-primary"><i data-lucide="upload"></i>Upload images</button>
            </form>
            <div class="itinerary-gallery">
                <?php $__empty_1 = true; $__currentLoopData = $itinerary->images->whereNull('itinerary_day_id'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <figure>
                        <img src="<?php echo e($image->url); ?>" alt="<?php echo e($image->alt_text); ?>" loading="lazy">
                        <figcaption><span><?php echo e($image->caption ?: 'Itinerary image'); ?><small><?php echo e($image->credit); ?></small></span><div>
                            <?php if($itinerary->cover_image !== $image->path): ?><form method="POST" action="<?php echo e(route('admin.itineraries.images.cover', [$itinerary, $image])); ?>"><?php echo csrf_field(); ?><button title="Use as cover"><i data-lucide="star"></i></button></form><?php else: ?><span class="gallery-cover-label">Cover</span><?php endif; ?>
                            <form method="POST" action="<?php echo e(route('admin.itineraries.images.destroy', [$itinerary, $image])); ?>"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button title="Remove"><i data-lucide="trash-2"></i></button></form>
                        </div></figcaption>
                    </figure>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="ops-empty">No gallery images yet. Upload several landscapes above.</div>
                <?php endif; ?>
            </div>
        </section>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\itineraries\form.blade.php ENDPATH**/ ?>