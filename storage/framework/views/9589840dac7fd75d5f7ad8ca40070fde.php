<?php $__env->startSection('title', $page ? 'Edit Page' : 'New Page'); ?>
<?php $__env->startSection('content'); ?>
<div class="page-heading">
    <div><p class="eyebrow">CMS</p><h1><?php echo e($page ? 'Edit: '.$page->title : 'New Page'); ?></h1></div>
</div>
<?php echo $__env->make('admin.partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<form method="POST" action="<?php echo e($page ? route('admin.cms.pages.update', $page) : route('admin.cms.pages.store')); ?>" class="ops-panel" enctype="multipart/form-data">
    <?php echo csrf_field(); ?> <?php if($page): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
    <div class="ops-form-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
        <label class="span-2">Page Title<input name="title" value="<?php echo e(old('title', $page->title ?? '')); ?>" required></label>
        <label>Type
            <select name="type">
                <option value="page" <?php if(old('type', $page->type ?? '') === 'page'): echo 'selected'; endif; ?>>Page</option>
                <option value="blog" <?php if(old('type', $page->type ?? '') === 'blog'): echo 'selected'; endif; ?>>Blog Post</option>
                <option value="destination" <?php if(old('type', $page->type ?? '') === 'destination'): echo 'selected'; endif; ?>>Destination</option>
            </select>
        </label>
        <label>Cover Image URL<input name="cover_image" value="<?php echo e(old('cover_image', $page->cover_image ?? '')); ?>"></label>
        <label>Upload / replace cover image<input type="file" name="cover_upload" accept="image/jpeg,image/png,image/webp,image/gif"><small>JPG, PNG, WebP or GIF; max 8 MB.</small></label>
        <label>SEO Title<input name="seo_title" value="<?php echo e(old('seo_title', $page->seo_title ?? '')); ?>"></label>
        <label class="span-2">SEO Description<textarea name="seo_description" rows="2"><?php echo e(old('seo_description', $page->seo_description ?? '')); ?></textarea></label>
        <label class="span-2">Content<textarea name="content" rows="15" style="font-family:monospace;"><?php echo e(old('content', $page->content ?? '')); ?></textarea></label>
        <?php if($page): ?>
        <label class="checkbox-label"><input type="checkbox" name="published" value="1" <?php if(old('published', $page->published)): echo 'checked'; endif; ?>> Published</label>
        <?php endif; ?>
    </div>
    <div class="ops-form-footer">
        <a href="<?php echo e(route('admin.cms.index')); ?>" class="button button-secondary">Cancel</a>
        <button class="button button-primary"><i data-lucide="save"></i><?php echo e($page ? 'Update' : 'Create'); ?></button>
    </div>
</form>
<style>.checkbox-label { display: flex; align-items: center; gap: 0.5rem; } .checkbox-label input { width: auto; }</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\cms\form.blade.php ENDPATH**/ ?>