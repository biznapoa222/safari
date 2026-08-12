@extends('layouts.admin')

@section('title', $title)

@section('content')
    <x-admin.top-bar
        :title="$title"
        description="{{ __('ui.module_intro', ['module' => strtolower($title)]) }}"
        addLabel="{{ __('ui.add_new') }}"
        :addRoute="null"
        :search="false"
    >
        <p class="breadcrumbs"><a href="{{ route('admin.dashboard') }}">{{ __('ui.home') }}</a><i data-lucide="chevron-right"></i>{{ $match['label'] ?? __('ui.workspace') }}</p>
    </x-admin.top-bar>

    @if($items->isNotEmpty())
        <section class="panel content-panel">
            <div class="panel-heading">
                <div><h3>{{ $title }}</h3><p>{{ $items->count() }} {{ __('ui.records') }}</p></div>
                <div class="table-search"><i data-lucide="search"></i><input placeholder="{{ __('ui.search') }}"></div>
            </div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>{{ __('ui.name') }}</th><th>{{ __('ui.location') }}</th><th>{{ __('ui.price_from') }}</th><th>{{ __('ui.translations') }}</th><th>{{ __('ui.status') }}</th><th></th></tr></thead>
                    <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td><strong>{{ $item->translation()?->title ?? $item->name }}</strong><small>{{ ucfirst(str_replace('_', ' ', $item->type)) }}</small></td>
                            <td>{{ $item->location }}, {{ $item->country }}</td>
                            <td>{{ $item->price_from ? '$'.number_format($item->price_from) : '—' }}</td>
                            <td>
                                <div class="translation-stack">
                                    @foreach(config('safari.languages') as $code => $language)
                                        @php $translation = $item->translations->firstWhere('language_code', $code); @endphp
                                        <span class="{{ $translation ? ($translation->status === 'approved' ? 'complete' : 'generated') : '' }}" title="{{ $language['name'] }}">{{ $language['badge'] }}</span>
                                    @endforeach
                                    <small>{{ $item->translationCompleteness() }}%</small>
                                </div>
                            </td>
                            <td><span class="status status--{{ $item->status }}">{{ ucfirst($item->status) }}</span></td>
                            <td><button class="row-action"><i data-lucide="more-horizontal"></i></button></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    @else
        <section class="module-placeholder">
            <div class="placeholder-art"><i data-lucide="{{ $match['icon'] ?? 'layout-grid' }}"></i></div>
            <span class="coming-label">{{ __('ui.module_ready') }}</span>
            <h2>{{ $title }}</h2>
            <p>{{ __('ui.module_description', ['module' => strtolower($title)]) }}</p>
            <div class="placeholder-actions">
                <button class="button button-primary"><i data-lucide="plus"></i>{{ __('ui.create_first_record') }}</button>
                <button class="button button-secondary"><i data-lucide="book-open"></i>{{ __('ui.view_guide') }}</button>
            </div>
        </section>
    @endif
@endsection
