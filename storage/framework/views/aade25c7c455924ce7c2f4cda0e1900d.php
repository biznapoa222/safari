<?php $__env->startSection('title', $definition['label']); ?>
<?php $__env->startSection('content'); ?>
<div class="page-heading"><div><p class="eyebrow">Website CMS</p><h1><?php echo e($definition['label']); ?></h1><p>Edit text and replace images without changing the website layout.</p></div><a href="<?php echo e(route('home')); ?>" target="_blank" class="button button-secondary">View website</a></div>
<?php echo $__env->make('admin.partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<form method="POST" action="<?php echo e(route('admin.cms.content.update', $section)); ?>" enctype="multipart/form-data" class="ops-panel">
 <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
 <div class="ops-form-grid website-settings-grid">
 <?php $__currentLoopData = $definition['fields']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <?php $value=old("content.$key", $values[$key] ?? $field['default'] ?? ''); ?>
  <label class="<?php echo e($field['type']==='textarea' ? 'span-2' : ''); ?>"><?php echo e($field['label']); ?>

   <?php if($field['type']==='textarea'): ?><textarea name="content[<?php echo e($key); ?>]" rows="4"><?php echo e($value); ?></textarea>
   <?php elseif($field['type']==='image'): ?>
    <input name="content[<?php echo e($key); ?>]" value="<?php echo e($value); ?>" placeholder="Image URL or stored path">
    <input type="file" name="uploads[<?php echo e($key); ?>]" accept="image/jpeg,image/png,image/webp,image/gif">
    <?php if($value): ?><span style="display:flex;gap:12px;align-items:center;margin-top:8px"><img src="<?php echo e(\App\Support\MediaPath::publicUrl($value)); ?>" alt="" style="width:120px;height:72px;object-fit:cover;border-radius:6px"><small>JPG, PNG, WebP or GIF; max 8 MB.<br><input type="checkbox" name="remove[<?php echo e($key); ?>]" value="1" style="width:auto"> Remove current image</small></span><?php endif; ?>
   <?php else: ?><input name="content[<?php echo e($key); ?>]" value="<?php echo e($value); ?>"><?php endif; ?>
  </label>
 <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
 </div>
 <?php if($errors->any()): ?><div class="ops-alert ops-alert--error" style="margin:18px"><?php echo e($errors->first()); ?></div><?php endif; ?>
 <div class="ops-form-footer"><a href="<?php echo e(route('admin.cms.index')); ?>" class="button button-secondary">Back</a><button class="button button-primary">Save changes</button></div>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\cms\content.blade.php ENDPATH**/ ?>