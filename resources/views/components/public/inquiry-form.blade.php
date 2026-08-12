@props(['destinations' => collect(), 'selectedItinerary' => null, 'prefillDestination' => null])

<form class="inquiry-form" method="POST" action="{{ route('enquire') }}">
    @csrf
    @if(session('success'))
        <div class="form-success"><i data-lucide="circle-check"></i>{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="form-errors"><i data-lucide="triangle-alert"></i>{{ $errors->first() }}</div>
    @endif

    @if($selectedItinerary)
        <div class="inquiry-itinerary-summary" style="margin:0 0 18px;padding:14px 16px;border:1px solid #e3d9c5;border-radius:10px;background:#faf6ec;display:flex;gap:12px;align-items:center;flex-wrap:wrap;font-size:11px">
            <i data-lucide="map"></i>
            <div style="flex:1;min-width:0">
                <strong>Selected Itinerary:</strong>
                <span>{{ $selectedItinerary['title'] ?? 'Itinerary' }}</span>
                @if(!empty($selectedItinerary['country']))<span> &middot; <strong>Country:</strong> {{ $selectedItinerary['country'] }}</span>@endif
                @if(!empty($selectedItinerary['days']))<span> &middot; {{ $selectedItinerary['days'] }} days</span>@endif
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
            <input type="hidden" name="destination_override" id="destinationOverride" value="{{ $selectedItinerary['country'] }}">
            @endif
            @if(!empty($selectedItinerary['url']))
            <input type="hidden" name="itinerary_url" value="{{ $selectedItinerary['url'] }}">
            @endif
        </div>
    @endif

    <fieldset>
        <legend>Your details</legend>
        <div class="field-row">
            <label><span>Full Name</span><input name="name" value="{{ old('name') }}" required autocomplete="name"></label>
            <label><span>Email</span><input type="email" name="email" value="{{ old('email') }}" required autocomplete="email"></label>
        </div>
        <div class="field-row">
            <label><span>Phone / WhatsApp</span><input name="phone" value="{{ old('phone') }}" autocomplete="tel"></label>
            <label><span>Country of Residence</span><input name="country" value="{{ old('country') }}" autocomplete="country-name"></label>
        </div>
    </fieldset>

    <fieldset>
        <legend>Your safari</legend>
        <div class="field-row">
            <label><span>Preferred Destination</span>
                <select name="destination">
                    <option value="">Not sure yet</option>
                    @foreach($destinations as $destination)
                        <option value="{{ $destination->name }}" @selected(old('destination', $prefillDestination) === $destination->name)>{{ $destination->name }}</option>
                    @endforeach
                    <option value="Rwanda" @selected(old('destination', $prefillDestination) === 'Rwanda')>Rwanda</option>
                    <option value="Multi-country safari" @selected(old('destination', $prefillDestination) === 'Multi-country safari')>Multi-country safari</option>
                </select>
            </label>
            <label><span>Preferred Start Date</span><input type="date" name="travel_date" value="{{ old('travel_date', request('travel_date')) }}"></label>
        </div>
        <div class="field-row compact-travel-row">
            <label><span>Adults</span><input type="number" min="1" max="60" name="adults" value="{{ old('adults', request('adults', 2)) }}" required></label>
            <label><span>Children</span><input type="number" min="0" max="60" name="children" value="{{ old('children', request('children', 0)) }}"></label>
            <label><span>Budget Per Person</span>
                <select name="budget">
                    <option value="">Not decided</option>
                    <option value="$3,000 - $5,000 per person" @selected(old('budget', request('budget')) === '$3,000 - $5,000 per person')>$3,000 - $5,000</option>
                    <option value="$5,000 - $8,000 per person" @selected(old('budget', request('budget')) === '$5,000 - $8,000 per person')>$5,000 - $8,000</option>
                    <option value="$8,000 - $12,000 per person" @selected(old('budget', request('budget')) === '$8,000 - $12,000 per person')>$8,000 - $12,000</option>
                    <option value="$12,000+ per person" @selected(old('budget', request('budget')) === '$12,000+ per person')>$12,000+</option>
                </select>
            </label>
        </div>
        <label><span>Safari Type</span>
            <select name="safari_type">
                <option value="Tailor-made safari" @selected(old('safari_type', request('safari_type', 'Tailor-made safari')) === 'Tailor-made safari')>Tailor-made safari</option>
                <option @selected(old('safari_type', request('safari_type')) === 'Family safari')>Family safari</option>
                <option @selected(old('safari_type', request('safari_type')) === 'Honeymoon safari')>Honeymoon safari</option>
                <option @selected(old('safari_type', request('safari_type')) === 'Private group safari')>Private group safari</option>
                <option @selected(old('safari_type', request('safari_type')) === 'Luxury lodge safari')>Luxury lodge safari</option>
                <option @selected(old('safari_type', request('safari_type')) === 'Gorilla and wildlife safari')>Gorilla and wildlife safari</option>
                <option @selected(old('safari_type', request('safari_type')) === 'Wellness and recovery retreat')>Wellness and recovery retreat</option>
                <option @selected(old('safari_type', request('safari_type')) === 'Sports and adventure travel')>Sports and adventure travel</option>
                <option @selected(old('safari_type', request('safari_type')) === 'Beach and coastal extension')>Beach and coastal extension</option>
            </select>
        </label>
        <label><span>Anything else?</span><textarea name="message" rows="4" placeholder="Special interests, celebrations or accessibility needs">{{ old('message', request('message')) }}</textarea></label>
    </fieldset>

    <button class="button inquiry-submit">Send Inquiry<i data-lucide="arrow-up-right"></i></button>
    <small class="privacy-note"><i data-lucide="lock-keyhole"></i>Sent securely to our safari team.</small>
</form>
