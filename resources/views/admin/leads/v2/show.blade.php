@extends('layouts.admin')
@section('title', 'Lead: '.$lead->name)
@section('content')
<div class="page-heading">
    <div><p class="eyebrow">Lead #{{ $lead->id }}</p><h1>{{ $lead->name }}</h1></div>
    <div class="heading-actions">
        <form method="POST" action="{{ route('admin.leads.convert', $lead) }}" style="display:inline;">
            @csrf
            <button class="button button-primary">Convert to Booking</button>
        </form>
    </div>
</div>
@include('admin.partials.flash')

<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;">
    {{-- Lead Details --}}
    <section class="ops-panel">
        <div class="ops-panel-title"><h2>Lead Details</h2></div>
        <form method="POST" action="{{ route('admin.leads.update', $lead) }}">
            @csrf @method('PUT')
            <div class="ops-form-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <label>Name<input name="name" value="{{ old('name', $lead->name) }}" required></label>
                <label>Email<input type="email" name="email" value="{{ old('email', $lead->email) }}" required></label>
                <label>Phone<input name="phone" value="{{ old('phone', $lead->phone) }}"></label>
                <label>Country<input name="country" value="{{ old('country', $lead->country) }}"></label>
                <label>Source
                    <select name="source">
                        @foreach(\App\Models\Lead::$sources as $k => $v)
                            <option value="{{ $k }}" @selected(old('source', $lead->source) === $k)>{{ $v }}</option>
                        @endforeach
                    </select>
                </label>
                <label>Status
                    <select name="status">
                        @foreach(\App\Models\Lead::$statuses as $k => $v)
                            <option value="{{ $k }}" @selected(old('status', $lead->status) === $k)>{{ $v }}</option>
                        @endforeach
                    </select>
                </label>
                <label>Destination<input name="destination" value="{{ old('destination', $lead->destination) }}"></label>
                <label>Travel Date<input type="date" name="travel_date" value="{{ old('travel_date', $lead->travel_date?->format('Y-m-d')) }}"></label>
                <label>Travelers<input type="number" name="travelers" value="{{ old('travelers', $lead->travelers) }}" min="1"></label>
                <label>Estimated Value<input type="number" step="0.01" name="estimated_value" value="{{ old('estimated_value', $lead->estimated_value) }}"></label>
                <label>Currency<input name="currency" value="{{ old('currency', $lead->currency) }}" maxlength="3"></label>
                <label>Interests<textarea name="interests" rows="2">{{ old('interests', $lead->interests) }}</textarea></label>
                <label class="span-2">Notes<textarea name="notes" rows="3">{{ old('notes', $lead->notes) }}</textarea></label>
            </div>
            <div class="ops-form-footer"><button class="button button-primary">Save</button></div>
        </form>
    </section>

    {{-- Sidebar --}}
    <div>
        <section class="ops-panel" style="margin-bottom:1rem;">
            <div class="ops-panel-title"><h2>Assignment</h2></div>
            <form method="POST" action="{{ route('admin.leads.assign', $lead) }}">
                @csrf
                <select name="assigned_consultant_id">
                    <option value="">Unassigned</option>
                    @foreach(\App\Models\User::where('is_active', true)->orderBy('name')->get() as $u)
                        <option value="{{ $u->id }}" @selected($lead->assigned_consultant_id === $u->id)>{{ $u->name }}</option>
                    @endforeach
                </select>
                <button class="button button-primary" style="margin-top:0.5rem;">Assign</button>
            </form>
        </section>

        <section class="ops-panel" style="margin-bottom:1rem;">
            <div class="ops-panel-title"><h2>Timeline</h2></div>
            <div style="font-size:0.85rem;">
                <div><strong>Created:</strong> {{ $lead->created_at->format('d M Y H:i') }}</div>
                <div><strong>Source:</strong> {{ \App\Models\Lead::$sources[$lead->source] ?? $lead->source }}</div>
                @if($lead->first_response_at)<div><strong>First Response:</strong> {{ $lead->first_response_at->format('d M Y H:i') }}</div>@endif
                @if($lead->quotation_sent_at)<div><strong>Quotation Sent:</strong> {{ $lead->quotation_sent_at->format('d M Y H:i') }}</div>@endif
                @if($lead->booking_at)<div><strong>Booked:</strong> {{ $lead->booking_at->format('d M Y H:i') }}</div>@endif
            </div>
        </section>

        @if($lead->bookings->count())
        <section class="ops-panel">
            <div class="ops-panel-title"><h2>Bookings</h2></div>
            @foreach($lead->bookings as $b)
                <div style="padding:0.5rem 0;border-bottom:1px solid var(--border);">
                    <a href="{{ route('admin.bookings.edit', $b) }}"><strong>{{ $b->reference }}</strong></a>
                    <div><small>{{ $b->status }} · {{ $b->currency }} {{ number_format($b->total_amount, 2) }}</small></div>
                </div>
            @endforeach
        </section>
        @endif
    </div>
</div>

{{-- Conversations --}}
<section class="ops-panel" style="margin-top:1.5rem;" id="conversations">
    <div class="ops-panel-title"><h2>Conversation History</h2></div>
    <div class="conversation-timeline" style="max-height:400px;overflow-y:auto;margin-bottom:1rem;">
        @forelse($lead->conversations as $conv)
        <div class="conversation-item {{ $conv->direction }}" style="padding:0.75rem;border-left:3px solid {{ $conv->direction === 'incoming' ? 'var(--primary)' : '#22c55e' }};margin-bottom:0.5rem;background:var(--bg-subtle);border-radius:0 0.375rem 0.375rem 0;">
            <div style="display:flex;justify-content:space-between;font-size:0.8rem;color:var(--text-muted);margin-bottom:0.25rem;">
                <span><strong>{{ ucfirst($conv->channel) }}</strong> {{ $conv->direction === 'incoming' ? '→' : '←' }}</span>
                <span>{{ $conv->created_at->format('d M Y H:i') }} {{ $conv->user?->name ? 'by '.$conv->user->name : '' }}</span>
            </div>
            <p style="margin:0;font-size:0.9rem;">{{ $conv->content }}</p>
            @if($conv->attachments)
                <div style="margin-top:0.25rem;">@foreach($conv->attachments as $att)<span class="badge">{{ $att }}</span> @endforeach</div>
            @endif
        </div>
        @empty
        <p class="text-muted">No conversations recorded yet.</p>
        @endforelse
    </div>
    <form method="POST" action="{{ route('admin.leads.conversations.store', $lead) }}" style="display:grid;grid-template-columns:1fr 2fr auto;gap:0.5rem;">
        @csrf
        <input type="hidden" name="lead_id" value="{{ $lead->id }}">
        <select name="channel">
            <option value="email">Email</option>
            <option value="whatsapp">WhatsApp</option>
            <option value="phone_call">Phone Call</option>
            <option value="internal_note">Internal Note</option>
        </select>
        <select name="direction">
            <option value="incoming">Incoming</option>
            <option value="outgoing">Outgoing</option>
        </select>
        <input name="content" placeholder="Add note..." required style="grid-column:1/-1;">
        <button class="button button-primary" style="grid-column:1/-1;">Add Conversation Entry</button>
    </form>
</section>

<style>
.badge { background: var(--primary-light); color: var(--primary); padding: 0.15rem 0.4rem; border-radius: 0.25rem; font-size: 0.75rem; }
</style>
@endsection
