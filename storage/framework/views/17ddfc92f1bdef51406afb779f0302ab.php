<?php $__env->startSection('title', 'Find Accommodation Bookings'); ?>
<?php $__env->startSection('content'); ?>
<?php
    $selectedAccommodation = '';
    $selectedKey = $filters['accommodation'] ?? '';
    foreach ($accommodations['rooms'] as $room) {
        if ($selectedKey === 'room:'.$room->id) $selectedAccommodation = $room->hotel_name.' - '.$room->room_name;
    }
    foreach ($accommodations['hotels'] as $hotel) {
        if ($selectedKey === 'hotel:'.$hotel->id) $selectedAccommodation = $hotel->name;
    }
    foreach ($accommodations['proposals'] as $proposal) {
        if ($selectedKey === 'proposal:'.$proposal->id) $selectedAccommodation = $proposal->reference.' - '.$proposal->title;
    }
?>

<section class="accommodation-bookings-page">
    <header class="accommodation-bookings-header">
        <div>
            <span>Requests / Accommodation</span>
            <h1>Find Accommodation Bookings</h1>
        </div>
        <a class="accommodation-close-button" href="<?php echo e(route('admin.requests.index')); ?>">Close</a>
    </header>

    <form class="accommodation-bookings-filters" method="GET" action="<?php echo e(route('admin.requests.accommodation-bookings')); ?>" data-accommodation-search-form>
        <label class="accommodation-filter accommodation-filter--wide">
            <span>Accommodation</span>
            <input type="search" name="accommodation_label" value="<?php echo e($selectedAccommodation); ?>" placeholder="Search accommodation..." list="accommodation-options" autocomplete="off" data-accommodation-label>
            <input type="hidden" name="accommodation" value="<?php echo e($selectedKey); ?>" data-accommodation-value>
            <datalist id="accommodation-options">
                <?php $__currentLoopData = $accommodations['rooms']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($room->hotel_name.' - '.$room->room_name); ?>" data-key="room:<?php echo e($room->id); ?>"></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php $__currentLoopData = $accommodations['hotels']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hotel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($hotel->name); ?>" data-key="hotel:<?php echo e($hotel->id); ?>"></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php $__currentLoopData = $accommodations['proposals']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proposal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($proposal->reference.' - '.$proposal->title); ?>" data-key="proposal:<?php echo e($proposal->id); ?>"></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </datalist>
        </label>
        <label class="accommodation-filter">
            <span>Minimum Date</span>
            <input type="date" name="minimum_date" value="<?php echo e($filters['minimum_date'] ?? ''); ?>" required data-accommodation-date>
        </label>
        <label class="accommodation-filter">
            <span>Maximum Date</span>
            <input type="date" name="maximum_date" value="<?php echo e($filters['maximum_date'] ?? ''); ?>" required data-accommodation-date>
        </label>
        <button class="accommodation-search-button" type="submit" data-accommodation-submit disabled>Search</button>
        <button class="accommodation-total-button" type="submit" name="total" value="1" data-accommodation-submit disabled>Get Total Bed Nights</button>
    </form>

    <section class="accommodation-bookings-results">
        <?php if($searched && $bookings->isEmpty()): ?>
            <div class="accommodation-empty">No accommodation bookings found <strong>0</strong></div>
        <?php elseif(!$searched): ?>
            <div class="accommodation-empty">Select a date range to search accommodation bookings.</div>
        <?php else: ?>
            <div class="accommodation-results-heading"><strong><?php echo e($bookings->count()); ?> booking<?php echo e($bookings->count() === 1 ? '' : 's'); ?></strong><span><?php echo e($filters['minimum_date']); ?> to <?php echo e($filters['maximum_date']); ?></span></div>
            <div class="accommodation-table-wrap">
                <table class="accommodation-bookings-table">
                    <thead><tr><th>Date</th><th>Proposal</th><th class="number">Amount of Persons</th></tr></thead>
                    <tbody>
                    <?php $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e(\Carbon\Carbon::parse($booking->starts_at)->format('d-m-Y')); ?></td>
                            <td><strong><?php echo e($booking->proposal_reference); ?></strong><small><?php echo e($booking->proposal_title); ?></small></td>
                            <td class="number"><?php echo e($booking->persons); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                    <tfoot><tr><th colspan="2">Total bed nights in selected period</th><th class="number"><?php echo e($totalBedNights ?: 0); ?></th></tr></tfoot>
                </table>
            </div>
        <?php endif; ?>
    </section>

    <footer class="accommodation-bookings-footer">
        <?php if($searched && !empty($filters['minimum_date'])): ?>
            <a class="accommodation-export-button" href="<?php echo e(route('admin.requests.accommodation-bookings.export', request()->query())); ?>">Export to Excel</a>
        <?php else: ?>
            <button class="accommodation-export-button" type="button" disabled>Export to Excel</button>
        <?php endif; ?>
    </footer>
</section>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var form = document.querySelector('[data-accommodation-search-form]');
    if (!form) return;
    var label = form.querySelector('[data-accommodation-label]');
    var value = form.querySelector('[data-accommodation-value]');
    var options = Array.from(form.querySelectorAll('#accommodation-options option'));
    var dates = Array.from(form.querySelectorAll('[data-accommodation-date]'));
    var buttons = Array.from(form.querySelectorAll('[data-accommodation-submit]'));
    var update = function () {
        var min = dates[0]?.value || '';
        var max = dates[1]?.value || '';
        var valid = min && max && max >= min;
        dates[1]?.setCustomValidity(max && min && max < min ? 'Maximum date must not be earlier than minimum date.' : '');
        buttons.forEach(function (button) { button.disabled = !valid; });
    };
    label?.addEventListener('input', function () {
        var match = options.find(function (option) { return option.value === label.value; });
        value.value = match?.dataset.key || '';
    });
    dates.forEach(function (date) { date.addEventListener('change', update); });
    form.addEventListener('submit', function () {
        buttons.forEach(function (button) { button.disabled = true; button.dataset.originalText = button.textContent; button.textContent = 'Searching...'; });
    });
    update();
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\requests\accommodation-bookings.blade.php ENDPATH**/ ?>