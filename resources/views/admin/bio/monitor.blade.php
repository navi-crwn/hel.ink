<x-app-layout>
    <x-slot name="pageTitle">Bio Pages Monitoring - Admin</x-slot>

    <div class="py-6">
        {{-- Header --}}
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Bio Pages Monitoring</h1>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Analytics & monitoring dashboard for all Link in Bio pages</p>
            </div>
            <div class="flex items-center gap-3">
                {{-- Period Filter --}}
                <form method="GET" class="flex items-center gap-2">
                    <select name="period" onchange="this.form.submit()" class="rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                        <option value="7" {{ $period == '7' ? 'selected' : '' }}>Last 7 days</option>
                        <option value="14" {{ $period == '14' ? 'selected' : '' }}>Last 14 days</option>
                        <option value="30" {{ $period == '30' ? 'selected' : '' }}>Last 30 days</option>
                        <option value="60" {{ $period == '60' ? 'selected' : '' }}>Last 60 days</option>
                        <option value="90" {{ $period == '90' ? 'selected' : '' }}>Last 90 days</option>
                    </select>
                </form>
                {{-- Export Dropdown --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Export
                        <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-lg bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 dark:bg-slate-800">
                        <a href="{{ route('admin.bio.export', ['type' => 'bio_pages']) }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700">Export Bio Pages</a>
                        <a href="{{ route('admin.bio.export', ['type' => 'clicks']) }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700">Export Clicks</a>
                    </div>
                </div>
                <a href="{{ route('admin.bio.index') }}" class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    Manage Bio Pages
                </a>
            </div>
        </div>

        {{-- Main Stats Cards --}}
        <div class="mb-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-lg bg-white p-6 shadow dark:bg-slate-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Total Bio Pages</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['total_bio_pages']) }}</p>
                        <p class="mt-1 text-xs text-green-600 dark:text-green-400">+{{ $periodStats['new_bio_pages'] }} in {{ $period }} days</p>
                    </div>
                    <div class="rounded-full bg-blue-100 p-3 dark:bg-blue-900/30">
                        <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="rounded-lg bg-white p-6 shadow dark:bg-slate-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Total Views</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['total_views']) }}</p>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">All time page views</p>
                    </div>
                    <div class="rounded-full bg-purple-100 p-3 dark:bg-purple-900/30">
                        <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="rounded-lg bg-white p-6 shadow dark:bg-slate-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Total Clicks</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['total_clicks']) }}</p>
                        <p class="mt-1 text-xs text-green-600 dark:text-green-400">+{{ number_format($periodStats['period_clicks']) }} in {{ $period }} days</p>
                    </div>
                    <div class="rounded-full bg-green-100 p-3 dark:bg-green-900/30">
                        <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="rounded-lg bg-white p-6 shadow dark:bg-slate-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Active Links</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['active_links']) }}</p>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">of {{ number_format($stats['total_links']) }} total</p>
                    </div>
                    <div class="rounded-full bg-yellow-100 p-3 dark:bg-yellow-900/30">
                        <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Secondary Stats --}}
        <div class="mb-6 grid gap-4 sm:grid-cols-3">
            <div class="rounded-lg bg-white p-4 shadow dark:bg-slate-800">
                <div class="flex items-center gap-3">
                    <div class="rounded-full bg-green-100 p-2 dark:bg-green-900/30">
                        <svg class="h-5 w-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['published'] }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Published</p>
                    </div>
                </div>
            </div>
            <div class="rounded-lg bg-white p-4 shadow dark:bg-slate-800">
                <div class="flex items-center gap-3">
                    <div class="rounded-full bg-gray-100 p-2 dark:bg-gray-700">
                        <svg class="h-5 w-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['draft'] }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Draft</p>
                    </div>
                </div>
            </div>
            <div class="rounded-lg bg-white p-4 shadow dark:bg-slate-800">
                <div class="flex items-center gap-3">
                    <div class="rounded-full bg-indigo-100 p-2 dark:bg-indigo-900/30">
                        <svg class="h-5 w-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['unique_users'] }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Unique Users</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Clicks Chart --}}
        <div class="mb-6 rounded-lg bg-white p-6 shadow dark:bg-slate-800">
            <h2 class="mb-4 text-lg font-semibold text-slate-900 dark:text-white">Clicks Over Time</h2>
            <div class="h-64">
                <canvas id="clicksChart"></canvas>
            </div>
        </div>

        {{-- Main Content Grid --}}
        <div class="grid gap-6 lg:grid-cols-3">
            {{-- Left Column - Top Bio Pages & Links --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Top Bio Pages --}}
                <div class="rounded-lg bg-white p-6 shadow dark:bg-slate-800">
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Top Bio Pages</h2>
                        <a href="{{ route('admin.bio.index') }}" class="text-sm text-blue-600 hover:underline dark:text-blue-400">View all</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-slate-200 dark:border-slate-700">
                                    <th class="pb-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Bio Page</th>
                                    <th class="pb-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">User</th>
                                    <th class="pb-3 text-right text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Views</th>
                                    <th class="pb-3 text-right text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Links</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                                @forelse($topBioPages as $bio)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                                        <td class="py-3">
                                            <a href="{{ route('admin.bio.show', $bio) }}" class="font-medium text-slate-900 hover:text-blue-600 dark:text-white dark:hover:text-blue-400">
                                                {{ Str::limit($bio->title, 25) }}
                                            </a>
                                            <p class="text-xs text-slate-500 dark:text-slate-400">/b/{{ $bio->slug }}</p>
                                        </td>
                                        <td class="py-3">
                                            <p class="text-sm text-slate-600 dark:text-slate-300">{{ $bio->user->name ?? 'N/A' }}</p>
                                        </td>
                                        <td class="py-3 text-right">
                                            <span class="font-semibold text-slate-900 dark:text-white">{{ number_format($bio->view_count) }}</span>
                                        </td>
                                        <td class="py-3 text-right">
                                            <span class="text-slate-600 dark:text-slate-400">{{ $bio->links_count }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-6 text-center text-sm text-slate-500 dark:text-slate-400">No bio pages yet</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Top Links --}}
                <div class="rounded-lg bg-white p-6 shadow dark:bg-slate-800">
                    <h2 class="mb-4 text-lg font-semibold text-slate-900 dark:text-white">Top Performing Links</h2>
                    <div class="space-y-3">
                        @forelse($topLinks as $link)
                            <div class="flex items-center justify-between rounded-lg border border-slate-200 p-3 dark:border-slate-700">
                                <div class="flex-1 min-w-0">
                                    <p class="truncate font-medium text-slate-900 dark:text-white">{{ $link->title }}</p>
                                    <p class="truncate text-xs text-slate-500 dark:text-slate-400">
                                        {{ $link->bioPage->title ?? 'Unknown' }} • {{ Str::limit($link->url, 40) }}
                                    </p>
                                </div>
                                <div class="ml-4 flex-shrink-0 text-right">
                                    <p class="text-lg font-bold text-slate-900 dark:text-white">{{ number_format($link->click_count) }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">clicks</p>
                                </div>
                            </div>
                        @empty
                            <div class="py-6 text-center text-sm text-slate-500 dark:text-slate-400">No clicks recorded yet</div>
                        @endforelse
                    </div>
                </div>

                {{-- Recent Bio Pages --}}
                <div class="rounded-lg bg-white p-6 shadow dark:bg-slate-800">
                    <h2 class="mb-4 text-lg font-semibold text-slate-900 dark:text-white">Recently Created</h2>
                    <div class="space-y-3">
                        @forelse($recentBioPages as $bio)
                            <div class="flex items-center gap-3">
                                @if($bio->avatar_url)
                                    <img src="{{ Storage::url($bio->avatar_url) }}" class="h-10 w-10 rounded-full object-cover" alt="">
                                @else
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 text-lg font-bold text-white">
                                        {{ substr($bio->title, 0, 1) }}
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <a href="{{ route('admin.bio.show', $bio) }}" class="font-medium text-slate-900 hover:text-blue-600 dark:text-white dark:hover:text-blue-400">
                                        {{ $bio->title }}
                                    </a>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">by {{ $bio->user->name ?? 'Unknown' }} • {{ $bio->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="flex-shrink-0">
                                    @if($bio->is_published)
                                        <span class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-800 dark:bg-green-900/30 dark:text-green-400">Published</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-800 dark:bg-gray-900/30 dark:text-gray-400">Draft</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="py-6 text-center text-sm text-slate-500 dark:text-slate-400">No bio pages yet</div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Right Column - Activity & Stats --}}
            <div class="space-y-6">
                {{-- Clicks by Country --}}
                <div class="rounded-lg bg-white p-6 shadow dark:bg-slate-800">
                    <h2 class="mb-4 text-lg font-semibold text-slate-900 dark:text-white">Clicks by Country</h2>
                    @if($clicksByCountry->count() > 0)
                        <div class="space-y-2">
                            @foreach($clicksByCountry as $country)
                                @php
                                    $maxCount = $clicksByCountry->max('count');
                                    $percentage = $maxCount > 0 ? ($country->count / $maxCount) * 100 : 0;
                                @endphp
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-slate-700 dark:text-slate-300">{{ $country->country }}</span>
                                        <span class="font-medium text-slate-900 dark:text-white">{{ number_format($country->count) }}</span>
                                    </div>
                                    <div class="h-2 w-full rounded-full bg-slate-200 dark:bg-slate-700">
                                        <div class="h-2 rounded-full bg-blue-500" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-6 text-center text-sm text-slate-500 dark:text-slate-400">No data available</div>
                    @endif
                </div>

                {{-- Clicks by Device --}}
                <div class="rounded-lg bg-white p-6 shadow dark:bg-slate-800">
                    <h2 class="mb-4 text-lg font-semibold text-slate-900 dark:text-white">Clicks by Device</h2>
                    @if($clicksByDevice->count() > 0)
                        <div class="space-y-3">
                            @foreach($clicksByDevice as $device)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        @if(strtolower($device->device) === 'mobile')
                                            <svg class="h-5 w-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                            </svg>
                                        @elseif(strtolower($device->device) === 'tablet')
                                            <svg class="h-5 w-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                            </svg>
                                        @else
                                            <svg class="h-5 w-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                        @endif
                                        <span class="text-sm text-slate-700 dark:text-slate-300 capitalize">{{ $device->device }}</span>
                                    </div>
                                    <span class="font-semibold text-slate-900 dark:text-white">{{ number_format($device->count) }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-6 text-center text-sm text-slate-500 dark:text-slate-400">No data available</div>
                    @endif
                </div>

                {{-- Clicks by Browser --}}
                <div class="rounded-lg bg-white p-6 shadow dark:bg-slate-800">
                    <h2 class="mb-4 text-lg font-semibold text-slate-900 dark:text-white">Clicks by Browser</h2>
                    @if($clicksByBrowser->count() > 0)
                        <div class="space-y-3">
                            @foreach($clicksByBrowser as $browser)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-slate-700 dark:text-slate-300">{{ $browser->browser }}</span>
                                    <span class="font-semibold text-slate-900 dark:text-white">{{ number_format($browser->count) }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-6 text-center text-sm text-slate-500 dark:text-slate-400">No data available</div>
                    @endif
                </div>

                {{-- Top Creators --}}
                <div class="rounded-lg bg-white p-6 shadow dark:bg-slate-800">
                    <h2 class="mb-4 text-lg font-semibold text-slate-900 dark:text-white">Top Creators</h2>
                    <div class="space-y-3">
                        @forelse($topCreators as $creator)
                            <div class="flex items-center gap-3">
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 text-sm font-bold text-white">
                                    {{ substr($creator->name, 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="truncate text-sm font-medium text-slate-900 dark:text-white">{{ $creator->name }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $creator->email }}</p>
                                </div>
                                <div class="flex-shrink-0 text-right">
                                    <span class="text-lg font-bold text-slate-900 dark:text-white">{{ $creator->bio_pages_count }}</span>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">pages</p>
                                </div>
                            </div>
                        @empty
                            <div class="py-6 text-center text-sm text-slate-500 dark:text-slate-400">No creators yet</div>
                        @endforelse
                    </div>
                </div>

                {{-- Recent Activity --}}
                <div class="rounded-lg bg-white p-6 shadow dark:bg-slate-800">
                    <h2 class="mb-4 text-lg font-semibold text-slate-900 dark:text-white">Recent Clicks</h2>
                    @if($recentClicks->count() > 0)
                        <div class="space-y-3 max-h-80 overflow-y-auto">
                            @foreach($recentClicks as $click)
                                <div class="border-b border-slate-100 pb-3 last:border-0 dark:border-slate-700">
                                    <p class="text-sm font-medium text-slate-900 dark:text-white truncate">
                                        {{ $click->bioLink->title ?? 'Unknown Link' }}
                                    </p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">
                                        {{ $click->bioLink->bioPage->title ?? 'Unknown' }}
                                    </p>
                                    <div class="mt-1 flex items-center justify-between text-xs text-slate-400 dark:text-slate-500">
                                        <span>{{ $click->created_at->diffForHumans() }}</span>
                                        <div class="flex items-center gap-2">
                                            @if($click->country)
                                                <span>{{ $click->country }}</span>
                                            @endif
                                            @if($click->device)
                                                <span>• {{ $click->device }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-6 text-center text-sm text-slate-500 dark:text-slate-400">No recent activity</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('clicksChart').getContext('2d');
            
            const isDark = document.documentElement.classList.contains('dark');
            const gridColor = isDark ? 'rgba(148, 163, 184, 0.1)' : 'rgba(148, 163, 184, 0.2)';
            const textColor = isDark ? '#94a3b8' : '#64748b';
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        label: 'Clicks',
                        data: @json($chartClicks),
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        fill: true,
                        tension: 0.3,
                        pointBackgroundColor: '#3b82f6',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: isDark ? '#1e293b' : '#fff',
                            titleColor: isDark ? '#f1f5f9' : '#0f172a',
                            bodyColor: isDark ? '#94a3b8' : '#64748b',
                            borderColor: isDark ? '#334155' : '#e2e8f0',
                            borderWidth: 1,
                            padding: 12,
                            cornerRadius: 8,
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: gridColor,
                            },
                            ticks: {
                                color: textColor,
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: gridColor,
                            },
                            ticks: {
                                color: textColor,
                                precision: 0
                            }
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
