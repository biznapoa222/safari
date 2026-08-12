<?php $__env->startSection('title', $template->name); ?>
<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginalad67f78bf768badc17b2fc4005a4f8bf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad67f78bf768badc17b2fc4005a4f8bf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.top-bar','data' => ['title' => $template->name,'search' => false,'addButton' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.top-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($template->name),'search' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'addButton' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
     <?php $__env->slot('actions', null, []); ?> 
        <a href="<?php echo e(route('admin.itinerary-templates.edit', $template)); ?>" class="button button-primary"><i data-lucide="square-pen"></i> Edit</a>
        <form method="POST" action="<?php echo e(route('admin.itinerary-templates.duplicate', $template)); ?>" style="display:inline" onsubmit="return confirm('Duplicate this template?')">
            <?php echo csrf_field(); ?>
            <button class="button button-secondary"><i data-lucide="copy"></i> Duplicate</button>
        </form>
        <a href="<?php echo e(route('admin.itinerary-templates.preview', $template)); ?>" target="_blank" class="button button-secondary"><i data-lucide="eye"></i> Preview</a>
        <a href="<?php echo e(route('admin.itinerary-templates.pdf', $template)); ?>" class="button button-secondary"><i data-lucide="file-text"></i> PDF</a>
        <form method="POST" action="<?php echo e(route('admin.itinerary-templates.destroy', $template)); ?>" style="display:inline" onsubmit="return confirm('Delete this template?')">
            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
            <button class="button button-secondary" style="color:var(--text-muted)"><i data-lucide="trash-2"></i> Delete</button>
        </form>
        <a href="<?php echo e(route('admin.itinerary-templates.index')); ?>" class="button button-ghost"><i data-lucide="arrow-left"></i> Back</a>
     <?php $__env->endSlot(); ?>
    <div style="display:flex;align-items:center;gap:8px;margin-top:6px">
        <?php if($template->category): ?>
        <span style="display:inline-flex;padding:3px 7px;border-radius:4px;font-size:7px;font-weight:700;background:#ede8df;color:#3a3530">
            <?php echo e($categories[$template->category] ?? $template->category); ?>

        </span>
        <?php endif; ?>
        <?php if($template->status === 'active'): ?>
        <span style="display:inline-flex;padding:3px 7px;border-radius:4px;font-size:7px;font-weight:700;color:#16a34a;background:#f0fdf4">Active</span>
        <?php elseif($template->status === 'inactive'): ?>
        <span style="display:inline-flex;padding:3px 7px;border-radius:4px;font-size:7px;font-weight:700;color:#dc2626;background:#fef2f2">Inactive</span>
        <?php else: ?>
        <span style="display:inline-flex;padding:3px 7px;border-radius:4px;font-size:7px;font-weight:700;color:#6b7280;background:#f3f4f6">Archived</span>
        <?php endif; ?>
        <span style="color:var(--text-muted);font-size:8px"><?php echo e($template->duration_days); ?> days · <?php echo e($template->days->count()); ?> day<?php echo e($template->days->count() !== 1 ? 's' : ''); ?> defined</span>
    </div>
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


<div style="display:flex;gap:0;border-bottom:1px solid var(--line);margin-bottom:16px">
    <button type="button" class="tab-btn active" data-tab="overview" style="padding:8px 16px;font-size:9px;font-weight:600;background:none;border:none;border-bottom:2px solid #234A36;color:#234A36;cursor:pointer">Overview</button>
    <button type="button" class="tab-btn" data-tab="itinerary" style="padding:8px 16px;font-size:9px;font-weight:600;background:none;border:none;border-bottom:2px solid transparent;color:var(--text-muted);cursor:pointer">Itinerary</button>
    <button type="button" class="tab-btn" data-tab="pricing" style="padding:8px 16px;font-size:9px;font-weight:600;background:none;border:none;border-bottom:2px solid transparent;color:var(--text-muted);cursor:pointer">Pricing</button>
    <button type="button" class="tab-btn" data-tab="policies" style="padding:8px 16px;font-size:9px;font-weight:600;background:none;border:none;border-bottom:2px solid transparent;color:var(--text-muted);cursor:pointer">Policies</button>
