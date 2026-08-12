@extends('layouts.admin')
@section('title', 'Requests')
@section('body_class', 'admin-body--legacy-requests')
@section('content')
@include('admin.partials.flash')

@php
    // Keep the visible Requests navigation limited to the approved workflow tabs.
    $tabs = [
        '' => 'ALL REQUESTS',
        'new' => 'NEW REQUESTS',
        'contacted' => 'EXISTING REQUESTS',
        'qualified' => 'PRE-CONFIRMED REQUESTS',
        'confirmed' => 'CONFIRMED REQUESTS',
        'travelled' => 'OPERATED REQUESTS',
        'cancelled' => 'CANCELLED REQUESTS',
        'archived' => 'DODO REQUESTS',
    ];
    $currentStatus = request('status', '');
    $createdDirection = request('sort') === 'created_at' && request('direction') === 'asc' ? 'asc' : 'desc';
    $nextCreatedDirection = $createdDirection === 'asc' ? 'desc' : 'asc';
@endphp

<section class="pm-requests-page">
    <header class="pm-requests-head">
        <h1>Requests</h1>
        <nav>
            <button type="button" data-request-modal-open>CREATE REQUEST</button>
            <a href="{{ route('admin.requests.accommodation-bookings') }}">FIND ACCOMMODATION BOOKINGS</a>
        </nav>
    </header>

    <div class="pm-request-tabs">
        @foreach($tabs as $val => $label)
            <a href="#" data-status-tab="{{ $val }}" class="{{ $currentStatus === $val ? 'active-tab is-active' : '' }}">{{ $label }}</a>
        @endforeach
    </div>

    @include('admin.requests.partials._filters')

    <section class="pm-table-panel" id="requestsTableWrapper">
        <div class="table-wrap">
            <table class="pm-requests-table">
                <thead>
                    <tr>
                        <th>Index</th>
                        <th>Follow Up Date</th>
                        <th>Arrival date</th>
                        <th><a class="request-sort-link" href="{{ route('admin.requests.index', array_merge(request()->query(), ['sort' => 'created_at', 'direction' => $nextCreatedDirection])) }}">Created date <i data-lucide="{{ $createdDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}"></i></a></th>
                        <th>First name</th>
                        <th>Last name</th>
                        <th>Status</th>
                        <th>Value</th>
                        <th>Rating</th>
                        <th>Seller notes</th>
                        <th>Is Diamond Luxury</th>
                        <th>Type</th>
                        <th>Site</th>
                        <th>Responsible user</th>
                        <th>Company</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @include('admin.requests.partials._table_rows')
                </tbody>
            </table>
        </div>
        @if($requests->hasPages())
            <div class="ops-pagination">{{ $requests->links() }}</div>
        @endif
    </section>
</section>

<div class="pm-modal" data-request-modal @if($errors->any()) style="display:flex" @endif>
    <div class="pm-modal-backdrop" data-request-modal-close></div>
    <form method="POST" action="{{ route('admin.requests.store') }}" class="pm-request-dialog">
        @csrf
        <header>Create new Travel Request</header>
        <div class="pm-dialog-body">
            <label class="pm-field pm-select">
                <span>Language of customer</span>
                <select name="language">
                    @foreach(config('safari.languages', []) as $code => $language)
                        <option value="{{ $code }}" @selected(old('language', 'en') === $code)>{{ $language['name'] }}</option>
                    @endforeach
                </select>
            </label>
            <label class="pm-field"><span>First name</span><input name="first_name" value="{{ old('first_name') }}" required></label>
            <label class="pm-field"><span>Surname</span><input name="surname" value="{{ old('surname') }}" required></label>
            <label class="pm-field"><span>Email</span><input type="email" name="client_email" value="{{ old('client_email') }}" required></label>
            <label class="pm-field"><span>Phone</span><input name="client_phone" value="{{ old('client_phone') }}"></label>
            <label class="pm-field pm-select"><span>Country</span><select name="country" required><option value="">Select country</option>@foreach($countries ?? [] as $country)<option value="{{ $country }}" @selected(old('country') === $country)>{{ $country }}</option>@endforeach</select></label>
            <label class="pm-field pm-icon-field"><span>Arrival date</span><input type="date" name="arrival_date" value="{{ old('arrival_date') }}" required><i data-lucide="calendar-days"></i></label>
            <label class="pm-field pm-select"><span>Company</span><input name="company" value="{{ old('company') }}"></label>
            <label class="pm-field pm-select">
                <span>Marketing channel</span>
                <select name="source">
                    <option value="manual">Manual</option>
                    <option value="website">Website</option>
                    <option value="email">Email</option>
                    <option value="whatsapp">WhatsApp</option>
                </select>
            </label>
            <label class="pm-checkbox"><input type="checkbox" name="travel_type" value="group"> <span>Is this joining-a-group trip?</span></label>
            <input type="hidden" name="client_name" data-client-name>
            <input type="hidden" name="adults" value="1">
            <input type="hidden" name="children" value="0">
            <input type="hidden" name="infants" value="0">
            <input type="hidden" name="currency" value="USD">
        </div>
        <footer>
            <button type="button" data-request-modal-close>CLOSE</button>
            <button type="submit" class="pm-create-btn" disabled>CREATE REQUEST</button>
        </footer>
    </form>
</div>

@include('admin.requests.partials._notes_panel')

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var modal = document.querySelector('[data-request-modal]');
    var openers = document.querySelectorAll('[data-request-modal-open]');
    var closers = document.querySelectorAll('[data-request-modal-close]');
    openers.forEach(function (btn) {
        btn.addEventListener('click', function () {
            modal.style.display = 'flex';
            setTimeout(function () { modal.querySelector('select, input')?.focus(); }, 50);
        });
    });
    closers.forEach(function (btn) {
        btn.addEventListener('click', function () { modal.style.display = 'none'; });
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') modal.style.display = 'none';
    });
    var requestForm = modal.querySelector('form');
    var createButton = requestForm?.querySelector('.pm-create-btn');
    var updateCreateState = function () {
        if (!requestForm || !createButton) return;
        var requiredFields = ['first_name', 'surname', 'client_email', 'country', 'arrival_date'];
        var complete = requiredFields.every(function (name) {
            return (requestForm.querySelector('[name="' + name + '"]')?.value || '').trim() !== '';
        });
        createButton.disabled = !complete;
        createButton.setAttribute('aria-disabled', complete ? 'false' : 'true');
    };
    requestForm?.querySelectorAll('input, select').forEach(function (field) {
        field.addEventListener('input', updateCreateState);
        field.addEventListener('change', updateCreateState);
    });
    updateCreateState();
    requestForm?.addEventListener('submit', function () {
        var first = this.querySelector('[name="first_name"]')?.value.trim() || '';
        var surname = this.querySelector('[name="surname"]')?.value.trim() || '';
        this.querySelector('[data-client-name]').value = (first + ' ' + surname).trim() || first || surname;
    });
});
</script>
@endpush
@endsection
