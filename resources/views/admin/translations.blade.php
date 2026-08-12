@extends('layouts.admin')

@section('title', __('ui.translation_manager'))

@section('content')
    <div class="page-heading compact">
        <div>
            <p class="eyebrow">{{ __('ui.content_localization') }}</p>
            <h1>{{ __('ui.translation_manager') }}</h1>
            <p>{{ __('ui.translation_intro') }}</p>
        </div>
        <div class="heading-actions">
            <button class="button button-secondary"><i data-lucide="sparkles"></i>{{ __('ui.generate_missing') }}</button>
            <button class="button button-primary"><i data-lucide="plus"></i>{{ __('ui.add_translation') }}</button>
        </div>
    </div>

    <section class="translation-summary">
        @foreach(config('safari.languages') as $code => $language)
            @php
                $available = $items->filter(fn ($item) => $item->translations->contains('language_code', $code))->count();
                $percent = $items->count() ? round(($available / $items->count()) * 100) : 0;
            @endphp
            <article>
                <span class="language-code large">{{ $language['badge'] }}</span>
                <div><strong>{{ $language['native'] }}</strong><small>{{ $available }}/{{ $items->count() }} records</small></div>
                <div class="mini-progress"><span style="width: {{ $percent }}%"></span></div>
                <b>{{ $percent }}%</b>
            </article>
        @endforeach
    </section>

    <section class="panel content-panel">
        <div class="panel-heading">
            <div><h3>{{ __('ui.all_content') }}</h3><p>{{ __('ui.translation_status_help') }}</p></div>
            <div class="table-search"><i data-lucide="search"></i><input placeholder="{{ __('ui.search_content') }}"></div>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>{{ __('ui.content') }}</th><th>{{ __('ui.type') }}</th><th>{{ __('ui.language_status') }}</th><th>{{ __('ui.completeness') }}</th><th>{{ __('ui.actions') }}</th></tr></thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <td><strong>{{ $item->name }}</strong><small>{{ $item->country }} · {{ $item->location }}</small></td>
                        <td><span class="type-pill">{{ ucfirst(str_replace('_', ' ', $item->type)) }}</span></td>
                        <td>
                            <div class="translation-badges">
                                @foreach(config('safari.languages') as $code => $language)
                                    @php $translation = $item->translations->firstWhere('language_code', $code); @endphp
                                    <button class="{{ $translation ? ($translation->status === 'approved' ? 'complete' : 'generated') : '' }}" title="{{ $translation?->status ?? 'Missing' }}">
                                        {{ $language['badge'] }}
                                    </button>
                                @endforeach
                            </div>
                        </td>
                        <td>
                            <div class="completion"><div><span style="width: {{ $item->translationCompleteness() }}%"></span></div><strong>{{ $item->translationCompleteness() }}%</strong></div>
                        </td>
                        <td>
                            <div class="translation-actions">
                                <button title="{{ __('ui.generate_translation') }}"><i data-lucide="sparkles"></i></button>
                                <button title="{{ __('ui.upgrade_translation') }}"><i data-lucide="wand-sparkles"></i></button>
                                <button><i data-lucide="more-horizontal"></i></button>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
