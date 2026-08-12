<?php $__env->startSection('title', 'Create Itinerary Template'); ?>
<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginalad67f78bf768badc17b2fc4005a4f8bf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad67f78bf768badc17b2fc4005a4f8bf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.top-bar','data' => ['title' => 'Create Itinerary Template','description' => 'Itinerary Templates','search' => false,'addButton' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.top-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Create Itinerary Template','description' => 'Itinerary Templates','search' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'addButton' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
    <a href="<?php echo e(route('admin.itinerary-templates.index')); ?>" class="button button-ghost"><i data-lucide="arrow-left"></i> Back to Templates</a>
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

<form method="POST" action="<?php echo e(route('admin.itinerary-templates.store')); ?>" style="display:grid;grid-template-columns:2fr 1fr;gap:16px;align-items:start">
    <?php echo csrf_field(); ?>

    <div style="display:flex;flex-direction:column;gap:16px">

        
        <section class="ops-panel">
            <div class="ops-panel-title"><h2>Basic Information</h2></div>
            <div style="padding:16px;display:grid;grid-template-columns:1fr 1fr;gap:12px">
                <div style="grid-column:1/-1">
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Name *</label>
                    <input type="text" name="name" value="<?php echo e(old('name')); ?>" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'">
                </div>
                <div style="grid-column:1/-1">
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Trip Name</label>
                    <input type="text" name="trip_name" value="<?php echo e(old('trip_name')); ?>" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'">
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Country</label>
                    <select name="destination_id" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'">
                        <option value="">Select Country</option>
                        <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($country->id); ?>" <?php if(old('destination_id') == $country->id): echo 'selected'; endif; ?>><?php echo e($country->country); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Duration (Days) *</label>
                    <input type="number" name="duration_days" id="durationDays" value="<?php echo e(old('duration_days', 1)); ?>" min="1" max="99" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'">
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Category</label>
                    <select name="category" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'">
                        <option value="">Select Category</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($val); ?>" <?php if(old('category') === $val): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Status</label>
                    <select name="status" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'">
                        <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($val); ?>" <?php if(old('status', 'active') === $val): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
        </section>

        
        <section class="ops-panel">
            <div class="ops-panel-title"><h2>Content</h2></div>
            <div style="padding:16px;display:flex;flex-direction:column;gap:12px">
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Overview</label>
                    <textarea name="overview" rows="4" style="width:100%;padding:9px 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none;resize:vertical" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'"><?php echo e(old('overview')); ?></textarea>
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Highlights</label>
                    <textarea name="highlights" rows="3" style="width:100%;padding:9px 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none;resize:vertical" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'"><?php echo e(old('highlights')); ?></textarea>
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Includes</label>
                    <textarea name="includes" rows="3" style="width:100%;padding:9px 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none;resize:vertical" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'"><?php echo e(old('includes')); ?></textarea>
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Excludes</label>
                    <textarea name="excludes" rows="3" style="width:100%;padding:9px 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none;resize:vertical" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'"><?php echo e(old('excludes')); ?></textarea>
                </div>
            </div>
        </section>

        
        <section class="ops-panel">
            <div class="ops-panel-title"><h2>Policies</h2></div>
            <div style="padding:16px;display:flex;flex-direction:column;gap:12px">
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Terms</label>
                    <textarea name="terms" rows="3" style="width:100%;padding:9px 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none;resize:vertical" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'"><?php echo e(old('terms')); ?></textarea>
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Booking Terms</label>
                    <textarea name="booking_terms" rows="3" style="width:100%;padding:9px 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none;resize:vertical" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'"><?php echo e(old('booking_terms')); ?></textarea>
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Payment Schedule</label>
                    <textarea name="payment_schedule" rows="3" style="width:100%;padding:9px 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none;resize:vertical" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'"><?php echo e(old('payment_schedule')); ?></textarea>
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Cancellation Policy</label>
                    <textarea name="cancellation_policy" rows="3" style="width:100%;padding:9px 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none;resize:vertical" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'"><?php echo e(old('cancellation_policy')); ?></textarea>
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Refund Policy</label>
                    <textarea name="refund_policy" rows="3" style="width:100%;padding:9px 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none;resize:vertical" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'"><?php echo e(old('refund_policy')); ?></textarea>
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Important Notes</label>
                    <textarea name="important_notes" rows="3" style="width:100%;padding:9px 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none;resize:vertical" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'"><?php echo e(old('important_notes')); ?></textarea>
                </div>
            </div>
        </section>

        
        <section class="ops-panel">
            <div class="ops-panel-title">
                <h2>Itinerary Days</h2>
                <button type="button" id="addDayBtn" class="button button-primary button-sm"><i data-lucide="plus" style="width:14px;height:14px"></i> Add Day</button>
            </div>
            <div id="daysContainer" style="padding:16px;display:flex;flex-direction:column;gap:12px">
                <p style="color:var(--text-muted);font-size:9px;text-align:center;padding:16px 0" id="noDaysMsg">No days added yet. Click "Add Day" to start building the itinerary.</p>
            </div>
        </section>

    </div>

    
    <div style="display:flex;flex-direction:column;gap:16px">
        <section class="ops-panel" style="position:sticky;top:96px">
            <div class="ops-panel-title"><h2>Actions</h2></div>
            <div style="padding:16px;display:flex;flex-direction:column;gap:10px">
                <button type="submit" class="button button-primary" style="width:100%;justify-content:center">
                    <i data-lucide="save" style="width:15px;height:15px"></i> Save Template
                </button>
                <a href="<?php echo e(route('admin.itinerary-templates.index')); ?>" style="display:block;text-align:center;color:var(--text-muted);font-size:9px;padding:8px 0;text-decoration:none">Cancel</a>
            </div>
        </section>
    </div>
