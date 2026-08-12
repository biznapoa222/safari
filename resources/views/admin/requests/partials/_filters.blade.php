<form id="requestsFilterForm" class="pm-request-filters">
    <input type="hidden" name="sort" value="{{ request('sort', 'created_at') }}">
    <input type="hidden" name="direction" value="{{ request('direction', 'desc') }}">
    <label class="pm-filter-field pm-filter-search">
        <span>Search</span>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="First, last names or email">
        <i data-lucide="search"></i>
    </label>
    <button class="pm-search-button" type="submit">SEARCH</button>
    <label class="pm-filter-field">
        <span>Filter by statuses</span>
        <select name="status">
            <option value="">All statuses</option>
            @foreach($statuses ?? [] as $st)
                <option value="{{ $st }}" @selected(request('status') === $st)>{{ ($statusOptions ?? [])[$st] ?? ucwords(str_replace('_', ' ', $st)) }}</option>
            @endforeach
        </select>
        <i data-lucide="search"></i>
    </label>
    <fieldset class="pm-filter-checks">
        <legend>Filter by type</legend>
        @foreach(['itinerary' => 'Itinerary', 'custom' => 'Custom', 'manual' => 'Manual', 'group' => 'Group'] as $value => $label)
            <label><input type="checkbox" name="request_types[]" value="{{ $value }}" @checked(in_array($value, (array) request('request_types', []), true))><span>{{ $label }}</span></label>
        @endforeach
    </fieldset>
    <label class="pm-filter-field">
        <span>Filter by seller</span>
        <select name="assigned_to">
            <option value="">All</option>
            @foreach($users ?? [] as $user)
                <option value="{{ $user->id }}" @selected(request('assigned_to') == $user->id)>{{ $user->name }}</option>
            @endforeach
        </select>
        <i data-lucide="user"></i>
    </label>
    <label class="pm-filter-field">
        <span>Filter by language</span>
        <select name="language">
            <option value="">nl, de, en, fr, es, sv, no, da, it, pl, pt</option>
            @foreach(($languages ?? []) as $code => $label)
                <option value="{{ $code }}" @selected(request('language') === $code)>{{ $code }} - {{ $label }}</option>
            @endforeach
        </select>
        <i data-lucide="search"></i>
    </label>
    <label class="pm-filter-field pm-company">
        <span>Filter by country</span>
        <select name="country">
            <option value="">All countries</option>
            @foreach($countries ?? [] as $country)
                <option value="{{ $country }}" @selected(request('country') === $country)>{{ $country }}</option>
            @endforeach
        </select>
        <i data-lucide="search"></i>
    </label>
    <label class="pm-filter-field pm-date">
        <i data-lucide="calendar-days"></i>
        <input type="date" name="followup_from" value="{{ request('followup_from') }}" placeholder="Follow-up date">
        <span>Follow-up date</span>
    </label>
    <label class="pm-filter-field pm-date">
        <i data-lucide="calendar-days"></i>
        <input type="date" name="arrival_from" value="{{ request('arrival_from') }}" placeholder="Arrival date">
        <span>Arrival date</span>
    </label>
</form>
