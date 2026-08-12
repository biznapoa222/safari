<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Shishi Footsteps ERP')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="login-body">
    @yield('content')
</body>
</html>
