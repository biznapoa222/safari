@props([
    'title' => 'No records yet.',
    'description' => '',
    'addRoute' => null,
    'addLabel' => 'Add First Record',
    'addIcon' => 'plus',
    'action' => null,
])

<section class="module-placeholder">
    <div class="placeholder-art"><i data-lucide="{{ $addIcon }}"></i></div>
    <h2>{{ $title }}</h2>
    @if($description)<p>{{ $description }}</p>@endif
    <div class="placeholder-actions">
        @if($action)
            <button class="button button-primary" onclick="{{ $action }}">
                <i data-lucide="{{ $addIcon }}"></i>{{ $addLabel }}
            </button>
        @elseif($addRoute)
            <a href="{{ $addRoute }}" class="button button-primary">
                <i data-lucide="{{ $addIcon }}"></i>{{ $addLabel }}
            </a>
        @endif
    </div>
</section>

@push('styles')
<style>
.module-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 4rem 2rem;
    text-align: center;
    min-height: 300px;
    border: 2px dashed var(--border);
    border-radius: 1rem;
    background: var(--bg-subtle);
}
.placeholder-art {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: var(--primary-light);
    color: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.5rem;
}
.placeholder-art i { width: 32px; height: 32px; }
.module-placeholder h2 { font-size: 1.25rem; font-weight: 600; margin: 0 0 0.5rem; }
.module-placeholder p { color: var(--text-muted); font-size: 0.9rem; margin: 0 0 1.5rem; max-width: 400px; }
.placeholder-actions { display: flex; gap: 0.75rem; }
</style>
@endpush
