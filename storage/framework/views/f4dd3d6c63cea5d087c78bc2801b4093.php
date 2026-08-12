<?php $__env->startSection('title', $title); ?>
<?php $__env->startSection('content'); ?>

<?php if (isset($component)) { $__componentOriginalad67f78bf768badc17b2fc4005a4f8bf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad67f78bf768badc17b2fc4005a4f8bf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.top-bar','data' => ['title' => $title,'description' => 'Operational register','addLabel' => 'Add Record','addOnclick' => 'openModal(\'add-modal\')','search' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.top-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($title),'description' => 'Operational register','addLabel' => 'Add Record','addOnclick' => 'openModal(\'add-modal\')','search' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalad67f78bf768badc17b2fc4005a4f8bf)): ?>
<?php $attributes = $__attributesOriginalad67f78bf768badc17b2fc4005a4f8bf; ?>
<?php unset($__attributesOriginalad67f78bf768badc17b2fc4005a4f8bf); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalad67f78bf768badc17b2fc4005a4f8bf)): ?>
<?php $component = $__componentOriginalad67f78bf768badc17b2fc4005a4f8bf; ?>
<?php unset($__componentOriginalad67f78bf768badc17b2fc4005a4f8bf); ?>
<?php endif; ?>

<?php echo $__env->make('admin.partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php if($records->count()): ?>
<div class="table-wrap">
    <table class="ops-table">
        <thead>
            <tr>
                <th>Record</th>
                <th>Date</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td>
                    <strong><?php echo e($record->title); ?></strong>
                    <?php if($record->reference || $record->notes): ?>
                    <small><?php echo e($record->reference ? $record->reference : ''); ?><?php echo e($record->reference && $record->notes ? ' · ' : ''); ?><?php echo e($record->notes ? \Illuminate\Support\Str::limit($record->notes, 60) : ''); ?></small>
                    <?php endif; ?>
                </td>
                <td><?php echo e($record->effective_date ?: '—'); ?></td>
                <td><?php echo e($record->amount ? number_format($record->amount, 2) : '—'); ?></td>
                <td><span class="status status--<?php echo e($record->status); ?>"><?php echo e(ucfirst($record->status)); ?></span></td>
                <td>
                    <div class="ops-actions">
                        <button onclick="openEditModal(<?php echo e($record->id); ?>)" title="Edit"><i data-lucide="square-pen"></i></button>
                        <form method="POST" action="<?php echo e(route('admin.records.destroy', [$slug, $record->id])); ?>" onsubmit="return confirm('Delete this record?')" style="display:inline;">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button title="Delete"><i data-lucide="trash-2"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<div class="ops-pagination"><?php echo e($records->links()); ?></div>
<?php else: ?>
<?php if (isset($component)) { $__componentOriginal99089f8e2ef4184d7d35db81d60c6521 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal99089f8e2ef4184d7d35db81d60c6521 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.empty-state','data' => ['title' => 'No records yet.','description' => 'Create the first '.e(strtolower($title)).' record.','action' => 'openModal(\'add-modal\')','addLabel' => 'Add First Record']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'No records yet.','description' => 'Create the first '.e(strtolower($title)).' record.','action' => 'openModal(\'add-modal\')','addLabel' => 'Add First Record']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal99089f8e2ef4184d7d35db81d60c6521)): ?>
<?php $attributes = $__attributesOriginal99089f8e2ef4184d7d35db81d60c6521; ?>
<?php unset($__attributesOriginal99089f8e2ef4184d7d35db81d60c6521); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal99089f8e2ef4184d7d35db81d60c6521)): ?>
<?php $component = $__componentOriginal99089f8e2ef4184d7d35db81d60c6521; ?>
<?php unset($__componentOriginal99089f8e2ef4184d7d35db81d60c6521); ?>
<?php endif; ?>
<?php endif; ?>


<?php if (isset($component)) { $__componentOriginal883972b03e56cea0994a1aaccc5761f0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal883972b03e56cea0994a1aaccc5761f0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.modal','data' => ['id' => 'add-modal','title' => 'Add Record']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'add-modal','title' => 'Add Record']); ?>
    <form method="POST" action="<?php echo e(route('admin.records.store', $slug)); ?>">
        <?php echo csrf_field(); ?>
        <div class="modal-form-grid">
            <label class="span-2">Title<input name="title" required></label>
            <label>Reference<input name="reference"></label>
            <label>Status
                <select name="status">
                    <option>active</option><option>pending</option><option>confirmed</option><option>completed</option><option>cancelled</option>
                </select>
            </label>
            <label>Effective date<input type="date" name="effective_date"></label>
            <label>Amount<input type="number" step="0.01" name="amount"></label>
            <label class="span-2">Notes<textarea name="notes" rows="4"></textarea></label>
        </div>
        <div class="modal-form-footer">
            <button type="button" class="button button-secondary" onclick="closeModal('add-modal')">Cancel</button>
            <button class="button button-primary"><i data-lucide="save"></i>Create record</button>
        </div>
    </form>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal883972b03e56cea0994a1aaccc5761f0)): ?>
