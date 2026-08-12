<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['variant' => 'admin']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['variant' => 'admin']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="language-switcher <?php echo e($variant === 'public' ? 'language-switcher--public' : ''); ?>">
    <button class="language-trigger" type="button" data-language-trigger aria-expanded="false">
        <span class="language-globe"><i data-lucide="languages"></i></span>
        <span><?php echo e(config('safari.languages.'.app()->getLocale().'.badge')); ?></span>
        <i data-lucide="chevron-down" class="chevron"></i>
    </button>
    <div class="language-menu" data-language-menu>
        <div class="language-menu__header"><?php echo e(__('ui.choose_language')); ?></div>
        <?php $__currentLoopData = config('safari.languages'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('locale.update', $code)); ?>" class="<?php echo e(app()->getLocale() === $code ? 'is-active' : ''); ?>">
                <span class="language-code"><?php echo e($language['badge']); ?></span>
                <span><?php echo e($language['native']); ?></span>
                <?php if(app()->getLocale() === $code): ?><i data-lucide="check"></i><?php endif; ?>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\components\language-switcher.blade.php ENDPATH**/ ?>