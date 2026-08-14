@props(['destinations' => collect(), 'selectedItinerary' => null, 'prefillDestination' => null, 'prefillInterest' => null, 'countryNames' => null, 'variant' => 'enquiry'])

@php
    $countries = collect($countryNames);
    if ($countries->isEmpty()) {
        $countries = collect($destinations)->pluck('name')->merge(['Kenya', 'Tanzania', 'Uganda', 'Rwanda', 'South Africa', 'Namibia', 'Botswana'])->unique()->values();
    }
    $safariPrefill = old('safari_type', request('safari_type', $prefillInterest ?: 'Tailor-made safari'));
    $isProposal = $variant === 'proposal';
@endphp

<form class="inquiry-form{{ $isProposal ? ' inquiry-form--proposal' : '' }}" method="POST" action="{{ route('enquire') }}">
    @csrf
    @if(session('success'))
        <div class="form-success"><i data-lucide="circle-check"></i>{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="form-errors"><i data-lucide="triangle-alert"></i>{{ $errors->first() }}</div>
    @endif

    @if($selectedItinerary)
        <div class="inquiry-itinerary-summary">
            <i data-lucide="map"></i>
            <div>
                <strong>Selected itinerary</strong>
                <span>{{ $selectedItinerary['title'] ?? 'Itinerary' }}</span>
                @if(!empty($selectedItinerary['country']))<span> · {{ $selectedItinerary['country'] }}</span>@endif
                @if(!empty($selectedItinerary['days']))<span> · {{ $selectedItinerary['days'] }} days</span>@endif
            </div>
            @if(!empty($selectedItinerary['id']))
            <input type="hidden" name="itinerary_id" value="{{ $selectedItinerary['id'] }}">
            @endif
            @if(!empty($selectedItinerary['slug']))
            <input type="hidden" name="itinerary_slug" value="{{ $selectedItinerary['slug'] }}">
            @endif
            @if(!empty($selectedItinerary['title']))
            <input type="hidden" name="itinerary_title" value="{{ $selectedItinerary['title'] }}">
            @endif
            @if(!empty($selectedItinerary['country']))
            <input type="hidden" name="destination_override" value="{{ $selectedItinerary['country'] }}">
            @endif
            @if(!empty($selectedItinerary['url']))
            <input type="hidden" name="itinerary_url" value="{{ $selectedItinerary['url'] }}">
            @endif
        </div>
    @endif

    <fieldset>
        <legend>Your details</legend>
        <div class="field-row">
            <label><span>Full name</span><input name="name" value="{{ old('name') }}" required autocomplete="name"></label>
            <label><span>Email</span><input type="email" name="email" value="{{ old('email') }}" required autocomplete="email"></label>
        </div>
        <div class="field-row">
            <label><span>Phone / WhatsApp</span><input name="phone" value="{{ old('phone') }}" autocomplete="tel"></label>
            <label><span>Country of residence</span><input name="country" value="{{ old('country') }}" autocomplete="country-name"></label>
        </div>
    </fieldset>

    <fieldset>
        <legend>{{ $isProposal ? 'The journey you have in mind' : 'Your safari' }}</legend>
        <div class="field-row">
            <label><span>Preferred destination</span>
                <select name="destination">
                    <option value="">Not sure yet</option>
                    @foreach($countries as $countryName)
                        <option value="{{ $countryName }}" @selected(old('destination', $prefillDestination) === $countryName)>{{ $countryName }}</option>
                    @endforeach
                    <option value="Multi-country safari" @selected(old('destination', $prefillDestination) === 'Multi-country safari')>Multi-country safari</option>
                </select>
            </label>
            <label><span>Preferred start date</span><input type="date" name="travel_date" value="{{ old('travel_date', request('travel_date')) }}"></label>
        </div>
        <div class="field-row compact-travel-row">
            <label><span>Adults</span><input type="number" min="1" max="60" name="adults" value="{{ old('adults', request('adults', 2)) }}" required></label>
            <label><span>Children</span><input type="number" min="0" max="60" name="children" value="{{ old('children', request('children', 0)) }}"></label>
            <label><span>Nights</span><input type="number" min="3" max="60" name="nights" value="{{ old('nights', request('nights')) }}" placeholder="Flexible"></label>
        </div>
        <div class="field-row">
            <label><span>Budget per person</span>
                <select name="budget">
                    <option value="">To be discussed</option>
                    <option value="$3,000 - $5,000 per person" @selected(old('budget', request('budget')) === '$3,000 - $5,000 per person')>$3,000 – $5,000</option>
                    <option value="$5,000 - $8,000 per person" @selected(old('budget', request('budget')) === '$5,000 - $8,000 per person')>$5,000 – $8,000</option>
                    <option value="$8,000 - $12,000 per person" @selected(old('budget', request('budget')) === '$8,000 - $12,000 per person')>$8,000 – $12,000</option>
                    <option value="$12,000+ per person" @selected(old('budget', request('budget')) === '$12,000+ per person')>$12,000+</option>
                </select>
            </label>
            <label><span>Golf</span>
                <select name="golf_interest">
                    <option value="No golf" @selected(old('golf_interest') === 'No golf')>No golf</option>
                    <option value="A round or two" @selected(old('golf_interest', request('safari_type') === 'Golf safari' ? 'A round or two' : '') === 'A round or two')>A round or two</option>
                    <option value="Golf is central" @selected(old('golf_interest') === 'Golf is central')>Golf is central</option>
                </select>
            </label>
        </div>
        <label><span>Travel style</span>
            <select name="safari_type">
                <option value="Tailor-made safari" @selected($safariPrefill === 'Tailor-made safari')>Tailor-made safari</option>
                <option value="Golf safari" @selected($safariPrefill === 'Golf safari')>Golf safari</option>
                <option value="Family safari" @selected($safariPrefill === 'Family safari')>Family safari</option>
                <option value="Honeymoon safari" @selected($safariPrefill === 'Honeymoon safari')>Honeymoon safari</option>
                <option value="Private group safari" @selected($safariPrefill === 'Private group safari')>Private group safari</option>
                <option value="Luxury lodge safari" @selected($safariPrefill === 'Luxury lodge safari')>Luxury lodge safari</option>
                <option value="Gorilla and wildlife safari" @selected($safariPrefill === 'Gorilla and wildlife safari')>Gorilla and wildlife safari</option>
                <option value="Wellness and recovery retreat" @selected($safariPrefill === 'Wellness and recovery retreat')>Wellness and recovery retreat</option>
                <option value="Beach and coastal extension" @selected($safariPrefill === 'Beach and coastal extension')>Beach and coastal extension</option>
            </select>
        </label>
        <label><span>Anything a trip advisor should know?</span><textarea name="message" rows="4" placeholder="Season, lodges you love, celebrations, accessibility, or a country you are sure about">{{ old('message', request('message')) }}</textarea></label>
    </fieldset>

    <button class="button inquiry-submit" type="submit">{{ $isProposal ? 'Request a private proposal' : 'Send inquiry' }}<i data-lucide="arrow-up-right"></i></button>
    <small class="privacy-note"><i data-lucide="lock-keyhole"></i>Sent securely to our safari team. No obligation.</small>
</form>