<?php $attributes = $__attributesOriginal883972b03e56cea0994a1aaccc5761f0; ?>
<?php unset($__attributesOriginal883972b03e56cea0994a1aaccc5761f0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal883972b03e56cea0994a1aaccc5761f0)): ?>
<?php $component = $__componentOriginal883972b03e56cea0994a1aaccc5761f0; ?>
<?php unset($__componentOriginal883972b03e56cea0994a1aaccc5761f0); ?>
<?php endif; ?>


<?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php if (isset($component)) { $__componentOriginal883972b03e56cea0994a1aaccc5761f0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal883972b03e56cea0994a1aaccc5761f0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.modal','data' => ['id' => 'edit-modal-'.e($record->id).'','title' => 'Edit: '.e($record->title).'','open' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'edit-modal-'.e($record->id).'','title' => 'Edit: '.e($record->title).'','open' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
    <form method="POST" action="<?php echo e(route('admin.records.update', [$slug, $record->id])); ?>">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
        <div class="modal-form-grid">
            <label class="span-2">Title<input name="title" value="<?php echo e($record->title); ?>" required></label>
            <label>Reference<input name="reference" value="<?php echo e($record->reference); ?>"></label>
            <label>Status
                <select name="status">
                    <?php $__currentLoopData = ['active','pending','confirmed','completed','cancelled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option <?php if($record->status === $status): echo 'selected'; endif; ?>><?php echo e($status); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </label>
            <label>Effective date<input type="date" name="effective_date" value="<?php echo e($record->effective_date); ?>"></label>
            <label>Amount<input type="number" step="0.01" name="amount" value="<?php echo e($record->amount); ?>"></label>
            <label class="span-2">Notes<textarea name="notes" rows="4"><?php echo e($record->notes); ?></textarea></label>
        </div>
        <div class="modal-form-footer">
            <button type="button" class="button button-secondary" onclick="closeModal('edit-modal-<?php echo e($record->id); ?>')">Cancel</button>
            <button class="button button-primary"><i data-lucide="save"></i>Save changes</button>
        </div>
    </form>
    <form method="POST" action="<?php echo e(route('admin.records.destroy', [$slug, $record->id])); ?>" onsubmit="return confirm('Delete this record?')" style="margin-top:0.75rem;">
        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
        <button class="button button-danger" style="width:100%;"><i data-lucide="trash-2"></i>Delete record</button>
    </form>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal883972b03e56cea0994a1aaccc5761f0)): ?>
<?php $attributes = $__attributesOriginal883972b03e56cea0994a1aaccc5761f0; ?>
<?php unset($__attributesOriginal883972b03e56cea0994a1aaccc5761f0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal883972b03e56cea0994a1aaccc5761f0)): ?>
<?php $component = $__componentOriginal883972b03e56cea0994a1aaccc5761f0; ?>
<?php unset($__componentOriginal883972b03e56cea0994a1aaccc5761f0); ?>
<?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function openEditModal(id) {
    var el = document.getElementById('edit-modal-' + id);
    if (el) el.style.display = 'flex';
}
</script>
<script>
document.querySelector('.button-primary[href]')?.addEventListener('click', function(e) {
    if(this.getAttribute('onclick')) return;
    var href = this.getAttribute('href');
    if(href && href === '#') {
        e.preventDefault();
        openModal('add-modal');
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.ops-actions { display: flex; gap: 0.25rem; align-items: center; }
.ops-actions button, .ops-actions a { padding: 0.25rem; border: none; background: none; cursor: pointer; color: #6b6b6b; }
.ops-actions button:hover, .ops-actions a:hover { color: #234A36; }
.modal-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.modal-form-grid label { display: flex; flex-direction: column; gap: 0.25rem; font-size: 0.85rem; font-weight: 500; }
.modal-form-grid input, .modal-form-grid select, .modal-form-grid textarea { padding: 0.5rem 0.75rem; border: 1px solid var(--border); border-radius: 0.375rem; font-size: 0.9rem; }
.modal-form-grid .span-2 { grid-column: span 2; }
.modal-form-footer { display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.25rem; padding-top: 1rem; border-top: 1px solid var(--border); }
.button-danger { background: #ef4444; color: #fff; }
.button-danger:hover { background: #dc2626; }
</style>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\records\index.blade.php ENDPATH**/ ?>