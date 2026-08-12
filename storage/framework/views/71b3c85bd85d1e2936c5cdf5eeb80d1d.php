<?php $__env->startSection('title', 'Shishi Footsteps Booking Form | Shishi Footsteps'); ?>
<?php $__env->startSection('description', 'Complete your Shishi Footsteps booking details securely online.'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $clientName = old('main_full_name', $proposal->client_name ?? '');
    $clientEmail = old('main_email', $proposal->client_email ?? '');
    $clientPhone = old('main_phone', $proposal->client_phone ?? '');
?>

<style>
    .booking-online{background:#F8F5EF;color:#2F2F2F}
    .booking-hero{padding:clamp(54px,8vw,110px) 5vw;background:linear-gradient(135deg,rgba(35,74,54,.94),rgba(35,74,54,.78)),url('https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=1800&q=82&fm=webp') center/cover;color:#fff}
    .booking-hero__inner{max-width:1180px;margin:auto;display:grid;grid-template-columns:minmax(0,1fr) 360px;gap:34px;align-items:end}
    .booking-eyebrow{display:inline-flex;align-items:center;gap:10px;margin:0 0 18px;font-weight:900;letter-spacing:.18em;text-transform:uppercase;color:#C8A96A}
    .booking-eyebrow:before{content:"";width:44px;height:3px;background:#C8A96A}
    .booking-hero h1{margin:0;font-size:clamp(42px,6vw,86px);line-height:.95;letter-spacing:-.04em}
    .booking-hero p{max-width:760px;margin:22px 0 0;font-size:clamp(17px,2vw,24px);line-height:1.6;color:rgba(255,255,255,.88)}
    .booking-card{padding:24px;border:1px solid rgba(200,169,106,.45);border-radius:28px;background:rgba(248,245,239,.12);backdrop-filter:blur(10px)}
    .booking-card strong{display:block;font-size:18px}.booking-card span{display:block;margin-top:8px;color:rgba(255,255,255,.78)}
    .booking-wrap{max-width:1180px;margin:-38px auto 90px;padding:0 5vw;position:relative}
    .booking-form{padding:clamp(22px,4vw,46px);border-radius:32px;background:#fff;box-shadow:0 22px 70px rgba(35,74,54,.12)}
    .booking-section{padding:26px 0;border-bottom:1px solid #eadfca}.booking-section:first-child{padding-top:0}.booking-section:last-child{border-bottom:0}
    .booking-section h2{margin:0 0 8px;color:#234A36;font-size:clamp(24px,3vw,38px);line-height:1}
    .booking-section p{margin:0 0 20px;color:#67645d;font-size:15px;line-height:1.6}
    .booking-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}
    .booking-field{display:flex;flex-direction:column;gap:7px}.booking-field.full{grid-column:1/-1}
    .booking-field label,.booking-label{font-size:12px;font-weight:900;letter-spacing:.08em;text-transform:uppercase;color:#234A36}
    .booking-field input,.booking-field select,.booking-field textarea{width:100%;min-height:52px;padding:13px 15px;border:1px solid #D8C7A2;border-radius:16px;background:#F8F5EF;color:#2F2F2F;font:inherit;font-size:16px;outline:none;transition:.2s}
    .booking-field textarea{min-height:120px;resize:vertical}.booking-field input:focus,.booking-field select:focus,.booking-field textarea:focus{border-color:#234A36;box-shadow:0 0 0 4px rgba(35,74,54,.1)}
    .choice-row{display:flex;flex-wrap:wrap;gap:10px}.choice-row label{display:flex;align-items:center;gap:8px;min-height:48px;padding:0 15px;border:1px solid #D8C7A2;border-radius:999px;background:#F8F5EF;font-weight:800;color:#234A36}
    .traveller-list{display:grid;gap:12px}.traveller-card{padding:16px;border:1px dashed #D8C7A2;border-radius:22px;background:#F8F5EF}.traveller-card__top{display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;color:#234A36;font-weight:900}
    .booking-actions{display:flex;flex-wrap:wrap;gap:12px;margin-top:28px}.booking-btn{border:0;border-radius:999px;padding:16px 26px;background:#234A36;color:#fff;font-weight:900;letter-spacing:.06em;text-transform:uppercase;cursor:pointer;box-shadow:0 12px 26px rgba(35,74,54,.22);transition:.2s}.booking-btn:hover{transform:translateY(-2px);background:#1d3d2d}.booking-btn.secondary{background:#C8A96A;color:#234A36;box-shadow:none}.booking-btn.ghost{background:#F8F5EF;color:#234A36;box-shadow:none;border:1px solid #D8C7A2}
    .booking-success{margin-bottom:18px;padding:16px 18px;border-radius:18px;background:#eaf7ee;color:#234A36;font-weight:800}.booking-errors{margin-bottom:18px;padding:16px 18px;border-radius:18px;background:#fff0f0;color:#8a1f1f}
    @media(max-width:780px){.booking-hero__inner,.booking-grid{grid-template-columns:1fr}.booking-wrap{margin-top:-24px}.booking-form{border-radius:24px}.booking-card{display:none}}
</style>

<main class="booking-online">
    <section class="booking-hero">
        <div class="booking-hero__inner">
            <div>
                <p class="booking-eyebrow">Secure online form</p>
                <h1>Shishi Footsteps Booking Form</h1>
                <p>Fill in the details we need for your safari booking. You can add every traveller online and upload passport copies securely.</p>
            </div>
            <?php if($proposal): ?>
                <aside class="booking-card">
                    <strong><?php echo e($proposal->reference); ?></strong>
                    <span><?php echo e($proposal->title); ?></span>
                    <span><?php echo e($proposal->guest_count); ?> guests · <?php echo e($proposal->duration_days); ?> days</span>
                </aside>
            <?php endif; ?>
        </div>
    </section>

    <section class="booking-wrap">
        <?php if(session('success')): ?>
            <div class="booking-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <?php if($errors->any()): ?>
            <div class="booking-errors">Please check the highlighted details and try again.</div>
        <?php endif; ?>

        <form class="booking-form" method="POST" action="<?php echo e(route('public.booking.form.submit', $token)); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>

            <div class="booking-section">
                <h2>Main booker</h2>
                <p>Personal details as shown on the passport.</p>
                <div class="booking-grid">
                    <div class="booking-field"><label>Full name *</label><input name="main_full_name" value="<?php echo e($clientName); ?>" required></div>
                    <div class="booking-field"><label>Email *</label><input type="email" name="main_email" value="<?php echo e($clientEmail); ?>" required></div>
                    <div class="booking-field"><label>Phone</label><input name="main_phone" value="<?php echo e($clientPhone); ?>"></div>
                    <div class="booking-field"><label>Date of birth</label><input type="date" name="main_date_of_birth" value="<?php echo e(old('main_date_of_birth')); ?>"></div>
                    <div class="booking-field"><label>Sex</label><select name="main_sex"><option value="">Select</option><option <?php if(old('main_sex')==='Female'): echo 'selected'; endif; ?>>Female</option><option <?php if(old('main_sex')==='Male'): echo 'selected'; endif; ?>>Male</option><option <?php if(old('main_sex')==='Prefer not to say'): echo 'selected'; endif; ?>>Prefer not to say</option></select></div>
                    <div class="booking-field"><label>Country</label><input name="country" value="<?php echo e(old('country', $proposal->client_country ?? '')); ?>"></div>
                    <div class="booking-field full"><label>Address</label><input name="address" value="<?php echo e(old('address')); ?>"></div>
                    <div class="booking-field"><label>City</label><input name="city" value="<?php echo e(old('city')); ?>"></div>
                    <div class="booking-field"><label>ZIP code</label><input name="zip_code" value="<?php echo e(old('zip_code')); ?>"></div>
                    <div class="booking-field full"><label>Preferred payment method</label><input name="payment_method" value="<?php echo e(old('payment_method')); ?>" placeholder="Bank transfer, card, Wise, other..."></div>
                </div>
            </div>

            <div class="booking-section">
                <h2>Emergency contact</h2>
                <p>One person who is not joining the trip.</p>
                <div class="booking-grid">
                    <div class="booking-field"><label>Full name</label><input name="emergency_name" value="<?php echo e(old('emergency_name')); ?>"></div>
                    <div class="booking-field"><label>Email</label><input type="email" name="emergency_email" value="<?php echo e(old('emergency_email')); ?>"></div>
                    <div class="booking-field full"><label>Phone</label><input name="emergency_phone" value="<?php echo e(old('emergency_phone')); ?>"></div>
                </div>
            </div>

            <div class="booking-section">
                <h2>Travel documents</h2>
                <p>Upload passport copies for the main booker and travellers. PDF, JPG or PNG accepted.</p>
                <div class="booking-field full"><label>Passport files</label><input type="file" name="passport_files[]" multiple accept=".pdf,.jpg,.jpeg,.png,.webp"></div>
            </div>

            <div class="booking-section">
                <h2>Flight details</h2>
                <p>Please use the direct flight number for the final flight into/out of the safari country.</p>
                <div class="booking-grid">
                    <div class="booking-field"><label>Arrival date</label><input type="date" name="arrival_date" value="<?php echo e(old('arrival_date', $proposal->start_date ?? '')); ?>"></div>
                    <div class="booking-field"><label>Arrival time</label><input name="arrival_time" value="<?php echo e(old('arrival_time')); ?>" placeholder="e.g. 21:35"></div>
                    <div class="booking-field"><label>Arrival airport / pickup</label><input name="arrival_airport" value="<?php echo e(old('arrival_airport')); ?>"></div>
                    <div class="booking-field"><label>Arrival flight number</label><input name="arrival_flight_number" value="<?php echo e(old('arrival_flight_number')); ?>"></div>
                    <div class="booking-field"><label>Early check-in needed?</label><select name="early_checkin"><option value="no">No</option><option value="yes" <?php if(old('early_checkin')==='yes'): echo 'selected'; endif; ?>>Yes</option></select></div>
                    <div></div>
                    <div class="booking-field"><label>Departure date</label><input type="date" name="departure_date" value="<?php echo e(old('departure_date', $proposal->end_date ?? '')); ?>"></div>
                    <div class="booking-field"><label>Departure time</label><input name="departure_time" value="<?php echo e(old('departure_time')); ?>" placeholder="e.g. 23:10"></div>
                    <div class="booking-field"><label>Departure airport / drop-off</label><input name="departure_airport" value="<?php echo e(old('departure_airport')); ?>"></div>
                    <div class="booking-field"><label>Departure flight number</label><input name="departure_flight_number" value="<?php echo e(old('departure_flight_number')); ?>"></div>
                    <div class="booking-field"><label>Late check-out needed?</label><select name="late_checkout"><option value="no">No</option><option value="yes" <?php if(old('late_checkout')==='yes'): echo 'selected'; endif; ?>>Yes</option></select></div>
                </div>
            </div>

            <div class="booking-section">
                <h2>Extras during your safari</h2>
                <div class="booking-grid">
                    <div class="booking-field"><label>Flying Doctors</label><select name="flying_doctors"><option value="no">No</option><option value="yes" <?php if(old('flying_doctors')==='yes'): echo 'selected'; endif; ?>>Yes</option></select></div>
                    <div class="booking-field"><label>For how many people?</label><input type="number" min="0" name="flying_doctors_people" value="<?php echo e(old('flying_doctors_people', 0)); ?>"></div>
                    <div class="booking-field"><label>Soft drinks and snacks</label><select name="soft_drinks"><option value="no">No</option><option value="yes" <?php if(old('soft_drinks')==='yes'): echo 'selected'; endif; ?>>Yes</option></select></div>
                    <div class="booking-field"><label>For how many people?</label><input type="number" min="0" name="soft_drinks_people" value="<?php echo e(old('soft_drinks_people', 0)); ?>"></div>
                    <div class="booking-field"><label>Binoculars</label><select name="binoculars"><option value="no">No</option><option value="yes" <?php if(old('binoculars')==='yes'): echo 'selected'; endif; ?>>Yes</option></select></div>
                    <div class="booking-field"><label>How many?</label><input type="number" min="0" name="binoculars_count" value="<?php echo e(old('binoculars_count', 0)); ?>"></div>
                    <div class="booking-field"><label>Baby seats</label><select name="baby_seats"><option value="no">No</option><option value="yes" <?php if(old('baby_seats')==='yes'): echo 'selected'; endif; ?>>Yes</option></select></div>
                    <div class="booking-field"><label>How many?</label><input type="number" min="0" name="baby_seats_count" value="<?php echo e(old('baby_seats_count', 0)); ?>"></div>
                </div>
            </div>

            <div class="booking-section">
                <h2>Other travellers</h2>
                <p>Add as many travellers as needed. Names should match passports.</p>
                <div id="travellerList" class="traveller-list"></div>
                <div class="booking-actions"><button type="button" class="booking-btn secondary" id="addTravellerBtn">Add traveller</button></div>
            </div>

            <div class="booking-section">
                <h2>Anything else?</h2>
                <div class="booking-field full"><label>Notes for Shishi</label><textarea name="notes" placeholder="Dietary requests, rooming notes, medical notes, special celebrations..."><?php echo e(old('notes')); ?></textarea></div>
            </div>

            <div class="booking-actions">
                <button class="booking-btn" type="submit">Submit booking form</button>
                <?php if($token): ?><a class="booking-btn ghost" href="<?php echo e(route('proposal.client', $token)); ?>">Back to proposal</a><?php endif; ?>
            </div>
        </form>
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var list = document.getElementById('travellerList');
    var addBtn = document.getElementById('addTravellerBtn');
    var index = 0;

    function addTraveller(data) {
        data = data || {};
        var card = document.createElement('div');
        card.className = 'traveller-card';
        card.innerHTML =
            '<div class="traveller-card__top"><span>Traveller ' + (index + 1) + '</span><button type="button" class="booking-btn ghost remove-traveller" style="padding:9px 14px">Remove</button></div>' +
            '<div class="booking-grid">' +
                '<div class="booking-field"><label>Full name</label><input name="travellers[' + index + '][full_name]" value="' + (data.full_name || '') + '"></div>' +
                '<div class="booking-field"><label>Date of birth</label><input type="date" name="travellers[' + index + '][date_of_birth]" value="' + (data.date_of_birth || '') + '"></div>' +
                '<div class="booking-field full"><label>Sex</label><select name="travellers[' + index + '][sex]"><option value="">Select</option><option>Female</option><option>Male</option><option>Prefer not to say</option></select></div>' +
            '</div>';
        card.querySelector('.remove-traveller').addEventListener('click', function () { card.remove(); });
        list.appendChild(card);
        index++;
    }

    addBtn.addEventListener('click', function () { addTraveller(); });
    addTraveller();
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\public\booking-form.blade.php ENDPATH**/ ?>