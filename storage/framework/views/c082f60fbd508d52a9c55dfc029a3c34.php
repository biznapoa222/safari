<?php $__env->startSection('title', 'Manage 2FA'); ?>
<?php $__env->startSection('content'); ?>
<?php echo $__env->make('admin.partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<section class="two-factor-page">
    <header class="two-factor-header">
        <div>
            <span>Administration / Security</span>
            <h1>Manage 2FA</h1>
            <p>Protect <?php echo e($user->email); ?> with Google Authenticator.</p>
        </div>
        <span class="two-factor-status <?php echo e($enabled ? 'is-enabled' : ($pendingSecret ? 'is-pending' : '')); ?>"><?php echo e($enabled ? 'Enabled' : ($pendingSecret ? 'Setup' : 'Off')); ?></span>
    </header>

    <section class="two-factor-card">
        <?php if($enabled): ?>
            <div class="two-factor-card-heading">
                <div><strong>Google Authenticator</strong><span>2-step verification is active on this account.</span></div>
                <span class="two-factor-status is-enabled">Enabled</span>
            </div>
            <div class="two-factor-alert">Your account will request a 6-digit authenticator code after password sign-in.</div>
            <form method="POST" action="<?php echo e(route('admin.two-factor.disable')); ?>" class="two-factor-disable-form">
                <?php echo csrf_field(); ?>
                <label>Current password<input type="password" name="password" autocomplete="current-password" required></label>
                <button class="two-factor-danger-button">Turn Off</button>
            </form>
        <?php elseif($pendingSecret): ?>
            <div class="two-factor-card-heading">
                <div><strong>Google Authenticator</strong><span>Scan or open the setup link, then confirm the 6-digit code.</span></div>
                <span class="two-factor-status is-pending">Setup</span>
            </div>
            <div class="two-factor-setup">
                <img src="<?php echo e($qrCode); ?>" alt="Google Authenticator QR code" class="two-factor-qr">
                <div class="two-factor-secret">
                    <span>Setup key</span>
                    <strong><?php echo e($pendingSecret); ?></strong>
                    <a href="<?php echo e($otpAuthUri); ?>">Open in Authenticator</a>
                </div>
            </div>
            <form method="POST" action="<?php echo e(route('admin.two-factor.confirm')); ?>" class="two-factor-confirm-form">
                <?php echo csrf_field(); ?>
                <label>6-digit code<input type="text" name="code" inputmode="numeric" autocomplete="one-time-code" maxlength="6" required></label>
                <button class="two-factor-primary-button">Confirm</button>
            </form>
        <?php else: ?>
            <div class="two-factor-card-heading">
                <div><strong>Google Authenticator</strong><span>Add a 6-digit verification code before sign-in completes.</span></div>
                <span class="two-factor-status">Off</span>
            </div>
            <form method="POST" action="<?php echo e(route('admin.two-factor.start')); ?>">
                <?php echo csrf_field(); ?>
                <button class="two-factor-primary-button">Turn On</button>
            </form>
        <?php endif; ?>
    </section>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\two-factor\index.blade.php ENDPATH**/ ?>