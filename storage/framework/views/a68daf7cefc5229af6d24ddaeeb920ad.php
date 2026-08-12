<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'id' => 'modal-'.uniqid(),
    'title' => 'Form',
    'size' => 'md', // sm, md, lg, xl
    'open' => false,
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'id' => 'modal-'.uniqid(),
    'title' => 'Form',
    'size' => 'md', // sm, md, lg, xl
    'open' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div id="<?php echo e($id); ?>" class="ops-modal-overlay" style="display:<?php echo e($open ? 'flex' : 'none'); ?>;<?php echo e($open ? '' : 'display:none;'); ?>" onclick="if(event.target===this)this.style.display='none'">
    <div class="ops-modal ops-modal--<?php echo e($size); ?>">
        <div class="ops-modal-header">
            <h2><?php echo e($title); ?></h2>
            <button type="button" class="ops-modal-close" onclick="document.getElementById('<?php echo e($id); ?>').style.display='none'" aria-label="Close">
                <i data-lucide="x"></i>
            </button>
        </div>
        <div class="ops-modal-body">
            <?php echo e($slot); ?>

        </div>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
<style>
.ops-modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    padding: 1rem;
}
.ops-modal {
    background: #F8F5EF;
    border-radius: 0.75rem;
    box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
    animation: modalIn 0.2s ease;
}
.ops-modal--sm { max-width: 400px; }
.ops-modal--md { max-width: 560px; }
.ops-modal--lg { max-width: 720px; }
.ops-modal--xl { max-width: 960px; }
.ops-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid #d9d0c1;
}
.ops-modal-header h2 { font-size: 1.1rem; font-weight: 600; margin: 0; color: #2F2F2F; }
.ops-modal-close {
    border: none;
    background: none;
    cursor: pointer;
    color: #6b6b6b;
    padding: 0.25rem;
    border-radius: 0.25rem;
}
.ops-modal-close:hover { background: #ede8df; color: #234A36; }
.ops-modal-body { padding: 1.5rem; }
.ops-modal-body label { display: flex; flex-direction: column; gap: 0.25rem; font-size: 0.85rem; font-weight: 600; color: #3a3530; }
.ops-modal-body input,
.ops-modal-body select,
.ops-modal-body textarea {
    padding: 0.5rem 0.75rem;
    border: 1px solid #d9d0c1;
    border-radius: 0.375rem;
    font-size: 0.9rem;
    background: #fdfcfa;
    color: #2F2F2F;
}
.ops-modal-body input:focus,
.ops-modal-body select:focus,
.ops-modal-body textarea:focus {
    outline: 2px solid #234A36;
    outline-offset: 1px;
    border-color: #234A36;
}
@keyframes modalIn {
    from { opacity: 0; transform: scale(0.95) translateY(-10px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
window.openModal = function(id) { document.getElementById(id).style.display = 'flex'; };
window.closeModal = function(id) { document.getElementById(id).style.display = 'none'; };
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\components\admin\modal.blade.php ENDPATH**/ ?>