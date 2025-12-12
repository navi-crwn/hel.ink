@php use Illuminate\Support\Str; use Illuminate\Support\Facades\Storage; @endphp
<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Link Analytics" subtitle="Performance metrics for this link">
            <x-slot name="actions">
                <a href="{{ route('dashboard') }}" class="rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">Dashboard</a>
                <a href="{{ route('links.edit', $link) }}" class="rounded-full bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-500">Edit Link</a>
            </x-slot>
        </x-page-header>
    </x-slot>
    <div class="py-6">
        <div class="mx-auto max-w-7xl space-y-4 sm:px-6 lg:px-8">
            <div class="grid gap-4 md:grid-cols-3">
                <div class="md:col-span-2 rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <p class="text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Shortlink</p>
                    <p class="mt-1 text-lg font-semibold text-blue-600 dark:text-blue-400 break-all">{{ $link->short_url }}</p>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400 truncate" title="{{ $link->target_url }}">→ {{ Str::limit($link->target_url, 80) }}</p>
                </div>
                <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-gradient-to-br from-blue-50 to-blue-100 p-4 dark:border-slate-800 dark:from-blue-900/20 dark:to-blue-800/20">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wider text-blue-600 dark:text-blue-400">Total Clicks</p>
                        <p class="mt-1 text-3xl font-bold text-blue-900 dark:text-blue-100">{{ number_format($link->clicks) }}</p>
                    </div>
                    @if ($link->qr_code_path)
                        <img src="{{ Storage::url($link->qr_code_path) }}" alt="QR" class="h-16 w-16 rounded-lg border-2 border-blue-200 dark:border-blue-700">
                    @endif
                </div>
            </div>
            <div class="grid gap-4 lg:grid-cols-2">
                <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-3">Daily Clicks (Last {{ $rangeInDays }} days)</h3>
                    <div class="relative" style="height: 200px;">
                        <canvas id="clickChart"></canvas>
                    </div>
                </div>
                <div class="grid grid-rows-2 gap-4">
                    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <h3 class="text-xs font-semibold text-slate-600 dark:text-slate-400 mb-2">Top Locations</h3>
                        <div class="space-y-1">
                            @forelse($countries->take(3) as $item)
                                <div class="flex items-center justify-between text-xs">
                                    <span>{!! country_flag($item->country) !!} {{ country_name($item->country) }}</span>
                                    <span class="text-slate-500">{{ $item->total }}</span>
                                </div>
                            @empty
                                <p class="text-xs text-slate-400">No data</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <h3 class="text-xs font-semibold text-slate-600 dark:text-slate-400 mb-2">Technology</h3>
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            @foreach($devices as $device => $count)
                                @php $pct = $totalClicks > 0 ? round(($count / $totalClicks) * 100) : 0; @endphp
                                <div>
                                    <span class="text-slate-600 dark:text-slate-300">{{ $device }}</span>
                                    <span class="text-slate-400">{{ $pct }}%</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900" x-data="clickLog()">
                <div class="border-b border-slate-200 p-4 dark:border-slate-700">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Click Log</h3>
                        <span class="text-xs text-slate-500">{{ $recentClicks->count() }} clicks (last {{ $rangeInDays }} days)</span>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <input x-model="searchQuery" @input="filterClicks" type="text" placeholder="Search IP, location, ISP..." class="text-xs rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-800 px-3 py-1.5 w-64">
                        <select x-model="deviceFilter" @change="filterClicks" class="text-xs rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-800 px-3 py-1.5">
                            <option value="">All Devices</option>
                            <option value="Mobile">Mobile</option>
                            <option value="Desktop">Desktop</option>
                        </select>
                        <select x-model="proxyFilter" @change="filterClicks" class="text-xs rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-800 px-3 py-1.5">
                            <option value="">All Traffic</option>
                            <option value="clean">Clean IPs</option>
                            <option value="proxy">Proxy/VPN</option>
                        </select>
                        <button @click="resetFilters" class="text-xs rounded-lg border border-slate-300 dark:border-slate-600 px-3 py-1.5 hover:bg-slate-50 dark:hover:bg-slate-800">Reset</button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-xs">
                        <thead class="text-left text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-800/50 sticky top-0">
                            <tr class="border-b border-slate-200 dark:border-slate-700">
                                <th class="py-2 px-3 font-medium">Time</th>
                                <th class="py-2 px-3 font-medium">Location</th>
                                <th class="py-2 px-3 font-medium">ISP</th>
                                <th class="py-2 px-3 font-medium">Device</th>
                                <th class="py-2 px-3 font-medium">Referrer</th>
                                <th class="py-2 px-3 font-medium text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            <template x-for="click in paginatedClicks" :key="click.id">
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30">
                                    <td class="py-2 px-3 whitespace-nowrap" x-text="click.time"></td>
                                    <td class="py-2 px-3" x-html="click.location"></td>
                                    <td class="py-2 px-3 truncate max-w-[120px]" :title="click.isp" x-text="click.isp"></td>
                                    <td class="py-2 px-3" x-text="click.device"></td>
                                    <td class="py-2 px-3 truncate max-w-[150px]" :title="click.referrer" x-text="click.referrer"></td>
                                    <td class="py-2 px-3 text-center" x-html="click.proxy_badge"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
                <div class="border-t border-slate-200 dark:border-slate-700 p-3 flex items-center justify-between">
                    <div class="text-xs text-slate-500">
                        Showing <span x-text="(currentPage - 1) * perPage + 1"></span> to <span x-text="Math.min(currentPage * perPage, filteredClicks.length)"></span> of <span x-text="filteredClicks.length"></span>
                    </div>
                    <div class="flex gap-1">
                        <button @click="currentPage--" :disabled="currentPage === 1" class="px-3 py-1 text-xs rounded border border-slate-200 dark:border-slate-700 disabled:opacity-50">Prev</button>
                        <button @click="currentPage++" :disabled="currentPage * perPage >= filteredClicks.length" class="px-3 py-1 text-xs rounded border border-slate-200 dark:border-slate-700 disabled:opacity-50">Next</button>
                    </div>
                </div>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Notes</h3>
                    <span class="text-xs text-slate-500">{{ $comments->count() }} notes</span>
                </div>
                @auth
                    <form method="POST" action="{{ route('links.comments.store', $link) }}" class="mb-3">
                        @csrf
                        <textarea name="body" rows="2" class="w-full rounded-lg border-slate-200 text-sm dark:border-slate-700 dark:bg-slate-800" placeholder="Add note..." required></textarea>
                        <button class="mt-2 rounded-lg bg-blue-600 px-4 py-1.5 text-xs font-semibold text-white hover:bg-blue-700">Add Note</button>
                    </form>
                @endauth
                <div class="space-y-2 max-h-48 overflow-y-auto">
                    @forelse ($comments as $comment)
                        <div class="rounded-lg border border-slate-200 px-3 py-2 dark:border-slate-700">
                            <p class="text-xs text-slate-700 dark:text-slate-300">{{ $comment->body }}</p>
                            <p class="text-xs text-slate-400 mt-1">{{ $comment->user->name ?? 'You' }} · {{ $comment->created_at->diffForHumans() }}</p>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400">No notes yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Chart
            new Chart(document.getElementById('clickChart'), {
                type: 'line',
                data: {
                    labels: {!! json_encode(array_keys($dailyClicks)) !!},
                    datasets: [{
                        data: {!! json_encode(array_values($dailyClicks)) !!},
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, ticks: { precision: 0 } }
                    }
                }
            });
            // Click Log Alpine Component
            function clickLog() {
                return {
                    allClicks: {!! json_encode($recentClicks->map(function($click) {
                        $ua = $click->user_agent ? app(\App\Services\UserAgentService::class)->parse($click->user_agent) : null;
                        $location = $click->country ? (country_flag($click->country) . ' ' . ($click->city ?? country_name($click->country))) : '-';
                        $proxyBadge = $click->is_proxy ? '<span class="inline-flex px-2 py-0.5 rounded text-xs bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">⚠</span>' : '<span class="text-green-500">✓</span>';
                        return [
                            'id' => $click->id,
                            'time' => $click->clicked_at ? $click->clicked_at->format('M d H:i') : $click->created_at->format('M d H:i'),
                            'location' => $location,
                            'isp' => Str::limit($click->isp ?? '-', 20),
                            'device' => $ua['device'] ?? '-',
                            'referrer' => Str::limit($click->referer ?? '(direct)', 25),
                            'proxy_badge' => $proxyBadge,
                            'device_raw' => $ua['device'] ?? '',
                            'is_proxy' => $click->is_proxy,
                            'search_text' => ($click->ip_address ?? '') . ' ' . ($click->city ?? '') . ' ' . ($click->isp ?? ''),
                        ];
                    })) !!},
                    filteredClicks: [],
                    searchQuery: '',
                    deviceFilter: '',
                    proxyFilter: '',
                    currentPage: 1,
                    perPage: 15,
                    init() {
                        this.filteredClicks = this.allClicks;
                    },
                    filterClicks() {
                        this.filteredClicks = this.allClicks.filter(click => {
                            let match = true;
                            if (this.searchQuery) {
                                match = match && click.search_text.toLowerCase().includes(this.searchQuery.toLowerCase());
                            }
                            if (this.deviceFilter) {
                                match = match && click.device_raw === this.deviceFilter;
                            }
                            if (this.proxyFilter === 'clean') {
                                match = match && !click.is_proxy;
                            } else if (this.proxyFilter === 'proxy') {
                                match = match && click.is_proxy;
                            }
                            return match;
                        });
                        this.currentPage = 1;
                    },
                    resetFilters() {
                        this.searchQuery = '';
                        this.deviceFilter = '';
                        this.proxyFilter = '';
                        this.filterClicks();
                    },
                    get paginatedClicks() {
                        const start = (this.currentPage - 1) * this.perPage;
                        return this.filteredClicks.slice(start, start + this.perPage);
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>
