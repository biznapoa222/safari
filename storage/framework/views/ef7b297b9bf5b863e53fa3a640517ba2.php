<?php if(session('success')): ?>
    <div class="ops-alert ops-alert--success"><i data-lucide="circle-check"></i><?php echo e(session('success')); ?></div>
<?php endif; ?>
<?php if(session('warning')): ?>
    <div class="ops-alert ops-alert--error"><i data-lucide="triangle-alert"></i><div><strong>Attention</strong><span><?php echo e(session('warning')); ?></span></div></div>
<?php endif; ?>
<?php if($errors->any()): ?>
    <div class="ops-alert ops-alert--error"><i data-lucide="triangle-alert"></i><div><strong>Please correct the following:</strong><span><?php echo e($errors->first()); ?></span></div></div>
<?php endif; ?>
<?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\partials\flash.blade.php ENDPATH**/ ?>