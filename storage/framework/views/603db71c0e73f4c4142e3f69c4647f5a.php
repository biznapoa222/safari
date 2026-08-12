<?php $__env->startSection('title', 'New Quotation'); ?>
<?php $__env->startSection('content'); ?>
<div class="ops-page-heading"><div><p class="eyebrow">Proposal planning</p><h1>Create quotation</h1><p>Start a day-by-day tailor-made itinerary for a client.</p></div><a class="button button-secondary" href="<?php echo e(route('admin.quotations.index')); ?>">Back</a></div>
<?php echo $__env->make('admin.partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<section class="ops-panel ops-form-panel narrow-panel">
    <form method="POST" action="<?php echo e(route('admin.quotations.store')); ?>"><?php echo csrf_field(); ?>
        <div class="ops-form-grid">
            <label class="span-2">Client<select name="client_id" required><option value="">Select client</option><?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($client->id); ?>" <?php if(old('client_id', $selectedClient) == $client->id): echo 'selected'; endif; ?>><?php echo e($client->name); ?> — <?php echo e($client->email); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></label>
            <label class="span-2">Quotation title<input name="title" value="<?php echo e(old('title', $enquiry ? (($enquiry->destination ?: 'Tailor-made').' Safari for '.$enquiry->name) : '')); ?>" required></label>
            <label>Start date<input type="date" name="start_date" value="<?php echo e(old('start_date', $enquiry->travel_date ?? today()->addMonths(2)->toDateString())); ?>" required></label>
            <label>Duration days<input type="number" name="duration_days" min="1" max="60" value="<?php echo e(old('duration_days', 10)); ?>" required></label>
            <label>Guests<input type="number" name="guest_count" min="1" max="100" value="<?php echo e(old('guest_count', $enquiry->travelers ?? 2)); ?>" required></label>
            <label>Start location<input name="start_location" value="<?php echo e(old('start_location', 'Nairobi')); ?>" required></label>
            <label>Currency<input name="currency" maxlength="3" value="<?php echo e(old('currency', 'USD')); ?>" required></label>
            <label>Office markup %<input type="number" step="0.01" name="office_markup_percent" value="<?php echo e(old('office_markup_percent', 20)); ?>" required></label>
            <label>Miscellaneous markup %<input type="number" step="0.01" name="misc_markup_percent" value="<?php echo e(old('misc_markup_percent', 5)); ?>" required></label>
        </div>
        <div class="ops-form-footer"><button class="button button-primary"><i data-lucide="arrow-right"></i>Create and plan itinerary</button></div>
    </form>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\quotations\create.blade.php ENDPATH**/ ?>