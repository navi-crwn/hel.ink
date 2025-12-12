<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ trim(($pageTitle ?? '') ?: 'HEL.ink - Your Destination Is A Hop Away') }}</title>
        <meta name="description" content="Manage every short link, analytics card, and quota from the Hop Easy Link workspace.">
        <link rel="icon" href="{{ route('brand.favicon') }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />
        
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom@2.0.1/dist/chartjs-plugin-zoom.min.js"></script>
        
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jvectormap@2.0.5/jquery-jvectormap.min.css">
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/jvectormap@2.0.5/jquery-jvectormap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/jvectormap@2.0.5/jquery-jvectormap-world-mill.min.js"></script>
        
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
        <script src="https://unpkg.com/qr-code-styling@1.6.0-rc.1/lib/qr-code-styling.js"></script>
        
        @stack('scripts-head')
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
        <style>[x-cloak]{display:none !important;}</style>
    </head>
    <body class="font-sans antialiased bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100">
        @auth
            <!-- Sidebar wrapper - separate x-data scope, does NOT wrap main content -->
            <div x-data="{ sidebarOpen: window.innerWidth >= 1024 }" 
                 x-init="
                    window.sidebarState = $data;
                    const updateMargin = () => {
                        const wrapper = document.getElementById('main-content-wrapper');
                        if (wrapper) {
                            wrapper.style.marginLeft = (sidebarOpen && window.innerWidth >= 1024) ? '256px' : '0px';
                        }
                    };
                    $watch('sidebarOpen', updateMargin);
                    updateMargin();
                    window.addEventListener('resize', () => {
                        if (window.innerWidth < 1024 && sidebarOpen) {
                            sidebarOpen = false;
                        } else if (window.innerWidth >= 1024 && !sidebarOpen) {
                            sidebarOpen = true;
                        }
                        updateMargin();
                    });
                 "
                 @keydown.escape.window="if (window.innerWidth < 1024) sidebarOpen = false"
                 class="contents">
                
                <button @click="sidebarOpen = !sidebarOpen" 
                        class="fixed top-20 z-50 flex h-10 w-6 items-center justify-center rounded-r-lg border border-l-0 border-slate-200 bg-white shadow-lg hover:w-8 dark:border-slate-700 dark:bg-slate-800"
                        :style="'left: ' + (sidebarOpen ? '256px' : '0px') + '; transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);'">
                    <svg class="h-4 w-4 text-slate-600 dark:text-slate-300" 
                         :class="sidebarOpen ? 'rotate-180' : ''" 
                         :style="'transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);'"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <div x-show="sidebarOpen && window.innerWidth < 1024" 
                     x-cloak
                     @click="sidebarOpen = false" 
                     x-transition:enter="transition-opacity ease-linear duration-[350ms]" 
                     x-transition:enter-start="opacity-0" 
                     x-transition:enter-end="opacity-100" 
                     x-transition:leave="transition-opacity ease-linear duration-[350ms]" 
                     x-transition:leave-start="opacity-100" 
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm lg:hidden"></div>

                <aside x-show="sidebarOpen"
                       x-transition:enter="transition-transform ease-out duration-[350ms]"
                       x-transition:enter-start="-translate-x-full"
                       x-transition:enter-end="translate-x-0"
                       x-transition:leave="transition-transform ease-in duration-[350ms]"
                       x-transition:leave-start="translate-x-0"
                       x-transition:leave-end="-translate-x-full"
                       class="fixed left-0 top-0 h-screen w-64 shadow-xl z-40"
                       style="transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);"
                       @click="if ($event.target.closest('a') && window.innerWidth < 1024) { sidebarOpen = false }">
                    <x-sidebar-desktop 
                        :isAdmin="auth()->user()->isAdmin()" 
                        :unreadReports="\App\Models\AbuseReport::where('status', 'pending')->count()" />
                </aside>
            </div>
            
            <!-- Main content wrapper - NO x-data to avoid scope isolation -->
            <div id="main-content-wrapper" class="min-h-screen transition-[margin-left] duration-[350ms]" 
                 style="margin-left: 256px;">
                <header class="sticky top-0 z-30 border-b border-slate-200 bg-white/95 backdrop-blur dark:border-slate-800 dark:bg-slate-900/95">
                    <div class="flex h-16 items-center justify-between px-4 sm:px-6">
                        <div class="flex-1"></div>
                        <div class="flex items-center gap-2 sm:gap-4">
                            @if(request()->routeIs('bio.*'))
                                <a href="{{ route('bio.create') }}" class="rounded-full bg-blue-600 px-5 md:px-8 py-2 text-sm md:text-base font-semibold text-white shadow-lg shadow-blue-500/30 hover:bg-blue-500 transition-all">
                                    <span class="hidden md:inline">+ Create Bio</span>
                                    <span class="md:hidden">+</span>
                                </a>
                            @elseif(request()->routeIs('folders.*'))
                                <button type="button" onclick="window.openFolderModal && window.openFolderModal()" class="rounded-full bg-blue-600 px-5 md:px-8 py-2 text-sm md:text-base font-semibold text-white shadow-lg shadow-blue-500/30 hover:bg-blue-500 transition-all">
                                    <span class="hidden md:inline">+ Create Folder</span>
                                    <span class="md:hidden">+</span>
                                </button>
                            @elseif(request()->routeIs('tags.*'))
                                <button type="button" onclick="window.openTagModal && window.openTagModal()" class="rounded-full bg-blue-600 px-5 md:px-8 py-2 text-sm md:text-base font-semibold text-white shadow-lg shadow-blue-500/30 hover:bg-blue-500 transition-all">
                                    <span class="hidden md:inline">+ Create Tag</span>
                                    <span class="md:hidden">+</span>
                                </button>
                            @else
                                <button type="button" onclick="window.openLinkPanel && window.openLinkPanel()" class="create-link-trigger rounded-full bg-blue-600 px-5 md:px-8 py-2 text-sm md:text-base font-semibold text-white shadow-lg shadow-blue-500/30 hover:bg-blue-500 transition-all">
                                    <span class="hidden md:inline">+ Create Link</span>
                                    <span class="md:hidden">+</span>
                                </button>
                            @endif
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="flex items-center gap-2 rounded-full bg-slate-100 px-3 sm:px-4 py-2 text-xs sm:text-sm font-semibold text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700 transition-all">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    <span class="hidden sm:inline">Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </header>

                <main class="w-full p-4 sm:p-6">
                    <div class="mx-auto w-full @yield('container-width', 'max-w-screen-xl 2xl:max-w-screen-2xl')">
                        @hasSection('content')
                            @yield('content')
                        @else
                            {{ $slot ?? '' }}
                        @endif
                    </div>
                </main>
            </div>
        @else
            <div class="min-h-screen">
                <main>
                    @hasSection('content')
                        @yield('content')
                    @else
                        {{ $slot ?? '' }}
                    @endif
                </main>
            </div>
        @endauth

        <x-cookie-consent />

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
                // initialize button state
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
