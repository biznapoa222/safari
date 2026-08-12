<?php $__env->startSection('title', $booking ? 'Booking: '.$booking->reference : 'New Booking'); ?>
<?php $__env->startSection('content'); ?>
<div class="page-heading">
    <div><p class="eyebrow">Booking</p><h1><?php echo e($booking ? 'Edit: '.$booking->reference : 'New Booking'); ?></h1></div>
</div>
<?php echo $__env->make('admin.partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<form method="POST" action="<?php echo e($booking ? route('admin.bookings.update', $booking) : route('admin.bookings.store')); ?>" class="ops-panel">
    <?php echo csrf_field(); ?> <?php if($booking): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
    <div class="ops-panel-title"><h2>Booking Details</h2></div>
    <div class="ops-form-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
        <label>Lead/Client
            <select name="lead_id">
                <option value="">-- Select Lead --</option>
                <?php $__currentLoopData = $leads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($l->id); ?>" <?php if(old('lead_id', $booking->lead_id ?? '') == $l->id): echo 'selected'; endif; ?>><?php echo e($l->name); ?> (<?php echo e($l->email); ?>)</option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </label>
        <label>Status
            <select name="status">
                <?php $__currentLoopData = \App\Models\Booking::$statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($k); ?>" <?php if(old('status', $booking->status ?? 'draft') === $k): echo 'selected'; endif; ?>><?php echo e($v); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </label>
        <label>Start Date<input type="date" name="start_date" value="<?php echo e(old('start_date', $booking->start_date?->format('Y-m-d'))); ?>"></label>
        <label>End Date<input type="date" name="end_date" value="<?php echo e(old('end_date', $booking->end_date?->format('Y-m-d'))); ?>"></label>
        <label>Number of Guests<input type="number" name="guests" value="<?php echo e(old('guests', $booking->guests ?? 2)); ?>" min="1"></label>
        <label>Currency
            <select name="currency">
                <?php $__currentLoopData = ['USD','EUR','GBP','KES','AUD','CAD']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($cur); ?>" <?php if(old('currency', $booking->currency ?? 'USD') === $cur): echo 'selected'; endif; ?>><?php echo e($cur); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </label>
        <label>Consultant
            <select name="assigned_consultant_id">
                <option value="">-- Select --</option>
                <?php $__currentLoopData = $consultants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($u->id); ?>" <?php if(old('assigned_consultant_id', $booking->assigned_consultant_id ?? '') == $u->id): echo 'selected'; endif; ?>><?php echo e($u->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </label>
        <label class="span-2">Notes<textarea name="notes" rows="3"><?php echo e(old('notes', $booking->notes ?? '')); ?></textarea></label>
    </div>

    <?php if($booking): ?>
    <div class="ops-form-grid" style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;margin-top:1rem;padding-top:1rem;border-top:1px solid var(--border);">
        <label>Total Amount<input type="number" step="0.01" name="total_amount" value="<?php echo e(old('total_amount', $booking->total_amount)); ?>"></label>
        <label>Amount Paid<input type="number" step="0.01" name="amount_paid" value="<?php echo e(old('amount_paid', $booking->amount_paid)); ?>"></label>
        <label>Payment Status
            <select name="payment_status">
                <option value="unpaid" <?php if(old('payment_status', $booking->payment_status) === 'unpaid'): echo 'selected'; endif; ?>>Unpaid</option>
                <option value="partial" <?php if(old('payment_status', $booking->payment_status) === 'partial'): echo 'selected'; endif; ?>>Partial</option>
                <option value="paid" <?php if(old('payment_status', $booking->payment_status) === 'paid'): echo 'selected'; endif; ?>>Paid</option>
            </select>
        </label>
        <label class="span-3 checkbox-label" style="margin-top:0.5rem;">
            <input type="checkbox" name="cancellation_policy_accepted" value="1" <?php if(old('cancellation_policy_accepted', $booking->cancellation_policy_accepted)): echo 'checked'; endif; ?>>
            Customer has read and understood the cancellation policy
            <?php if($booking->cancellation_accepted_at): ?>
                <small class="text-muted">(Accepted <?php echo e($booking->cancellation_accepted_at->format('d/m/Y H:i')); ?>)</small>
            <?php endif; ?>
        </label>
    </div>
    <?php endif; ?>

    <div class="ops-form-footer">
        <a href="<?php echo e(route('admin.bookings.index')); ?>" class="button button-secondary">Cancel</a>
        <button class="button button-primary"><i data-lucide="save"></i><?php echo e($booking ? 'Update' : 'Create'); ?></button>
    </div>
</form>

<?php if($booking): ?>

<section class="ops-panel" style="margin-top:1.5rem;">
    <div class="ops-panel-title"><h2>Payments (<?php echo e($booking->payments->count()); ?>)</h2>
        <button class="button button-sm button-secondary" onclick="document.getElementById('payment-form').classList.toggle('hidden')">Record Payment</button>
        <button class="button button-sm button-secondary" onclick="document.getElementById('link-form').classList.toggle('hidden')">Generate Payment Link</button>
    </div>
    <form id="payment-form" method="POST" action="<?php echo e(route('admin.payments.store', $booking)); ?>" class="hidden" style="margin-bottom:1rem;">
        <?php echo csrf_field(); ?>
        <div class="ops-form-grid" style="display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:0.75rem;">
            <input type="number" step="0.01" name="amount" placeholder="Amount" required>
            <select name="currency"><?php $__currentLoopData = ['USD','EUR','GBP','KES']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($cur); ?>"><?php echo e($cur); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select>
            <select name="method">
                <?php $__currentLoopData = \App\Models\Payment::$methods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($k); ?>"><?php echo e($v); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <select name="type">
                <option value="payment">Payment</option>
                <option value="deposit">Deposit</option>
                <option value="balance">Balance</option>
            </select>
            <input type="date" name="paid_at" value="<?php echo e(date('Y-m-d')); ?>" required>
            <button class="button button-primary">Record</button>
        </div>
    </form>
    <form id="link-form" method="POST" action="<?php echo e(route('admin.payments.links.store', $booking)); ?>" class="hidden" style="margin-bottom:1rem;">
        <?php echo csrf_field(); ?>
        <div class="ops-form-grid" style="display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:0.75rem;">
            <select name="type"><option value="payment">Full Payment</option><option value="deposit">Deposit</option><option value="balance">Balance</option></select>
            <input type="number" step="0.01" name="amount" placeholder="Amount" required>
            <select name="currency"><?php $__currentLoopData = ['USD','EUR','GBP','KES']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($cur); ?>"><?php echo e($cur); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select>
            <button class="button button-primary">Generate Link</button>
        </div>
    </form>
    <?php if($booking->payments->count()): ?>
    <div class="table-wrap">
        <table class="ops-table">
            <thead><tr><th>Reference</th><th>Amount</th><th>Currency</th><th>Method</th><th>Type</th><th>Status</th><th>Date</th><th></th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $booking->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($p->reference); ?></td>
                    <td><strong><?php echo e(number_format($p->amount, 2)); ?></strong></td>
                    <td><?php echo e($p->currency); ?></td>
                    <td><?php echo e(\App\Models\Payment::$methods[$p->method] ?? $p->method); ?></td>
                    <td><?php echo e(ucfirst($p->type)); ?></td>
                    <td><span class="status status--<?php echo e($p->status); ?>"><?php echo e(ucfirst($p->status)); ?></span></td>
                    <td><?php echo e($p->paid_at?->format('d/m/Y') ?? '-'); ?></td>
                    <td>
                        <form method="POST" action="<?php echo e(route('admin.payments.destroy', $p)); ?>" onsubmit="return confirm('Delete payment?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="row-action"><i data-lucide="trash-2"></i></button></form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
    <?php if($booking->paymentLinks->count()): ?>
    <div style="margin-top:1rem;">
        <h3>Payment Links</h3>
        <?php $__currentLoopData = $booking->paymentLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div style="display:flex;justify-content:space-between;padding:0.5rem;background:var(--bg-subtle);border-radius:0.375rem;margin-bottom:0.25rem;">
            <span><?php echo e(ucfirst($pl->type)); ?> - <?php echo e($pl->currency); ?> <?php echo e(number_format($pl->amount,2)); ?></span>
            <span><?php echo e($pl->is_used ? 'Used' : 'Active'); ?></span>
            <small><a href="<?php echo e(route('admin.payments.links.show', $pl->token)); ?>" target="_blank">View Link</a></small>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>
</section>


<section class="ops-panel" style="margin-top:1rem;">
    <div class="ops-panel-title"><h2>Financial Summary</h2></div>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;text-align:center;">
        <div style="padding:1rem;background:var(--bg-subtle);border-radius:0.5rem;">
            <small class="text-muted">Total Amount</small>
            <h3><?php echo e($booking->currency); ?> <?php echo e(number_format($booking->total_amount, 2)); ?></h3>
        </div>
        <div style="padding:1rem;background:var(--bg-subtle);border-radius:0.5rem;">
            <small class="text-muted">Amount Paid</small>
            <h3 class="text-green"><?php echo e($booking->currency); ?> <?php echo e(number_format($booking->amount_paid, 2)); ?></h3>
        </div>
        <div style="padding:1rem;background:var(--bg-subtle);border-radius:0.5rem;">
            <small class="text-muted">Balance Due</small>
            <h3 style="color:<?php echo e($booking->balance > 0 ? '#ef4444' : '#22c55e'); ?>"><?php echo e($booking->currency); ?> <?php echo e(number_format($booking->balance, 2)); ?></h3>
        </div>
    </div>
</section>
<?php endif; ?>

<?php $__env->startPush('styles'); ?>
<style>
.checkbox-label { display: flex; align-items: center; gap: 0.5rem; cursor: pointer; }
.checkbox-label input { width: auto; }
.hidden { display: none; }
.row-action { border: none; background: none; cursor: pointer; color: var(--text-muted); padding: 0.25rem; }
.row-action:hover { color: #ef4444; }
.text-green { color: #22c55e; }
</style>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\bookings\form.blade.php ENDPATH**/ ?>