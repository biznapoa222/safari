<?php $__env->startSection('title', 'Homepage Settings'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-heading">
    <div>
        <p class="eyebrow">Website CMS</p>
        <h1>Homepage Settings</h1>
        <p>Control the public hero, featured website content, and homepage SEO metadata.</p>
    </div>
    <a href="<?php echo e(route('home')); ?>" target="_blank" class="button button-secondary"><i data-lucide="external-link"></i>View website</a>
</div>

<?php echo $__env->make('admin.partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<form method="POST" action="<?php echo e(route('admin.cms.home-settings.update')); ?>" class="ops-panel" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>

    <div class="ops-form-grid website-settings-grid">
        <label class="span-2">Hero image URL
            <input name="hero_image" value="<?php echo e(old('hero_image', $settings->hero_image)); ?>" placeholder="https://...jpg or /storage/...webp">
        </label>

        <label>Hero title
            <input name="hero_title" value="<?php echo e(old('hero_title', $settings->hero_title)); ?>" required>
        </label>

        <label>Open Graph image URL
            <input name="open_graph_image" value="<?php echo e(old('open_graph_image', $settings->open_graph_image)); ?>">
        </label>

        <label class="span-2">Hero subtitle
            <textarea name="hero_subtitle" rows="3"><?php echo e(old('hero_subtitle', $settings->hero_subtitle)); ?></textarea>
        </label>

        <section class="span-2 destination-media-manager">
            <div class="destination-media-heading">
                <div><strong>Destination image library</strong><small>Control the unique hero and menu/card gallery for every country. Paste image URLs or upload files; uploads replace the hero and append to the gallery.</small></div>
                <span><i data-lucide="images"></i> Editable website media</span>
            </div>
            <?php $__currentLoopData = ['kenya'=>'Kenya','tanzania'=>'Tanzania','uganda'=>'Uganda','rwanda'=>'Rwanda','south-africa'=>'South Africa','namibia'=>'Namibia','botswana'=>'Botswana']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slug => $countryName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $media = $settings->mediaFor($slug); ?>
                <details class="destination-media-country" <?php if($loop->first): ?> open <?php endif; ?>>
                    <summary><strong><?php echo e($countryName); ?></strong><span><?php echo e(count($media['gallery'])); ?> gallery images <i data-lucide="chevron-down"></i></span></summary>
                    <div class="destination-media-fields">
                        <label>Hero image URL<input name="destination_media[<?php echo e($slug); ?>][hero]" value="<?php echo e(old("destination_media.{$slug}.hero", $media['hero'])); ?>"></label>
                        <label>Upload replacement hero<input type="file" name="destination_uploads[<?php echo e($slug); ?>][hero]" accept="image/jpeg,image/png,image/webp"></label>
                        <div class="destination-media-preview"><img src="<?php echo e(\App\Support\MediaPath::publicUrl($media['hero'])); ?>" alt="<?php echo e($countryName); ?> current hero"><small>Current hero</small></div>
                    </div>
                    <div class="destination-gallery-fields">
                        <?php $__currentLoopData = array_pad($media['gallery'], 6, ''); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label>Gallery image <?php echo e($index + 1); ?><input name="destination_media[<?php echo e($slug); ?>][gallery][]" value="<?php echo e(old("destination_media.{$slug}.gallery.{$index}", $url)); ?>"></label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <label class="destination-gallery-upload">Add gallery images<input type="file" name="destination_uploads[<?php echo e($slug); ?>][gallery][]" accept="image/jpeg,image/png,image/webp" multiple><small>Up to 8 images total. Each image can be changed later.</small></label>
                </details>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </section>

        <label>Featured destinations
            <select name="featured_destinations[]" multiple size="6">
                <?php $__currentLoopData = $destinations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $destination): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($destination->id); ?>" <?php if(in_array($destination->id, old('featured_destinations', $settings->featured_destinations ?? []))): echo 'selected'; endif; ?>><?php echo e($destination->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <small>Leave blank to show the first active countries.</small>
        </label>

        <label>Featured safaris
            <select name="featured_safaris[]" multiple size="6">
                <?php $__currentLoopData = $safaris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $safari): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($safari->id); ?>" <?php if(in_array($safari->id, old('featured_safaris', $settings->featured_safaris ?? []))): echo 'selected'; endif; ?>><?php echo e($safari->title); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <small>Leave blank to show featured published itineraries.</small>
        </label>

        <label>Featured activities
            <select name="featured_activities[]" multiple size="6">
                <?php $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($activity->id); ?>" <?php if(in_array($activity->id, old('featured_activities', $settings->featured_activities ?? []))): echo 'selected'; endif; ?>><?php echo e($activity->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <small>Leave blank to show published website activities.</small>
        </label>

        <label class="settings-toggle">Published accommodation
            <span>
                <input type="checkbox" name="show_published_accommodation" value="1" <?php if(old('show_published_accommodation', $settings->show_published_accommodation)): echo 'checked'; endif; ?>>
                Display published accommodation on the public homepage (<?php echo e($accommodationsCount); ?> currently published)
            </span>
        </label>

        <label>SEO title
            <input name="seo_title" value="<?php echo e(old('seo_title', $settings->seo_title)); ?>">
        </label>

        <label>SEO description
            <textarea name="seo_description" rows="4"><?php echo e(old('seo_description', $settings->seo_description)); ?></textarea>
        </label>
    </div>

    <?php if($errors->any()): ?>
        <div class="ops-alert ops-alert--error" style="margin: 0 18px 18px;">
            <i data-lucide="triangle-alert"></i>
            <span><?php echo e($errors->first()); ?></span>
        </div>
    <?php endif; ?>

    <div class="ops-form-footer">
        <a href="<?php echo e(route('admin.cms.index')); ?>" class="button button-secondary">All CMS pages</a>
        <button class="button button-primary"><i data-lucide="save"></i>Save homepage settings</button>
    </div>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\cms\home-settings.blade.php ENDPATH**/ ?>