@props(['label'])

<p {{ $attributes->merge(['class' => 'section-label']) }}>{{ $label }}</p>
