@props(['unreadReports', 'mobile' => false])

<aside x-data="{ openMenu: null }" class="{{ $mobile ? 'w-full' : 'h-screen w-64 border-r' }} bg-white dark:bg-slate-900 {{ !$mobile ? 'border-slate-200 dark:border-slate-800' : '' }}" @click="if ($event.target.closest('a') && window.innerWidth < 768) { $dispatch('close-sidebar') }">
    <div class="{{ $mobile ? 'py-2' : 'flex h-full flex-col' }}">
        @if(!$mobile)
        <div class="flex h-16 items-center border-b border-slate-200 px-6 dark:border-slate-800">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                <x-application-logo class="h-8 w-auto" />
                <span class="font-semibold text-slate-900 dark:text-white">HEL.ink Admin</span>
            </a>
        </div>
        @endif

        <nav class="{{ $mobile ? 'px-4' : 'flex-1 overflow-y-auto px-3 py-4' }}">
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400' : 'text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li x-data="{ open: {{ request()->routeIs('analytics*') || request()->routeIs('admin.analytics*') || request()->routeIs('admin.geoip*') || request()->routeIs('admin.proxy*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="flex w-full items-center justify-between gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('analytics*') || request()->routeIs('admin.analytics*') || request()->routeIs('admin.geoip*') || request()->routeIs('admin.proxy*') ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400' : 'text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800' }}">
                        <div class="flex items-center gap-3">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <span>Analytics</span>
                        </div>
                        <svg class="h-4 w-4 transition-transform" :class="open ? 'rotate-90' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                    <ul x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1" class="ml-8 mt-1 space-y-1">
                        <li>
                            <a href="{{ route('admin.analytics.global') }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm transition-colors {{ request()->routeIs('admin.analytics.global') ? 'text-blue-600 dark:text-blue-400' : 'text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white' }}">
                                Global Analytics
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('analytics') }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm transition-colors {{ request()->routeIs('analytics') && !request()->routeIs('admin.analytics*') ? 'text-blue-600 dark:text-blue-400' : 'text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white' }}">
                                My Analytics
                            </a>
                        </li>
                        <li class="border-t border-slate-200 dark:border-slate-700 pt-1 mt-1">
                            <a href="{{ route('admin.geoip.monitor') }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm transition-colors {{ request()->routeIs('admin.geoip.monitor') ? 'text-blue-600 dark:text-blue-400' : 'text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white' }}">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                GeoIP Monitor
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.proxy.monitor') }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm transition-colors {{ request()->routeIs('admin.proxy.monitor') ? 'text-blue-600 dark:text-blue-400' : 'text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white' }}">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                Proxy Detection
                            </a>
                        </li>
                    </ul>
                </li>

                <li x-data="{ open: {{ request()->routeIs('admin.links*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="flex w-full items-center justify-between gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.links*') ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400' : 'text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800' }}">
                        <div class="flex items-center gap-3">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                            <span>Manage Links</span>
                        </div>
                        <svg class="h-4 w-4 transition-transform" :class="open ? 'rotate-90' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                    <ul x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1" class="ml-8 mt-1 space-y-1">
                        <li>
                            <a href="{{ route('admin.links.index') }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm transition-colors {{ request()->routeIs('admin.links.index') ? 'text-blue-600 dark:text-blue-400' : 'text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white' }}">
                                Global Links
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.links.my') }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm transition-colors {{ request()->routeIs('admin.links.my') ? 'text-blue-600 dark:text-blue-400' : 'text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white' }}">
                                My Links
                            </a>
                        </li>
                    </ul>
                </li>

                <li x-data="{ open: {{ request()->routeIs('admin.users*') || request()->routeIs('admin.ip-*') || request()->routeIs('admin.domain-*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="flex w-full items-center justify-between gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.users*') || request()->routeIs('admin.ip-*') || request()->routeIs('admin.domain-*') ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400' : 'text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800' }}">
                        <div class="flex items-center gap-3">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span>Security & Users</span>
                        </div>
                        <svg class="h-4 w-4 transition-transform" :class="open ? 'rotate-90' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                    <ul x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1" class="ml-8 mt-1 space-y-1">
                        <li>
                            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm transition-colors {{ request()->routeIs('admin.users*') ? 'text-blue-600 dark:text-blue-400' : 'text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white' }}">
                                Users
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.ip-bans.index') }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm transition-colors {{ request()->routeIs('admin.ip-bans*') ? 'text-blue-600 dark:text-blue-400' : 'text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white' }}">
                                IP Bans
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.ip-watchlist.index') }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm transition-colors {{ request()->routeIs('admin.ip-watchlist*') ? 'text-blue-600 dark:text-blue-400' : 'text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white' }}">
                                IP Watchlist
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.domain-blacklist.index') }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm transition-colors {{ request()->routeIs('admin.domain-blacklist*') ? 'text-blue-600 dark:text-blue-400' : 'text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white' }}">
                                Domain Blacklist
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="{{ route('admin.abuse.index') }}" class="flex items-center justify-between gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.abuse*') ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400' : 'text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800' }}">
                        <div class="flex items-center gap-3">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span>Abuse Reports</span>
                        </div>
                        @if($unreadReports > 0)
                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs font-bold text-white">
                                {{ $unreadReports > 9 ? '9+' : $unreadReports }}
                            </span>
                        @endif
                    </a>
                </li>

                <li x-data="{ open: {{ request()->routeIs('admin.seo*') || request()->routeIs('admin.site*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="flex w-full items-center justify-between gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.seo*') || request()->routeIs('admin.site*') ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400' : 'text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800' }}">
                        <div class="flex items-center gap-3">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                            </svg>
                            <span>Manage Site</span>
                        </div>
                        <svg class="h-4 w-4 transition-transform" :class="open ? 'rotate-90' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                    <ul x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1" class="ml-8 mt-1 space-y-1">
                        <li>
                            <a href="{{ route('admin.seo.edit') }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm transition-colors {{ request()->routeIs('admin.seo*') ? 'text-blue-600 dark:text-blue-400' : 'text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white' }}">
                                SEO Settings
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="{{ route('settings') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('settings') ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400' : 'text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Settings</span>
                    </a>
                </li>
            </div>
        </nav>

        <div class="border-t border-slate-700 p-4">
            <button type="button" onclick="toggleTheme()" id="theme-toggle-button" class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-slate-300 hover:bg-slate-700">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
                <span>Toggle Theme</span>
            </button>
        </div>
    </div>
</aside>
