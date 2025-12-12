@php
    $navItems = [
        ['label' => 'Links', 'route' => 'dashboard', 'icon' => '🔗'],
        ['label' => 'Analytics', 'route' => 'analytics', 'icon' => '📈'],
        ['label' => 'Link in Bio', 'route' => 'bio.index', 'icon' => '📱'],
        ['label' => 'Folders', 'route' => 'folders.index', 'icon' => '📁'],
        ['label' => 'Tags', 'route' => 'tags.index', 'icon' => '🏷️'],
        ['label' => 'Settings', 'route' => 'settings', 'icon' => '⚙️'],
    ];
@endphp
<aside class="hidden w-64 shrink-0 border-r border-slate-200 bg-white/90 px-4 py-6 backdrop-blur dark:border-slate-800 dark:bg-slate-900/80 md:flex md:flex-col" x-data="{ isDark: document.documentElement.classList.contains('dark') }" @theme-changed.window="isDark = document.documentElement.classList.contains('dark')">
    <div class="flex items-center gap-2 px-3">
        <img x-show="!isDark" src="{{ route('brand.logo.dark') }}" alt="HEL.ink logo" class="h-10 w-10 rounded-full">
        <img x-show="isDark" x-cloak src="{{ route('brand.logo') }}" alt="HEL.ink logo" class="h-10 w-10 rounded-full">
        <div>
            <p class="text-sm text-slate-500 dark:text-slate-400">Welcome back,</p>
            <p class="font-semibold text-slate-900 dark:text-white">{{ Auth::user()->name }}</p>
        </div>
    </div>
    <nav class="mt-8 flex-1 space-y-1 text-sm">
        <p class="px-3 text-xs uppercase tracking-wide text-slate-400">Workspace</p>
        @foreach ($navItems as $item)
            <a href="{{ route($item['route']) }}"
               class="mt-1 flex items-center gap-3 rounded-2xl px-4 py-3 font-medium {{ request()->routeIs(str_contains($item['route'], '.') ? explode('.', $item['route'])[0].'*' : $item['route']) ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800' }}">
                <span class="text-lg">{{ $item['icon'] }}</span>
               {{ $item['label'] }}
            </a>
        @endforeach
        <div class="mt-6 rounded-2xl border border-slate-200 p-4 text-xs text-slate-500 dark:border-slate-800 dark:text-slate-400">
            <p class="font-semibold text-slate-700 dark:text-white">Usage & limits</p>
            <p class="mt-2">Active links: {{ number_format(Auth::user()->links()->where('status', 'active')->count()) }} / {{ number_format(config('limits.user.max_active_links')) }}</p>
            <p class="mt-1">Daily quota: {{ config('limits.user.max_links_per_day') }} creations.</p>
        </div>
        <a href="{{ route('report.create') }}" class="mt-4 flex items-center justify-between rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-600 hover:bg-slate-100 dark:border-slate-800 dark:text-slate-200 dark:hover:bg-slate-800/60">
            Report abuse
            <span class="text-lg">🚨</span>
        </a>
        <button id="theme-toggle-button" type="button" aria-pressed="false" onclick="toggleTheme()" class="mt-2 flex w-full items-center justify-between rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-600 hover:bg-slate-100 dark:border-slate-800 dark:text-slate-200 dark:hover:bg-slate-800/60">
            Switch Theme
            <span class="text-lg">🌓</span>
        </button>
    </nav>
</aside>
