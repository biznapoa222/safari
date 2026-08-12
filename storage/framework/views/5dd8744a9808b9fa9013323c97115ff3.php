<form id="requestsFilterForm" class="pm-request-filters">
    <input type="hidden" name="sort" value="<?php echo e(request('sort', 'created_at')); ?>">
    <input type="hidden" name="direction" value="<?php echo e(request('direction', 'desc')); ?>">
    <label class="pm-filter-field pm-filter-search">
        <span>Search</span>
        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="First, last names or email">
        <i data-lucide="search"></i>
    </label>
    <button class="pm-search-button" type="submit">SEARCH</button>
    <label class="pm-filter-field">
        <span>Filter by statuses</span>
        <select name="status">
            <option value="">All statuses</option>
            <?php $__currentLoopData = $statuses ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($st); ?>" <?php if(request('status') === $st): echo 'selected'; endif; ?>><?php echo e(($statusOptions ?? [])[$st] ?? ucwords(str_replace('_', ' ', $st))); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <i data-lucide="search"></i>
    </label>
    <fieldset class="pm-filter-checks">
        <legend>Filter by type</legend>
        <?php $__currentLoopData = ['itinerary' => 'Itinerary', 'custom' => 'Custom', 'manual' => 'Manual', 'group' => 'Group']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <label><input type="checkbox" name="request_types[]" value="<?php echo e($value); ?>" <?php if(in_array($value, (array) request('request_types', []), true)): echo 'checked'; endif; ?>><span><?php echo e($label); ?></span></label>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </fieldset>
    <label class="pm-filter-field">
        <span>Filter by seller</span>
        <select name="assigned_to">
            <option value="">All</option>
            <?php $__currentLoopData = $users ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($user->id); ?>" <?php if(request('assigned_to') == $user->id): echo 'selected'; endif; ?>><?php echo e($user->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <i data-lucide="user"></i>
    </label>
    <label class="pm-filter-field">
        <span>Filter by language</span>
        <select name="language">
            <option value="">nl, de, en, fr, es, sv, no, da, it, pl, pt</option>
            <?php $__currentLoopData = ($languages ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($code); ?>" <?php if(request('language') === $code): echo 'selected'; endif; ?>><?php echo e($code); ?> - <?php echo e($label); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <i data-lucide="search"></i>
    </label>
    <label class="pm-filter-field pm-company">
        <span>Filter by country</span>
        <select name="country">
            <option value="">All countries</option>
            <?php $__currentLoopData = $countries ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($country); ?>" <?php if(request('country') === $country): echo 'selected'; endif; ?>><?php echo e($country); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <i data-lucide="search"></i>
    </label>
    <label class="pm-filter-field pm-date">
        <i data-lucide="calendar-days"></i>
        <input type="date" name="followup_from" value="<?php echo e(request('followup_from')); ?>" placeholder="Follow-up date">
        <span>Follow-up date</span>
    </label>
    <label class="pm-filter-field pm-date">
        <i data-lucide="calendar-days"></i>
        <input type="date" name="arrival_from" value="<?php echo e(request('arrival_from')); ?>" placeholder="Arrival date">
        <span>Arrival date</span>
    </label>
</form>
<?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views/admin/requests/partials/_filters.blade.php ENDPATH**/ ?>