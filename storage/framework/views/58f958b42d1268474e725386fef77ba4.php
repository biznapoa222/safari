<?php $__env->startSection('title', 'Edit Request: '.$request->request_number); ?>
<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginalad67f78bf768badc17b2fc4005a4f8bf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad67f78bf768badc17b2fc4005a4f8bf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.top-bar','data' => ['title' => 'Edit Request: '.e($request->request_number).'','description' => 'Request Management']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.top-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Edit Request: '.e($request->request_number).'','description' => 'Request Management']); ?>
    <a href="<?php echo e(route('admin.requests.show', $request->id)); ?>" class="icon-button"><i data-lucide="arrow-left"></i></a>
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

<form method="POST" action="<?php echo e(route('admin.requests.update', $request->id)); ?>" style="display:grid;grid-template-columns:2fr 1fr;gap:16px">
    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

    <div style="display:flex;flex-direction:column;gap:16px">
        
        <section class="ops-panel">
            <div class="ops-panel-title"><h2>General Information</h2></div>
            <div style="padding:16px;display:grid;grid-template-columns:1fr 1fr;gap:12px">
                <div style="grid-column:1/-1"><?php echo $__env->make('admin.requests.partials._client_search', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Company</label>
                    <input type="text" name="company" value="<?php echo e(old('company', $request->company)); ?>" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Language</label>
                    <select name="language" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                        <option value="en" <?php if(old('language', $request->language) === 'en'): echo 'selected'; endif; ?>>English</option>
                        <option value="fr" <?php if(old('language', $request->language) === 'fr'): echo 'selected'; endif; ?>>French</option>
                        <option value="de" <?php if(old('language', $request->language) === 'de'): echo 'selected'; endif; ?>>German</option>
                        <option value="es" <?php if(old('language', $request->language) === 'es'): echo 'selected'; endif; ?>>Spanish</option>
                        <option value="it" <?php if(old('language', $request->language) === 'it'): echo 'selected'; endif; ?>>Italian</option>
                        <option value="nl" <?php if(old('language', $request->language) === 'nl'): echo 'selected'; endif; ?>>Dutch</option>
                        <option value="pt" <?php if(old('language', $request->language) === 'pt'): echo 'selected'; endif; ?>>Portuguese</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Source</label>
                    <select name="source" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                        <option value="manual" <?php if(old('source', $request->source) === 'manual'): echo 'selected'; endif; ?>>Manual</option>
                        <option value="website" <?php if(old('source', $request->source) === 'website'): echo 'selected'; endif; ?>>Website</option>
                        <option value="whatsapp" <?php if(old('source', $request->source) === 'whatsapp'): echo 'selected'; endif; ?>>WhatsApp</option>
                        <option value="email" <?php if(old('source', $request->source) === 'email'): echo 'selected'; endif; ?>>Email</option>
                        <option value="walk_in" <?php if(old('source', $request->source) === 'walk_in'): echo 'selected'; endif; ?>>Walk In</option>
                        <option value="api" <?php if(old('source', $request->source) === 'api'): echo 'selected'; endif; ?>>API</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Priority</label>
                    <select name="priority" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                        <option value="low" <?php if(old('priority', $request->priority) === 'low'): echo 'selected'; endif; ?>>Low</option>
                        <option value="medium" <?php if(old('priority', $request->priority) === 'medium'): echo 'selected'; endif; ?>>Medium</option>
                        <option value="high" <?php if(old('priority', $request->priority) === 'high'): echo 'selected'; endif; ?>>High</option>
                        <option value="urgent" <?php if(old('priority', $request->priority) === 'urgent'): echo 'selected'; endif; ?>>Urgent</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Assigned Consultant</label>
                    <select name="assigned_consultant_id" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                        <option value="">Unassigned</option>
                        <?php $__currentLoopData = $users ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($user->id); ?>" <?php if(old('assigned_consultant_id', $request->assigned_consultant_id) == $user->id): echo 'selected'; endif; ?>><?php echo e($user->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
        </section>

        
        <section class="ops-panel">
            <div class="ops-panel-title"><h2>Travel Details</h2></div>
            <div style="padding:16px;display:grid;grid-template-columns:1fr 1fr;gap:12px">
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Arrival Date</label>
                    <input type="date" name="arrival_date" id="arrival_date" value="<?php echo e(old('arrival_date', $request->arrival_date?->format('Y-m-d'))); ?>" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Departure Date</label>
                    <input type="date" name="departure_date" id="departure_date" value="<?php echo e(old('departure_date', $request->departure_date?->format('Y-m-d'))); ?>" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Nights</label>
                    <input type="number" name="nights" id="nights" value="<?php echo e(old('nights', $request->nights)); ?>" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Destination</label>
                    <input type="text" name="destination" value="<?php echo e(old('destination', $request->destination)); ?>" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Adults</label>
                    <input type="number" name="adults" value="<?php echo e(old('adults', $request->adults)); ?>" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Children</label>
                    <input type="number" name="children" value="<?php echo e(old('children', $request->children)); ?>" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Infants</label>
                    <input type="number" name="infants" value="<?php echo e(old('infants', $request->infants)); ?>" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Accommodation Tier</label>
                    <select name="accommodation_tier" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                        <option value="">Select...</option>
                        <?php $__currentLoopData = ['luxury','midrange','budget','camping']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($tier); ?>" <?php if(old('accommodation_tier', $request->accommodation_tier) === $tier): echo 'selected'; endif; ?>><?php echo e(ucfirst($tier)); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Travel Type</label>
                    <select name="travel_type" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                        <option value="">Select...</option>
                        <?php $__currentLoopData = ['honeymoon','family','group','corporate','solo','adventure']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($tt); ?>" <?php if(old('travel_type', $request->travel_type) === $tt): echo 'selected'; endif; ?>><?php echo e(ucfirst($tt)); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Budget</label>
                    <input type="number" step="0.01" name="budget" value="<?php echo e(old('budget', $request->budget)); ?>" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Currency</label>
                    <select name="currency" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                        <?php $__currentLoopData = ['USD','EUR','GBP','ZAR','TZS','KES','UGX','RWF']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($c); ?>" <?php if(old('currency', $request->currency) === $c): echo 'selected'; endif; ?>><?php echo e($c); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
        </section>

        
        <section class="ops-panel">
            <div class="ops-panel-title"><h2>Requirements</h2></div>
            <div style="padding:16px;display:grid;grid-template-columns:1fr 1fr;gap:12px">
                <?php $__currentLoopData = ['flight_required'=>'Flight Required','pickup_required'=>'Pickup Required','guide_required'=>'Guide Required','visa_required'=>'Visa Required','insurance_required'=>'Insurance Required']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <label style="display:flex;align-items:center;gap:8px;font-size:9px;cursor:pointer">
                    <input type="checkbox" name="<?php echo e($field); ?>" value="1" <?php if(old($field, $request->$field)): echo 'checked'; endif; ?> style="accent-color:var(--primary)">
                    <?php echo e($label); ?>

                </label>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <div style="grid-column:1/-1">
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Transport</label>
                    <input type="text" name="transport" value="<?php echo e(old('transport', $request->transport)); ?>" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                </div>
            </div>
        </section>

        
        <section class="ops-panel">
            <div class="ops-panel-title"><h2>Notes</h2></div>
            <div style="padding:16px;display:flex;flex-direction:column;gap:12px">
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Internal Notes</label>
                    <textarea name="internal_notes" rows="3" style="width:100%;padding:10px 12px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF"><?php echo e(old('internal_notes', $request->internal_notes)); ?></textarea>
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Special Requests</label>
                    <textarea name="special_requests" rows="3" style="width:100%;padding:10px 12px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF"><?php echo e(old('special_requests', $request->special_requests)); ?></textarea>
                </div>
            </div>
        </section>
    </div>

    
    <div style="display:flex;flex-direction:column;gap:12px">
        <section class="ops-panel">
            <div class="ops-panel-title"><h2>Actions</h2></div>
            <div style="padding:16px;display:flex;flex-direction:column;gap:8px">
                <button type="submit" class="button button-primary" style="width:100%;justify-content:center">Update Request</button>
                <a href="<?php echo e(route('admin.requests.show', $request->id)); ?>" class="button button-ghost" style="width:100%;justify-content:center;text-align:center;text-decoration:none">Cancel</a>
            </div>
        </section>
        <section class="ops-panel">
            <div class="ops-panel-title"><h2>Summary</h2></div>
            <div style="padding:16px;font-size:9px">
                <p style="margin:0 0 8px;color:var(--text-muted)">Request <?php echo e($request->request_number); ?></p>
                <p style="margin:0 0 4px">Status: <strong><?php echo e(ucwords(str_replace('_', ' ', $request->status))); ?></strong></p>
                <p style="margin:0">Created: <?php echo e($request->created_at->format('d M Y')); ?></p>
            </div>
        </section>
    </div>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\requests\edit.blade.php ENDPATH**/ ?>