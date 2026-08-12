@extends('layouts.public')

@section('title', 'Frequently Asked Questions | Shishi Footsteps')
@section('description', 'Find answers to common questions about luxury golf tours, safari holidays, bookings, travel planning and experiences with Shishi Footsteps.')

@section('content')
@php($cms = fn($key, $fallback='') => \App\Models\CmsContentBlock::value('faqs', $key, $fallback))
@php($global = fn($key, $fallback='') => \App\Models\CmsContentBlock::value('global', $key, $fallback))

<x-public.page-hero label="FAQs" :title="$cms('hero_title', 'Frequently Asked Questions')" :subtitle="$cms('hero_subtitle', 'Everything you need to know about planning your African golf safari and travel experiences.')" :image="\App\Support\MediaPath::publicUrl($cms('hero_image'))" />

<section class="faqs-editorial" id="start">
    <div>
        <x-public.section-label label="How Can We Help?" />
        <h2>{{ $cms('editorial_title', 'Your questions, answered') }}</h2>
    </div>
    <p>{{ $cms('editorial_text', 'Whether you are planning a golf tour, combining a safari with your rounds, or arranging a complete African holiday, we are here to make the process effortless. Below you will find answers to the most common questions our travellers ask.') }}</p>
</section>

<section class="faqs-content-band" id="faqs">
    <div class="faqs-grid">

        <div class="faqs-column">
            <h3 class="faqs-category-label">Booking & Planning</h3>

            <details class="faq-item" open>
                <summary class="faq-question"><span>How do I start planning a golf tour with Shishi Footsteps?</span><i data-lucide="chevron-down"></i></summary>
                <div class="faq-answer"><p>Simply complete our enquiry form or contact us directly by email, phone or WhatsApp. Share your preferred travel dates, destinations, group size and any golf or non-golf interests. Our team will create a personalised itinerary within a few working days.</p></div>
            </details>

            <details class="faq-item">
                <summary class="faq-question"><span>How far in advance should I book?</span><i data-lucide="chevron-down"></i></summary>
                <div class="faq-answer"><p>We recommend booking three to six months in advance for peak seasons such as the Great Migration period in East Africa, the South African summer or holiday periods. For off-peak travel, six to eight weeks is usually sufficient. Early booking also secures preferred tee times and accommodation.</p></div>
            </details>

            <details class="faq-item">
                <summary class="faq-question"><span>Can I customise an existing itinerary?</span><i data-lucide="chevron-down"></i></summary>
                <div class="faq-answer"><p>Every itinerary we offer is a starting point. We tailor each journey to your interests, pace, budget and travel style. You can add safari days, beach extensions, cultural experiences, wellness activities or additional golf rounds at any stage of planning.</p></div>
            </details>

            <details class="faq-item">
                <summary class="faq-question"><span>Do you offer group and corporate golf travel?</span><i data-lucide="chevron-down"></i></summary>
                <div class="faq-answer"><p>Yes. We arrange group golf tours for golf clubs, corporate groups, incentive trips and special occasions. Our team handles logistics including transport, tee time coordination, group accommodation, dining, team-building activities and event management.</p></div>
            </details>

            <details class="faq-item">
                <summary class="faq-question"><span>Is there a minimum or maximum group size?</span><i data-lucide="chevron-down"></i></summary>
                <div class="faq-answer"><p>We welcome solo travellers, couples, families and groups of any size. Larger groups benefit from dedicated vehicles, private guides and group-specific arrangements. There is no strict maximum as long as we can coordinate suitable logistics.</p></div>
            </details>
        </div>

        <div class="faqs-column">
            <h3 class="faqs-category-label">Golf & Courses</h3>

            <details class="faq-item">
                <summary class="faq-question"><span>Do I need to bring my own golf clubs?</span><i data-lucide="chevron-down"></i></summary>
                <div class="faq-answer"><p>You may bring your own clubs or hire quality rental sets at most of the courses we use. We arrange club hire, buggy or cart reservations, caddies and practice facilities in advance so everything is ready when you arrive.</p></div>
            </details>

            <details class="faq-item">
                <summary class="faq-question"><span>What is the standard of African golf courses?</span><i data-lucide="chevron-down"></i></summary>
                <div class="faq-answer"><p>Africa offers world-class championship courses. We select courses based on course conditioning, scenic quality, professional standards and overall playing experience. Many of the courses we use have hosted international tournaments and maintain excellent facilities.</p></div>
            </details>

            <details class="faq-item">
                <summary class="faq-question"><span>Can beginners join a golf tour?</span><i data-lucide="chevron-down"></i></summary>
                <div class="faq-answer"><p>Absolutely. We welcome golfers of all abilities. Many of our itineraries include access to driving ranges, practice facilities and PGA coaching options. We can tailor the itinerary to include learning experiences alongside competitive rounds for mixed-ability groups.</p></div>
            </details>

            <details class="faq-item">
                <summary class="faq-question"><span>Which countries offer the best golf experiences?</span><i data-lucide="chevron-down"></i></summary>
                <div class="faq-answer"><p>Kenya, South Africa, Rwanda, Uganda and Tanzania all offer exceptional golf. Kenya and South Africa have the most established championship circuits. Rwanda and Uganda offer unique highland courses alongside gorilla trekking. Tanzania provides golf in extraordinary wildlife settings.</p></div>
            </details>

            <details class="faq-item">
                <summary class="faq-question"><span>Are caddies available at the courses?</span><i data-lucide="chevron-down"></i></summary>
                <div class="faq-answer"><p>Yes. Caddies are available at most courses we use and are included in our arrangements where applicable. Experienced local caddies provide valuable course knowledge and enhance the playing experience.</p></div>
            </details>
        </div>

        <div class="faqs-column">
            <h3 class="faqs-category-label">Safari & Beyond Golf</h3>

            <details class="faq-item">
                <summary class="faq-question"><span>Can I combine a golf tour with a wildlife safari?</span><i data-lucide="chevron-down"></i></summary>
                <div class="faq-answer"><p>This is one of our specialties. Many of our most popular itineraries combine championship golf rounds with safari experiences. We design the itinerary so travel between golf destinations and safari lodges is seamless and enjoyable.</p></div>
            </details>

            <details class="faq-item">
                <summary class="faq-question"><span>What non-golf experiences can be added?</span><i data-lucide="chevron-down"></i></summary>
                <div class="faq-answer"><p>We arrange safaris, beach holidays, gorilla trekking, hiking, overlanding, adventure runs, water sports, cultural experiences, wellness retreats, camping and culinary journeys. Your itinerary can include any combination of these alongside your golf rounds.</p></div>
            </details>

            <details class="faq-item">
                <summary class="faq-question"><span>Is it safe to travel in East Africa?</span><i data-lucide="chevron-down"></i></summary>
                <div class="faq-answer"><p>East Africa is a well-established travel destination with millions of visitors each year. We use trusted local guides, vetted accommodation and tested logistics on every journey. Our team provides real-time support throughout your trip and monitors travel advisories.</p></div>
            </details>

            <details class="faq-item">
                <summary class="faq-question"><span>Do you arrange gorilla trekking permits?</span><i data-lucide="chevron-down"></i></summary>
                <div class="faq-answer"><p>Yes. We secure gorilla trekking permits in Uganda and Rwanda as part of your itinerary. Permits are limited and should be booked well in advance. We handle the entire booking and coordination process.</p></div>
            </details>
        </div>

        <div class="faqs-column">
            <h3 class="faqs-category-label">Travel & Practical</h3>

            <details class="faq-item">
                <summary class="faq-question"><span>What is included in a Shishi Footsteps itinerary?</span><i data-lucide="chevron-down"></i></summary>
                <div class="faq-answer"><p>Each itinerary varies, but generally includes accommodation, ground transport, airport transfers, game drives or activities as specified, golf tee times, caddies, some meals and the services of a local guide. Specific inclusions are detailed in each itinerary overview.</p></div>
            </details>

            <details class="faq-item">
                <summary class="faq-question"><span>Do I need a visa to visit Kenya or other African countries?</span><i data-lucide="chevron-down"></i></summary>
                <div class="faq-answer"><p>Visa requirements depend on your nationality. Many visitors to Kenya can obtain an e-visa before travel. We provide guidance on visa requirements for every destination in your itinerary and can assist with the application process.</p></div>
            </details>

            <details class="faq-item">
                <summary class="faq-question"><span>What vaccinations are recommended?</span><i data-lucide="chevron-down"></i></summary>
                <div class="faq-answer"><p>We recommend consulting a travel health specialist before your trip. Common recommendations for East Africa include yellow fever, hepatitis A and B, typhoid and routine vaccinations. Malaria prophylaxis is also advisable for most destinations.</p></div>
            </details>

            <details class="faq-item">
                <summary class="faq-question"><span>What is your cancellation and refund policy?</span><i data-lucide="chevron-down"></i></summary>
                <div class="faq-answer"><p>Our cancellation terms vary depending on how far in advance you cancel and the specific components of your itinerary. We provide full terms during the booking process. We strongly recommend comprehensive travel insurance for all bookings.</p></div>
            </details>

            <details class="faq-item">
                <summary class="faq-question"><span>Can you arrange travel insurance?</span><i data-lucide="chevron-down"></i></summary>
                <div class="faq-answer"><p>While we do not sell insurance directly, we strongly recommend comprehensive travel insurance that covers trip cancellation, medical expenses, evacuation and golf equipment. We can suggest suitable providers upon request.</p></div>
            </details>

            <details class="faq-item">
                <summary class="faq-question"><span>What payment methods do you accept?</span><i data-lucide="chevron-down"></i></summary>
                <div class="faq-answer"><p>We accept bank transfers and major payment methods. A deposit is typically required to confirm your booking, with the balance due before departure. Payment details are provided once your itinerary is finalised.</p></div>
            </details>
        </div>

    </div>
</section>

<section class="content-band" style="background:var(--sf-porcelain);">
    <div class="section-heading centered">
        <div>
            <x-public.section-label label="Still Have Questions?" />
            <h2>We would love to hear from you</h2>
        </div>
    </div>
    <p style="max-width:680px;margin:12px auto 0;text-align:center;color:#68736d;font-size:14px;line-height:2;">If you cannot find the answer you are looking for, our team is ready to help. Get in touch and we will respond within one working day.</p>
    <div style="display:flex;justify-content:center;gap:16px;margin-top:32px;flex-wrap:wrap;">
        <a href="{{ route('public.contact') }}" class="button hero-primary">Contact Us<i data-lucide="arrow-up-right"></i></a>
        <a href="https://wa.me/254725346022?text={{ urlencode('Hello Shishi Footsteps, I have a question about planning a golf and travel journey.') }}" class="button hero-secondary" target="_blank" rel="noopener">WhatsApp Us<i data-lucide="arrow-up-right"></i></a>
    </div>
</section>
@endsection
