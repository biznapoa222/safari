<?php $__env->startSection('title', 'Payment Link Expired'); ?>
<?php $__env->startSection('content'); ?>
<section style="min-height:60vh;display:flex;align-items:center;justify-content:center;padding:2rem;text-align:center;">
    <div>
        <i data-lucide="clock" style="width:64px;height:64px;color:var(--text-muted);margin-bottom:1rem;"></i>
        <h1>Payment Link Expired</h1>
        <p>This payment link is no longer valid. Please contact your safari specialist for a new payment link.</p>
        <a href="<?php echo e(route('home')); ?>" class="button button-primary" style="margin-top:1rem;">Return to Home</a>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\public\payment-link\expired.blade.php ENDPATH**/ ?>