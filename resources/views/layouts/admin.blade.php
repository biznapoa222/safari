<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('images/brand/favicon-512.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/brand/apple-touch-icon.png') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base-url" content="{{ url('') }}">
    <title>@yield('title', __('ui.dashboard')) - Shishi Footsteps</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Great+Vibes&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/requests.js'])
    @stack('styles')
    <style>
        .row-actions-dropdown { position: relative; display: inline-flex; }
        .row-actions-trigger {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            background: #F8F5EF;
            border: 1px solid #d9d0c1;
            border-radius: 8px;
            color: #3a3530;
            cursor: pointer;
            transition: background .15s ease, border-color .15s ease, transform .15s ease;
        }
        .row-actions-trigger:hover { background: #ede8df; border-color: #b5aa99; }
        .row-actions-trigger:focus { outline: none; border-color: #234A36; }
        .row-actions-trigger svg { width: 14px; height: 14px; }
        .row-actions-menu {
            position: absolute;
            top: calc(100% + 6px);
            right: 0;
            min-width: 180px;
            background: #ffffff;
            border: 1px solid #d9d0c1;
            border-radius: 10px;
            box-shadow: 0 12px 28px rgba(35, 74, 54, 0.18);
            padding: 6px;
            z-index: 50;
            display: none;
            flex-direction: column;
            gap: 2px;
        }
        .row-actions-dropdown.is-open .row-actions-menu { display: flex; }
        .row-actions-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            border-radius: 7px;
            font-size: 10px;
            font-weight: 600;
            color: #234A36;
            text-decoration: none;
            background: transparent;
            border: 0;
            cursor: pointer;
            text-align: left;
            width: 100%;
        }
        .row-actions-item:hover { background: #faf6ec; color: #1a3829; }
        .row-actions-item svg { width: 14px; height: 14px; flex-shrink: 0; }
        .row-actions-item--form { color: #b91c1c; }
        .row-actions-item--form:hover { background: #fef2f2; color: #7f1d1d; }

        /* Top corner Emails dropdown */
        .topbar-dropdown { position: relative; }
        .topbar-dropdown summary {
            list-style: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            height: 38px;
            padding: 0 12px;
            border: 1px solid #d9d0c1;
            border-radius: 9px;
            background: #F8F5EF;
            color: #3a3530;
            font-size: 10px;
            font-weight: 700;
            cursor: pointer;
        }
        .topbar-dropdown summary::-webkit-details-marker { display: none; }
        .topbar-dropdown summary svg { width: 14px; height: 14px; }
        .topbar-dropdown[open] summary { background: #ede8df; border-color: #b5aa99; }
        .topbar-dropdown-menu {
            position: absolute;
            top: calc(100% + 6px);
            right: 0;
            min-width: 220px;
            background: #ffffff;
            border: 1px solid #d9d0c1;
            border-radius: 12px;
            box-shadow: 0 18px 32px rgba(35, 74, 54, 0.18);
            padding: 6px;
            display: flex;
            flex-direction: column;
            gap: 2px;
            z-index: 60;
        }
        .topbar-dropdown-menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 8px;
            color: #234A36;
            text-decoration: none;
            font-size: 10px;
            font-weight: 600;
        }
        .topbar-dropdown-menu a:hover { background: #faf6ec; color: #1a3829; }
        .topbar-dropdown-menu a svg { width: 14px; height: 14px; }
        .topbar-dropdown-menu hr { border: none; border-top: 1px solid #ede8df; margin: 4px 0; }
    </style>
</head>
<body class="admin-body @yield('body_class')">
    <div class="app-shell">
        <aside class="sidebar" id="sidebar">
            <a href="{{ route('admin.dashboard') }}" class="brand shishi-brand">
                <span class="brand-menu-icon"><i data-lucide="menu"></i></span>
                <span><strong>Shishi Footsteps</strong></span>
            </a>

            <nav class="sidebar-nav">
                <p class="nav-section">{{ __('ui.main_menu') }}</p>
                @foreach(config('navigation') as $menu)
                    @php
                        $menuSlug = \Illuminate\Support\Str::slug($menu['label']);
                        $menuKey = \Illuminate\Support\Str::of($menu['label'])->lower()->replace(' & ', '_and_')->replace(' / ', '_')->replace(' ', '_')->toString();
                        $children = $menu['children'] ?? [];
                        $activeSlug = request()->route('slug');
                        $childRouteIs = function ($child) {
                            $slug = \Illuminate\Support\Str::slug($child);
                            $wf = config('workflow_routes.'.$slug);
                            return $wf ? request()->routeIs($wf[0]) : false;
                        };
                        $forceOpen = ($menu['label'] ?? '') === 'Content';
                        $isActive = request()->routeIs($menu['route'] ?? '')
                            || $activeSlug === $menuSlug
                            || collect($children)->contains(fn ($child) => \Illuminate\Support\Str::slug($child) === $activeSlug)
                            || collect($children)->contains(fn ($child) => $childRouteIs($child));
                    @endphp

                    @if(empty($children))
                        <a class="nav-item {{ $isActive ? 'is-active' : '' }}" href="{{ route($menu['route']) }}">
                            <i data-lucide="{{ $menu['icon'] }}"></i>
                            <span>{{ __('ui.nav.'.$menuKey) }}</span>
                        </a>
                    @else
                        <div class="nav-group {{ ($isActive || $forceOpen) ? 'is-open' : '' }}">
                            <button class="nav-item nav-toggle {{ $isActive ? 'is-active' : '' }}" type="button" aria-expanded="{{ $isActive ? 'true' : 'false' }}">
                                <i data-lucide="{{ $menu['icon'] }}"></i>
                                <span>{{ __('ui.nav.'.$menuKey) }}</span>
                                <i class="nav-chevron" data-lucide="chevron-down"></i>
                            </button>
                            <div class="nav-children">
                                @foreach($children as $child)
                                    @php $childSlug = \Illuminate\Support\Str::slug($child); @endphp
                                    @php
                                        $workflow = config('workflow_routes.'.$childSlug);
                                        $childUrl = $workflow
                                            ? route($workflow[0], $workflow[1] ?? [])
                                            : route('admin.records.index', ['slug' => $childSlug]);
                                    @endphp
                                    <a href="{{ $childUrl }}"
                                       class="{{ ($childRouteIs($child) || $activeSlug === $childSlug) ? 'is-active' : '' }}">
                                        <span></span>{{ $child }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </nav>

            <div class="sidebar-account">
                <div class="sidebar-user-line"><i data-lucide="circle-user-round"></i><span>{{ auth()->user()->name }}</span></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"><i data-lucide="log-out"></i><span>Logout</span></button>
                </form>
                <label><i data-lucide="pencil"></i><span><small>Select language</small><strong>English</strong></span><i data-lucide="chevron-down"></i></label>
                <label><i data-lucide="pencil"></i><span><small>Select currency</small><strong>USD</strong></span><i data-lucide="chevron-down"></i></label>
            </div>
        </aside>

        <main class="workspace">
            <header class="topbar">
                <div class="topbar-left">
                    <button class="icon-button mobile-menu" type="button" data-sidebar-toggle><i data-lucide="menu"></i></button>
                    <div class="search-box">
                        <i data-lucide="search"></i>
                        <input type="search" placeholder="{{ __('ui.search_placeholder') }}">
                        <kbd>⌘ K</kbd>
                    </div>
                </div>
                <div class="topbar-actions">
                    <a href="{{ route('home') }}" class="site-link" target="_blank"><i data-lucide="globe-2"></i><span>{{ __('ui.view_website') }}</span></a>
                    <x-language-switcher />

                    <details class="topbar-dropdown" id="emailsDropdown">
                        <summary class="topbar-dropdown-trigger"><i data-lucide="mail"></i><span>Emails</span><i data-lucide="chevron-down" style="width:14px;height:14px"></i></summary>
                        <div class="topbar-dropdown-menu">
                            <a href="{{ route('admin.mail.inbox') }}"><i data-lucide="inbox"></i> Inbox</a>
                            <a href="{{ route('admin.mail.incoming.accounts') }}"><i data-lucide="server"></i> IMAP accounts</a>
                            <a href="{{ route('admin.mail.settings') }}"><i data-lucide="settings"></i> Mail settings</a>
                            <hr>
                            <a href="{{ route('admin.quotations.index') }}" onclick="event.preventDefault();if(typeof changeQuotationTo==='function'){document.getElementById('sendReadyToBookAllForm')?.submit();}else{window.location.href='{{ route('admin.mail.settings') }}';}"><i data-lucide="mail-plus"></i> Ready to book</a>
                            <a href="{{ route('admin.mail.settings') }}"><i data-lucide="mail-check"></i> Pre-confirmation</a>
                            <a href="{{ route('admin.mail.settings') }}"><i data-lucide="badge-check"></i> Confirmation</a>
                            <hr>
                            <a href="{{ route('admin.mail.settings') }}#sent"><i data-lucide="history"></i> Recent emails</a>
                        </div>
                    </details>

                    <button class="icon-button notification-button"><i data-lucide="bell"></i><span></span></button>
                    <details class="profile-menu">
                        <summary class="profile"><span class="avatar">{{ auth()->user()->initials() }}</span><span><strong>{{ auth()->user()->name }}</strong><small>{{ \App\Models\User::roles()[auth()->user()->role] ?? ucfirst(auth()->user()->role) }}</small></span><i data-lucide="chevron-down"></i></summary>
                        <div><span><small>Signed in as</small><strong>{{ auth()->user()->email }}</strong></span>@if(auth()->user()->role === 'administrator')<a href="{{ route('admin.users.index') }}"><i data-lucide="users"></i>Users & roles</a>@endif<form method="POST" action="{{ route('logout') }}">@csrf<button><i data-lucide="log-out"></i>Sign out</button></form></div>
                    </details>
                </div>
            </header>

            <div class="page-content">
                @yield('content')
            </div>
        </main>
    </div>
    <div class="sidebar-overlay" data-sidebar-toggle></div>
    <script src="https://unpkg.com/lucide@0.468.0/dist/umd/lucide.min.js"></script>
    @stack('scripts')
</body>
</html>
