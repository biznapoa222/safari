<?php $__env->startSection('title', 'Payment'); ?>
<?php $__env->startSection('content'); ?>
<section style="min-height:80vh;display:flex;align-items:center;justify-content:center;padding:2rem;">
    <div style="max-width:500px;width:100%;">
        <div style="text-align:center;margin-bottom:2rem;">
            <h1 style="font-family:'Outfit',sans-serif;font-size:2rem;">Complete Your Payment</h1>
            <p style="color:var(--text-muted);">Secure payment for your safari booking</p>
        </div>
        <div class="ops-panel">
            <div style="padding:1rem;text-align:center;">
                <small class="text-muted">Amount Due</small>
                <h2 style="font-size:2.5rem;"><?php echo e($link->currency); ?> <?php echo e(number_format($link->amount, 2)); ?></h2>
                <p style="margin:0.5rem 0;"><?php echo e(ucfirst($link->type)); ?> Payment</p>
            </div>
            <hr style="border-color:var(--border);margin:1rem 0;">
            <form method="POST" action="<?php echo e(route('admin.payments.links.pay', $link->token)); ?>" style="text-align:center;">
                <?php echo csrf_field(); ?>
                <div style="margin-bottom:1.5rem;">
                    <p style="font-weight:600;margin-bottom:0.75rem;font-size:0.9rem;">Choose payment method</p>
                    <?php $__currentLoopData = $gateways; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label style="display:flex;align-items:center;gap:0.75rem;padding:0.75rem 1rem;border:2px solid var(--border);border-radius:0.5rem;margin-bottom:0.5rem;cursor:pointer;transition:border-color 0.15s;<?php echo e($loop->first ? 'border-color:var(--primary);' : ''); ?>">
                            <input type="radio" name="gateway" value="<?php echo e($g); ?>" <?php echo e($loop->first ? 'checked' : ''); ?> style="width:auto;">
                            <span style="font-weight:500;">
                                <?php switch($g):
                                    case ('stripe'): ?> Credit / Debit Card (Stripe) <?php break; ?>
                                    <?php case ('flutterwave'): ?> Mobile Money / Card (Flutterwave) <?php break; ?>
                                    <?php case ('manual'): ?> Offline / Manual Payment <?php break; ?>
                                    <?php default: ?> <?php echo e(ucfirst($g)); ?>

                                <?php endswitch; ?>
                            </span>
                        </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div style="margin-bottom:1rem;">
                    <label class="checkbox-label" style="display:flex;align-items:center;gap:0.5rem;justify-content:center;font-size:0.9rem;">
                        <input type="checkbox" name="accept_cancellation" value="1" required>
                        I have read and understood the cancellation policy
                    </label>
                </div>
                <button class="button button-primary" style="width:100%;padding:1rem;font-size:1.1rem;">
                    Pay <?php echo e($link->currency); ?> <?php echo e(number_format($link->amount, 2)); ?>

                </button>
                <p style="margin-top:1rem;font-size:0.8rem;color:var(--text-muted);">
                    <i data-lucide="lock" style="width:14px;height:14px;"></i>
                    Secured by industry-standard encryption
                </p>
            </form>
        </div>
    </div>
</section>
<style>.checkbox-label input { width: auto; }</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\public\payment-link\show.blade.php ENDPATH**/ ?>