<?php $__env->startSection('title', 'Reservation Invoices'); ?>
<?php $__env->startSection('content'); ?>
<div class="ops-page-heading">
    <div><p class="eyebrow">Reservations</p><h1>Supplier invoices</h1><p>Upload every accommodation, activity, transport and vehicle invoice before evaluation.</p></div>
    <div class="heading-actions"><a class="button button-secondary" href="<?php echo e(route('admin.evaluations.index')); ?>"><i data-lucide="clipboard-check"></i>Evaluation queue</a></div>
</div>
<?php echo $__env->make('admin.partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="invoice-upload-layout">
    <section class="ops-panel ops-form-panel invoice-upload-panel">
        <div class="ops-panel-title"><div><h2>Upload supplier invoice</h2><p>PDF, JPG, PNG or WebP, maximum 10 MB.</p></div><i data-lucide="upload-cloud"></i></div>
        <form method="POST" action="<?php echo e(route('admin.evaluations.invoices.upload')); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="ops-form-grid">
                <label class="span-2">Confirmed proposal
                    <select name="quotation_id" required data-invoice-quotation>
                        <option value="">Select proposal</option>
                        <?php $__currentLoopData = $quotations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quotation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($quotation->id); ?>"><?php echo e($quotation->reference); ?> - <?php echo e($quotation->title); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </label>
                <label class="span-2">Reservation (optional)
                    <select name="reservation_id">
                        <option value="">General proposal invoice</option>
                        <?php $__currentLoopData = $reservations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reservation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($reservation->id); ?>"><?php echo e($reservation->quotation_reference); ?> - <?php echo e(ucfirst($reservation->reservation_type)); ?> - <?php echo e($reservation->supplier ?: 'Supplier'); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </label>
                <label class="span-2">Supplier / company<input name="company_name" value="<?php echo e(old('company_name')); ?>" required></label>
                <label class="span-2">Invoice file<input type="file" name="document" accept=".pdf,.jpg,.jpeg,.png,.webp" required></label>
                <label class="span-2">Reservation note<textarea name="comments" rows="4" placeholder="Missing details, supplier contact or follow-up note"><?php echo e(old('comments')); ?></textarea></label>
            </div>
            <div class="ops-form-footer"><button class="button button-primary"><i data-lucide="upload"></i>Upload invoice</button></div>
        </form>
    </section>

    <section class="ops-panel">
        <form class="ops-filters" method="GET">
            <select name="status"><option value="">All statuses</option><?php $__currentLoopData = ['uploaded','recorded','evaluated','requires_amendment','approved','payment_ready','paid']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($status); ?>" <?php if(request('status') === $status): echo 'selected'; endif; ?>><?php echo e(ucwords(str_replace('_', ' ', $status))); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select>
            <button class="button button-primary">Filter</button>
        </form>
        <div class="table-wrap"><table class="ops-table"><thead><tr><th>Proposal</th><th>Supplier</th><th>Invoice</th><th>Uploaded by</th><th>Status</th><th></th></tr></thead><tbody>
        <?php $__empty_1 = true; $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><strong><?php echo e($invoice->quotation_reference); ?></strong><small><?php echo e($invoice->client_name); ?></small></td>
                <td><?php echo e($invoice->company_name); ?></td>
                <td><?php echo e($invoice->invoice_number ?: 'Details pending'); ?><small><?php echo e($invoice->created_at ? \Carbon\Carbon::parse($invoice->created_at)->format('d M Y H:i') : ''); ?></small></td>
                <td><?php echo e($invoice->uploader_name ?: 'System'); ?></td>
                <td><span class="ops-pill <?php echo e(in_array($invoice->status, ['approved','payment_ready','paid']) ? 'ops-pill--green' : ($invoice->status === 'requires_amendment' ? 'ops-pill--red' : 'ops-pill--blue')); ?>"><?php echo e(ucwords(str_replace('_', ' ', $invoice->status))); ?></span></td>
                <td class="ops-actions"><?php if($invoice->file_path): ?><a href="<?php echo e(route('admin.evaluations.invoices.download', $invoice->id)); ?>" target="_blank" title="View invoice"><i data-lucide="file-search"></i></a><?php endif; ?><a href="<?php echo e(route('admin.evaluations.show', $invoice->quotation_id)); ?>" title="Open evaluation"><i data-lucide="arrow-up-right"></i></a></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="6" class="empty-cell">No supplier invoices have been uploaded.</td></tr><?php endif; ?>
        </tbody></table></div>
        <div class="ops-pagination"><?php echo e($invoices->links()); ?></div>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\evaluations\invoices.blade.php ENDPATH**/ ?>