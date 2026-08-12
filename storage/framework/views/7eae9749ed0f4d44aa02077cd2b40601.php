<?php $__env->startSection('title', $hotel ? 'Edit Accommodation' : 'New Accommodation'); ?>

<?php $__env->startSection('content'); ?>
<div class="ops-page-heading">
    <div><p class="eyebrow">Accommodation setup</p><h1><?php echo e($hotel ? $hotel->name : 'New accommodation'); ?></h1><p>Manage supplier details, room categories, capacity and seasonal buy-in/selling rates.</p></div>
    <a class="button button-secondary" href="<?php echo e(route('admin.accommodations.index')); ?>"><i data-lucide="arrow-left"></i>Back to list</a>
</div>
<?php echo $__env->make('admin.partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="ops-detail-layout">
    <nav class="ops-side-tabs">
        <a href="#accommodation"><i data-lucide="building-2"></i>Accommodation</a>
        <a href="#rooms"><i data-lucide="bed-double"></i>Rooms & ages</a>
        <a href="#prices"><i data-lucide="badge-dollar-sign"></i>Manage prices</a>
        <a href="#availability"><i data-lucide="calendar-check"></i>Availability</a>
        <a href="<?php echo e(route('admin.translations')); ?>"><i data-lucide="languages"></i>Translations</a>
    </nav>
    <div class="ops-detail-content">
        <section id="accommodation" class="ops-panel ops-form-panel">
            <div class="ops-panel-title"><div><h2>Accommodation details</h2><p>Supplier contact, classification, markup and publishing.</p></div></div>
            <form method="POST" action="<?php echo e($hotel ? route('admin.accommodations.update', $hotel->id) : route('admin.accommodations.store')); ?>">
                <?php echo csrf_field(); ?> <?php if($hotel): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
                <div class="ops-form-grid">
                    <label class="span-2">Title<input name="name" value="<?php echo e(old('name', $hotel->name ?? '')); ?>" required></label>
                    <label class="span-2">Destination
                        <select name="destination_id">
                            <option value="">Select destination</option>
                            <?php $__currentLoopData = $destinations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $destination): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($destination->id); ?>" <?php if((string) old('destination_id', $hotel->destination_id ?? '') === (string) $destination->id): echo 'selected'; endif; ?>><?php echo e($destination->country); ?> — <?php echo e($destination->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </label>
                    <label>Tier<input name="tier" value="<?php echo e(old('tier', $hotel->tier ?? 'standard')); ?>" placeholder="Standard, luxury, premium..."></label>
                    <label>Star rating<input type="number" min="1" max="7" name="star_rating" value="<?php echo e(old('star_rating', $hotel->star_rating ?? '')); ?>"></label>
                    <label>Default meal plan<input name="meal_plan" value="<?php echo e(old('meal_plan', $hotel->meal_plan ?? 'Full Board')); ?>"></label>
                    <label>Reservations email<input type="email" name="reservation_email" value="<?php echo e(old('reservation_email', $hotel->reservation_email ?? '')); ?>"></label>
                    <label>Website<input name="website" value="<?php echo e(old('website', $hotel->website ?? '')); ?>" placeholder="https://..."></label>
                    <label>GPS / map location<input name="gps" value="<?php echo e(old('gps', $hotel->gps ?? '')); ?>"></label>
                    <label>Currency<input name="currency" maxlength="3" value="<?php echo e(old('currency', $hotel->currency ?? 'USD')); ?>" required></label>
                    <label>Default markup %<input type="number" step="0.01" name="default_markup_percent" value="<?php echo e(old('default_markup_percent', $hotel->default_markup_percent ?? 20)); ?>" required></label>
                    <label>Status<select name="status"><option value="1" <?php if((string) old('status', $hotel->status ?? 1) === '1'): echo 'selected'; endif; ?>>Active</option><option value="0" <?php if((string) old('status', $hotel->status ?? 1) === '0'): echo 'selected'; endif; ?>>Inactive</option></select></label>
                    <label class="span-2">Description<textarea name="description" rows="4"><?php echo e(old('description', $hotel->description ?? '')); ?></textarea></label>
                    <label class="span-2">Amenities<textarea name="amenities" rows="3" placeholder="Pool, Wi-Fi, family rooms, bush dinner..."><?php echo e(old('amenities', $hotel->amenities ?? '')); ?></textarea></label>
                    <label class="span-2">Image URL<input name="hero_image" value="<?php echo e(old('hero_image', $hotel->hero_image ?? '')); ?>" placeholder="/images/accommodations/example.jpg"></label>
                    <label class="span-2">Gallery URLs<textarea name="gallery" rows="3" placeholder="One image URL per line or JSON list"><?php echo e(old('gallery', $hotel->gallery ?? '')); ?></textarea></label>
                    <label class="span-2">Rate notes<textarea name="rates" rows="3"><?php echo e(old('rates', $hotel->rates ?? '')); ?></textarea></label>
                </div>
                <div class="ops-form-footer"><button class="button button-primary"><i data-lucide="save"></i><?php echo e($hotel ? 'Save changes' : 'Create accommodation'); ?></button></div>
            </form>
        </section>

        <?php if($hotel): ?>
        <section id="rooms" class="ops-panel ops-form-panel">
            <div class="ops-panel-title"><div><h2>Rooms & ages</h2><p>Set room capacity and age bands used for babies, children and adults.</p></div></div>
            <form class="room-create-form" method="POST" action="<?php echo e(route('admin.accommodations.rooms.store', $hotel->id)); ?>">
                <?php echo csrf_field(); ?>
                <div class="ops-form-grid">
                    <div class="room-age-card span-2">
                        <div class="room-age-card__title">Age rules</div>
                        <div class="room-age-grid">
                            <label>Baby maximum age<input type="number" name="baby_max_age" value="<?php echo e(old('baby_max_age', 2)); ?>" min="0" max="17" required></label>
                            <label>Child minimum age<input type="number" name="child_min_age" value="<?php echo e(old('child_min_age', 3)); ?>" min="0" max="17" required></label>
                            <label>Child maximum age<input type="number" name="child_max_age" value="<?php echo e(old('child_max_age', 11)); ?>" min="0" max="17" required></label>
                            <label>Adult minimum age<input type="number" name="adult_min_age" value="<?php echo e(old('adult_min_age', 12)); ?>" min="1" max="30" required></label>
                        </div>
                    </div>
                    <label class="span-2">Room type name<input name="name" placeholder="Double room, Family suite..." required></label>
                    <label>Max adults<input type="number" name="max_adults" value="<?php echo e(old('max_adults', 2)); ?>" min="1" max="12" required></label>
                    <label>Max children<input type="number" name="max_children" value="<?php echo e(old('max_children', 0)); ?>" min="0" max="12" required></label>
                    <label>Available rooms<input type="number" name="inventory" value="<?php echo e(old('inventory', 1)); ?>" min="1" title="Available rooms" required></label>
                    <label class="check-label"><input type="checkbox" name="is_family_room" value="1"> Family</label>
                    <label class="check-label"><input type="checkbox" name="is_interconnecting" value="1"> Interconnecting</label>
                    <label class="span-2">Notes<input name="notes" placeholder="Child sharing rule, cot availability, triple setup..."></label>
                </div>
                <div class="ops-form-footer"><button class="button button-primary"><i data-lucide="plus"></i>Add room</button></div>
            </form>
            <div class="room-rate-list" id="prices">
                <?php $__empty_1 = true; $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <article class="room-rate-card">
                        <header><div><h3><?php echo e($room->name); ?></h3><p><?php echo e($room->max_adults); ?> adults · <?php echo e($room->max_children); ?> children · <?php echo e($room->inventory); ?> rooms · Baby 0–<?php echo e($room->baby_max_age ?? 2); ?> yrs · Child <?php echo e($room->child_min_age ?? 3); ?>–<?php echo e($room->child_max_age ?? 11); ?> yrs · Adult <?php echo e($room->adult_min_age ?? 12); ?>+ yrs <?php if($room->is_family_room): ?> · Family <?php endif; ?> <?php if($room->is_interconnecting): ?> · Interconnecting <?php endif; ?></p></div><form method="POST" action="<?php echo e(route('admin.accommodations.rooms.destroy', [$hotel->id, $room->id])); ?>" onsubmit="return confirm('Delete this room and all rates?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="danger-icon"><i data-lucide="trash-2"></i></button></form></header>
                        <div class="table-wrap"><table class="ops-table compact-table"><thead><tr><th>Season</th><th>Dates</th><th>Meal plan</th><th>Basis</th><th>Buy-in</th><th>Markup</th><th>Selling</th><th></th></tr></thead><tbody>
                        <?php $__currentLoopData = $room->rates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><tr><td><?php echo e($rate->season_name); ?></td><td><?php echo e(\Carbon\Carbon::parse($rate->valid_from)->format('d M Y')); ?> – <?php echo e(\Carbon\Carbon::parse($rate->valid_to)->format('d M Y')); ?></td><td><?php echo e($rate->meal_plan); ?></td><td><?php echo e(str_replace('_', ' ', ucfirst($rate->occupancy_basis))); ?></td><td><span class="buy-price"><?php echo e($rate->currency); ?> <?php echo e(number_format($rate->buy_rate, 2)); ?></span></td><td><?php echo e(number_format($rate->markup_percent, 1)); ?>%</td><td><strong><?php echo e($rate->currency); ?> <?php echo e(number_format($rate->sell_rate, 2)); ?></strong></td><td><form method="POST" action="<?php echo e(route('admin.accommodations.rates.destroy', [$hotel->id, $room->id, $rate->id])); ?>"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="danger-icon"><i data-lucide="x"></i></button></form></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody></table></div>
                        <form class="ops-inline-form rate-create-form" method="POST" action="<?php echo e(route('admin.accommodations.rates.store', [$hotel->id, $room->id])); ?>">
                            <?php echo csrf_field(); ?>
                            <input name="season_name" placeholder="Season" required>
                            <input type="date" name="valid_from" required>
                            <input type="date" name="valid_to" required>
                            <input name="meal_plan" value="Full Board" required>
                            <select name="occupancy_basis"><option value="per_room">Per room</option><option value="per_person">Per person</option><option value="per_adult">Per adult</option><option value="per_child">Per child</option></select>
                            <input type="number" step="0.01" name="buy_rate" placeholder="Buy rate" required>
                            <input type="number" step="0.01" name="markup_percent" value="<?php echo e($hotel->default_markup_percent); ?>" required>
                            <input name="currency" value="<?php echo e($hotel->currency); ?>" maxlength="3" required>
                            <button class="button button-primary"><i data-lucide="plus"></i>Add rate</button>
                        </form>
                    </article>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="ops-empty">Add the first room category above.</div>
                <?php endif; ?>
            </div>
        </section>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\accommodations\form.blade.php ENDPATH**/ ?>