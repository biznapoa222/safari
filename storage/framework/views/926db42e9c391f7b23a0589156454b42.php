<?php $__env->startSection('title', 'Website CMS'); ?>
<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginalad67f78bf768badc17b2fc4005a4f8bf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad67f78bf768badc17b2fc4005a4f8bf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.top-bar','data' => ['title' => 'Website Pages','description' => 'Content Management','addLabel' => 'New Page','addRoute' => ''.e(route('admin.cms.pages.create')).'','search' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.top-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Website Pages','description' => 'Content Management','addLabel' => 'New Page','addRoute' => ''.e(route('admin.cms.pages.create')).'','search' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalad67f78bf768badc17b2fc4005a4f8bf)): ?>
<?php $attributes = $__attributesOriginalad67f78bf768badc17b2fc4005a4f8bf; ?>
<?php unset($__attributesOriginalad67f78bf768badc17b2fc4005a4f8bf); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalad67f78bf768badc17b2fc4005a4f8bf)): ?>
<?php $component = $__componentOriginalad67f78bf768badc17b2fc4005a4f8bf; ?>
<?php unset($__componentOriginalad67f78bf768badc17b2fc4005a4f8bf); ?>
<?php endif; ?>
<?php echo $__env->make('admin.partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<div class="ops-actions-bar">
    <a href="<?php echo e(route('admin.cms.home-settings')); ?>" class="button button-secondary"><i data-lucide="settings-2"></i>Homepage Settings</a>
    <a href="<?php echo e(route('home')); ?>" target="_blank" class="button button-secondary"><i data-lucide="external-link"></i>View Website</a>
</div>
<div class="ops-panel" style="margin-bottom:24px;padding:20px"><h2 style="margin-top:0">Website content sections</h2><p>Edit shared contact details and the text/images used by each main public page.</p><div style="display:flex;flex-wrap:wrap;gap:10px"><?php $__currentLoopData = config('cms.pages'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section => $definition): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><a class="button button-secondary" href="<?php echo e(route('admin.cms.content.edit', $section)); ?>"><?php echo e($definition['label']); ?></a><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div></div>
<div class="table-wrap">
    <table class="ops-table">
        <thead><tr><th>Title</th><th>Type</th><th>Slug</th><th>Published</th><th>Updated</th><th>Actions</th></tr></thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><strong><?php echo e($page->title); ?></strong></td>
                <td><span class="status"><?php echo e(ucfirst($page->type)); ?></span></td>
                <td><small><?php echo e($page->slug); ?></small></td>
                <td>
                    <form method="POST" action="<?php echo e(route('admin.cms.pages.publish', $page)); ?>" style="display:inline;">
                        <?php echo csrf_field(); ?>
                        <button style="border:none;background:none;cursor:pointer;">
                            <?php if($page->published): ?><span class="text-green">Published</span><?php else: ?><span class="text-red">Draft</span><?php endif; ?>
                        </button>
                    </form>
                </td>
                <td><small><?php echo e($page->updated_at->format('d/m/Y')); ?></small></td>
                <td>
                    <div class="ops-actions">
                        <a href="<?php echo e(route('admin.cms.pages.edit', $page)); ?>"><i data-lucide="square-pen"></i></a>
                        <form method="POST" action="<?php echo e(route('admin.cms.pages.destroy', $page)); ?>" onsubmit="return confirm('Delete?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button><i data-lucide="trash-2"></i></button></form>
                    </div>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="6" class="text-center text-muted">No pages yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<div class="ops-pagination"><?php echo e($pages->links()); ?></div>
<style>.text-green { color: #22c55e; } .text-red { color: #ef4444; } .ops-actions { display: flex; gap: 0.25rem; } .ops-actions a, .ops-actions button { padding: 0.25rem; border: none; background: none; cursor: pointer; color: var(--text-muted); } .ops-actions a:hover, .ops-actions button:hover { color: var(--primary); }</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\cms\index.blade.php ENDPATH**/ ?>