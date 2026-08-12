<?php $__env->startSection('title', 'Lead: '.$lead->name); ?>
<?php $__env->startSection('content'); ?>
<div class="page-heading">
    <div><p class="eyebrow">Lead #<?php echo e($lead->id); ?></p><h1><?php echo e($lead->name); ?></h1></div>
    <div class="heading-actions">
        <form method="POST" action="<?php echo e(route('admin.leads.convert', $lead)); ?>" style="display:inline;">
            <?php echo csrf_field(); ?>
            <button class="button button-primary">Convert to Booking</button>
        </form>
    </div>
</div>
<?php echo $__env->make('admin.partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;">
    
    <section class="ops-panel">
        <div class="ops-panel-title"><h2>Lead Details</h2></div>
        <form method="POST" action="<?php echo e(route('admin.leads.update', $lead)); ?>">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <div class="ops-form-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <label>Name<input name="name" value="<?php echo e(old('name', $lead->name)); ?>" required></label>
                <label>Email<input type="email" name="email" value="<?php echo e(old('email', $lead->email)); ?>" required></label>
                <label>Phone<input name="phone" value="<?php echo e(old('phone', $lead->phone)); ?>"></label>
                <label>Country<input name="country" value="<?php echo e(old('country', $lead->country)); ?>"></label>
                <label>Source
                    <select name="source">
                        <?php $__currentLoopData = \App\Models\Lead::$sources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($k); ?>" <?php if(old('source', $lead->source) === $k): echo 'selected'; endif; ?>><?php echo e($v); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </label>
                <label>Status
                    <select name="status">
                        <?php $__currentLoopData = \App\Models\Lead::$statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($k); ?>" <?php if(old('status', $lead->status) === $k): echo 'selected'; endif; ?>><?php echo e($v); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </label>
                <label>Destination<input name="destination" value="<?php echo e(old('destination', $lead->destination)); ?>"></label>
                <label>Travel Date<input type="date" name="travel_date" value="<?php echo e(old('travel_date', $lead->travel_date?->format('Y-m-d'))); ?>"></label>
                <label>Travelers<input type="number" name="travelers" value="<?php echo e(old('travelers', $lead->travelers)); ?>" min="1"></label>
                <label>Estimated Value<input type="number" step="0.01" name="estimated_value" value="<?php echo e(old('estimated_value', $lead->estimated_value)); ?>"></label>
                <label>Currency<input name="currency" value="<?php echo e(old('currency', $lead->currency)); ?>" maxlength="3"></label>
                <label>Interests<textarea name="interests" rows="2"><?php echo e(old('interests', $lead->interests)); ?></textarea></label>
                <label class="span-2">Notes<textarea name="notes" rows="3"><?php echo e(old('notes', $lead->notes)); ?></textarea></label>
            </div>
            <div class="ops-form-footer"><button class="button button-primary">Save</button></div>
        </form>
    </section>

    
    <div>
        <section class="ops-panel" style="margin-bottom:1rem;">
            <div class="ops-panel-title"><h2>Assignment</h2></div>
            <form method="POST" action="<?php echo e(route('admin.leads.assign', $lead)); ?>">
                <?php echo csrf_field(); ?>
                <select name="assigned_consultant_id">
                    <option value="">Unassigned</option>
                    <?php $__currentLoopData = \App\Models\User::where('is_active', true)->orderBy('name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($u->id); ?>" <?php if($lead->assigned_consultant_id === $u->id): echo 'selected'; endif; ?>><?php echo e($u->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <button class="button button-primary" style="margin-top:0.5rem;">Assign</button>
            </form>
        </section>

        <section class="ops-panel" style="margin-bottom:1rem;">
            <div class="ops-panel-title"><h2>Timeline</h2></div>
            <div style="font-size:0.85rem;">
                <div><strong>Created:</strong> <?php echo e($lead->created_at->format('d M Y H:i')); ?></div>
                <div><strong>Source:</strong> <?php echo e(\App\Models\Lead::$sources[$lead->source] ?? $lead->source); ?></div>
                <?php if($lead->first_response_at): ?><div><strong>First Response:</strong> <?php echo e($lead->first_response_at->format('d M Y H:i')); ?></div><?php endif; ?>
                <?php if($lead->quotation_sent_at): ?><div><strong>Quotation Sent:</strong> <?php echo e($lead->quotation_sent_at->format('d M Y H:i')); ?></div><?php endif; ?>
                <?php if($lead->booking_at): ?><div><strong>Booked:</strong> <?php echo e($lead->booking_at->format('d M Y H:i')); ?></div><?php endif; ?>
            </div>
        </section>

        <?php if($lead->bookings->count()): ?>
        <section class="ops-panel">
            <div class="ops-panel-title"><h2>Bookings</h2></div>
            <?php $__currentLoopData = $lead->bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div style="padding:0.5rem 0;border-bottom:1px solid var(--border);">
                    <a href="<?php echo e(route('admin.bookings.edit', $b)); ?>"><strong><?php echo e($b->reference); ?></strong></a>
                    <div><small><?php echo e($b->status); ?> · <?php echo e($b->currency); ?> <?php echo e(number_format($b->total_amount, 2)); ?></small></div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </section>
        <?php endif; ?>
    </div>
</div>


<section class="ops-panel" style="margin-top:1.5rem;" id="conversations">
    <div class="ops-panel-title"><h2>Conversation History</h2></div>
    <div class="conversation-timeline" style="max-height:400px;overflow-y:auto;margin-bottom:1rem;">
        <?php $__empty_1 = true; $__currentLoopData = $lead->conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="conversation-item <?php echo e($conv->direction); ?>" style="padding:0.75rem;border-left:3px solid <?php echo e($conv->direction === 'incoming' ? 'var(--primary)' : '#22c55e'); ?>;margin-bottom:0.5rem;background:var(--bg-subtle);border-radius:0 0.375rem 0.375rem 0;">
            <div style="display:flex;justify-content:space-between;font-size:0.8rem;color:var(--text-muted);margin-bottom:0.25rem;">
                <span><strong><?php echo e(ucfirst($conv->channel)); ?></strong> <?php echo e($conv->direction === 'incoming' ? '→' : '←'); ?></span>
                <span><?php echo e($conv->created_at->format('d M Y H:i')); ?> <?php echo e($conv->user?->name ? 'by '.$conv->user->name : ''); ?></span>
            </div>
            <p style="margin:0;font-size:0.9rem;"><?php echo e($conv->content); ?></p>
            <?php if($conv->attachments): ?>
                <div style="margin-top:0.25rem;"><?php $__currentLoopData = $conv->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $att): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><span class="badge"><?php echo e($att); ?></span> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div>
            <?php endif; ?>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p class="text-muted">No conversations recorded yet.</p>
        <?php endif; ?>
    </div>
    <form method="POST" action="<?php echo e(route('admin.leads.conversations.store', $lead)); ?>" style="display:grid;grid-template-columns:1fr 2fr auto;gap:0.5rem;">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="lead_id" value="<?php echo e($lead->id); ?>">
        <select name="channel">
            <option value="email">Email</option>
            <option value="whatsapp">WhatsApp</option>
            <option value="phone_call">Phone Call</option>
            <option value="internal_note">Internal Note</option>
        </select>
        <select name="direction">
            <option value="incoming">Incoming</option>
            <option value="outgoing">Outgoing</option>
        </select>
        <input name="content" placeholder="Add note..." required style="grid-column:1/-1;">
        <button class="button button-primary" style="grid-column:1/-1;">Add Conversation Entry</button>
    </form>
</section>

<style>
.badge { background: var(--primary-light); color: var(--primary); padding: 0.15rem 0.4rem; border-radius: 0.25rem; font-size: 0.75rem; }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\leads\v2\show.blade.php ENDPATH**/ ?>