</div>


<div class="tab-content" id="tab-overview">
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:16px;align-items:start">
        <div style="display:flex;flex-direction:column;gap:16px">
            <?php if($template->overview): ?>
            <section class="ops-panel">
                <div class="ops-panel-title"><h2>Overview</h2></div>
                <div style="padding:16px">
                    <p style="margin:0;font-size:9px;color:var(--text);white-space:pre-wrap"><?php echo e($template->overview); ?></p>
                </div>
            </section>
            <?php endif; ?>

            <?php if($template->highlights || $template->includes || $template->excludes): ?>
            <section class="ops-panel">
                <div class="ops-panel-title"><h2>Details</h2></div>
                <div style="padding:16px;display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px">
                    <?php if($template->highlights): ?>
                    <div>
                        <h3 style="font-size:9px;font-weight:700;color:#234A36;margin:0 0 8px">Highlights</h3>
                        <p style="margin:0;font-size:9px;color:var(--text);white-space:pre-wrap"><?php echo e($template->highlights); ?></p>
                    </div>
                    <?php endif; ?>
                    <?php if($template->includes): ?>
                    <div>
                        <h3 style="font-size:9px;font-weight:700;color:#234A36;margin:0 0 8px">Includes</h3>
                        <p style="margin:0;font-size:9px;color:var(--text);white-space:pre-wrap"><?php echo e($template->includes); ?></p>
                    </div>
                    <?php endif; ?>
                    <?php if($template->excludes): ?>
                    <div>
                        <h3 style="font-size:9px;font-weight:700;color:#234A36;margin:0 0 8px">Excludes</h3>
                        <p style="margin:0;font-size:9px;color:var(--text);white-space:pre-wrap"><?php echo e($template->excludes); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </section>
            <?php endif; ?>
        </div>

        <div style="display:flex;flex-direction:column;gap:16px">
            <section class="ops-panel">
                <div class="ops-panel-title"><h2>Details</h2></div>
                <div style="padding:16px;display:flex;flex-direction:column;gap:0;font-size:9px">
                    <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--line)">
                        <span style="color:var(--text-muted)">Name</span>
                        <span style="color:var(--text);font-weight:600"><?php echo e($template->name); ?></span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--line)">
                        <span style="color:var(--text-muted)">Trip Name</span>
                        <span style="color:var(--text)"><?php echo e($template->trip_name ?? '—'); ?></span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--line)">
                        <span style="color:var(--text-muted)">Destination</span>
                        <span style="color:var(--text)"><?php echo e($template->destination->name ?? '—'); ?></span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--line)">
                        <span style="color:var(--text-muted)">Duration</span>
                        <span style="color:var(--text)"><?php echo e($template->duration_days); ?> days</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--line)">
                        <span style="color:var(--text-muted)">Category</span>
                        <span style="color:var(--text)"><?php echo e($template->category ? ($categories[$template->category] ?? $template->category) : '—'); ?></span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--line)">
                        <span style="color:var(--text-muted)">Status</span>
                        <span style="color:var(--text)"><?php echo e(ucfirst($template->status)); ?></span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:8px 0">
                        <span style="color:var(--text-muted)">Created</span>
                        <span style="color:var(--text)"><?php echo e($template->created_at->format('d M Y')); ?></span>
                    </div>
                </div>
            </section>

            <?php if($template->notes): ?>
            <section class="ops-panel">
                <div class="ops-panel-title"><h2>Notes</h2></div>
                <div style="padding:16px">
                    <p style="margin:0;font-size:9px;color:var(--text);white-space:pre-wrap"><?php echo e($template->notes); ?></p>
                </div>
            </section>
            <?php endif; ?>
        </div>
    </div>
</div>


