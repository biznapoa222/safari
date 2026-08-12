<?php $__env->startSection('title', 'Create Request'); ?>
<?php $__env->startSection('content'); ?>

<?php if (isset($component)) { $__componentOriginalad67f78bf768badc17b2fc4005a4f8bf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad67f78bf768badc17b2fc4005a4f8bf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.top-bar','data' => ['title' => 'Create Request','description' => 'Request Management','search' => false,'addButton' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.top-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Create Request','description' => 'Request Management','search' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'addButton' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
    <a href="<?php echo e(route('admin.requests.index')); ?>" class="button button-ghost"><i data-lucide="arrow-left"></i> Back to Requests</a>
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

<form method="POST" action="<?php echo e(route('admin.requests.store')); ?>" style="display:grid;grid-template-columns:2fr 1fr;gap:16px;align-items:start">
    <?php echo csrf_field(); ?>

    
    <div style="display:flex;flex-direction:column;gap:16px">

        
        <section class="ops-panel">
            <div class="ops-panel-title"><h2>General Information</h2></div>
            <div style="padding:16px">
                <?php echo $__env->make('admin.requests.partials._client_search', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:12px">
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Company</label>
                        <input type="text" name="company" value="<?php echo e(old('company')); ?>" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'">
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Language</label>
                        <select name="language" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'">
                            <option value="en" <?php if(old('language') === 'en'): echo 'selected'; endif; ?>>English</option>
                            <option value="fr" <?php if(old('language') === 'fr'): echo 'selected'; endif; ?>>French</option>
                            <option value="de" <?php if(old('language') === 'de'): echo 'selected'; endif; ?>>German</option>
                            <option value="es" <?php if(old('language') === 'es'): echo 'selected'; endif; ?>>Spanish</option>
                            <option value="it" <?php if(old('language') === 'it'): echo 'selected'; endif; ?>>Italian</option>
                            <option value="nl" <?php if(old('language') === 'nl'): echo 'selected'; endif; ?>>Dutch</option>
                            <option value="pt" <?php if(old('language') === 'pt'): echo 'selected'; endif; ?>>Portuguese</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Source</label>
                        <select name="source" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'">
                            <option value="manual" <?php if(old('source') === 'manual'): echo 'selected'; endif; ?>>Manual</option>
                            <option value="website" <?php if(old('source') === 'website'): echo 'selected'; endif; ?>>Website</option>
                            <option value="whatsapp" <?php if(old('source') === 'whatsapp'): echo 'selected'; endif; ?>>WhatsApp</option>
                            <option value="email" <?php if(old('source') === 'email'): echo 'selected'; endif; ?>>Email</option>
                            <option value="walk_in" <?php if(old('source') === 'walk_in'): echo 'selected'; endif; ?>>Walk In</option>
                            <option value="api" <?php if(old('source') === 'api'): echo 'selected'; endif; ?>>API</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Priority</label>
                        <select name="priority" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'">
                            <option value="low" <?php if(old('priority') === 'low'): echo 'selected'; endif; ?>>Low</option>
                            <option value="medium" <?php if(old('priority') === 'medium'): echo 'selected'; endif; ?>>Medium</option>
                            <option value="high" <?php if(old('priority') === 'high'): echo 'selected'; endif; ?>>High</option>
                            <option value="urgent" <?php if(old('priority') === 'urgent'): echo 'selected'; endif; ?>>Urgent</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Assigned Consultant</label>
                        <select name="assigned_consultant_id" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'">
                            <option value="">Unassigned</option>
                            <?php $__currentLoopData = $users ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($user->id); ?>" <?php if(old('assigned_consultant_id') == $user->id): echo 'selected'; endif; ?>><?php echo e($user->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
            </div>
        </section>

        
        <section class="ops-panel">
            <div class="ops-panel-title"><h2>Travel Details</h2></div>
            <div style="padding:16px">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Arrival Date</label>
                        <input type="date" name="arrival_date" id="arrivalDate" value="<?php echo e(old('arrival_date')); ?>" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'">
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Departure Date</label>
                        <input type="date" name="departure_date" id="departureDate" value="<?php echo e(old('departure_date')); ?>" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'">
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Nights</label>
                        <input type="number" name="nights" id="nights" value="<?php echo e(old('nights')); ?>" min="0" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'">
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Adults</label>
                        <input type="number" name="adults" value="<?php echo e(old('adults', 2)); ?>" min="0" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'">
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Children</label>
                        <input type="number" name="children" value="<?php echo e(old('children', 0)); ?>" min="0" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'">
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Infants</label>
                        <input type="number" name="infants" value="<?php echo e(old('infants', 0)); ?>" min="0" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'">
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Destination</label>
                        <input type="text" name="destination" value="<?php echo e(old('destination')); ?>" list="destinations" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none" placeholder="Type destination..." onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'">
                        <datalist id="destinations">
                            <?php $__currentLoopData = $destinations ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($d); ?>"><?php echo e($d); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </datalist>
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Accommodation Tier</label>
                        <select name="accommodation_tier" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'">
                            <option value="">Select Tier</option>
                            <option value="luxury" <?php if(old('accommodation_tier') === 'luxury'): echo 'selected'; endif; ?>>Luxury</option>
                            <option value="midrange" <?php if(old('accommodation_tier') === 'midrange'): echo 'selected'; endif; ?>>Midrange</option>
                            <option value="budget" <?php if(old('accommodation_tier') === 'budget'): echo 'selected'; endif; ?>>Budget</option>
                            <option value="camping" <?php if(old('accommodation_tier') === 'camping'): echo 'selected'; endif; ?>>Camping</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Travel Type</label>
                        <select name="travel_type" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'">
                            <option value="">Select Type</option>
                            <option value="honeymoon" <?php if(old('travel_type') === 'honeymoon'): echo 'selected'; endif; ?>>Honeymoon</option>
                            <option value="family" <?php if(old('travel_type') === 'family'): echo 'selected'; endif; ?>>Family</option>
                            <option value="group" <?php if(old('travel_type') === 'group'): echo 'selected'; endif; ?>>Group</option>
                            <option value="corporate" <?php if(old('travel_type') === 'corporate'): echo 'selected'; endif; ?>>Corporate</option>
                            <option value="solo" <?php if(old('travel_type') === 'solo'): echo 'selected'; endif; ?>>Solo</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Budget</label>
                        <input type="number" step="0.01" name="budget" value="<?php echo e(old('budget')); ?>" min="0" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'">
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Currency</label>
                        <select name="currency" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'">
                            <option value="USD" <?php if(old('currency') === 'USD'): echo 'selected'; endif; ?>>USD</option>
                            <option value="EUR" <?php if(old('currency') === 'EUR'): echo 'selected'; endif; ?>>EUR</option>
                            <option value="GBP" <?php if(old('currency') === 'GBP'): echo 'selected'; endif; ?>>GBP</option>
                            <option value="ZAR" <?php if(old('currency') === 'ZAR'): echo 'selected'; endif; ?>>ZAR</option>
                            <option value="TZS" <?php if(old('currency') === 'TZS'): echo 'selected'; endif; ?>>TZS</option>
                            <option value="KES" <?php if(old('currency') === 'KES'): echo 'selected'; endif; ?>>KES</option>
                            <option value="UGX" <?php if(old('currency') === 'UGX'): echo 'selected'; endif; ?>>UGX</option>
                            <option value="RWF" <?php if(old('currency') === 'RWF'): echo 'selected'; endif; ?>>RWF</option>
                        </select>
                    </div>
                </div>
            </div>
        </section>

        
        <section class="ops-panel">
            <div class="ops-panel-title"><h2>Requirements</h2></div>
            <div style="padding:16px">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px">
                    <label style="display:flex;align-items:center;gap:8px;font-size:9px;color:var(--text);cursor:pointer">
                        <input type="checkbox" name="flight_required" value="1" <?php if(old('flight_required')): echo 'checked'; endif; ?> style="width:14px;height:14px;accent-color:var(--primary)">
                        Flight Required
                    </label>
                    <label style="display:flex;align-items:center;gap:8px;font-size:9px;color:var(--text);cursor:pointer">
                        <input type="checkbox" name="pickup_required" value="1" <?php if(old('pickup_required')): echo 'checked'; endif; ?> style="width:14px;height:14px;accent-color:var(--primary)">
                        Pickup Required
                    </label>
                    <label style="display:flex;align-items:center;gap:8px;font-size:9px;color:var(--text);cursor:pointer">
                        <input type="checkbox" name="guide_required" value="1" <?php if(old('guide_required')): echo 'checked'; endif; ?> style="width:14px;height:14px;accent-color:var(--primary)">
                        Guide Required
                    </label>
                    <label style="display:flex;align-items:center;gap:8px;font-size:9px;color:var(--text);cursor:pointer">
                        <input type="checkbox" name="visa_required" value="1" <?php if(old('visa_required')): echo 'checked'; endif; ?> style="width:14px;height:14px;accent-color:var(--primary)">
                        Visa Required
                    </label>
                    <label style="display:flex;align-items:center;gap:8px;font-size:9px;color:var(--text);cursor:pointer">
                        <input type="checkbox" name="insurance_required" value="1" <?php if(old('insurance_required')): echo 'checked'; endif; ?> style="width:14px;height:14px;accent-color:var(--primary)">
                        Insurance Required
                    </label>
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Transport</label>
                    <input type="text" name="transport" value="<?php echo e(old('transport')); ?>" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none" placeholder="Transport requirements..." onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'">
                </div>
            </div>
        </section>

        
        <section class="ops-panel">
            <div class="ops-panel-title"><h2>Notes</h2></div>
            <div style="padding:16px;display:flex;flex-direction:column;gap:14px">
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Internal Notes</label>
                    <textarea name="internal_notes" rows="4" style="width:100%;padding:9px 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none;resize:vertical" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'"><?php echo e(old('internal_notes')); ?></textarea>
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Special Requests</label>
                    <textarea name="special_requests" rows="4" style="width:100%;padding:9px 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none;resize:vertical" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'"><?php echo e(old('special_requests')); ?></textarea>
                </div>
            </div>
        </section>
    </div>

    
    <div style="display:flex;flex-direction:column;gap:16px">
        <section class="ops-panel" style="position:sticky;top:96px">
            <div class="ops-panel-title"><h2>Actions</h2></div>
            <div style="padding:16px;display:flex;flex-direction:column;gap:10px">
                <button type="submit" class="button button-primary" style="width:100%;justify-content:center">
                    <i data-lucide="save" style="width:15px;height:15px"></i> Save
                </button>
                <button type="submit" name="save_and_continue" value="1" class="button button-secondary" style="width:100%;justify-content:center">
                    <i data-lucide="arrow-right" style="width:15px;height:15px"></i> Save & Continue
                </button>
                <a href="<?php echo e(route('admin.requests.index')); ?>" style="display:block;text-align:center;color:var(--text-muted);font-size:9px;padding:8px 0;text-decoration:none">Cancel</a>
            </div>
        </section>

        <section class="ops-panel">
            <div class="ops-panel-title"><h2>Summary</h2></div>
            <div style="padding:16px;display:flex;flex-direction:column;gap:10px;font-size:9px">
                <div style="display:flex;justify-content:space-between">
                    <span style="color:var(--text-muted)">Nights</span>
                    <span style="color:var(--text);font-weight:600" id="summaryNights">0</span>
                </div>
                <div style="display:flex;justify-content:space-between">
                    <span style="color:var(--text-muted)">Total Guests</span>
                    <span style="color:var(--text);font-weight:600" id="summaryGuests">0</span>
                </div>
                <div style="display:flex;justify-content:space-between">
                    <span style="color:var(--text-muted)">Adults</span>
                    <span style="color:var(--text);font-weight:600" id="summaryAdults">0</span>
                </div>
                <div style="display:flex;justify-content:space-between">
                    <span style="color:var(--text-muted)">Children</span>
                    <span style="color:var(--text);font-weight:600" id="summaryChildren">0</span>
                </div>
                <div style="display:flex;justify-content:space-between">
                    <span style="color:var(--text-muted)">Infants</span>
                    <span style="color:var(--text);font-weight:600" id="summaryInfants">0</span>
                </div>
            </div>
        </section>
    </div>
</form>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const arrival = document.getElementById('arrivalDate');
    const departure = document.getElementById('departureDate');
    const nights = document.getElementById('nights');
    const adults = document.querySelector('[name="adults"]');
    const children = document.querySelector('[name="children"]');
    const infants = document.querySelector('[name="infants"]');

    function calcNights() {
        if (arrival.value && departure.value) {
            const a = new Date(arrival.value);
            const d = new Date(departure.value);
            const diff = Math.max(0, Math.round((d - a) / (1000 * 60 * 60 * 24)));
            nights.value = diff;
            document.getElementById('summaryNights').textContent = diff;
        }
    }

    function calcGuests() {
        const a = parseInt(adults?.value) || 0;
        const c = parseInt(children?.value) || 0;
        const i = parseInt(infants?.value) || 0;
        document.getElementById('summaryGuests').textContent = a + c + i;
        document.getElementById('summaryAdults').textContent = a;
        document.getElementById('summaryChildren').textContent = c;
        document.getElementById('summaryInfants').textContent = i;
    }

    arrival?.addEventListener('change', calcNights);
    departure?.addEventListener('change', calcNights);
    adults?.addEventListener('input', calcGuests);
    children?.addEventListener('input', calcGuests);
    infants?.addEventListener('input', calcGuests);
    calcNights();
    calcGuests();
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\requests\create.blade.php ENDPATH**/ ?>