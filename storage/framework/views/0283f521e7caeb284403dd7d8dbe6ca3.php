<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['destinations' => collect(), 'selectedItinerary' => null, 'prefillDestination' => null]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['destinations' => collect(), 'selectedItinerary' => null, 'prefillDestination' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<form class="inquiry-form" method="POST" action="<?php echo e(route('enquire')); ?>">
    <?php echo csrf_field(); ?>
    <?php if(session('success')): ?>
        <div class="form-success"><i data-lucide="circle-check"></i><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if($errors->any()): ?>
        <div class="form-errors"><i data-lucide="triangle-alert"></i><?php echo e($errors->first()); ?></div>
    <?php endif; ?>

    <?php if($selectedItinerary): ?>
        <div class="inquiry-itinerary-summary" style="margin:0 0 18px;padding:14px 16px;border:1px solid #e3d9c5;border-radius:10px;background:#faf6ec;display:flex;gap:12px;align-items:center;flex-wrap:wrap;font-size:11px">
            <i data-lucide="map"></i>
            <div style="flex:1;min-width:0">
                <strong>Selected Itinerary:</strong>
                <span><?php echo e($selectedItinerary['title'] ?? 'Itinerary'); ?></span>
                <?php if(!empty($selectedItinerary['country'])): ?><span> &middot; <strong>Country:</strong> <?php echo e($selectedItinerary['country']); ?></span><?php endif; ?>
                <?php if(!empty($selectedItinerary['days'])): ?><span> &middot; <?php echo e($selectedItinerary['days']); ?> days</span><?php endif; ?>
            </div>
            <?php if(!empty($selectedItinerary['id'])): ?>
            <input type="hidden" name="itinerary_id" value="<?php echo e($selectedItinerary['id']); ?>">
            <?php endif; ?>
            <?php if(!empty($selectedItinerary['slug'])): ?>
            <input type="hidden" name="itinerary_slug" value="<?php echo e($selectedItinerary['slug']); ?>">
            <?php endif; ?>
            <?php if(!empty($selectedItinerary['title'])): ?>
            <input type="hidden" name="itinerary_title" value="<?php echo e($selectedItinerary['title']); ?>">
            <?php endif; ?>
            <?php if(!empty($selectedItinerary['country'])): ?>
            <input type="hidden" name="destination_override" id="destinationOverride" value="<?php echo e($selectedItinerary['country']); ?>">
            <?php endif; ?>
            <?php if(!empty($selectedItinerary['url'])): ?>
            <input type="hidden" name="itinerary_url" value="<?php echo e($selectedItinerary['url']); ?>">
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <fieldset>
        <legend>Your details</legend>
        <div class="field-row">
            <label><span>Full Name</span><input name="name" value="<?php echo e(old('name')); ?>" required autocomplete="name"></label>
            <label><span>Email</span><input type="email" name="email" value="<?php echo e(old('email')); ?>" required autocomplete="email"></label>
        </div>
        <div class="field-row">
            <label><span>Phone / WhatsApp</span><input name="phone" value="<?php echo e(old('phone')); ?>" autocomplete="tel"></label>
            <label><span>Country of Residence</span><input name="country" value="<?php echo e(old('country')); ?>" autocomplete="country-name"></label>
        </div>
    </fieldset>

    <fieldset>
        <legend>Your safari</legend>
        <div class="field-row">
            <label><span>Preferred Destination</span>
                <select name="destination">
                    <option value="">Not sure yet</option>
                    <?php $__currentLoopData = $destinations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $destination): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($destination->name); ?>" <?php if(old('destination', $prefillDestination) === $destination->name): echo 'selected'; endif; ?>><?php echo e($destination->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <option value="Rwanda" <?php if(old('destination', $prefillDestination) === 'Rwanda'): echo 'selected'; endif; ?>>Rwanda</option>
                    <option value="Multi-country safari" <?php if(old('destination', $prefillDestination) === 'Multi-country safari'): echo 'selected'; endif; ?>>Multi-country safari</option>
                </select>
            </label>
            <label><span>Preferred Start Date</span><input type="date" name="travel_date" value="<?php echo e(old('travel_date', request('travel_date'))); ?>"></label>
        </div>
        <div class="field-row compact-travel-row">
            <label><span>Adults</span><input type="number" min="1" max="60" name="adults" value="<?php echo e(old('adults', request('adults', 2))); ?>" required></label>
            <label><span>Children</span><input type="number" min="0" max="60" name="children" value="<?php echo e(old('children', request('children', 0))); ?>"></label>
            <label><span>Budget Per Person</span>
                <select name="budget">
                    <option value="">Not decided</option>
                    <option value="$3,000 - $5,000 per person" <?php if(old('budget', request('budget')) === '$3,000 - $5,000 per person'): echo 'selected'; endif; ?>>$3,000 - $5,000</option>
                    <option value="$5,000 - $8,000 per person" <?php if(old('budget', request('budget')) === '$5,000 - $8,000 per person'): echo 'selected'; endif; ?>>$5,000 - $8,000</option>
                    <option value="$8,000 - $12,000 per person" <?php if(old('budget', request('budget')) === '$8,000 - $12,000 per person'): echo 'selected'; endif; ?>>$8,000 - $12,000</option>
                    <option value="$12,000+ per person" <?php if(old('budget', request('budget')) === '$12,000+ per person'): echo 'selected'; endif; ?>>$12,000+</option>
                </select>
            </label>
        </div>
        <label><span>Safari Type</span>
            <select name="safari_type">
                <option value="Tailor-made safari" <?php if(old('safari_type', request('safari_type', 'Tailor-made safari')) === 'Tailor-made safari'): echo 'selected'; endif; ?>>Tailor-made safari</option>
                <option <?php if(old('safari_type', request('safari_type')) === 'Family safari'): echo 'selected'; endif; ?>>Family safari</option>
                <option <?php if(old('safari_type', request('safari_type')) === 'Honeymoon safari'): echo 'selected'; endif; ?>>Honeymoon safari</option>
                <option <?php if(old('safari_type', request('safari_type')) === 'Private group safari'): echo 'selected'; endif; ?>>Private group safari</option>
                <option <?php if(old('safari_type', request('safari_type')) === 'Luxury lodge safari'): echo 'selected'; endif; ?>>Luxury lodge safari</option>
                <option <?php if(old('safari_type', request('safari_type')) === 'Gorilla and wildlife safari'): echo 'selected'; endif; ?>>Gorilla and wildlife safari</option>
                <option <?php if(old('safari_type', request('safari_type')) === 'Wellness and recovery retreat'): echo 'selected'; endif; ?>>Wellness and recovery retreat</option>
                <option <?php if(old('safari_type', request('safari_type')) === 'Sports and adventure travel'): echo 'selected'; endif; ?>>Sports and adventure travel</option>
                <option <?php if(old('safari_type', request('safari_type')) === 'Beach and coastal extension'): echo 'selected'; endif; ?>>Beach and coastal extension</option>
            </select>
        </label>
        <label><span>Anything else?</span><textarea name="message" rows="4" placeholder="Special interests, celebrations or accessibility needs"><?php echo e(old('message', request('message'))); ?></textarea></label>
    </fieldset>

    <button class="button inquiry-submit">Send Inquiry<i data-lucide="arrow-up-right"></i></button>
    <small class="privacy-note"><i data-lucide="lock-keyhole"></i>Sent securely to our safari team.</small>
</form>
<?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\components\public\inquiry-form.blade.php ENDPATH**/ ?>