<div class="tab-content" id="tab-itinerary" style="display:none">
    <section class="ops-panel">
        <div class="ops-panel-title"><h2>Day-by-Day Itinerary</h2></div>
        <div style="padding:16px;display:flex;flex-direction:column;gap:0">
            <?php $__empty_1 = true; $__currentLoopData = $template->days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div style="display:flex;gap:14px;padding:14px 0;border-bottom:1px solid var(--line);<?php echo e($loop->last ? 'border-bottom:0' : ''); ?>">
                <div style="display:flex;flex-direction:column;align-items:center;min-width:40px">
                    <span style="display:flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:50%;background:#234A36;color:#fff;font-size:9px;font-weight:700"><?php echo e($day->day_number); ?></span>
                    <?php if(!$loop->last): ?>
                    <div style="width:1px;flex:1;background:var(--line);margin-top:6px"></div>
                    <?php endif; ?>
                </div>
                <div style="flex:1">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px">
                        <h3 style="margin:0;font-size:9px;font-weight:700;color:var(--text)"><?php echo e($day->title ?? 'Day ' . $day->day_number); ?></h3>
                        <?php if($day->destination): ?>
                        <span style="font-size:8px;color:var(--text-muted)"><?php echo e($day->destination->name ?? ''); ?></span>
                        <?php endif; ?>
                    </div>
                    <?php if($day->hotel || $day->hotel_name): ?>
                    <div style="display:flex;gap:12px;margin-bottom:6px">
                        <span style="font-size:8px;color:var(--text-muted)"><strong>Accommodation:</strong> <?php echo e($day->hotel->name ?? $day->hotel_name); ?></span>
                        <?php if($day->room_type): ?>
                        <span style="font-size:8px;color:var(--text-muted)"><strong>Room:</strong> <?php echo e($day->room_type); ?></span>
                        <?php endif; ?>
                        <?php if($day->meal_plan): ?>
                        <span style="font-size:8px;color:var(--text-muted)"><strong>Meals:</strong> <?php echo e($day->meal_plan); ?></span>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    <?php if($day->morning_activity || $day->afternoon_activity || $day->evening_activity): ?>
                    <div style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:6px">
                        <?php if($day->morning_activity): ?>
                        <span style="display:inline-flex;padding:3px 7px;border-radius:4px;font-size:7px;font-weight:600;background:#ede8df;color:#3a3530">Morning: <?php echo e($day->morning_activity); ?></span>
                        <?php endif; ?>
                        <?php if($day->afternoon_activity): ?>
                        <span style="display:inline-flex;padding:3px 7px;border-radius:4px;font-size:7px;font-weight:600;background:#ede8df;color:#3a3530">Afternoon: <?php echo e($day->afternoon_activity); ?></span>
                        <?php endif; ?>
                        <?php if($day->evening_activity): ?>
                        <span style="display:inline-flex;padding:3px 7px;border-radius:4px;font-size:7px;font-weight:600;background:#ede8df;color:#3a3530">Evening: <?php echo e($day->evening_activity); ?></span>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    <?php if($day->activities->count()): ?>
                    <div style="display:flex;flex-wrap:wrap;gap:4px;margin-bottom:6px">
                        <?php $__currentLoopData = $day->activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $act): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span style="display:inline-flex;padding:2px 6px;border-radius:3px;font-size:7px;background:#234A36;color:#fff"><?php echo e($act->activity_name ?? $act->activity->name ?? 'Activity'); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php endif; ?>
                    <?php if($day->description): ?>
                    <p style="margin:0;font-size:8px;color:var(--text-muted);white-space:pre-wrap"><?php echo e($day->description); ?></p>
                    <?php endif; ?>
                    <?php if($day->included_services): ?>
                    <p style="margin:4px 0 0;font-size:8px;color:#16a34a"><strong>Included:</strong> <?php echo e($day->included_services); ?></p>
                    <?php endif; ?>
                    <?php if($day->optional_activities): ?>
                    <p style="margin:4px 0 0;font-size:8px;color:#ca8a04"><strong>Optional:</strong> <?php echo e($day->optional_activities); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p style="color:var(--text-muted);font-size:9px;text-align:center;padding:16px 0">No days defined yet.</p>
            <?php endif; ?>
        </div>
    </section>
</div>


