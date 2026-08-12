<?php $__env->startSection('title', 'Authenticator Code'); ?>
<?php $__env->startSection('content'); ?>
<div class="two-factor-challenge-page">
    <div class="two-factor-challenge-card">
        <a href="<?php echo e(route('login')); ?>" class="two-factor-brand">Shishi Footsteps</a>
        <div class="two-factor-challenge-icon"><i data-lucide="shield-check"></i></div>
        <h1>Authenticator code</h1>
        <p>Enter the 6-digit code from Google Authenticator.</p>
        <?php if($errors->any()): ?><div class="two-factor-error"><?php echo e($errors->first('code')); ?></div><?php endif; ?>
        <form method="POST" action="<?php echo e(route('two-factor.challenge.verify')); ?>">
            <?php echo csrf_field(); ?>
            <label>6-digit code<input type="text" name="code" inputmode="numeric" autocomplete="one-time-code" maxlength="6" autofocus required></label>
            <button class="two-factor-primary-button">Verify</button>
        </form>
        <a class="two-factor-back-link" href="<?php echo e(route('login')); ?>">Use another account</a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\auth\two-factor-challenge.blade.php ENDPATH**/ ?>