</form>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var daysContainer = document.getElementById('daysContainer');
    var noDaysMsg = document.getElementById('noDaysMsg');
    var addDayBtn = document.getElementById('addDayBtn');
    var durationInput = document.getElementById('durationDays');
    var dayIndex = <?php echo e(old('days') ? count(old('days')) : 0); ?>;
    var oldDays = {};
    var destinations = <?php echo json_encode($destinations->map(function($d) { return ['id' => $d->id, 'name' => $d->name]; }), 512) ?>;
    var hotelCache = {};
    var activityCache = {};

    <?php if(old('days')): ?>
    oldDays = <?php echo json_encode(old('days'), 15, 512) ?>;
    <?php endif; ?>

    function loadHotels(destId, callback) {
        var cacheKey = destId || 'all';
        if (hotelCache[cacheKey]) { callback(hotelCache[cacheKey]); return; }
        var url = '<?php echo e(route("admin.hotels.data")); ?>' + (destId ? '?destination_id=' + destId : '');
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function(r) { return r.json(); })
            .then(function(data) { hotelCache[cacheKey] = data; callback(data); });
    }

    function loadActivities(destId, callback) {
        if (activityCache[destId]) { callback(activityCache[destId]); return; }
        var url = '<?php echo e(route("admin.activities.data")); ?>?destination_id=' + destId;
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function(r) { return r.json(); })
            .then(function(data) { activityCache[destId] = data; callback(data); });
    }

    function createSelect(options, name, value, placeholder) {
        var sel = document.createElement('select');
        sel.name = name;
        sel.style.cssText = 'width:100%;height:34px;padding:0 10px;border:1px solid #d9d0c1;border-radius:6px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none;box-sizing:border-box';
        if (placeholder) {
            var opt = document.createElement('option');
            opt.value = '';
            opt.textContent = placeholder;
            sel.appendChild(opt);
        }
        (options || []).forEach(function(o) {
            var opt = document.createElement('option');
            opt.value = o.id;
            opt.textContent = o.name || o.text;
            if (value && value == o.id) opt.selected = true;
            sel.appendChild(opt);
        });
        return sel;
    }

    function fillHotelSelect(select, hotels, selectedHotelId) {
        var current = selectedHotelId || select.value;
        select.innerHTML = '';
        var opt = document.createElement('option');
        opt.value = '';
        opt.textContent = hotels.length ? 'Select Hotel' : 'No hotels found for this country';
        select.appendChild(opt);
        hotels.forEach(function(h) {
            var o = document.createElement('option');
            o.value = h.id;
            o.textContent = h.name + (h.tier ? ' — ' + h.tier : '') + (h.star_rating ? ' · ' + h.star_rating + '★' : '');
            o.dataset.mealPlan = h.meal_plan || '';
            if (current == h.id) o.selected = true;
            select.appendChild(o);
        });
    }

    function createDayRow(index, data) {
        data = data || {};
        var div = document.createElement('div');
        div.className = 'day-row';
        div.style.cssText = 'background:#ede8df;border-radius:8px;padding:14px;position:relative';

        var header = document.createElement('div');
        header.style.cssText = 'display:flex;align-items:center;justify-content:space-between;margin-bottom:10px';
        header.innerHTML = '<strong style="font-size:9px;color:#234A36">Day ' + (index + 1) + '</strong>' +
            '<button type="button" class="removeDayBtn" style="background:none;border:none;cursor:pointer;color:#dc2626;font-size:9px;display:inline-flex;align-items:center;gap:3px;padding:0"><i data-lucide="trash-2" style="width:13px;height:13px"></i> Remove</button>';

        div.appendChild(header);

        var grid = document.createElement('div');
        grid.style.cssText = 'display:grid;grid-template-columns:1fr 1fr;gap:8px';

        var hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'days[' + index + '][day_number]';
        hidden.value = index + 1;
        grid.appendChild(hidden);

        var titleWrap = document.createElement('div');
        titleWrap.style.gridColumn = '1/-1';
        titleWrap.innerHTML = '<label style="display:block;font-size:7px;font-weight:600;color:var(--text-muted);margin-bottom:4px">Title</label>';
        var titleInput = document.createElement('input');
        titleInput.type = 'text';
        titleInput.name = 'days[' + index + '][title]';
        titleInput.value = data.title || '';
        titleInput.style.cssText = 'width:100%;height:34px;padding:0 10px;border:1px solid #d9d0c1;border-radius:6px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none;box-sizing:border-box';
        titleWrap.appendChild(titleInput);
        grid.appendChild(titleWrap);

        var destWrap = document.createElement('div');
        destWrap.innerHTML = '<label style="display:block;font-size:7px;font-weight:600;color:var(--text-muted);margin-bottom:4px">Destination</label>';
        var destSelect = createSelect(destinations, 'days[' + index + '][destination_id]', data.destination_id, 'Select Destination');
        destSelect.addEventListener('change', function() {
            var destId = this.value;
            if (destId) {
                loadHotels(destId, function(hotels) {
                    var hotelSelects = div.querySelectorAll('.hotel-select');
                    hotelSelects.forEach(function(s) {
                        fillHotelSelect(s, hotels);
                    });
                });
                loadActivities(destId, function(activities) {
                    var actSelects = div.querySelectorAll('.activity-select');
                    actSelects.forEach(function(s) {
                        var cur = s.value;
                        s.innerHTML = '';
                        var opt = document.createElement('option');
                        opt.value = '';
                        opt.textContent = 'Select Activity';
                        s.appendChild(opt);
                        activities.forEach(function(a) {
                            var o = document.createElement('option');
                            o.value = a.id;
                            o.textContent = a.name;
                            if (cur == a.id) o.selected = true;
                            s.appendChild(o);
                        });
                    });
                });
            }
        });
        destWrap.appendChild(destSelect);
        grid.appendChild(destWrap);

        var hotelWrap = document.createElement('div');
        hotelWrap.innerHTML = '<label style="display:block;font-size:7px;font-weight:600;color:var(--text-muted);margin-bottom:4px">Hotel</label>';
        var hotelSelect = createSelect([], 'days[' + index + '][hotel_id]', data.hotel_id, 'Select Hotel');
        hotelSelect.className = 'hotel-select';
        hotelWrap.appendChild(hotelSelect);
        grid.appendChild(hotelWrap);
        loadHotels(data.destination_id || '', function(hotels) {
            fillHotelSelect(hotelSelect, hotels, data.hotel_id);
        });

        var fields = [
            { name: 'room_type', label: 'Room Type' },
            { name: 'meal_plan', label: 'Meal Plan' },
        ];
        var mealPlanInput = null;
        fields.forEach(function(f) {
            var wrap = document.createElement('div');
            wrap.innerHTML = '<label style="display:block;font-size:7px;font-weight:600;color:var(--text-muted);margin-bottom:4px">' + f.label + '</label>';
            var inp = document.createElement('input');
            inp.type = 'text';
            inp.name = 'days[' + index + '][' + f.name + ']';
            inp.value = data[f.name] || '';
            inp.style.cssText = 'width:100%;height:34px;padding:0 10px;border:1px solid #d9d0c1;border-radius:6px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none;box-sizing:border-box';
            if (f.name === 'meal_plan') mealPlanInput = inp;
            wrap.appendChild(inp);
            grid.appendChild(wrap);
        });
        hotelSelect.addEventListener('change', function() {
            var selected = this.options[this.selectedIndex];
            if (mealPlanInput && selected && selected.dataset.mealPlan && !mealPlanInput.value) {
                mealPlanInput.value = selected.dataset.mealPlan;
            }
        });

        var timeFields = [
            { name: 'morning_activity', label: 'Morning Activity' },
            { name: 'afternoon_activity', label: 'Afternoon Activity' },
            { name: 'evening_activity', label: 'Evening Activity' },
        ];
        timeFields.forEach(function(f) {
            var wrap = document.createElement('div');
            wrap.innerHTML = '<label style="display:block;font-size:7px;font-weight:600;color:var(--text-muted);margin-bottom:4px">' + f.label + '</label>';
            var inp = document.createElement('input');
            inp.type = 'text';
            inp.name = 'days[' + index + '][' + f.name + ']';
            inp.value = data[f.name] || '';
            inp.style.cssText = 'width:100%;height:34px;padding:0 10px;border:1px solid #d9d0c1;border-radius:6px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none;box-sizing:border-box';
            wrap.appendChild(inp);
            grid.appendChild(wrap);
        });

        var descWrap = document.createElement('div');
        descWrap.style.gridColumn = '1/-1';
        descWrap.innerHTML = '<label style="display:block;font-size:7px;font-weight:600;color:var(--text-muted);margin-bottom:4px">Description</label>';
        var descTA = document.createElement('textarea');
        descTA.name = 'days[' + index + '][description]';
        descTA.rows = 2;
        descTA.value = data.description || '';
        descTA.style.cssText = 'width:100%;padding:7px 10px;border:1px solid #d9d0c1;border-radius:6px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none;resize:vertical;box-sizing:border-box';
        descWrap.appendChild(descTA);
        grid.appendChild(descWrap);

        var incWrap = document.createElement('div');
        incWrap.style.gridColumn = '1/-1';
        incWrap.innerHTML = '<label style="display:block;font-size:7px;font-weight:600;color:var(--text-muted);margin-bottom:4px">Included Services</label>';
        var incTA = document.createElement('textarea');
        incTA.name = 'days[' + index + '][included_services]';
        incTA.rows = 2;
        incTA.value = data.included_services || '';
        incTA.style.cssText = 'width:100%;padding:7px 10px;border:1px solid #d9d0c1;border-radius:6px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none;resize:vertical;box-sizing:border-box';
        incWrap.appendChild(incTA);
        grid.appendChild(incWrap);

        var optWrap = document.createElement('div');
        optWrap.style.gridColumn = '1/-1';
        optWrap.innerHTML = '<label style="display:block;font-size:7px;font-weight:600;color:var(--text-muted);margin-bottom:4px">Optional Activities</label>';
        var optTA = document.createElement('textarea');
        optTA.name = 'days[' + index + '][optional_activities]';
        optTA.rows = 2;
        optTA.value = data.optional_activities || '';
        optTA.style.cssText = 'width:100%;padding:7px 10px;border:1px solid #d9d0c1;border-radius:6px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none;resize:vertical;box-sizing:border-box';
        optWrap.appendChild(optTA);
        grid.appendChild(optWrap);

        div.appendChild(grid);

        // Trigger destination load if data has destination_id
        if (data.destination_id) {
            destSelect.value = data.destination_id;
            var evt = new Event('change');
            destSelect.dispatchEvent(evt);
        }

        if (typeof lucide !== 'undefined') {
            lucide.createIcons({ root: div });
        }

        return div;
    }

    function updateDayNumbers() {
        var rows = daysContainer.querySelectorAll('.day-row');
        rows.forEach(function(row, i) {
            var header = row.querySelector('strong');
            if (header) header.textContent = 'Day ' + (i + 1);
            var hidden = row.querySelector('input[type="hidden"][name$="[day_number]"]');
            if (hidden) hidden.value = i + 1;
        });
    }

    function addDay(data) {
        data = data || {};
        var row = createDayRow(dayIndex, data);
        daysContainer.appendChild(row);
        dayIndex++;
        noDaysMsg.style.display = 'none';
        updateDayNumbers();
    }

    addDayBtn.addEventListener('click', function() {
        addDay();
    });

    daysContainer.addEventListener('click', function(e) {
        if (e.target.closest('.removeDayBtn')) {
            var row = e.target.closest('.day-row');
            if (row) {
                if (daysContainer.querySelectorAll('.day-row').length <= 1 || confirm('Remove this day?')) {
                    row.remove();
                    updateDayNumbers();
                    if (daysContainer.querySelectorAll('.day-row').length === 0) {
                        noDaysMsg.style.display = 'block';
                    }
                }
            }
        }
    });

    durationInput.addEventListener('change', function() {
        var target = parseInt(this.value) || 1;
        var current = daysContainer.querySelectorAll('.day-row').length;
        if (current === 0 && target > 0) {
            for (var i = 0; i < target; i++) {
                addDay();
            }
        }
    });

    if (Object.keys(oldDays).length > 0) {
        for (var i = 0; i < oldDays.length; i++) {
            addDay(oldDays[i]);
        }
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\itinerary-templates\create.blade.php ENDPATH**/ ?>