<div class="tab-content" id="tab-pricing" style="display:none">
    <section class="ops-panel">
        <div class="ops-panel-title"><h2>Pricing</h2></div>
        <div class="table-wrap">
            <table class="ops-table">
                <thead>
                    <tr>
                        <th>Currency</th>
                        <th>Price Per Person</th>
                        <th>Single Supplement</th>
                        <th>Total Cost</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $template->pricing; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $price): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td style="font-size:9px"><?php echo e($price->currency); ?></td>
                        <td style="font-size:9px"><?php echo e(number_format($price->price_per_person, 2)); ?></td>
                        <td style="font-size:9px"><?php echo e(number_format($price->single_supplement, 2)); ?></td>
                        <td style="font-size:9px"><?php echo e(number_format($price->total_cost, 2)); ?></td>
                        <td style="font-size:9px;color:var(--text-muted)"><?php echo e($price->notes ?? '—'); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" style="text-align:center;padding:32px 16px;color:var(--text-muted);font-size:9px">No pricing defined.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>


<div class="tab-content" id="tab-policies" style="display:none">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <?php if($template->terms): ?>
        <section class="ops-panel">
            <div class="ops-panel-title"><h2>Terms</h2></div>
            <div style="padding:16px">
                <p style="margin:0;font-size:9px;color:var(--text);white-space:pre-wrap"><?php echo e($template->terms); ?></p>
            </div>
        </section>
        <?php endif; ?>
        <?php if($template->booking_terms): ?>
        <section class="ops-panel">
            <div class="ops-panel-title"><h2>Booking Terms</h2></div>
            <div style="padding:16px">
                <p style="margin:0;font-size:9px;color:var(--text);white-space:pre-wrap"><?php echo e($template->booking_terms); ?></p>
            </div>
        </section>
        <?php endif; ?>
        <?php if($template->payment_schedule): ?>
        <section class="ops-panel">
            <div class="ops-panel-title"><h2>Payment Schedule</h2></div>
            <div style="padding:16px">
                <p style="margin:0;font-size:9px;color:var(--text);white-space:pre-wrap"><?php echo e($template->payment_schedule); ?></p>
            </div>
        </section>
        <?php endif; ?>
        <?php if($template->cancellation_policy): ?>
        <section class="ops-panel">
            <div class="ops-panel-title"><h2>Cancellation Policy</h2></div>
            <div style="padding:16px">
                <p style="margin:0;font-size:9px;color:var(--text);white-space:pre-wrap"><?php echo e($template->cancellation_policy); ?></p>
            </div>
        </section>
        <?php endif; ?>
        <?php if($template->refund_policy): ?>
        <section class="ops-panel">
            <div class="ops-panel-title"><h2>Refund Policy</h2></div>
            <div style="padding:16px">
                <p style="margin:0;font-size:9px;color:var(--text);white-space:pre-wrap"><?php echo e($template->refund_policy); ?></p>
            </div>
        </section>
        <?php endif; ?>
        <?php if($template->important_notes): ?>
        <section class="ops-panel">
            <div class="ops-panel-title"><h2>Important Notes</h2></div>
            <div style="padding:16px">
                <p style="margin:0;font-size:9px;color:var(--text);white-space:pre-wrap"><?php echo e($template->important_notes); ?></p>
            </div>
        </section>
        <?php endif; ?>
        <?php if(!$template->terms && !$template->booking_terms && !$template->payment_schedule && !$template->cancellation_policy && !$template->refund_policy && !$template->important_notes): ?>
        <section class="ops-panel" style="grid-column:1/-1">
            <div style="padding:16px;text-align:center;color:var(--text-muted);font-size:9px">No policies defined.</div>
        </section>
        <?php endif; ?>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var tabs = document.querySelectorAll('.tab-btn');
    tabs.forEach(function(tab) {
        tab.addEventListener('click', function() {
            var target = this.dataset.tab;
            tabs.forEach(function(t) {
                t.style.color = 'var(--text-muted)';
                t.style.borderBottomColor = 'transparent';
            });
            this.style.color = '#234A36';
            this.style.borderBottomColor = '#234A36';
            document.querySelectorAll('.tab-content').forEach(function(c) {
                c.style.display = 'none';
            });
            document.getElementById('tab-' + target).style.display = '';
        });
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\itinerary-templates\show.blade.php ENDPATH**/ ?>