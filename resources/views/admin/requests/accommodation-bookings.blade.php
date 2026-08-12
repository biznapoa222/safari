@extends('layouts.admin')

@section('title', 'Find Accommodation Bookings')
@section('content')
@php
    $selectedAccommodation = '';
    $selectedKey = $filters['accommodation'] ?? '';
    foreach ($accommodations['rooms'] as $room) {
        if ($selectedKey === 'room:'.$room->id) $selectedAccommodation = $room->hotel_name.' - '.$room->room_name;
    }
    foreach ($accommodations['hotels'] as $hotel) {
        if ($selectedKey === 'hotel:'.$hotel->id) $selectedAccommodation = $hotel->name;
    }
    foreach ($accommodations['proposals'] as $proposal) {
        if ($selectedKey === 'proposal:'.$proposal->id) $selectedAccommodation = $proposal->reference.' - '.$proposal->title;
    }
@endphp

<section class="accommodation-bookings-page">
    <header class="accommodation-bookings-header">
        <div>
            <span>Requests / Accommodation</span>
            <h1>Find Accommodation Bookings</h1>
        </div>
        <a class="accommodation-close-button" href="{{ route('admin.requests.index') }}">Close</a>
    </header>

    <form class="accommodation-bookings-filters" method="GET" action="{{ route('admin.requests.accommodation-bookings') }}" data-accommodation-search-form>
        <label class="accommodation-filter accommodation-filter--wide">
            <span>Accommodation</span>
            <input type="search" name="accommodation_label" value="{{ $selectedAccommodation }}" placeholder="Search accommodation..." list="accommodation-options" autocomplete="off" data-accommodation-label>
            <input type="hidden" name="accommodation" value="{{ $selectedKey }}" data-accommodation-value>
            <datalist id="accommodation-options">
                @foreach($accommodations['rooms'] as $room)
                    <option value="{{ $room->hotel_name.' - '.$room->room_name }}" data-key="room:{{ $room->id }}"></option>
                @endforeach
                @foreach($accommodations['hotels'] as $hotel)
                    <option value="{{ $hotel->name }}" data-key="hotel:{{ $hotel->id }}"></option>
                @endforeach
                @foreach($accommodations['proposals'] as $proposal)
                    <option value="{{ $proposal->reference.' - '.$proposal->title }}" data-key="proposal:{{ $proposal->id }}"></option>
                @endforeach
            </datalist>
        </label>
        <label class="accommodation-filter">
            <span>Minimum Date</span>
            <input type="date" name="minimum_date" value="{{ $filters['minimum_date'] ?? '' }}" required data-accommodation-date>
        </label>
        <label class="accommodation-filter">
            <span>Maximum Date</span>
            <input type="date" name="maximum_date" value="{{ $filters['maximum_date'] ?? '' }}" required data-accommodation-date>
        </label>
        <button class="accommodation-search-button" type="submit" data-accommodation-submit disabled>Search</button>
        <button class="accommodation-total-button" type="submit" name="total" value="1" data-accommodation-submit disabled>Get Total Bed Nights</button>
    </form>

    <section class="accommodation-bookings-results">
        @if($searched && $bookings->isEmpty())
            <div class="accommodation-empty">No accommodation bookings found <strong>0</strong></div>
        @elseif(!$searched)
            <div class="accommodation-empty">Select a date range to search accommodation bookings.</div>
        @else
            <div class="accommodation-results-heading"><strong>{{ $bookings->count() }} booking{{ $bookings->count() === 1 ? '' : 's' }}</strong><span>{{ $filters['minimum_date'] }} to {{ $filters['maximum_date'] }}</span></div>
            <div class="accommodation-table-wrap">
                <table class="accommodation-bookings-table">
                    <thead><tr><th>Date</th><th>Proposal</th><th class="number">Amount of Persons</th></tr></thead>
                    <tbody>
                    @foreach($bookings as $booking)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($booking->starts_at)->format('d-m-Y') }}</td>
                            <td><strong>{{ $booking->proposal_reference }}</strong><small>{{ $booking->proposal_title }}</small></td>
                            <td class="number">{{ $booking->persons }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot><tr><th colspan="2">Total bed nights in selected period</th><th class="number">{{ $totalBedNights ?: 0 }}</th></tr></tfoot>
                </table>
            </div>
        @endif
    </section>

    <footer class="accommodation-bookings-footer">
        @if($searched && !empty($filters['minimum_date']))
            <a class="accommodation-export-button" href="{{ route('admin.requests.accommodation-bookings.export', request()->query()) }}">Export to Excel</a>
        @else
            <button class="accommodation-export-button" type="button" disabled>Export to Excel</button>
        @endif
    </footer>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var form = document.querySelector('[data-accommodation-search-form]');
    if (!form) return;
    var label = form.querySelector('[data-accommodation-label]');
    var value = form.querySelector('[data-accommodation-value]');
    var options = Array.from(form.querySelectorAll('#accommodation-options option'));
    var dates = Array.from(form.querySelectorAll('[data-accommodation-date]'));
    var buttons = Array.from(form.querySelectorAll('[data-accommodation-submit]'));
    var update = function () {
        var min = dates[0]?.value || '';
        var max = dates[1]?.value || '';
        var valid = min && max && max >= min;
        dates[1]?.setCustomValidity(max && min && max < min ? 'Maximum date must not be earlier than minimum date.' : '');
        buttons.forEach(function (button) { button.disabled = !valid; });
    };
    label?.addEventListener('input', function () {
        var match = options.find(function (option) { return option.value === label.value; });
        value.value = match?.dataset.key || '';
    });
    dates.forEach(function (date) { date.addEventListener('change', update); });
    form.addEventListener('submit', function () {
        buttons.forEach(function (button) { button.disabled = true; button.dataset.originalText = button.textContent; button.textContent = 'Searching...'; });
    });
    update();
});
</script>
@endpush
@endsection
