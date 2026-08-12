<?php $__env->startSection('title', 'Website Requests'); ?>
<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginalad67f78bf768badc17b2fc4005a4f8bf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad67f78bf768badc17b2fc4005a4f8bf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.top-bar','data' => ['title' => 'Leads','description' => 'CRM','addButton' => false,'searchPlaceholder' => 'Search leads...']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.top-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Leads','description' => 'CRM','addButton' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'searchPlaceholder' => 'Search leads...']); ?>
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
<section class="pipeline-summary">
    <?php $__currentLoopData = ['new','assigned','contacted','qualified','quotation','won','on_trip','completed']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('admin.legacy-leads.index', ['status' => $stage])); ?>"><span><?php echo e(DB::table('website_enquiries')->where('lifecycle_status', $stage)->count()); ?></span><small><?php echo e(ucwords(str_replace('_', ' ', $stage))); ?></small></a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</section>
<section class="ops-panel">
    <form class="ops-filters" method="GET">
        <select name="status"><option value="">All pipeline stages</option><?php $__currentLoopData = ['new','assigned','contacted','qualified','quotation','won','lost','on_trip','completed']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($stage); ?>" <?php if(request('status') === $stage): echo 'selected'; endif; ?>><?php echo e(ucwords(str_replace('_', ' ', $stage))); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select>
        <select name="assigned_to"><option value="">All owners</option><?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option <?php if(request('assigned_to') === $user->name): echo 'selected'; endif; ?>><?php echo e($user->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select>
        <button class="button button-primary">Filter</button>
    </form>
    <div class="table-wrap"><table class="ops-table leads-table"><thead><tr><th>Request</th><th>Travel plan</th><th>Source</th><th>Owner & next action</th><th>Stage</th><th>Value</th><th>Actions</th></tr></thead><tbody>
    <?php $__empty_1 = true; $__currentLoopData = $leads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lead): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
            <td><strong><?php echo e($lead->name); ?></strong><small><?php echo e($lead->email); ?> · <?php echo e($lead->country); ?></small><p><?php echo e(\Illuminate\Support\Str::limit($lead->message, 90)); ?></p></td>
            <td><?php echo e($lead->destination ?: 'Tailor-made'); ?><small><?php echo e($lead->travelers); ?> travelers · <?php echo e($lead->travel_date ? \Carbon\Carbon::parse($lead->travel_date)->format('d M Y') : 'Flexible dates'); ?></small></td>
            <td><span class="ops-pill ops-pill--blue"><?php echo e(ucfirst($lead->source)); ?></span><small><?php echo e(strtoupper($lead->language_code)); ?></small></td>
            <td>
                <form class="lead-update-form" method="POST" action="<?php echo e(route('admin.legacy-leads.update', $lead->id)); ?>"><?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                    <select name="assigned_to"><option value="">Unassigned</option><?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option <?php if($lead->assigned_to === $user->name): echo 'selected'; endif; ?>><?php echo e($user->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select>
                    <input type="datetime-local" name="next_follow_up_at" value="<?php echo e($lead->next_follow_up_at ? \Carbon\Carbon::parse($lead->next_follow_up_at)->format('Y-m-d\TH:i') : ''); ?>">
            </td>
            <td><select name="lifecycle_status"><?php $__currentLoopData = ['new','assigned','contacted','qualified','quotation','won','lost','on_trip','completed']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($stage); ?>" <?php if($lead->lifecycle_status === $stage): echo 'selected'; endif; ?>><?php echo e(ucwords(str_replace('_', ' ', $stage))); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></td>
            <td><input class="money-input" type="number" step="0.01" name="estimated_value" value="<?php echo e($lead->estimated_value); ?>"><input type="hidden" name="message" value="<?php echo e($lead->message); ?>"></td>
            <td><div class="ops-actions"><button title="Save follow-up"><i data-lucide="save"></i></button></form><?php if(!$lead->converted_quotation_id): ?><form method="POST" action="<?php echo e(route('admin.legacy-leads.convert', $lead->id)); ?>"><?php echo csrf_field(); ?><button class="convert-button" title="Create quotation"><i data-lucide="file-plus-2"></i></button></form><?php else: ?><a href="<?php echo e(route('admin.quotations.show', $lead->converted_quotation_id)); ?>" title="Open quotation"><i data-lucide="external-link"></i></a><?php endif; ?></div></td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="7" class="empty-cell">No requests in this stage.</td></tr><?php endif; ?>
    </tbody></table></div><div class="ops-pagination"><?php echo e($leads->links()); ?></div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\leads\index.blade.php ENDPATH**/ ?>