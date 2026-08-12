@props(['placeholder' => 'Search templates...', 'endpoint' => route('admin.itinerary-templates.search')])

<div class="search-dropdown search-dropdown--admin" data-endpoint="{{ $endpoint }}" data-show-all="0">
    <label class="ops-search" style="flex:1;min-width:240px">
        <i data-lucide="search"></i>
        <input
            type="text"
            class="search-dropdown-input"
            placeholder="{{ $placeholder }}"
            autocomplete="off"
        />
    </label>
    <div class="search-dropdown-results hidden" role="listbox" aria-label="Quick results" style="position:absolute;left:0;right:0;top:100%;margin-top:6px;background:#fff;border:1px solid #d9d0c1;border-radius:10px;box-shadow:0 12px 28px rgba(0,0,0,.1);z-index:999;max-height:340px;overflow-y:auto"></div>
</div>

@once
@push('styles')
<style>
.search-dropdown--admin .search-dropdown-results .sdr-item { display:flex;gap:10px;align-items:center;padding:9px 12px;border-bottom:1px solid #ede8df;cursor:pointer;font-size:10px;color:#3a3530;text-decoration:none; }
.search-dropdown--admin .search-dropdown-results .sdr-item:hover, .search-dropdown--admin .search-dropdown-results .sdr-item.is-active { background:#faf6ec; }
.search-dropdown--admin .search-dropdown-results .sdr-item strong { color:#234A36; }
.search-dropdown--admin .search-dropdown-results .sdr-meta { padding:8px 12px;font-size:7px;color:#6b6b6b;text-transform:uppercase;letter-spacing:.4px;background:#faf6ec;border-bottom:1px solid #ede8df; }
.search-dropdown--admin .search-dropdown-results .sdr-hint { padding:14px;color:#6b6b6b;font-size:9px;text-align:center; }
</style>
@endpush
@endonce
