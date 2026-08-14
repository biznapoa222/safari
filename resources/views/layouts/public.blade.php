<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @php $settings = $settings ?? \App\Models\WebsiteSetting::home(); @endphp
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('description', $settings->seo_description ?? 'Tailor-made luxury safari journeys across East and Southern Africa.')">
    <meta property="og:title" content="@yield('title', $settings->seo_title ?? 'Shishi Footsteps | Luxury African Safaris')">
    <meta property="og:description" content="@yield('description', $settings->seo_description ?? 'Plan a private luxury safari with Shishi Footsteps.')">
    <meta property="og:image" content="@yield('og_image', $settings->open_graph_image ?? $settings->hero_image)">
    <title>@yield('title', $settings->seo_title ?? 'Shishi Footsteps | Luxury African Safaris')</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('images/brand/favicon-512.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/brand/apple-touch-icon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&family=Outfit:wght@300;400;500;600;700;800;900&family=Great+Vibes&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="public-body">
    <x-public.header />

    @yield('content')

    <x-public.footer />
    <x-public.chat-widget />
    <script src="https://unpkg.com/lucide@0.468.0/dist/umd/lucide.min.js"></script>
</body>
</html>
