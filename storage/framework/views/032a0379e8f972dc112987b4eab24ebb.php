<form class="itinerary-day-form" method="POST" enctype="multipart/form-data" action="<?php echo e($day ? route('admin.itineraries.days.update', [$itinerary, $day]) : route('admin.itineraries.days.store', $itinerary)); ?>">
    <?php echo csrf_field(); ?> <?php if($day): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
    <div class="ops-form-grid">
        <label>Day number<input type="number" name="day_number" value="<?php echo e($day?->day_number); ?>" min="1" placeholder="Next"></label>
        <label>Day title<input name="title" value="<?php echo e($day?->title); ?>" placeholder="Arrival in Nairobi" required></label>
        <label>Location<input name="location" value="<?php echo e($day?->location); ?>" placeholder="Nairobi"></label>
        <label>Overnight location<input name="overnight" value="<?php echo e($day?->overnight); ?>" placeholder="Nairobi"></label>
        <label>Accommodation<input name="accommodation" value="<?php echo e($day?->accommodation); ?>" placeholder="Hotel or safari camp"></label>
        <label>Meal plan<input name="meal_plan" value="<?php echo e($day?->meal_plan); ?>" placeholder="Breakfast, lunch and dinner"></label>
        <label>Distance (km)<input type="number" name="distance_km" value="<?php echo e($day?->distance_km); ?>" min="0"></label>
        <label>Driving hours<input type="number" step="0.25" name="driving_hours" value="<?php echo e($day?->driving_hours); ?>" min="0" max="24"></label>
        <label class="span-2">Day summary<textarea name="summary" rows="2"><?php echo e($day?->summary); ?></textarea></label>
        <label class="span-2">Detailed day description<textarea name="description" rows="6"><?php echo e($day?->description); ?></textarea></label>
        <label>Activities, one per line<textarea name="activities_text" rows="5"><?php echo e(implode("\n", $day?->activities ?? [])); ?></textarea></label>
        <label>Primary day image<input type="file" name="primary_image_upload" accept="image/jpeg,image/png,image/webp"></label>
        <label class="span-2">Additional day gallery<input type="file" name="images[]" accept="image/jpeg,image/png,image/webp" multiple></label>
        <label>Gallery caption<input name="caption" placeholder="Optional shared caption"></label>
        <label>Photo credit<input name="credit" placeholder="Optional credit"></label>
    </div>
    <div class="ops-form-footer"><button class="button button-primary"><i data-lucide="save"></i><?php echo e($day ? 'Save day' : 'Add day'); ?></button></div>
</form>
<?php /**PATH C:\shishifootsteps\safari\resources\views/admin/itineraries/partials/day-form.blade.php ENDPATH**/ ?>