@php($global = fn($key,$fallback='') => \App\Models\CmsContentBlock::value('global',$key,$fallback))
<footer class="public-footer reference-footer">
    <section class="footer-identity">
        <a href="{{ route('home') }}" class="footer-logo" aria-label="Shishi Footsteps home"><img src="{{ asset('images/brand/shishi-footsteps-white.png') }}" alt="Shishi Footsteps"></a>

        <a class="footer-phone" href="tel:{{ preg_replace('/[^+0-9]/','',$global('phone','+254 725 346 022')) }}"><i data-lucide="phone"></i>{{ $global('phone','+254 725 346 022') }}</a>

        <div class="footer-trust" aria-label="Travel memberships and awards" style="display:none">
            <span><b>SGR</b><small>Safari specialist</small></span>
            <span><b>ATTA</b><small>Member</small></span>
            <span class="footer-choice"><i data-lucide="binoculars"></i><b>Travelers’</b><small>Choice 2026</small></span>
        </div>

        <p>&copy; {{ date('Y') }} {{ $global('company_name','Shishi Footsteps') }} Ltd.</p>
        <nav class="footer-legal">
            <a href="{{ route('public.about') }}#terms">Terms and conditions</a>
            <a href="{{ route('public.about') }}#privacy">Privacy policy</a>
            <a href="{{ route('public.contact') }}">Contact us</a>
        </nav>
    </section>

    <nav class="footer-link-column" aria-label="Our trips">
        <h2>Our trips</h2>
        @foreach(['Kenya','Tanzania','Uganda','Rwanda','South Africa','Namibia','Botswana'] as $country)
            <a href="{{ route('public.destinations.show', \Illuminate\Support\Str::slug($country)) }}">{{ $country }} tours</a>
        @endforeach
        <a href="{{ route('public.safaris') }}">All safari trips</a>
        <a href="{{ route('public.golf') }}">Golf safaris</a>
        <a href="{{ route('public.experiences') }}">Activities</a>
        <a href="{{ route('public.accommodations') }}">Accommodations</a>
        <a href="{{ route('public.itineraries') }}">Sample itineraries</a>
    </nav>

    <nav class="footer-link-column" aria-label="Travel information">
        <h2>Travel information</h2>
        <a href="{{ route('public.about') }}">Practical information</a>
        <a href="{{ route('public.about') }}#guarantees">Our guarantees</a>
        <a href="{{ route('public.about') }}#responsible-travel">Responsible travel</a>
        <a href="{{ route('public.contact') }}">Flying Doctors</a>
        <a href="{{ route('public.about') }}">About Shishi Footsteps</a>
        <a href="{{ route('public.contact') }}#faq">Frequently asked questions</a>
        <a href="{{ route('public.blog') }}">Travel stories</a>
        <a href="{{ route('public.booking') }}">Plan your safari</a>
    </nav>

    <section class="footer-newsletter">
        <h2>{{ $global('footer_heading','Get safari inspiration') }}</h2>
        <p>{{ $global('footer_text','Get travel ideas, destination tips and new safari journeys sent to your inbox.') }}</p>
        @if(session('newsletter_success'))<p class="newsletter-success">{{ session('newsletter_success') }}</p>@endif
        <form action="{{ route('public.newsletter') }}" method="POST">
            @csrf
            <div>
                <input type="text" name="newsletter_name" value="{{ old('newsletter_name') }}" placeholder="Your name" aria-label="Your name">
                <input type="email" name="newsletter_email" value="{{ old('newsletter_email') }}" placeholder="Your e-mail" aria-label="Your email" required>
            </div>
            <footer>
                <small>By joining, you agree to our <a href="{{ route('public.about') }}#privacy">privacy policy</a>.</small>
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
        <p class="footer-address">{{ $global('address','Nairobi, Kenya') }}<br><a href="mailto:{{ $global('email','info@shishifootsteps.com') }}">{{ $global('email','info@shishifootsteps.com') }}</a></p>
    </section>
</footer>
