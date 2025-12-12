<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', 'HEL.ink Editor')</title>
        <meta name="description" content="Edit your bio page with HEL.ink editor.">
        <link rel="icon" href="{{ route('brand.favicon') }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
        <script src="https://unpkg.com/qr-code-styling@1.6.0-rc.1/lib/qr-code-styling.js"></script>
        @stack('scripts-head')
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
        <style>[x-cloak]{display:none !important;}</style>
    </head>
    <body class="font-sans antialiased bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100">
        @hasSection('content')
            @yield('content')
        @else
            {{ $slot ?? '' }}
        @endif
        <script>
            (function() {
                const stored = localStorage.getItem('helink-theme');
                if (stored === 'dark') document.documentElement.classList.add('dark');
                if (stored === 'light') document.documentElement.classList.remove('dark');
                function updateThemeButton(isDark) {
                    const btn = document.getElementById('theme-toggle-button');
                    if (!btn) return;
                    btn.setAttribute('aria-pressed', isDark ? 'true' : 'false');
                    btn.title = isDark ? 'Dark mode active' : 'Light mode active';
                }
                updateThemeButton(document.documentElement.classList.contains('dark'));
                window.toggleTheme = function() {
                    const isDark = document.documentElement.classList.toggle('dark');
                    localStorage.setItem('helink-theme', isDark ? 'dark' : 'light');
                    updateThemeButton(isDark);
                    window.dispatchEvent(new CustomEvent('theme-changed'));
                }
            })();
        </script>
        @stack('scripts')
    </body>
</html>
