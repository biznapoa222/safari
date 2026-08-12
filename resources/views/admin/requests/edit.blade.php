@extends('layouts.admin')
@section('title', 'Edit Request: '.$request->request_number)
@section('content')
<x-admin.top-bar
    title="Edit Request: {{ $request->request_number }}"
    description="Request Management"
>
    <a href="{{ route('admin.requests.show', $request->id) }}" class="icon-button"><i data-lucide="arrow-left"></i></a>
</x-admin.top-bar>

@include('admin.partials.flash')

<form method="POST" action="{{ route('admin.requests.update', $request->id) }}" style="display:grid;grid-template-columns:2fr 1fr;gap:16px">
    @csrf @method('PUT')

    <div style="display:flex;flex-direction:column;gap:16px">
        {{-- General Information --}}
        <section class="ops-panel">
            <div class="ops-panel-title"><h2>General Information</h2></div>
            <div style="padding:16px;display:grid;grid-template-columns:1fr 1fr;gap:12px">
                <div style="grid-column:1/-1">@include('admin.requests.partials._client_search')</div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Company</label>
                    <input type="text" name="company" value="{{ old('company', $request->company) }}" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Language</label>
                    <select name="language" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                        <option value="en" @selected(old('language', $request->language) === 'en')>English</option>
                        <option value="fr" @selected(old('language', $request->language) === 'fr')>French</option>
                        <option value="de" @selected(old('language', $request->language) === 'de')>German</option>
                        <option value="es" @selected(old('language', $request->language) === 'es')>Spanish</option>
                        <option value="it" @selected(old('language', $request->language) === 'it')>Italian</option>
                        <option value="nl" @selected(old('language', $request->language) === 'nl')>Dutch</option>
                        <option value="pt" @selected(old('language', $request->language) === 'pt')>Portuguese</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Source</label>
                    <select name="source" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                        <option value="manual" @selected(old('source', $request->source) === 'manual')>Manual</option>
                        <option value="website" @selected(old('source', $request->source) === 'website')>Website</option>
                        <option value="whatsapp" @selected(old('source', $request->source) === 'whatsapp')>WhatsApp</option>
                        <option value="email" @selected(old('source', $request->source) === 'email')>Email</option>
                        <option value="walk_in" @selected(old('source', $request->source) === 'walk_in')>Walk In</option>
                        <option value="api" @selected(old('source', $request->source) === 'api')>API</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Priority</label>
                    <select name="priority" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                        <option value="low" @selected(old('priority', $request->priority) === 'low')>Low</option>
                        <option value="medium" @selected(old('priority', $request->priority) === 'medium')>Medium</option>
                        <option value="high" @selected(old('priority', $request->priority) === 'high')>High</option>
                        <option value="urgent" @selected(old('priority', $request->priority) === 'urgent')>Urgent</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Assigned Consultant</label>
                    <select name="assigned_consultant_id" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                        <option value="">Unassigned</option>
                        @foreach($users ?? [] as $user)
                        <option value="{{ $user->id }}" @selected(old('assigned_consultant_id', $request->assigned_consultant_id) == $user->id)>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </section>

        {{-- Travel Details --}}
        <section class="ops-panel">
            <div class="ops-panel-title"><h2>Travel Details</h2></div>
            <div style="padding:16px;display:grid;grid-template-columns:1fr 1fr;gap:12px">
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Arrival Date</label>
                    <input type="date" name="arrival_date" id="arrival_date" value="{{ old('arrival_date', $request->arrival_date?->format('Y-m-d')) }}" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Departure Date</label>
                    <input type="date" name="departure_date" id="departure_date" value="{{ old('departure_date', $request->departure_date?->format('Y-m-d')) }}" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Nights</label>
                    <input type="number" name="nights" id="nights" value="{{ old('nights', $request->nights) }}" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Destination</label>
                    <input type="text" name="destination" value="{{ old('destination', $request->destination) }}" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Adults</label>
                    <input type="number" name="adults" value="{{ old('adults', $request->adults) }}" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Children</label>
                    <input type="number" name="children" value="{{ old('children', $request->children) }}" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Infants</label>
                    <input type="number" name="infants" value="{{ old('infants', $request->infants) }}" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Accommodation Tier</label>
                    <select name="accommodation_tier" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                        <option value="">Select...</option>
                        @foreach(['luxury','midrange','budget','camping'] as $tier)
                        <option value="{{ $tier }}" @selected(old('accommodation_tier', $request->accommodation_tier) === $tier)>{{ ucfirst($tier) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Travel Type</label>
                    <select name="travel_type" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                        <option value="">Select...</option>
                        @foreach(['honeymoon','family','group','corporate','solo','adventure'] as $tt)
                        <option value="{{ $tt }}" @selected(old('travel_type', $request->travel_type) === $tt)>{{ ucfirst($tt) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Budget</label>
                    <input type="number" step="0.01" name="budget" value="{{ old('budget', $request->budget) }}" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Currency</label>
                    <select name="currency" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                        @foreach(['USD','EUR','GBP','ZAR','TZS','KES','UGX','RWF'] as $c)
                        <option value="{{ $c }}" @selected(old('currency', $request->currency) === $c)>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </section>

        {{-- Requirements --}}
        <section class="ops-panel">
            <div class="ops-panel-title"><h2>Requirements</h2></div>
            <div style="padding:16px;display:grid;grid-template-columns:1fr 1fr;gap:12px">
                @foreach(['flight_required'=>'Flight Required','pickup_required'=>'Pickup Required','guide_required'=>'Guide Required','visa_required'=>'Visa Required','insurance_required'=>'Insurance Required'] as $field => $label)
                <label style="display:flex;align-items:center;gap:8px;font-size:9px;cursor:pointer">
                    <input type="checkbox" name="{{ $field }}" value="1" @checked(old($field, $request->$field)) style="accent-color:var(--primary)">
                    {{ $label }}
                </label>
                @endforeach
                <div style="grid-column:1/-1">
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Transport</label>
                    <input type="text" name="transport" value="{{ old('transport', $request->transport) }}" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                </div>
            </div>
        </section>

        {{-- Notes --}}
        <section class="ops-panel">
            <div class="ops-panel-title"><h2>Notes</h2></div>
            <div style="padding:16px;display:flex;flex-direction:column;gap:12px">
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Internal Notes</label>
                    <textarea name="internal_notes" rows="3" style="width:100%;padding:10px 12px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">{{ old('internal_notes', $request->internal_notes) }}</textarea>
                </div>
                <div>
                    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Special Requests</label>
                    <textarea name="special_requests" rows="3" style="width:100%;padding:10px 12px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">{{ old('special_requests', $request->special_requests) }}</textarea>
                </div>
            </div>
        </section>
    </div>

    {{-- Sidebar --}}
    <div style="display:flex;flex-direction:column;gap:12px">
        <section class="ops-panel">
            <div class="ops-panel-title"><h2>Actions</h2></div>
            <div style="padding:16px;display:flex;flex-direction:column;gap:8px">
                <button type="submit" class="button button-primary" style="width:100%;justify-content:center">Update Request</button>
                <a href="{{ route('admin.requests.show', $request->id) }}" class="button button-ghost" style="width:100%;justify-content:center;text-align:center;text-decoration:none">Cancel</a>
            </div>
        </section>
        <section class="ops-panel">
            <div class="ops-panel-title"><h2>Summary</h2></div>
            <div style="padding:16px;font-size:9px">
                <p style="margin:0 0 8px;color:var(--text-muted)">Request {{ $request->request_number }}</p>
                <p style="margin:0 0 4px">Status: <strong>{{ ucwords(str_replace('_', ' ', $request->status)) }}</strong></p>
                <p style="margin:0">Created: {{ $request->created_at->format('d M Y') }}</p>
            </div>
        </section>
    </div>
</form>
@endsection
