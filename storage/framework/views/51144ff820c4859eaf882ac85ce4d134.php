<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['destinations' => collect(), 'selectedItinerary' => null, 'prefillDestination' => null, 'prefillInterest' => null, 'countryNames' => null, 'variant' => 'enquiry']));

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

foreach (array_filter((['destinations' => collect(), 'selectedItinerary' => null, 'prefillDestination' => null, 'prefillInterest' => null, 'countryNames' => null, 'variant' => 'enquiry']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $countries = collect($countryNames);
    if ($countries->isEmpty()) {
        $countries = collect($destinations)->pluck('name')->merge(['Kenya', 'Tanzania', 'Uganda', 'Rwanda', 'South Africa', 'Namibia', 'Botswana'])->unique()->values();
    }
    $safariPrefill = old('safari_type', request('safari_type', $prefillInterest ?: 'Tailor-made safari'));
    $isProposal = $variant === 'proposal';
?>

<form class="inquiry-form<?php echo e($isProposal ? ' inquiry-form--proposal' : ''); ?>" method="POST" action="<?php echo e(route('enquire')); ?>">
    <?php echo csrf_field(); ?>
    <?php if(session('success')): ?>
        <div class="form-success"><i data-lucide="circle-check"></i><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if($errors->any()): ?>
        <div class="form-errors"><i data-lucide="triangle-alert"></i><?php echo e($errors->first()); ?></div>
    <?php endif; ?>

    <?php if($selectedItinerary): ?>
        <div class="inquiry-itinerary-summary">
            <i data-lucide="map"></i>
            <div>
                <strong>Selected itinerary</strong>
                <span><?php echo e($selectedItinerary['title'] ?? 'Itinerary'); ?></span>
                <?php if(!empty($selectedItinerary['country'])): ?><span> · <?php echo e($selectedItinerary['country']); ?></span><?php endif; ?>
                <?php if(!empty($selectedItinerary['days'])): ?><span> · <?php echo e($selectedItinerary['days']); ?> days</span><?php endif; ?>
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
            <input type="hidden" name="destination_override" value="<?php echo e($selectedItinerary['country']); ?>">
            <?php endif; ?>
            <?php if(!empty($selectedItinerary['url'])): ?>
            <input type="hidden" name="itinerary_url" value="<?php echo e($selectedItinerary['url']); ?>">
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <fieldset>
        <legend>Your details</legend>
        <div class="field-row">
            <label><span>Full name</span><input name="name" value="<?php echo e(old('name')); ?>" required autocomplete="name"></label>
            <label><span>Email</span><input type="email" name="email" value="<?php echo e(old('email')); ?>" required autocomplete="email"></label>
        </div>
        <div class="field-row">
            <label><span>Phone / WhatsApp</span><input name="phone" value="<?php echo e(old('phone')); ?>" autocomplete="tel"></label>
            <label><span>Country of residence</span><input name="country" value="<?php echo e(old('country')); ?>" autocomplete="country-name"></label>
        </div>
    </fieldset>

    <fieldset>
        <legend><?php echo e($isProposal ? 'The journey you have in mind' : 'Your safari'); ?></legend>
        <div class="field-row">
            <label><span>Preferred destination</span>
                <select name="destination">
                    <option value="">Not sure yet</option>
                    <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $countryName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($countryName); ?>" <?php if(old('destination', $prefillDestination) === $countryName): echo 'selected'; endif; ?>><?php echo e($countryName); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <option value="Multi-country safari" <?php if(old('destination', $prefillDestination) === 'Multi-country safari'): echo 'selected'; endif; ?>>Multi-country safari</option>
                </select>
            </label>
            <label><span>Preferred start date</span><input type="date" name="travel_date" value="<?php echo e(old('travel_date', request('travel_date'))); ?>"></label>
        </div>
        <div class="field-row compact-travel-row">
            <label><span>Adults</span><input type="number" min="1" max="60" name="adults" value="<?php echo e(old('adults', request('adults', 2))); ?>" required></label>
            <label><span>Children</span><input type="number" min="0" max="60" name="children" value="<?php echo e(old('children', request('children', 0))); ?>"></label>
            <label><span>Nights</span><input type="number" min="3" max="60" name="nights" value="<?php echo e(old('nights', request('nights'))); ?>" placeholder="Flexible"></label>
        </div>
        <div class="field-row">
            <label><span>Budget per person</span>
                <select name="budget">
                    <option value="">To be discussed</option>
                    <option value="$3,000 - $5,000 per person" <?php if(old('budget', request('budget')) === '$3,000 - $5,000 per person'): echo 'selected'; endif; ?>>$3,000 – $5,000</option>
                    <option value="$5,000 - $8,000 per person" <?php if(old('budget', request('budget')) === '$5,000 - $8,000 per person'): echo 'selected'; endif; ?>>$5,000 – $8,000</option>
                    <option value="$8,000 - $12,000 per person" <?php if(old('budget', request('budget')) === '$8,000 - $12,000 per person'): echo 'selected'; endif; ?>>$8,000 – $12,000</option>
                    <option value="$12,000+ per person" <?php if(old('budget', request('budget')) === '$12,000+ per person'): echo 'selected'; endif; ?>>$12,000+</option>
                </select>
            </label>
            <label><span>Golf</span>
                <select name="golf_interest">
                    <option value="No golf" <?php if(old('golf_interest') === 'No golf'): echo 'selected'; endif; ?>>No golf</option>
                    <option value="A round or two" <?php if(old('golf_interest', request('safari_type') === 'Golf safari' ? 'A round or two' : '') === 'A round or two'): echo 'selected'; endif; ?>>A round or two</option>
                    <option value="Golf is central" <?php if(old('golf_interest') === 'Golf is central'): echo 'selected'; endif; ?>>Golf is central</option>
                </select>
            </label>
        </div>
        <label><span>Travel style</span>
            <select name="safari_type">
                <option value="Tailor-made safari" <?php if($safariPrefill === 'Tailor-made safari'): echo 'selected'; endif; ?>>Tailor-made safari</option>
                <option value="Golf safari" <?php if($safariPrefill === 'Golf safari'): echo 'selected'; endif; ?>>Golf safari</option>
                <option value="Family safari" <?php if($safariPrefill === 'Family safari'): echo 'selected'; endif; ?>>Family safari</option>
                <option value="Honeymoon safari" <?php if($safariPrefill === 'Honeymoon safari'): echo 'selected'; endif; ?>>Honeymoon safari</option>
                <option value="Private group safari" <?php if($safariPrefill === 'Private group safari'): echo 'selected'; endif; ?>>Private group safari</option>
                <option value="Luxury lodge safari" <?php if($safariPrefill === 'Luxury lodge safari'): echo 'selected'; endif; ?>>Luxury lodge safari</option>
                <option value="Gorilla and wildlife safari" <?php if($safariPrefill === 'Gorilla and wildlife safari'): echo 'selected'; endif; ?>>Gorilla and wildlife safari</option>
                <option value="Wellness and recovery retreat" <?php if($safariPrefill === 'Wellness and recovery retreat'): echo 'selected'; endif; ?>>Wellness and recovery retreat</option>
                <option value="Beach and coastal extension" <?php if($safariPrefill === 'Beach and coastal extension'): echo 'selected'; endif; ?>>Beach and coastal extension</option>
            </select>
        </label>
        <label><span>Anything a trip advisor should know?</span><textarea name="message" rows="4" placeholder="Season, lodges you love, celebrations, accessibility, or a country you are sure about"><?php echo e(old('message', request('message'))); ?></textarea></label>
    </fieldset>

    <button class="button inquiry-submit" type="submit"><?php echo e($isProposal ? 'Request a private proposal' : 'Send inquiry'); ?><i data-lucide="arrow-up-right"></i></button>
    <small class="privacy-note"><i data-lucide="lock-keyhole"></i>Sent securely to our safari team. No obligation.</small>
</form>
<?php /**PATH C:\shishifootsteps\safari\resources\views/components/public/inquiry-form.blade.php ENDPATH**/ ?>