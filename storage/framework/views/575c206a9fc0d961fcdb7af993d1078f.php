<?php $__env->startSection('title', __('ui.translation_manager')); ?>

<?php $__env->startSection('content'); ?>
    <div class="page-heading compact">
        <div>
            <p class="eyebrow"><?php echo e(__('ui.content_localization')); ?></p>
            <h1><?php echo e(__('ui.translation_manager')); ?></h1>
            <p><?php echo e(__('ui.translation_intro')); ?></p>
        </div>
        <div class="heading-actions">
            <button class="button button-secondary"><i data-lucide="sparkles"></i><?php echo e(__('ui.generate_missing')); ?></button>
            <button class="button button-primary"><i data-lucide="plus"></i><?php echo e(__('ui.add_translation')); ?></button>
        </div>
    </div>

    <section class="translation-summary">
        <?php $__currentLoopData = config('safari.languages'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $available = $items->filter(fn ($item) => $item->translations->contains('language_code', $code))->count();
                $percent = $items->count() ? round(($available / $items->count()) * 100) : 0;
            ?>
            <article>
                <span class="language-code large"><?php echo e($language['badge']); ?></span>
                <div><strong><?php echo e($language['native']); ?></strong><small><?php echo e($available); ?>/<?php echo e($items->count()); ?> records</small></div>
                <div class="mini-progress"><span style="width: <?php echo e($percent); ?>%"></span></div>
                <b><?php echo e($percent); ?>%</b>
            </article>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </section>

    <section class="panel content-panel">
        <div class="panel-heading">
            <div><h3><?php echo e(__('ui.all_content')); ?></h3><p><?php echo e(__('ui.translation_status_help')); ?></p></div>
            <div class="table-search"><i data-lucide="search"></i><input placeholder="<?php echo e(__('ui.search_content')); ?>"></div>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th><?php echo e(__('ui.content')); ?></th><th><?php echo e(__('ui.type')); ?></th><th><?php echo e(__('ui.language_status')); ?></th><th><?php echo e(__('ui.completeness')); ?></th><th><?php echo e(__('ui.actions')); ?></th></tr></thead>
                <tbody>
                <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><strong><?php echo e($item->name); ?></strong><small><?php echo e($item->country); ?> · <?php echo e($item->location); ?></small></td>
                        <td><span class="type-pill"><?php echo e(ucfirst(str_replace('_', ' ', $item->type))); ?></span></td>
                        <td>
                            <div class="translation-badges">
                                <?php $__currentLoopData = config('safari.languages'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $translation = $item->translations->firstWhere('language_code', $code); ?>
                                    <button class="<?php echo e($translation ? ($translation->status === 'approved' ? 'complete' : 'generated') : ''); ?>" title="<?php echo e($translation?->status ?? 'Missing'); ?>">
                                        <?php echo e($language['badge']); ?>

                                    </button>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </td>
                        <td>
                            <div class="completion"><div><span style="width: <?php echo e($item->translationCompleteness()); ?>%"></span></div><strong><?php echo e($item->translationCompleteness()); ?>%</strong></div>
                        </td>
                        <td>
                            <div class="translation-actions">
                                <button title="<?php echo e(__('ui.generate_translation')); ?>"><i data-lucide="sparkles"></i></button>
                                <button title="<?php echo e(__('ui.upgrade_translation')); ?>"><i data-lucide="wand-sparkles"></i></button>
                                <button><i data-lucide="more-horizontal"></i></button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\translations.blade.php ENDPATH**/ ?>