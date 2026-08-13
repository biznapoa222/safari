<?php ($global = fn($key,$fallback='') => \App\Models\CmsContentBlock::value('global',$key,$fallback)); ?>
<footer class="public-footer reference-footer">
    <section class="footer-identity">
        <a href="<?php echo e(route('home')); ?>" class="footer-logo" aria-label="Shishi Footsteps home"><img src="<?php echo e(asset('images/brand/shishi-footsteps-white.png')); ?>" alt="Shishi Footsteps"></a>

        <a class="footer-phone" href="tel:<?php echo e(preg_replace('/[^+0-9]/','',$global('phone','+254 725 346 022'))); ?>"><i data-lucide="phone"></i><?php echo e($global('phone','+254 725 346 022')); ?></a>

        <div class="footer-trust" aria-label="Travel memberships and awards" style="display:none">
            <span><b>SGR</b><small>Safari specialist</small></span>
            <span><b>ATTA</b><small>Member</small></span>
            <span class="footer-choice"><i data-lucide="binoculars"></i><b>Travelers’</b><small>Choice 2026</small></span>
        </div>

        <p>&copy; <?php echo e(date('Y')); ?> <?php echo e($global('company_name','Shishi Footsteps')); ?> Ltd.</p>
        <nav class="footer-legal">
            <a href="<?php echo e(route('public.about')); ?>#terms">Terms and conditions</a>
            <a href="<?php echo e(route('public.about')); ?>#privacy">Privacy policy</a>
            <a href="<?php echo e(route('public.contact')); ?>">Contact us</a>
        </nav>
    </section>

    <nav class="footer-link-column" aria-label="Our trips">
        <h2>Our trips</h2>
        <?php $__currentLoopData = ['Kenya','Tanzania','Uganda','Rwanda','South Africa','Namibia','Botswana']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('public.destinations.show', \Illuminate\Support\Str::slug($country))); ?>"><?php echo e($country); ?> tours</a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('public.safaris')); ?>">All safari trips</a>
        <a href="<?php echo e(route('public.golf')); ?>">Golf safaris</a>
        <a href="<?php echo e(route('public.experiences')); ?>">Activities</a>
        <a href="<?php echo e(route('public.accommodations')); ?>">Accommodations</a>
        <a href="<?php echo e(route('public.itineraries')); ?>">Sample itineraries</a>
    </nav>

    <nav class="footer-link-column" aria-label="Travel information">
        <h2>Travel information</h2>
        <a href="<?php echo e(route('public.about')); ?>">Practical information</a>
        <a href="<?php echo e(route('public.about')); ?>#guarantees">Our guarantees</a>
        <a href="<?php echo e(route('public.about')); ?>#responsible-travel">Responsible travel</a>
        <a href="<?php echo e(route('public.contact')); ?>">Flying Doctors</a>
        <a href="<?php echo e(route('public.about')); ?>">About Shishi Footsteps</a>
        <a href="<?php echo e(route('public.contact')); ?>#faq">Frequently asked questions</a>
        <a href="<?php echo e(route('public.blog')); ?>">Travel stories</a>
        <a href="<?php echo e(route('public.booking')); ?>">Plan your safari</a>
    </nav>

    <section class="footer-newsletter">
        <h2><?php echo e($global('footer_heading','Get safari inspiration')); ?></h2>
        <p><?php echo e($global('footer_text','Get travel ideas, destination tips and new safari journeys sent to your inbox.')); ?></p>
        <?php if(session('newsletter_success')): ?><p class="newsletter-success"><?php echo e(session('newsletter_success')); ?></p><?php endif; ?>
        <form action="<?php echo e(route('public.newsletter')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div>
                <input type="text" name="newsletter_name" value="<?php echo e(old('newsletter_name')); ?>" placeholder="Your name" aria-label="Your name">
                <input type="email" name="newsletter_email" value="<?php echo e(old('newsletter_email')); ?>" placeholder="Your e-mail" aria-label="Your email" required>
            </div>
            <footer>
                <small>By joining, you agree to our <a href="<?php echo e(route('public.about')); ?>#privacy">privacy policy</a>.</small>
                <button type="submit">Join us</button>
            </footer>
        </form>

        <h2 class="social-heading">Social media</h2>
        <div class="footer-socials">
            <a href="https://www.youtube.com/" target="_blank" rel="noopener" aria-label="YouTube"><i data-lucide="youtube"></i></a>
            <a href="https://www.tripadvisor.com/" target="_blank" rel="noopener" aria-label="Tripadvisor"><b>TA</b></a>
            <a href="https://www.google.com/" target="_blank" rel="noopener" aria-label="Google"><b>G</b></a>
            <a href="https://www.instagram.com/" target="_blank" rel="noopener" aria-label="Instagram"><i data-lucide="instagram"></i></a>
            <a href="https://www.facebook.com/" target="_blank" rel="noopener" aria-label="Facebook"><i data-lucide="facebook"></i></a>
            <a href="https://www.linkedin.com/" target="_blank" rel="noopener" aria-label="LinkedIn"><i data-lucide="linkedin"></i></a>
        </div>
        <p class="footer-address"><?php echo e($global('address','Nairobi, Kenya')); ?><br><a href="mailto:<?php echo e($global('email','info@shishifootsteps.com')); ?>"><?php echo e($global('email','info@shishifootsteps.com')); ?></a></p>
    </section>
</footer>
<?php /**PATH C:\shishifootsteps\safari\resources\views/components/public/footer.blade.php ENDPATH**/ ?>