@php
    $trendLabels = $activityTrend->keys()->map(fn ($day) => \Carbon\Carbon::parse($day)->format('M d'));
    $trendValues = $activityTrend->values();
@endphp
<x-app-layout>
    <x-slot name="pageTitle">HEL.ink - Admin</x-slot>

    <div class="py-4">
        <div class="mx-auto max-w-7xl space-y-4 px-4 md:px-6">
            <div class="grid gap-4 md:grid-cols-4">
                <div class="rounded-xl bg-white p-4 shadow dark:bg-gray-900">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total users</p>
                    <p class="text-3xl font-semibold text-gray-900 dark:text-white">{{ $stats['users'] }}</p>
                </div>
               <div class="rounded-xl bg-white p-4 shadow dark:bg-gray-900">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total links</p>
                    <p class="text-3xl font-semibold text-gray-900 dark:text-white">{{ $stats['links'] }}</p>
                </div>
                <div class="rounded-xl bg-white p-4 shadow dark:bg-gray-900">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total clicks</p>
                    <p class="text-3xl font-semibold text-gray-900 dark:text-white">{{ $stats['clicks'] }}</p>
                </div>
                <div class="rounded-xl bg-white p-4 shadow dark:bg-gray-900">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Clicks today</p>
                    <p class="text-3xl font-semibold text-gray-900 dark:text-white">{{ $stats['today_clicks'] }}</p>
                </div>
            </div>
            <div class="grid gap-6 lg:grid-cols-2">
                <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Clicks (last 10 days)</h3>
                        <span class="text-xs text-gray-500">Live stream</span>
                    </div>
                    <canvas id="adminTrendChart" class="mt-4" height="140"></canvas>
                </div>
                <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">System health</h3>
                    <div class="mt-4 grid gap-4 md:grid-cols-2">
                        <div class="rounded-xl border border-gray-100 p-4 dark:border-gray-800" x-data="{ restarting: false }">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Queue worker</p>
                                    <p class="text-2xl font-semibold {{ $queueHeartbeat ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                        {{ $queueHeartbeat ? 'Online' : 'Stale' }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $queueHeartbeat ? 'Last ping '.\Carbon\Carbon::parse($queueHeartbeat)->diffForHumans() : 'No heartbeat recorded' }}
                                    </p>
                                </div>
                                <form method="POST" action="{{ route('admin.queue.restart') }}" class="ml-2" @submit="restarting = true">
                                    @csrf
                                    <button type="submit" 
                                            :disabled="restarting"
                                            class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 disabled:opacity-50 disabled:cursor-not-allowed"
                                            title="Restart queue worker">
                                        <svg class="h-4 w-4" :class="{ 'animate-spin': restarting }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="rounded-xl border border-gray-100 p-4 dark:border-gray-800">
                            <p class="text-sm text-gray-500 dark:text-gray-400">IP bans</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $ipBanCount }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Active rules guarding public form.</p>
                        </div>
                        <div class="rounded-xl border border-gray-100 p-4 dark:border-gray-800">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Suspended users</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $suspendedUsers }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Accounts currently locked.</p>
                        </div>
                        <div class="rounded-xl border border-gray-100 p-4 dark:border-gray-800">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Inactive links</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $inactiveLinks }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Links disabled by owners or moderators.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent users</h3>
                        @if (Auth::user()->isSuperAdmin())
                            <a href="{{ route('admin.users.index') }}" class="text-sm text-blue-600 hover:underline">Manage users</a>
                        @endif
                    </div>
                    <ul class="mt-4 space-y-3">
                        @forelse ($recentUsers as $user)
                            <li class="rounded-xl border border-gray-100 px-4 py-3 dark:border-gray-800">
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                <p class="text-xs text-gray-400">Role: {{ $user->isSuperAdmin() ? 'Superadmin' : ucfirst($user->role) }} · Status: {{ ucfirst($user->status) }}</p>
                                <p class="text-xs text-gray-500">Last login: {{ $user->last_login_at?->diffForHumans() ?? 'Never' }} {{ $user->last_login_ip ? '('.$user->last_login_ip.')' : '' }}</p>
                            </li>
                        @empty
                            <li class="text-sm text-gray-500">No activity yet.</li>
                        @endforelse
                    </ul>
                </div>
                <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent links</h3>
                        <div class="flex items-center gap-3 text-sm">
                            <a href="{{ route('admin.links.index') }}" class="text-blue-600 hover:underline">Manage links</a>
                            <a href="{{ route('admin.ip-bans.index') }}" class="text-rose-500 hover:underline">IP Ban</a>
                        </div>
                    </div>
                    <ul class="mt-4 space-y-3">
                        @forelse ($recentLinks as $link)
                            <li class="rounded-xl border border-gray-100 px-4 py-3 dark:border-gray-800">
                                <p class="font-semibold text-blue-600 dark:text-blue-400">{{ $link->short_url }}</p>
                                <p class="text-sm text-gray-500 truncate">{{ $link->target_url }}</p>
                                <p class="text-xs text-gray-400">By: {{ $link->user->name ?? 'Guest' }} · Status: {{ ucfirst($link->status) }}</p>
                            </li>
                        @empty
                            <li class="text-sm text-gray-500">No data yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Latest abuse reports</h3>
                    <a href="{{ route('admin.abuse.index') }}" class="text-sm text-rose-500 hover:underline">View all reports</a>
                    </div>
                    <ul class="mt-4 space-y-3">
                        @forelse ($openReports as $report)
                            <li class="rounded-xl border border-gray-100 px-4 py-3 dark:border-gray-800">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $report->slug ?? 'URL' }}</p>
                                <p class="text-xs text-gray-400 break-all">{{ $report->url }}</p>
                                <p class="mt-1 text-xs text-gray-400">Status: {{ ucfirst($report->status) }} · {{ $report->created_at->diffForHumans() }}</p>
                            </li>
                        @empty
                            <li class="text-sm text-gray-500 dark:text-gray-400">No pending reports.</li>
                        @endforelse
                    </ul>
                </div>
                <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Top countries</h3>
                    <ul class="mt-4 space-y-2 text-sm">
                        @forelse ($topCountries as $country)
                            <li class="flex items-center justify-between rounded-xl border border-gray-100 px-4 py-2 dark:border-gray-800">
                                <span>{{ $country->country ?? 'Unknown' }}</span>
                                <span class="text-gray-500">{{ $country->total }} clicks</span>
                            </li>
                        @empty
                            <li class="text-gray-500 dark:text-gray-400">No country data yet.</li>
                        @endforelse
                    </ul>
                    <p class="mt-4 text-xs text-gray-500 dark:text-gray-400">Use this list to spot unusual geo spikes quickly.</p>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const adminCtx = document.getElementById('adminTrendChart').getContext('2d');
            new Chart(adminCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($trendLabels) !!},
                    datasets: [{
                        label: 'Clicks',
                        data: {!! json_encode($trendValues) !!},
                        borderColor: '#0ea5e9',
                        backgroundColor: 'rgba(14,165,233,0.15)',
                        fill: true,
                        tension: 0.3,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
                }
            });
        </script>
    @endpush
</x-app-layout>
