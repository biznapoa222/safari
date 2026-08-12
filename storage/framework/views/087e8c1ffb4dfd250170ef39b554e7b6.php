<?php $__env->startSection('title', $title); ?>

<?php $__env->startSection('content'); ?>
    <?php if (isset($component)) { $__componentOriginalad67f78bf768badc17b2fc4005a4f8bf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad67f78bf768badc17b2fc4005a4f8bf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.top-bar','data' => ['title' => $title,'description' => ''.e(__('ui.module_intro', ['module' => strtolower($title)])).'','addLabel' => ''.e(__('ui.add_new')).'','addRoute' => null,'search' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.top-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($title),'description' => ''.e(__('ui.module_intro', ['module' => strtolower($title)])).'','addLabel' => ''.e(__('ui.add_new')).'','addRoute' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(null),'search' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
        <p class="breadcrumbs"><a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('ui.home')); ?></a><i data-lucide="chevron-right"></i><?php echo e($match['label'] ?? __('ui.workspace')); ?></p>
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

    <?php if($items->isNotEmpty()): ?>
        <section class="panel content-panel">
            <div class="panel-heading">
                <div><h3><?php echo e($title); ?></h3><p><?php echo e($items->count()); ?> <?php echo e(__('ui.records')); ?></p></div>
                <div class="table-search"><i data-lucide="search"></i><input placeholder="<?php echo e(__('ui.search')); ?>"></div>
            </div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th><?php echo e(__('ui.name')); ?></th><th><?php echo e(__('ui.location')); ?></th><th><?php echo e(__('ui.price_from')); ?></th><th><?php echo e(__('ui.translations')); ?></th><th><?php echo e(__('ui.status')); ?></th><th></th></tr></thead>
                    <tbody>
                    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><strong><?php echo e($item->translation()?->title ?? $item->name); ?></strong><small><?php echo e(ucfirst(str_replace('_', ' ', $item->type))); ?></small></td>
                            <td><?php echo e($item->location); ?>, <?php echo e($item->country); ?></td>
                            <td><?php echo e($item->price_from ? '$'.number_format($item->price_from) : '—'); ?></td>
                            <td>
                                <div class="translation-stack">
                                    <?php $__currentLoopData = config('safari.languages'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php $translation = $item->translations->firstWhere('language_code', $code); ?>
                                        <span class="<?php echo e($translation ? ($translation->status === 'approved' ? 'complete' : 'generated') : ''); ?>" title="<?php echo e($language['name']); ?>"><?php echo e($language['badge']); ?></span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <small><?php echo e($item->translationCompleteness()); ?>%</small>
                                </div>
                            </td>
                            <td><span class="status status--<?php echo e($item->status); ?>"><?php echo e(ucfirst($item->status)); ?></span></td>
                            <td><button class="row-action"><i data-lucide="more-horizontal"></i></button></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </section>
    <?php else: ?>
        <section class="module-placeholder">
            <div class="placeholder-art"><i data-lucide="<?php echo e($match['icon'] ?? 'layout-grid'); ?>"></i></div>
            <span class="coming-label"><?php echo e(__('ui.module_ready')); ?></span>
            <h2><?php echo e($title); ?></h2>
            <p><?php echo e(__('ui.module_description', ['module' => strtolower($title)])); ?></p>
            <div class="placeholder-actions">
                <button class="button button-primary"><i data-lucide="plus"></i><?php echo e(__('ui.create_first_record')); ?></button>
                <button class="button button-secondary"><i data-lucide="book-open"></i><?php echo e(__('ui.view_guide')); ?></button>
            </div>
        </section>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\module.blade.php ENDPATH**/ ?>