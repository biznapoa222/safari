@extends('layouts.admin')
@section('title', 'Create Request')
@section('content')

<x-admin.top-bar title="Create Request" description="Request Management" :search="false" :addButton="false">
    <a href="{{ route('admin.requests.index') }}" class="button button-ghost"><i data-lucide="arrow-left"></i> Back to Requests</a>
</x-admin.top-bar>

@include('admin.partials.flash')

<form method="POST" action="{{ route('admin.requests.store') }}" style="display:grid;grid-template-columns:2fr 1fr;gap:16px;align-items:start">
    @csrf

    {{-- Main Content --}}
    <div style="display:flex;flex-direction:column;gap:16px">

        {{-- General Information --}}
        <section class="ops-panel">
            <div class="ops-panel-title"><h2>General Information</h2></div>
            <div style="padding:16px">
                @include('admin.requests.partials._client_search')

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:12px">
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Company</label>
                        <input type="text" name="company" value="{{ old('company') }}" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'">
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Language</label>
                        <select name="language" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'">
                            <option value="en" @selected(old('language') === 'en')>English</option>
                            <option value="fr" @selected(old('language') === 'fr')>French</option>
                            <option value="de" @selected(old('language') === 'de')>German</option>
                            <option value="es" @selected(old('language') === 'es')>Spanish</option>
                            <option value="it" @selected(old('language') === 'it')>Italian</option>
                            <option value="nl" @selected(old('language') === 'nl')>Dutch</option>
                            <option value="pt" @selected(old('language') === 'pt')>Portuguese</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Source</label>
                        <select name="source" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'">
                            <option value="manual" @selected(old('source') === 'manual')>Manual</option>
                            <option value="website" @selected(old('source') === 'website')>Website</option>
                            <option value="whatsapp" @selected(old('source') === 'whatsapp')>WhatsApp</option>
                            <option value="email" @selected(old('source') === 'email')>Email</option>
                            <option value="walk_in" @selected(old('source') === 'walk_in')>Walk In</option>
                            <option value="api" @selected(old('source') === 'api')>API</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Priority</label>
                        <select name="priority" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'">
                            <option value="low" @selected(old('priority') === 'low')>Low</option>
                            <option value="medium" @selected(old('priority') === 'medium')>Medium</option>
                            <option value="high" @selected(old('priority') === 'high')>High</option>
                            <option value="urgent" @selected(old('priority') === 'urgent')>Urgent</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Assigned Consultant</label>
                        <select name="assigned_consultant_id" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'">
                            <option value="">Unassigned</option>
                            @foreach($users ?? [] as $user)
                            <option value="{{ $user->id }}" @selected(old('assigned_consultant_id') == $user->id)>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </section>

        {{-- Travel Details --}}
        <section class="ops-panel">
            <div class="ops-panel-title"><h2>Travel Details</h2></div>
            <div style="padding:16px">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Arrival Date</label>
                        <input type="date" name="arrival_date" id="arrivalDate" value="{{ old('arrival_date') }}" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'">
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Departure Date</label>
                        <input type="date" name="departure_date" id="departureDate" value="{{ old('departure_date') }}" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'">
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Nights</label>
                        <input type="number" name="nights" id="nights" value="{{ old('nights') }}" min="0" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'">
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Adults</label>
                        <input type="number" name="adults" value="{{ old('adults', 2) }}" min="0" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'">
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Children</label>
                        <input type="number" name="children" value="{{ old('children', 0) }}" min="0" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'">
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Infants</label>
                        <input type="number" name="infants" value="{{ old('infants', 0) }}" min="0" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'">
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Destination</label>
                        <input type="text" name="destination" value="{{ old('destination') }}" list="destinations" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none" placeholder="Type destination..." onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'">
                        <datalist id="destinations">
                            @foreach($destinations ?? [] as $d)
                            <option value="{{ $d }}">{{ $d }}</option>
                            @endforeach
                        </datalist>
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Accommodation Tier</label>
                        <select name="accommodation_tier" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'">
                            <option value="">Select Tier</option>
                            <option value="luxury" @selected(old('accommodation_tier') === 'luxury')>Luxury</option>
                            <option value="midrange" @selected(old('accommodation_tier') === 'midrange')>Midrange</option>
                            <option value="budget" @selected(old('accommodation_tier') === 'budget')>Budget</option>
                            <option value="camping" @selected(old('accommodation_tier') === 'camping')>Camping</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Travel Type</label>
                        <select name="travel_type" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'">
                            <option value="">Select Type</option>
                            <option value="honeymoon" @selected(old('travel_type') === 'honeymoon')>Honeymoon</option>
                            <option value="family" @selected(old('travel_type') === 'family')>Family</option>
                            <option value="group" @selected(old('travel_type') === 'group')>Group</option>
                            <option value="corporate" @selected(old('travel_type') === 'corporate')>Corporate</option>
                            <option value="solo" @selected(old('travel_type') === 'solo')>Solo</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Budget</label>
                        <input type="number" step="0.01" name="budget" value="{{ old('budget') }}" min="0" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'">
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Currency</label>
                        <select name="currency" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'">
                            <option value="USD" @selected(old('currency') === 'USD')>USD</option>
                            <option value="EUR" @selected(old('currency') === 'EUR')>EUR</option>
                            <option value="GBP" @selected(old('currency') === 'GBP')>GBP</option>
                            <option value="ZAR" @selected(old('currency') === 'ZAR')>ZAR</option>
                            <option value="TZS" @selected(old('currency') === 'TZS')>TZS</option>
                            <option value="KES" @selected(old('currency') === 'KES')>KES</option>
                            <option value="UGX" @selected(old('currency') === 'UGX')>UGX</option>
                            <option value="RWF" @selected(old('currency') === 'RWF')>RWF</option>
                        </select>
                    </div>
                </div>
            </div>
        </section>

        {{-- Requirements --}}
        <section class="ops-panel">
            <div class="ops-panel-title"><h2>Requirements</h2></div>
            <div style="padding:16px">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px">
                    <label style="display:flex;align-items:center;gap:8px;font-size:9px;color:var(--text);cursor:pointer">
                        <input type="checkbox" name="flight_required" value="1" @checked(old('flight_required')) style="width:14px;height:14px;accent-color:var(--primary)">
                        Flight Required
                    </label>
                    <label style="display:flex;align-items:center;gap:8px;font-size:9px;color:var(--text);cursor:pointer">
                        <input type="checkbox" name="pickup_required" value="1" @checked(old('pickup_required')) style="width:14px;height:14px;accent-color:var(--primary)">
                        Pickup Required
                    </label>
                    <label style="display:flex;align-items:center;gap:8px;font-size:9px;color:var(--text);cursor:pointer">
                        <input type="checkbox" name="guide_required" value="1" @checked(old('guide_required')) style="width:14px;height:14px;accent-color:var(--primary)">
                        Guide Required
                    </label>
                    <label style="display:flex;align-items:center;gap:8px;font-size:9px;color:var(--text);cursor:pointer">
                        <input type="checkbox" name="visa_required" value="1" @checked(old('visa_required')) style="width:14px;height:14px;accent-color:var(--primary)">
                        Visa Required
                    </label>
                    <label style="display:flex;align-items:center;gap:8px;font-size:9px;color:var(--text);cursor:pointer">
                        <input type="checkbox" name="insurance_required" value="1" @checked(old('insurance_required')) style="width:14px;height:14px;accent-color:var(--primary)">
                        Insurance Required
                    </label>
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Transport</label>
                    <input type="text" name="transport" value="{{ old('transport') }}" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none" placeholder="Transport requirements..." onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'">
                </div>
            </div>
        </section>

        {{-- Notes --}}
        <section class="ops-panel">
            <div class="ops-panel-title"><h2>Notes</h2></div>
            <div style="padding:16px;display:flex;flex-direction:column;gap:14px">
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Internal Notes</label>
                    <textarea name="internal_notes" rows="4" style="width:100%;padding:9px 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none;resize:vertical" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'">{{ old('internal_notes') }}</textarea>
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Special Requests</label>
                    <textarea name="special_requests" rows="4" style="width:100%;padding:9px 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none;resize:vertical" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'">{{ old('special_requests') }}</textarea>
                </div>
            </div>
        </section>
    </div>

    {{-- Sidebar --}}
    <div style="display:flex;flex-direction:column;gap:16px">
        <section class="ops-panel" style="position:sticky;top:96px">
            <div class="ops-panel-title"><h2>Actions</h2></div>
            <div style="padding:16px;display:flex;flex-direction:column;gap:10px">
                <button type="submit" class="button button-primary" style="width:100%;justify-content:center">
                    <i data-lucide="save" style="width:15px;height:15px"></i> Save
                </button>
                <button type="submit" name="save_and_continue" value="1" class="button button-secondary" style="width:100%;justify-content:center">
                    <i data-lucide="arrow-right" style="width:15px;height:15px"></i> Save & Continue
                </button>
                <a href="{{ route('admin.requests.index') }}" style="display:block;text-align:center;color:var(--text-muted);font-size:9px;padding:8px 0;text-decoration:none">Cancel</a>
            </div>
        </section>

        <section class="ops-panel">
            <div class="ops-panel-title"><h2>Summary</h2></div>
            <div style="padding:16px;display:flex;flex-direction:column;gap:10px;font-size:9px">
                <div style="display:flex;justify-content:space-between">
                    <span style="color:var(--text-muted)">Nights</span>
                    <span style="color:var(--text);font-weight:600" id="summaryNights">0</span>
                </div>
                <div style="display:flex;justify-content:space-between">
                    <span style="color:var(--text-muted)">Total Guests</span>
                    <span style="color:var(--text);font-weight:600" id="summaryGuests">0</span>
                </div>
                <div style="display:flex;justify-content:space-between">
                    <span style="color:var(--text-muted)">Adults</span>
                    <span style="color:var(--text);font-weight:600" id="summaryAdults">0</span>
                </div>
                <div style="display:flex;justify-content:space-between">
                    <span style="color:var(--text-muted)">Children</span>
                    <span style="color:var(--text);font-weight:600" id="summaryChildren">0</span>
                </div>
                <div style="display:flex;justify-content:space-between">
                    <span style="color:var(--text-muted)">Infants</span>
                    <span style="color:var(--text);font-weight:600" id="summaryInfants">0</span>
                </div>
            </div>
        </section>
    </div>
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const arrival = document.getElementById('arrivalDate');
    const departure = document.getElementById('departureDate');
    const nights = document.getElementById('nights');
    const adults = document.querySelector('[name="adults"]');
    const children = document.querySelector('[name="children"]');
    const infants = document.querySelector('[name="infants"]');

    function calcNights() {
        if (arrival.value && departure.value) {
            const a = new Date(arrival.value);
            const d = new Date(departure.value);
            const diff = Math.max(0, Math.round((d - a) / (1000 * 60 * 60 * 24)));
            nights.value = diff;
            document.getElementById('summaryNights').textContent = diff;
        }
    }

    function calcGuests() {
        const a = parseInt(adults?.value) || 0;
        const c = parseInt(children?.value) || 0;
        const i = parseInt(infants?.value) || 0;
        document.getElementById('summaryGuests').textContent = a + c + i;
        document.getElementById('summaryAdults').textContent = a;
        document.getElementById('summaryChildren').textContent = c;
        document.getElementById('summaryInfants').textContent = i;
    }

    arrival?.addEventListener('change', calcNights);
    departure?.addEventListener('change', calcNights);
    adults?.addEventListener('input', calcGuests);
    children?.addEventListener('input', calcGuests);
    infants?.addEventListener('input', calcGuests);
    calcNights();
    calcGuests();
});
</script>
@endpush
@endsection
