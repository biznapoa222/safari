@props(['variant' => 'admin'])

<div class="language-switcher {{ $variant === 'public' ? 'language-switcher--public' : '' }}">
    <button class="language-trigger" type="button" data-language-trigger aria-expanded="false">
        <span class="language-globe"><i data-lucide="languages"></i></span>
        <span>{{ config('safari.languages.'.app()->getLocale().'.badge') }}</span>
        <i data-lucide="chevron-down" class="chevron"></i>
    </button>
    <div class="language-menu" data-language-menu>
        <div class="language-menu__header">{{ __('ui.choose_language') }}</div>
        @foreach(config('safari.languages') as $code => $language)
            <a href="{{ route('locale.update', $code) }}" class="{{ app()->getLocale() === $code ? 'is-active' : '' }}">
                <span class="language-code">{{ $language['badge'] }}</span>
                <span>{{ $language['native'] }}</span>
                @if(app()->getLocale() === $code)<i data-lucide="check"></i>@endif
            </a>
        @endforeach
    </div>
</div>
