<?php $__env->startSection('title', 'Requests'); ?>
<?php $__env->startSection('body_class', 'admin-body--legacy-requests'); ?>
<?php $__env->startSection('content'); ?>
<?php echo $__env->make('admin.partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php
    // Keep the visible Requests navigation limited to the approved workflow tabs.
    $tabs = [
        '' => 'ALL REQUESTS',
        'new' => 'NEW REQUESTS',
        'contacted' => 'EXISTING REQUESTS',
        'qualified' => 'PRE-CONFIRMED REQUESTS',
        'confirmed' => 'CONFIRMED REQUESTS',
        'travelled' => 'OPERATED REQUESTS',
        'cancelled' => 'CANCELLED REQUESTS',
        'archived' => 'DODO REQUESTS',
    ];
    $currentStatus = request('status', '');
    $createdDirection = request('sort') === 'created_at' && request('direction') === 'asc' ? 'asc' : 'desc';
    $nextCreatedDirection = $createdDirection === 'asc' ? 'desc' : 'asc';
?>

<section class="pm-requests-page">
    <header class="pm-requests-head">
        <h1>Requests</h1>
        <nav>
            <button type="button" data-request-modal-open>CREATE REQUEST</button>
            <a href="<?php echo e(route('admin.requests.accommodation-bookings')); ?>">FIND ACCOMMODATION BOOKINGS</a>
        </nav>
    </header>

    <div class="pm-request-tabs">
        <?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="#" data-status-tab="<?php echo e($val); ?>" class="<?php echo e($currentStatus === $val ? 'active-tab is-active' : ''); ?>"><?php echo e($label); ?></a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <?php echo $__env->make('admin.requests.partials._filters', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <section class="pm-table-panel" id="requestsTableWrapper">
        <div class="table-wrap">
            <table class="pm-requests-table">
                <thead>
                    <tr>
                        <th>Index</th>
                        <th>Follow Up Date</th>
                        <th>Arrival date</th>
                        <th><a class="request-sort-link" href="<?php echo e(route('admin.requests.index', array_merge(request()->query(), ['sort' => 'created_at', 'direction' => $nextCreatedDirection]))); ?>">Created date <i data-lucide="<?php echo e($createdDirection === 'asc' ? 'arrow-up' : 'arrow-down'); ?>"></i></a></th>
                        <th>First name</th>
                        <th>Last name</th>
                        <th>Status</th>
                        <th>Value</th>
                        <th>Rating</th>
                        <th>Seller notes</th>
                        <th>Is Diamond Luxury</th>
                        <th>Type</th>
                        <th>Site</th>
                        <th>Responsible user</th>
                        <th>Company</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php echo $__env->make('admin.requests.partials._table_rows', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </tbody>
            </table>
        </div>
        <?php if($requests->hasPages()): ?>
            <div class="ops-pagination"><?php echo e($requests->links()); ?></div>
        <?php endif; ?>
    </section>
</section>

<div class="pm-modal" data-request-modal <?php if($errors->any()): ?> style="display:flex" <?php endif; ?>>
    <div class="pm-modal-backdrop" data-request-modal-close></div>
    <form method="POST" action="<?php echo e(route('admin.requests.store')); ?>" class="pm-request-dialog">
        <?php echo csrf_field(); ?>
        <header>Create new Travel Request</header>
        <div class="pm-dialog-body">
            <label class="pm-field pm-select">
                <span>Language of customer</span>
                <select name="language">
                    <?php $__currentLoopData = config('safari.languages', []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($code); ?>" <?php if(old('language', 'en') === $code): echo 'selected'; endif; ?>><?php echo e($language['name']); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </label>
            <label class="pm-field"><span>First name</span><input name="first_name" value="<?php echo e(old('first_name')); ?>" required></label>
            <label class="pm-field"><span>Surname</span><input name="surname" value="<?php echo e(old('surname')); ?>" required></label>
            <label class="pm-field"><span>Email</span><input type="email" name="client_email" value="<?php echo e(old('client_email')); ?>" required></label>
            <label class="pm-field"><span>Phone</span><input name="client_phone" value="<?php echo e(old('client_phone')); ?>"></label>
            <label class="pm-field pm-select"><span>Country</span><select name="country" required><option value="">Select country</option><?php $__currentLoopData = $countries ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($country); ?>" <?php if(old('country') === $country): echo 'selected'; endif; ?>><?php echo e($country); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></label>
            <label class="pm-field pm-icon-field"><span>Arrival date</span><input type="date" name="arrival_date" value="<?php echo e(old('arrival_date')); ?>" required><i data-lucide="calendar-days"></i></label>
            <label class="pm-field pm-select"><span>Company</span><input name="company" value="<?php echo e(old('company')); ?>"></label>
            <label class="pm-field pm-select">
                <span>Marketing channel</span>
                <select name="source">
                    <option value="manual">Manual</option>
                    <option value="website">Website</option>
                    <option value="email">Email</option>
                    <option value="whatsapp">WhatsApp</option>
                </select>
            </label>
            <label class="pm-checkbox"><input type="checkbox" name="travel_type" value="group"> <span>Is this joining-a-group trip?</span></label>
            <input type="hidden" name="client_name" data-client-name>
            <input type="hidden" name="adults" value="1">
            <input type="hidden" name="children" value="0">
            <input type="hidden" name="infants" value="0">
            <input type="hidden" name="currency" value="USD">
        </div>
        <footer>
            <button type="button" data-request-modal-close>CLOSE</button>
            <button type="submit" class="pm-create-btn" disabled>CREATE REQUEST</button>
        </footer>
    </form>
</div>

<?php echo $__env->make('admin.requests.partials._notes_panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var modal = document.querySelector('[data-request-modal]');
    var openers = document.querySelectorAll('[data-request-modal-open]');
    var closers = document.querySelectorAll('[data-request-modal-close]');
    openers.forEach(function (btn) {
        btn.addEventListener('click', function () {
            modal.style.display = 'flex';
            setTimeout(function () { modal.querySelector('select, input')?.focus(); }, 50);
        });
    });
    closers.forEach(function (btn) {
        btn.addEventListener('click', function () { modal.style.display = 'none'; });
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') modal.style.display = 'none';
    });
    var requestForm = modal.querySelector('form');
    var createButton = requestForm?.querySelector('.pm-create-btn');
    var updateCreateState = function () {
        if (!requestForm || !createButton) return;
        var requiredFields = ['first_name', 'surname', 'client_email', 'country', 'arrival_date'];
        var complete = requiredFields.every(function (name) {
            return (requestForm.querySelector('[name="' + name + '"]')?.value || '').trim() !== '';
        });
        createButton.disabled = !complete;
        createButton.setAttribute('aria-disabled', complete ? 'false' : 'true');
    };
    requestForm?.querySelectorAll('input, select').forEach(function (field) {
        field.addEventListener('input', updateCreateState);
        field.addEventListener('change', updateCreateState);
    });
    updateCreateState();
    requestForm?.addEventListener('submit', function () {
        var first = this.querySelector('[name="first_name"]')?.value.trim() || '';
        var surname = this.querySelector('[name="surname"]')?.value.trim() || '';
        this.querySelector('[data-client-name]').value = (first + ' ' + surname).trim() || first || surname;
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views/admin/requests/index.blade.php ENDPATH**/ ?>