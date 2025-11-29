<x-app-layout>
    <x-slot name="pageTitle">Global Analytics</x-slot>

    <div class="py-4" x-data="{ 
        topLinksPage: 0, 
        topCountriesPage: 0, 
        recentActivityPage: 0, 
        recentClicksPage: 0 
    }">
        <div class="mx-auto max-w-7xl space-y-4 px-4 md:px-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Global Analytics</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">Platform-wide statistics and performance</p>
            </div>
            <div class="flex items-center gap-2">
                <select onchange="window.location.href='?period='+this.value" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm text-slate-900 focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                    <option value="daily" {{ $period === 'daily' ? 'selected' : '' }}>Daily (30 days)</option>
                    <option value="weekly" {{ $period === 'weekly' ? 'selected' : '' }}>Weekly (12 weeks)</option>
                    <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>Monthly (12 months)</option>
                </select>
            </div>
        </div>

        <div class="grid gap-3 md:grid-cols-4">
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-[10px] uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Users</p>
                <p class="mt-1 text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($totalUsers) }}</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-[10px] uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Links</p>
                <p class="mt-1 text-2xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($totalLinks) }}</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-[10px] uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Clicks</p>
                <p class="mt-1 text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ number_format($totalClicks) }}</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-[10px] uppercase tracking-wider text-slate-500 dark:text-slate-400">Active Links</p>
                <p class="mt-1 text-2xl font-bold text-purple-600 dark:text-purple-400">{{ number_format($activeLinks) }}</p>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h3 class="mb-3 text-base font-semibold text-slate-900 dark:text-white">Performance Analytics</h3>
            @if($performanceData['clicks']->count() > 0)
            <div class="grid gap-4 lg:grid-cols-3">
                <div>
                    <p class="text-xs font-medium text-slate-700 dark:text-slate-300 mb-2">Clicks Growth</p>
                    <div class="h-48 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 p-3">
                        <canvas id="clicksLine"></canvas>
                    </div>
                </div>
                
                <div>
                    <p class="text-xs font-medium text-slate-700 dark:text-slate-300 mb-2">Links Created</p>
                    <div class="h-48 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 p-3">
                        <canvas id="linksLine"></canvas>
                    </div>
                </div>

                <div>
                    <p class="text-xs font-medium text-slate-700 dark:text-slate-300 mb-2">User Registrations</p>
                    <div class="h-48 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 p-3">
                        <canvas id="usersLine"></canvas>
                    </div>
                </div>
            </div>
            @else
            <p class="text-sm text-slate-500 text-center py-8">No performance data available</p>
            @endif
        </div>

        <div class="grid gap-4 lg:grid-cols-2">
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-base font-semibold text-slate-900 dark:text-white">Top Links</h3>
                    <span class="text-xs text-slate-500">Total Clicks</span>
                </div>
                @php 
                    $linksPerPage = 5;
                    $topLinksArray = $topLinks->take(20)->toArray();
                    $totalLinksPages = ceil(count($topLinksArray) / $linksPerPage);
                @endphp
                <div class="space-y-2 min-h-[280px]">
                    @foreach(array_chunk($topLinksArray, $linksPerPage) as $pageIndex => $linksChunk)
                        <div x-show="topLinksPage === {{ $pageIndex }}" class="space-y-2">
                            @foreach($linksChunk as $link)
                                @php $link = (object) $link; @endphp
                                <div class="flex items-center justify-between rounded-lg bg-slate-50 hover:bg-slate-100 p-3 dark:bg-slate-800 dark:hover:bg-slate-700 transition-colors">
                                    <div class="flex-1 min-w-0 mr-3">
                                        <p class="text-sm font-medium text-blue-600 dark:text-blue-400 truncate">{{ $link->slug }}</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ Str::limit($link->target_url, 35) }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-slate-900 dark:text-white">{{ number_format($link->clicks) }}</p>
                                        <p class="text-[10px] text-slate-500">clicks</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
                @if($totalLinksPages > 1)
                <div class="flex items-center justify-center gap-2 mt-3 pt-3 border-t border-slate-200 dark:border-slate-700">
                    <button @click="topLinksPage = Math.max(0, topLinksPage - 1)" :disabled="topLinksPage === 0" class="p-2 rounded-lg border border-slate-300 dark:border-slate-600 hover:bg-slate-100 dark:hover:bg-slate-800 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    <span class="text-sm text-slate-600 dark:text-slate-400" x-text="`Page ${topLinksPage + 1} of {{ $totalLinksPages }}`"></span>
                    <button @click="topLinksPage = Math.min({{ $totalLinksPages - 1 }}, topLinksPage + 1)" :disabled="topLinksPage === {{ $totalLinksPages - 1 }}" class="p-2 rounded-lg border border-slate-300 dark:border-slate-600 hover:bg-slate-100 dark:hover:bg-slate-800 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>
                @endif
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="mb-3 text-base font-semibold text-slate-900 dark:text-white">Top Countries</h3>
                @php 
                    $countriesPerPage = 10;
                    $topCountriesArray = $topCountries->take(20)->toArray();
                    $totalCountriesPages = ceil(count($topCountriesArray) / $countriesPerPage);
                    
                    // Country code to name mapping
                    $countryNames = [
                        'SG' => 'Singapore', 'US' => 'United States', 'ID' => 'Indonesia', 'DE' => 'Germany',
                        'AU' => 'Australia', 'GB' => 'United Kingdom', 'IN' => 'India', 'NL' => 'Netherlands',
                        'BR' => 'Brazil', 'FR' => 'France', 'CA' => 'Canada', 'JP' => 'Japan',
                        'CN' => 'China', 'KR' => 'South Korea', 'IT' => 'Italy', 'ES' => 'Spain',
                        'MX' => 'Mexico', 'RU' => 'Russia', 'PH' => 'Philippines', 'TH' => 'Thailand',
                        'VN' => 'Vietnam', 'MY' => 'Malaysia', 'PL' => 'Poland', 'TR' => 'Turkey',
                    ];
                @endphp
                <div class="space-y-2 min-h-[560px]">
                    @foreach(array_chunk($topCountriesArray, $countriesPerPage) as $pageIndex => $countriesChunk)
                        <div x-show="topCountriesPage === {{ $pageIndex }}" class="space-y-2.5">
                            @foreach($countriesChunk as $country)
                                @php 
                                    $country = (object) $country;
                                    $countryCode = strtoupper($country->country ?? '');
                                    $countryName = $countryNames[$countryCode] ?? $countryCode;
                                @endphp
                                <div class="flex items-center justify-between p-2 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg transition-colors">
                                    <div class="flex items-center gap-3 flex-1">
                                        @if($countryCode && strlen($countryCode) === 2)
                                            <img src="https://flagcdn.com/24x18/{{ strtolower($countryCode) }}.png" 
                                                 alt="{{ $countryCode }}" 
                                                 class="w-6 h-4 rounded shadow-sm"
                                                 onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2224%22 height=%2218%22><rect width=%22100%%22 height=%22100%%22 fill=%22%23ccc%22/></svg>'">
                                        @else
                                            <div class="w-6 h-4 bg-slate-200 dark:bg-slate-700 rounded"></div>
                                        @endif
                                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $countryName }}</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="h-2 w-24 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
                                            @php
                                                $maxTotal = collect($topCountriesArray)->first()['total'] ?? 1;
                                                $percentage = ($country->total / $maxTotal) * 100;
                                            @endphp
                                            <div class="h-full bg-gradient-to-r from-blue-500 to-blue-600" style="width: {{ $percentage }}%"></div>
                                        </div>
                                        <span class="text-sm font-bold text-slate-900 dark:text-white w-12 text-right">{{ number_format($country->total) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
                @if($totalCountriesPages > 1)
                <div class="flex items-center justify-center gap-2 mt-3 pt-3 border-t border-slate-200 dark:border-slate-700">
                    <button @click="topCountriesPage = Math.max(0, topCountriesPage - 1)" :disabled="topCountriesPage === 0" class="p-2 rounded-lg border border-slate-300 dark:border-slate-600 hover:bg-slate-100 dark:hover:bg-slate-800 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    <span class="text-sm text-slate-600 dark:text-slate-400" x-text="`Page ${topCountriesPage + 1} of {{ $totalCountriesPages }}`"></span>
                    <button @click="topCountriesPage = Math.min({{ $totalCountriesPages - 1 }}, topCountriesPage + 1)" :disabled="topCountriesPage === {{ $totalCountriesPages - 1 }}" class="p-2 rounded-lg border border-slate-300 dark:border-slate-600 hover:bg-slate-100 dark:hover:bg-slate-800 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>
                @endif
            </div>
        </div>

        <div class="grid gap-4 lg:grid-cols-2">
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="mb-3 text-base font-semibold text-slate-900 dark:text-white">Recent Activity Log</h3>
                @php 
                    $activityPerPage = 10;
                    $totalActivityPages = ceil($recentClicks->count() / $activityPerPage);
                @endphp
                <div class="min-h-[400px]">
                    @foreach($recentClicks->chunk($activityPerPage) as $pageIndex => $activityChunk)
                        <div x-show="recentActivityPage === {{ $pageIndex }}" class="space-y-1.5">
                            @foreach($activityChunk as $click)
                                @php 
                                    $countryCode = strtoupper($click->country ?? '');
                                @endphp
                                <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 text-xs transition-colors">
                                    <span class="text-slate-500 font-mono min-w-[55px]">{{ \Carbon\Carbon::parse($click->created_at)->format('H:i:s') }}</span>
                                    <span class="text-blue-600 dark:text-blue-400 flex-1 truncate font-medium">{{ $click->link->slug ?? 'unknown' }}</span>
                                    <div class="flex items-center gap-2 min-w-[140px]">
                                        @if($countryCode && strlen($countryCode) === 2)
                                            <img src="https://flagcdn.com/16x12/{{ strtolower($countryCode) }}.png" 
                                                 alt="{{ $countryCode }}" 
                                                 class="w-4 h-3 rounded shadow-sm"
                                                 onerror="this.style.display='none'">
                                        @endif
                                        <span class="text-slate-600 dark:text-slate-400 truncate">{{ $click->city ?? $click->country ?? '—' }}</span>
                                    </div>
                                    <span class="text-slate-500 min-w-[20px]">
                                        @if(str_contains($click->user_agent ?? '', 'Mobile'))📱
                                        @elseif(str_contains($click->user_agent ?? '', 'Tablet'))📲
                                        @else💻
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
                @if($totalActivityPages > 1)
                <div class="flex items-center justify-center gap-2 mt-3 pt-3 border-t border-slate-200 dark:border-slate-700">
                    <button @click="recentActivityPage = Math.max(0, recentActivityPage - 1)" :disabled="recentActivityPage === 0" class="p-2 rounded-lg border border-slate-300 dark:border-slate-600 hover:bg-slate-100 dark:hover:bg-slate-800 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    <span class="text-sm text-slate-600 dark:text-slate-400" x-text="`Page ${recentActivityPage + 1} of {{ $totalActivityPages }}`"></span>
                    <button @click="recentActivityPage = Math.min({{ $totalActivityPages - 1 }}, recentActivityPage + 1)" :disabled="recentActivityPage === {{ $totalActivityPages - 1 }}" class="p-2 rounded-lg border border-slate-300 dark:border-slate-600 hover:bg-slate-100 dark:hover:bg-slate-800 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>
                @endif
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="mb-3 text-base font-semibold text-slate-900 dark:text-white">Recent Clicks</h3>
                @php 
                    $clicksPerPage = 10;
                    $totalClicksPages = ceil($recentClicks->count() / $clicksPerPage);
                @endphp
                <div class="min-h-[400px]">
                    @foreach($recentClicks->chunk($clicksPerPage) as $pageIndex => $clicksChunk)
                        <div x-show="recentClicksPage === {{ $pageIndex }}" class="space-y-1.5">
                            @foreach($clicksChunk as $click)
                                @php 
                                    $countryCode = strtoupper($click->country ?? '');
                                    $countryName = $countryNames[$countryCode] ?? $countryCode;
                                @endphp
                                <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                    <div class="flex items-center gap-2 flex-1 min-w-0">
                                        <span class="text-xs text-slate-500 font-mono min-w-[45px]">{{ \Carbon\Carbon::parse($click->created_at)->format('H:i') }}</span>
                                        <a href="{{ route('links.show', $click->link->slug ?? '#') }}" class="text-sm text-blue-600 hover:underline dark:text-blue-400 truncate">
                                            {{ $click->link->slug ?? 'unknown' }}
                                        </a>
                                    </div>
                                    <div class="flex items-center gap-2 ml-2">
                                        @if($countryCode && strlen($countryCode) === 2)
                                            <img src="https://flagcdn.com/16x12/{{ strtolower($countryCode) }}.png" 
                                                 alt="{{ $countryCode }}" 
                                                 class="w-4 h-3 rounded shadow-sm"
                                                 onerror="this.style.display='none'">
                                        @endif
                                        <span class="text-xs text-slate-600 dark:text-slate-400">{{ $countryName }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
                @if($totalClicksPages > 1)
                <div class="flex items-center justify-center gap-2 mt-3 pt-3 border-t border-slate-200 dark:border-slate-700">
                    <button @click="recentClicksPage = Math.max(0, recentClicksPage - 1)" :disabled="recentClicksPage === 0" class="p-2 rounded-lg border border-slate-300 dark:border-slate-600 hover:bg-slate-100 dark:hover:bg-slate-800 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    <span class="text-sm text-slate-600 dark:text-slate-400" x-text="`Page ${recentClicksPage + 1} of {{ $totalClicksPages }}`"></span>
                    <button @click="recentClicksPage = Math.min({{ $totalClicksPages - 1 }}, recentClicksPage + 1)" :disabled="recentClicksPage === {{ $totalClicksPages - 1 }}" class="p-2 rounded-lg border border-slate-300 dark:border-slate-600 hover:bg-slate-100 dark:hover:bg-slate-800 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>
                @endif
            </div>
        </div>

        <div class="grid gap-4 items-start xl:grid-cols-3">
            <div class="xl:col-span-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="mb-3 flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400">Geographic</p>
                        <h3 class="text-base font-semibold text-slate-900 dark:text-white">Distribution Map</h3>
                    </div>
                    <div class="text-right text-xs text-slate-500 dark:text-slate-400">
                        <p>Total clicks</p>
                        <p class="text-lg font-bold text-blue-600 dark:text-blue-300">{{ number_format($totalClicks) }}</p>
                    </div>
                </div>
                <div class="flex flex-col gap-3 lg:flex-row lg:items-start">
                    <div class="flex-1 min-w-[360px]">
                        <div id="choropleth-map" class="w-full rounded-lg overflow-hidden border-2 border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800" style="height: 420px;"></div>
                        <div class="mt-2 flex items-center justify-between text-[10px] text-slate-600 dark:text-slate-400">
                            <span class="font-medium">Low Traffic</span>
                            <div class="flex gap-0.5 items-center">
                                <div class="w-8 h-3 rounded-sm border border-slate-300" style="background: #e2e8f0;"><span class="text-[8px] pl-0.5">0</span></div>
                                <div class="w-8 h-3 rounded-sm border border-slate-300" style="background: #bfdbfe;"></div>
                                <div class="w-8 h-3 rounded-sm border border-slate-300" style="background: #60a5fa;"></div>
                                <div class="w-8 h-3 rounded-sm border border-slate-300" style="background: #2563eb;"></div>
                                <div class="w-8 h-3 rounded-sm border border-slate-300" style="background: #1d4ed8;"></div>
                            </div>
                            <span class="font-medium">High Traffic</span>
                        </div>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-3 text-sm dark:border-slate-700 dark:bg-slate-900/80 flex-1 min-w-[360px]">
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div class="rounded-lg border border-slate-200 bg-white p-2.5 dark:border-slate-700 dark:bg-slate-800">
                                <div class="space-y-0.5 leading-tight">
                                    <p class="text-slate-500 dark:text-slate-400">Top country</p>
                                    <p class="text-sm font-semibold text-slate-900 dark:text-white truncate">{{ $topCountries->first()->country ?? '—' }}</p>
                                    <p class="text-slate-500 dark:text-slate-400 truncate">{{ $topCountries->first()->total ?? 0 }} clicks</p>
                                </div>
                            </div>
                            <div class="rounded-lg border border-slate-200 bg-white p-2.5 dark:border-slate-700 dark:bg-slate-800">
                                <div class="space-y-0.5 leading-tight">
                                    <p class="text-slate-500 dark:text-slate-400">Countries</p>
                                    <p class="text-sm font-semibold text-slate-900 dark:text-white truncate">{{ count($mapData) }}</p>
                                    <p class="text-slate-500 dark:text-slate-400 truncate">click to focus</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <p class="text-xs uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400">Top locations</p>
                            <ul class="mt-2 space-y-1.5">
                                @foreach($topCountries->take(6) as $country)
                                    @php $code = strtoupper($country->country ?? ''); @endphp
                                    <li>
                                        <button type="button" class="flex w-full items-center justify-between rounded-lg border border-slate-200 px-2.5 py-2 text-left hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700/80"
                                            onclick="window.focusGlobalCountry('{{ $code }}')">
                                            <span class="flex items-center gap-2 text-slate-800 dark:text-slate-100">
                                                {!! country_flag($code) !!} {{ $code }}
                                            </span>
                                            <span class="text-slate-500 dark:text-slate-400">{{ number_format($country->total) }}</span>
                                        </button>
                                    </li>
                                @endforeach
                                @if($topCountries->isEmpty())
                                    <li class="text-slate-500 dark:text-slate-400">No geo data</li>
                                @endif
                            </ul>
                        </div>
                        <div class="mt-3 rounded-lg border border-slate-200 bg-white p-2.5 dark:border-slate-700 dark:bg-slate-800">
                            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">Selected country</p>
                            <p id="global-map-country-name" class="text-base font-bold text-slate-900 dark:text-white mt-1">Click a country</p>
                            <p id="global-map-country-clicks" class="text-xs text-slate-500 dark:text-slate-400">—</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="grid gap-4 md:grid-cols-3 items-start">
                    <div>
                        <h3 class="mb-2 text-sm font-semibold text-slate-900 dark:text-white">Internet Providers</h3>
                        <div class="space-y-1 text-[11px]">
                            @forelse($topIsps->take(6) as $isp)
                                <div class="flex items-center justify-between py-0.5">
                                    <span class="text-slate-600 dark:text-slate-400 truncate mr-2">{{ Str::limit($isp->isp, 16) }}</span>
                                    <span class="text-slate-900 dark:text-white font-medium">{{ number_format($isp->total) }}</span>
                                </div>
                            @empty
                                <p class="text-slate-500 text-center py-2 text-xs">No ISP data</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="flex flex-col items-center gap-2">
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Device & Platform</h3>
                        @php
                            $devices = \App\Models\LinkClick::selectRaw("
                                CASE 
                                    WHEN user_agent LIKE '%Mobile%' OR user_agent LIKE '%Android%' OR user_agent LIKE '%iPhone%' THEN 'Mobile'
                                    WHEN user_agent LIKE '%Tablet%' OR user_agent LIKE '%iPad%' THEN 'Tablet'
                                    ELSE 'Desktop'
                                END as device_type,
                                COUNT(*) as total
                            ")
                            ->groupBy('device_type')
                            ->orderByDesc('total')
                            ->get();
                        @endphp
                        <div class="flex items-center justify-center h-20">
                            <canvas id="deviceChart"></canvas>
                        </div>
                        <div class="space-y-1 text-[11px] w-full">
                            @foreach($devices as $device)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-1.5">
                                        <div class="w-2.5 h-2.5 rounded-full device-color-{{ $loop->index }}"></div>
                                        <span class="text-slate-700 dark:text-slate-300">{{ $device->device_type }}</span>
                                    </div>
                                    <span class="text-slate-900 dark:text-white font-medium">{{ number_format($device->total) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <h3 class="mb-2 text-sm font-semibold text-slate-900 dark:text-white">Security Overview</h3>
                        <div class="flex items-center justify-center h-20 mb-2">
                            <canvas id="securityChart"></canvas>
                        </div>
                        <div class="space-y-1 text-[11px]">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-2.5 h-2.5 rounded-full bg-emerald-500"></div>
                                    <span class="text-slate-700 dark:text-slate-300">Clean</span>
                                </div>
                                <span class="text-emerald-600 dark:text-emerald-400 font-bold">{{ number_format($totalClicks - $proxyCount) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-2.5 h-2.5 rounded-full bg-red-500"></div>
                                    <span class="text-slate-700 dark:text-slate-300">Proxy</span>
                                </div>
                                <span class="text-red-600 dark:text-red-400 font-bold">{{ number_format($proxyCount) }}</span>
                            </div>
                            <div class="pt-1 mt-1 border-t border-slate-200 dark:border-slate-700">
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-600 dark:text-slate-400">Rate</span>
                                    <span class="text-slate-900 dark:text-white font-bold">{{ $proxyPercentage }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .leaflet-popup-content-wrapper { padding: 5px 7px; border-radius: 10px; }
        .leaflet-popup-content { margin: 0; width: 80px !important; }
        .leaflet-popup-tip { width: 10px; height: 10px; }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 Initializing Global Analytics...');
            
            const mapData = @json($mapData);
            const maxClicks = Math.max(...Object.values(mapData), 1);
            const iso3To2 = {"ATG":"AG","BTN":"BT","ITA":"IT","TUV":"TV","AIA":"AI","AUS":"AU","BLZ":"BZ","VUT":"VU","BLR":"BY","MUS":"MU","LAO":"LA","SEN":"SN","TUR":"TR","BOL":"BO","LKA":"LK","NFK":"NF","CHN":"CN","BES":"BQ","GGY":"GG","SDN":"SD","MYT":"YT","BLM":"BL","VAT":"VA","TCA":"TC","CUW":"CW","BWA":"BW","BEN":"BJ","LTU":"LT","MSR":"MS","VGB":"VG","BDI":"BI","UMI":"UM","IRL":"IE","SLB":"SB","BMU":"BM","FIN":"FI","PER":"PE","BGD":"BD","DNK":"DK","VCT":"VC","DOM":"DO","MDA":"MD","BGR":"BG","CRI":"CR","NAM":"NA","SJM":"SJ","LUX":"LU","RUS":"RU","ARE":"AE","SXM":"SX","BHS":"BS","JPN":"JP","NGA":"NG","GHA":"GH","SLE":"SL","SPM":"PM","ALB":"AL","TKL":"TK","SHN":"SH","TON":"TO","TKM":"TM","DJI":"DJ","CAF":"CF","LBN":"LB","LVA":"LV","CCK":"CC","GMB":"GM","HND":"HN","NIU":"NU","MRT":"MR","UNK":"XK","WLF":"WF","SGS":"GS","PYF":"PF","TGO":"TG","BEL":"BE","ZMB":"ZM","CYM":"KY","PCN":"PN","COK":"CK","MDG":"MG","MNE":"ME","KOR":"KR","ETH":"ET","MNG":"MN","SVK":"SK","CUB":"CU","ATA":"AQ","GTM":"GT","GUF":"GF","NOR":"NO","GRD":"GD","REU":"RE","CHL":"CL","COL":"CO","SAU":"SA","ISR":"IL","DEU":"DE","NZL":"NZ","GRL":"GL","KGZ":"KG","SLV":"SV","FRO":"FO","PLW":"PW","MLT":"MT","SYR":"SY","TLS":"TL","HRV":"HR","PNG":"PG","NLD":"NL","LBR":"LR","SOM":"SO","VEN":"VE","HTI":"HT","DZA":"DZ","MNP":"MP","MAF":"MF","HMD":"HM","ABW":"AW","EGY":"EG","MWI":"MW","GNQ":"GQ","VIR":"VI","ECU":"EC","UZB":"UZ","GAB":"GA","SSD":"SS","IRN":"IR","KAZ":"KZ","NIC":"NI","ISL":"IS","SVN":"SI","GLP":"GP","CMR":"CM","ARG":"AR","AZE":"AZ","UGA":"UG","NER":"NE","CXR":"CX","MMR":"MM","POL":"PL","JOR":"JO","HKG":"HK","COD":"CD","ERI":"ER","KIR":"KI","MHL":"MH","BFA":"BF","ZWE":"ZW","KEN":"KE","COM":"KM","GIB":"GI","BRN":"BN","SWE":"SE","LSO":"LS","IMN":"IM","FSM":"FM","TZA":"TZ","CPV":"CV","AFG":"AF","AND":"AD","GRC":"GR","VNM":"VN","ATF":"TF","IRQ":"IQ","LBY":"LY","PRT":"PT","PAK":"PK","MDV":"MV","MAR":"MA","BIH":"BA","WSM":"WS","PSE":"PS","OMN":"OM","BHR":"BH","USA":"US","PRI":"PR","IOT":"IO","JEY":"JE","MKD":"MK","TUN":"TN","TTO":"TT","EST":"EE","SGP":"SG","PAN":"PA","CHE":"CH","URY":"UY","TJK":"TJ","TWN":"TW","ZAF":"ZA","LIE":"LI","BRA":"BR","ARM":"AM","GEO":"GE","ALA":"AX","QAT":"QA","DMA":"DM","UKR":"UA","GIN":"GN","MAC":"MO","ESH":"EH","CZE":"CZ","AUT":"AT","KNA":"KN","LCA":"LC","YEM":"YE","RWA":"RW","MCO":"MC","STP":"ST","COG":"CG","PRY":"PY","BVT":"BV","MOZ":"MZ","FRA":"FR","SWZ":"SZ","BRB":"BB","ESP":"ES","THA":"TH","GNB":"GW","AGO":"AO","IND":"IN","MTQ":"MQ","NCL":"NC","SYC":"SC","FLK":"FK","GBR":"GB","FJI":"FJ","SMR":"SM","MLI":"ML","CAN":"CA","JAM":"JM","NRU":"NR","IDN":"ID","GUM":"GU","CIV":"CI","KWT":"KW","PHL":"PH","GUY":"GY","HUN":"HU","MEX":"MX","PRK":"KP","ROU":"RO","SUR":"SR","ASM":"AS","NPL":"NP","TCD":"TD","SRB":"RS","KHM":"KH","MYS":"MY","CYP":"CY"};
            const normalizeCountryCode = (code) => {
                if (!code) return '';
                const upper = String(code).toUpperCase();
                if (upper.length === 2) return upper;
                return iso3To2[upper] || upper;
            };
            const countryLayers = {};

            const setGlobalSelected = (name, clicks) => {
                const nameEl = document.getElementById('global-map-country-name');
                const clicksEl = document.getElementById('global-map-country-clicks');
                if (nameEl && clicksEl) {
                    nameEl.textContent = name;
                    clicksEl.textContent = clicks ? `${clicks.toLocaleString()} clicks` : 'No clicks yet';
                }
            };

            window.focusGlobalCountry = (countryCode) => {
                const normalized = normalizeCountryCode(countryCode);
                if (countryLayers[normalized] && countryLayers[normalized].getBounds) {
                    map.fitBounds(countryLayers[normalized].getBounds());
                    countryLayers[normalized].openPopup();
                    const clicks = mapData[normalized] || 0;
                    const name = countryLayers[normalized].feature?.properties?.name || normalized;
                    setGlobalSelected(name, clicks);
                }
            };
            
            console.log('🗺️ Map data:', mapData, 'Max clicks:', maxClicks);
            let map;
            
            try {
                // Initialize map
                map = L.map('choropleth-map', {
                    center: [20, 0],
                    zoom: 2,
                    minZoom: 1,
                    maxZoom: 6,
                    zoomControl: true,
                    scrollWheelZoom: false,
                    worldCopyJump: true,
                    attributionControl: false
                });

                console.log('✅ Map initialized');

                // Add tile layer
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    noWrap: false
                }).addTo(map);

                console.log('✅ Tiles added');

                // Country boundaries GeoJSON
                fetch('https://raw.githubusercontent.com/johan/world.geo.json/master/countries.geo.json')
                    .then(response => {
                        console.log('📡 GeoJSON response:', response.status);
                        if (!response.ok) throw new Error('Failed to fetch GeoJSON: ' + response.status);
                        return response.json();
                    })
                    .then(geoData => {
                        console.log('✅ GeoJSON loaded:', geoData.features ? geoData.features.length : 0, 'countries');
                        console.log('📊 Available country data:', Object.keys(mapData));
                        
                        function getColor(countryCode) {
                            const code = normalizeCountryCode(countryCode);
                            const clicks = mapData[code] || 0;
                            if (clicks === 0) return '#e2e8f0';
                            const intensity = clicks / maxClicks;
                            if (intensity > 0.7) return '#1d4ed8';
                            if (intensity > 0.4) return '#2563eb';
                            if (intensity > 0.2) return '#60a5fa';
                            return '#bfdbfe';
                        }

                        function style(feature) {
                            return {
                                fillColor: getColor(feature.id ? feature.id.toUpperCase() : ''),
                                weight: 1,
                                opacity: 1,
                                color: '#cbd5e1',
                                fillOpacity: 0.8
                            };
                        }

                        function highlightFeature(e) {
                            const layer = e.target;
                            layer.setStyle({
                                weight: 2,
                                color: '#475569',
                                fillOpacity: 0.95
                            });
                            if (!L.Browser.ie && !L.Browser.opera && !L.Browser.edge) {
                                layer.bringToFront();
                            }
                        }

                        function resetHighlight(e) {
                            geojson.resetStyle(e.target);
                        }

                        function onEachFeature(feature, layer) {
                            const countryCode = normalizeCountryCode(feature.id || feature.properties?.iso_a2);
                            const clicks = mapData[countryCode] || 0;
                            const countryName = feature.properties.name;
                            countryLayers[countryCode] = layer;
                            
                            layer.bindPopup(`
                                <div class="text-center p-1" style="width: 110px;">
                                    <p class="font-bold text-sm">${countryName}</p>
                                    <p class="text-xs text-slate-600">${clicks.toLocaleString()} clicks</p>
                                </div>
                            `);
                            
                            layer.on({
                                mouseover: highlightFeature,
                                mouseout: resetHighlight,
                                click: function(e) {
                                    map.fitBounds(e.target.getBounds());
                                    setGlobalSelected(countryName, clicks);
                                }
                            });
                        }

                        const geojson = L.geoJSON(geoData, {
                            style: style,
                            onEachFeature: onEachFeature
                        }).addTo(map);
                    })
                    .catch(error => {
                        console.error('❌ Map loading error:', error);
                        document.getElementById('choropleth-map').innerHTML = 
                            '<div class="flex items-center justify-center h-full"><div class="text-center"><p class="text-sm text-red-600 font-semibold">Map Failed to Load</p><p class="text-xs text-slate-500 mt-1">' + error.message + '</p><button onclick="location.reload()" class="mt-2 px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">Retry</button></div></div>';
                    });
            } catch (error) {
                console.error('❌ Map initialization error:', error);
                document.getElementById('choropleth-map').innerHTML = 
                    '<div class="flex items-center justify-center h-full text-red-600"><p class="text-sm">Failed to initialize map: ' + error.message + '</p></div>';
            }

            const labelsLine = {!! json_encode($performanceData['clicks']->keys()) !!};
            const clicksValues = {!! json_encode(array_values($performanceData['clicks']->toArray())) !!};
            const linksValues = {!! json_encode(array_values($performanceData['links']->toArray())) !!};
            const usersValues = {!! json_encode(array_values($performanceData['users']->toArray())) !!};

            const buildLine = (id, data, color) => {
                const el = document.getElementById(id);
                if (!el) return;
                new Chart(el, {
                    type: 'line',
                    data: {
                        labels: labelsLine,
                        datasets: [{
                            data,
                            borderColor: color,
                            backgroundColor: color + '33',
                            borderWidth: 2,
                            tension: 0.35,
                            pointRadius: 3,
                            pointHoverRadius: 5
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { ticks: { autoSkip: true, maxTicksLimit: 6 } },
                            y: { beginAtZero: true, grid: { color: 'rgba(148,163,184,0.15)' } }
                        }
                    }
                });
            };

            buildLine('clicksLine', clicksValues, '#3b82f6');
            buildLine('linksLine', linksValues, '#10b981');
            buildLine('usersLine', usersValues, '#8b5cf6');

            // Device Chart (SMALLER)
            const deviceData = @json($devices);
            const deviceLabels = deviceData.map(d => d.device_type);
            const deviceCounts = deviceData.map(d => d.total);
            const deviceColors = ['#3b82f6', '#10b981', '#f59e0b'];

            deviceColors.forEach((color, index) => {
                const style = document.createElement('style');
                style.innerHTML = `.device-color-${index} { background-color: ${color}; }`;
                document.head.appendChild(style);
            });

            const deviceChart = new Chart(document.getElementById('deviceChart'), {
                type: 'doughnut',
                data: {
                    labels: deviceLabels,
                    datasets: [{
                        data: deviceCounts,
                        backgroundColor: deviceColors,
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((context.parsed / total) * 100).toFixed(1);
                                    return context.label + ': ' + context.parsed.toLocaleString() + ' (' + percentage + '%)';
                                }
                            }
                        }
                    },
                    cutout: '65%'
                }
            });

            // Security Chart (SMALLER)
            const cleanTraffic = {{ $totalClicks - $proxyCount }};
            const proxyTraffic = {{ $proxyCount }};

            const securityChart = new Chart(document.getElementById('securityChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Clean Traffic', 'Proxy/VPN'],
                    datasets: [{
                        data: [cleanTraffic, proxyTraffic],
                        backgroundColor: ['#10b981', '#ef4444'],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((context.parsed / total) * 100).toFixed(1);
                                    return context.label + ': ' + context.parsed.toLocaleString() + ' (' + percentage + '%)';
                                }
                            }
                        }
                    },
                    cutout: '65%'
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
