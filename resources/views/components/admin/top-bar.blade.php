@props([
    'title' => '',
    'description' => '',
    'search' => true,
    'searchPlaceholder' => 'Search...',
    'searchValue' => '',
    'addRoute' => null,
    'addLabel' => 'Add New',
    'addIcon' => 'plus',
    'addButton' => true,
    'addOnclick' => null,
    'filters' => false,
    'extraActions' => '',
])

<div class="page-heading">
    <div>
        @if($description)<p class="eyebrow">{{ $description }}</p>@endif
        <h1>{{ $title }}</h1>
        {{ $slot ?? '' }}
    </div>
    <div class="heading-actions">
        {{ $extraActions }}
        @if(isset($actions)){{ $actions }}@endif
        @if($search)
        <form method="GET" class="inline-search" style="display:contents;">
            @foreach(request()->except('search', 'page') as $k => $v)
                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
            @endforeach
            <label class="ops-search">
                <i data-lucide="search"></i>
                <input name="search" value="{{ $searchValue ?: request('search') }}" placeholder="{{ $searchPlaceholder }}">
            </label>
            <button class="button button-secondary button-sm" style="display:none;">Go</button>
        </form>
        @endif
        @if($filters && !($filters instanceof \Illuminate\Support\HtmlString))
            {{ $filters }}
        @endif
        @if($addButton)
            @if($addOnclick)
            <button class="button button-primary" onclick="{{ $addOnclick }}">
                <i data-lucide="{{ $addIcon }}"></i>{{ $addLabel }}
            </button>
            @elseif($addRoute)
            <a href="{{ $addRoute }}" class="button button-primary">
                <i data-lucide="{{ $addIcon }}"></i>{{ $addLabel }}
            </a>
            @endif
        @endif
    </div>
</div>

@push('styles')
<style>
.page-heading {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}
.page-heading h1 {
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0;
}
.page-heading .eyebrow {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--text-muted);
    margin-bottom: 0.25rem;
}
.heading-actions {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
    flex-shrink: 0;
}
.inline-search { display: flex; align-items: center; gap: 0.5rem; }
.ops-search {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.4rem 0.75rem;
    border: 1px solid var(--border);
    border-radius: 0.5rem;
    background: var(--bg);
}
.ops-search input {
    border: none;
    background: none;
    outline: none;
    font-size: 0.85rem;
    min-width: 180px;
}
.ops-search i { color: var(--text-muted); width: 16px; height: 16px; }
.button { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border-radius: 0.5rem; font-weight: 600; font-size: 0.85rem; cursor: pointer; border: none; text-decoration: none; transition: all 0.15s; }
.button-primary { background: #234A36; color: #fff; }
.button-primary:hover { background: #1a3829; }
.button-primary:active { background: #142d20; }
.button-secondary { background: #F8F5EF; color: #3a3530; border: 1px solid #c9c0b2; }
.button-secondary:hover { background: #ede8df; border-color: #b5aa99; }
.button-sm { padding: 0.35rem 0.75rem; font-size: 0.8rem; }
@media (max-width: 768px) {
    .page-heading { flex-direction: column; }
    .heading-actions { width: 100%; }
    .ops-search { flex: 1; }
}
</style>
@endpush
