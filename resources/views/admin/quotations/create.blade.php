@extends('layouts.admin')
@section('title', 'New Quotation')
@section('content')
<div class="ops-page-heading"><div><p class="eyebrow">Proposal planning</p><h1>Create quotation</h1><p>Start a day-by-day tailor-made itinerary for a client.</p></div><a class="button button-secondary" href="{{ route('admin.quotations.index') }}">Back</a></div>
@include('admin.partials.flash')
<section class="ops-panel ops-form-panel narrow-panel">
    <form method="POST" action="{{ route('admin.quotations.store') }}">@csrf
        <div class="ops-form-grid">
            <label class="span-2">Client<select name="client_id" required><option value="">Select client</option>@foreach($clients as $client)<option value="{{ $client->id }}" @selected(old('client_id', $selectedClient) == $client->id)>{{ $client->name }} — {{ $client->email }}</option>@endforeach</select></label>
            <label class="span-2">Quotation title<input name="title" value="{{ old('title', $enquiry ? (($enquiry->destination ?: 'Tailor-made').' Safari for '.$enquiry->name) : '') }}" required></label>
            <label>Start date<input type="date" name="start_date" value="{{ old('start_date', $enquiry->travel_date ?? today()->addMonths(2)->toDateString()) }}" required></label>
            <label>Duration days<input type="number" name="duration_days" min="1" max="60" value="{{ old('duration_days', 10) }}" required></label>
            <label>Guests<input type="number" name="guest_count" min="1" max="100" value="{{ old('guest_count', $enquiry->travelers ?? 2) }}" required></label>
            <label>Start location<input name="start_location" value="{{ old('start_location', 'Nairobi') }}" required></label>
            <label>Currency<input name="currency" maxlength="3" value="{{ old('currency', 'USD') }}" required></label>
            <label>Office markup %<input type="number" step="0.01" name="office_markup_percent" value="{{ old('office_markup_percent', 20) }}" required></label>
            <label>Miscellaneous markup %<input type="number" step="0.01" name="misc_markup_percent" value="{{ old('misc_markup_percent', 5) }}" required></label>
        </div>
        <div class="ops-form-footer"><button class="button button-primary"><i data-lucide="arrow-right"></i>Create and plan itinerary</button></div>
    </form>
</section>
@endsection
