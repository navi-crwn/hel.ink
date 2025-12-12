<x-app-layout>
    <x-slot name="pageTitle">HEL.ink - Analytics</x-slot>
    <x-slot name="header">
        <x-page-header title="Analytics" subtitle="Deep dive into performance">
            <x-slot name="actions">
                @if(auth()->user()->isSuperAdmin())
                    <a href="{{ route('dashboard') }}" class="rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">Admin Home</a>
                    <a href="{{ route('admin.users.index') }}" class="rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">Manage Users</a>
                    <a href="{{ route('admin.links.index') }}" class="rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">Manage Links</a>
                    <a href="{{ route('admin.abuse.index') }}" class="rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">Abuse Reports</a>
                    <a href="{{ route('admin.seo.edit') }}" class="rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">SEO</a>
                    <a href="{{ route('profile.edit') }}" class="rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">Account</a>
                @else
                    <a href="{{ route('dashboard') }}" class="rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">Dashboard</a>
                    <a href="{{ route('profile.edit') }}" class="rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">Account</a>
                @endif
            </x-slot>
        </x-page-header>
    </x-slot>
    <div class="py-10">
        <div class="mx-auto max-w-6xl space-y-6 sm:px-6 lg:px-8">
            <form method="GET" x-ref="form" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="grid grid-cols-2 gap-3 md:grid-cols-4 lg:grid-cols-5">
                    <div class="col-span-2 md:col-span-2 lg:col-span-2" x-data="{ 
                        open: false, 
                        search: '', 
                        selected: '{{ $linkFilter }}',
                        selectedText: '{{ $linkFilter === "all" ? "All Links" : ($userLinks->firstWhere("id", $linkFilter)?->slug ?? "All Links") }}',
                        links: {{ $userLinks->map(fn($l) => ['id' => $l->id, 'slug' => $l->slug, 'url' => \Illuminate\Support\Str::limit($l->target_url, 30)])->toJson() }},
                        get filteredLinks() {
                            if (!this.search) return this.links;
                            const s = this.search.toLowerCase();
                            return this.links.filter(l => l.slug.toLowerCase().includes(s) || l.url.toLowerCase().includes(s));
                        },
                        selectLink(id, text) {
                            this.selected = id;
                            this.selectedText = text;
                            this.open = false;
                            this.$refs.hiddenInput.value = id;
                            this.$refs.form.submit();
                        }
                    }" @click.away="open = false" class="relative">
                        <label class="text-xs font-medium text-slate-600 dark:text-slate-300">Link</label>
                        <input type="hidden" name="link_id" x-ref="hiddenInput" :value="selected">
                        <button type="button" @click="open = !open" class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-left text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white flex items-center justify-between">
                            <span class="truncate block" x-text="selectedText"></span>
                            <svg class="h-4 w-4 ml-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" x-transition class="absolute z-50 mt-1 w-full min-w-[200px] max-w-xs rounded-xl border border-slate-200 bg-white shadow-xl dark:border-slate-700 dark:bg-slate-900" style="display: none;">
                            <div class="p-2">
                                <input type="text" x-model="search" placeholder="Search links..." class="w-full rounded-lg border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white" @click.stop>
                            </div>
                            <div class="max-h-64 overflow-y-auto">
                                <button type="button" @click="selectLink('all', 'All Links')" class="w-full px-3 py-2 text-left text-sm hover:bg-slate-50 dark:hover:bg-slate-800" :class="selected === 'all' ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400' : 'text-slate-700 dark:text-slate-300'">
                                    All Links
                                </button>
                                <template x-for="link in filteredLinks" :key="link.id">
                                    <button type="button" @click="selectLink(link.id.toString(), link.slug)" class="w-full px-3 py-2 text-left text-sm hover:bg-slate-50 dark:hover:bg-slate-800" :class="selected == link.id ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400' : 'text-slate-700 dark:text-slate-300'">
                                        <div class="font-medium" x-text="link.slug"></div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400 truncate" x-text="link.url"></div>
                                    </button>
                                </template>
                                <div x-show="filteredLinks.length === 0" class="px-3 py-4 text-center text-sm text-slate-500 dark:text-slate-400">
                                    No links found
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-slate-600 dark:text-slate-300">Time range</label>
                        <select name="hours" onchange="this.form.submit()" class="mt-1 w-full rounded-xl border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                            @foreach ([6, 12, 24, 48, 72] as $option)
                                <option value="{{ $option }}" @selected($range == $option)>Last {{ $option }} hrs</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-slate-600 dark:text-slate-300">Location</label>
                        <select name="country" onchange="this.form.submit()" class="mt-1 w-full rounded-xl border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                            <option value="all" @selected($countryFilter === 'all')>All locations</option>
                            @foreach ($availableCountries as $country)
                                <option value="{{ $country->country ?? 'unknown' }}" @selected(($country->country ?? 'unknown') === $countryFilter)>{{ $country->label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-slate-600 dark:text-slate-300">Device</label>
                        <select name="device" onchange="this.form.submit()" class="mt-1 w-full rounded-xl border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                            <option value="all" @selected($deviceFilter === 'all')>All devices</option>
                            @foreach ($availableDevices as $device)
                                <option value="{{ strtolower($device) }}" @selected(strtolower($device) === $deviceFilter)>{{ $device }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <a href="{{ route('analytics') }}" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-center text-sm text-slate-600 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">Reset</a>
                    </div>
                </div>
            </form>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="grid gap-3 md:grid-cols-3">
                    <div class="rounded-xl border border-blue-200 bg-blue-50 p-3 dark:border-blue-900/40 dark:bg-blue-900/20">
                        <p class="text-xs font-medium uppercase tracking-wider text-blue-600 dark:text-blue-400">Clicks</p>
                        <p class="mt-1 text-2xl font-bold text-blue-900 dark:text-blue-100">{{ number_format($totalClicks) }}</p>
                        <p class="text-xs text-blue-600/70 dark:text-blue-300/70">Total interactions</p>
                    </div>
                    <div class="rounded-xl border border-purple-200 bg-purple-50 p-3 dark:border-purple-900/40 dark:bg-purple-900/20">
                        <p class="text-xs font-medium uppercase tracking-wider text-purple-600 dark:text-purple-400">Top country</p>
                        <p class="mt-1 text-2xl font-bold text-purple-900 dark:text-purple-100">{{ $countries->keys()->first() ?? '—' }}</p>
                        <p class="text-xs text-purple-600/70 dark:text-purple-300/70">{{ $countries->values()->first() ? $countries->values()->first().' clicks' : 'No geo data' }}</p>
                    </div>
                    <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-3 dark:border-emerald-900/40 dark:bg-emerald-900/20">
                        <p class="text-xs font-medium uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Locations</p>
                        <p class="mt-1 text-2xl font-bold text-emerald-900 dark:text-emerald-100">{{ $countryCount }}</p>
                        <p class="text-xs text-emerald-600/70 dark:text-emerald-300/70">Countries detected</p>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Performance</p>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Clicks Over Time</h3>
                    </div>
                    <span class="text-sm text-slate-500">Last {{ $range }} hours</span>
                </div>
                <div class="relative" style="height: 300px;">
                    <canvas id="analyticsChart"></canvas>
                </div>
            </div>
            <div class="grid gap-6 lg:grid-cols-2">
                <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900" x-data="{ tab: 'links' }">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-gray-400">Short links</p>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Engagement</h3>
                        </div>
                        <div class="flex gap-2 text-xs font-semibold text-gray-500 dark:text-gray-400">
                            <button type="button" @click="tab = 'links'" :class="tab === 'links' ? 'text-blue-600 dark:text-blue-300' : ''">Links</button>
                            <button type="button" @click="tab = 'destinations'" :class="tab === 'destinations' ? 'text-blue-600 dark:text-blue-300' : ''">Destination URLs</button>
                            <button type="button" disabled class="opacity-40 cursor-not-allowed">Tags</button>
                        </div>
                    </div>
                    <div class="mt-4 space-y-2" x-show="tab === 'links'">
                        @forelse ($topLinks as $row)
                            <div class="flex items-center justify-between rounded-2xl border border-gray-100 px-4 py-3 dark:border-gray-800">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $row->link->short_url ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-xs">{{ $row->link->target_url }}</p>
                                </div>
                                <span class="text-sm text-gray-500">{{ $row->total }} clicks</span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400">No clicks recorded for this range.</p>
                        @endforelse
                    </div>
                    <div class="mt-4 space-y-2" x-show="tab === 'destinations'">
                        @forelse ($topDestinations as $host => $total)
                            <div class="flex items-center justify-between rounded-2xl border border-gray-100 px-4 py-3 dark:border-gray-800">
                                <span class="text-sm text-gray-700 dark:text-gray-200">{{ $host }}</span>
                                <span class="text-sm text-gray-500">{{ $total }} clicks</span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400">No destination data.</p>
                        @endforelse
                    </div>
                </div>
                <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900" x-data="{ tab: 'domains' }">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-gray-400">Referrers</p>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Source breakdown</h3>
                        </div>
                        <div class="flex gap-2 text-xs font-semibold text-gray-500 dark:text-gray-400">
                            <button type="button" @click="tab = 'domains'" :class="tab === 'domains' ? 'text-blue-600 dark:text-blue-300' : ''">Domain</button>
                            <button type="button" @click="tab = 'urls'" :class="tab === 'urls' ? 'text-blue-600 dark:text-blue-300' : ''">URL</button>
                            <button type="button" disabled class="opacity-40 cursor-not-allowed">UTM</button>
                        </div>
                    </div>
                    <div class="mt-4 space-y-2">
                        @php
                            $domainBreakdown = [];
                            foreach ($referers as $ref => $total) {
                                $host = $ref === '(direct)' ? '(direct)' : parse_url($ref, PHP_URL_HOST);
                                $domain = $host ?: $ref;
                                $domainBreakdown[$domain] = ($domainBreakdown[$domain] ?? 0) + $total;
                            }
                        @endphp
                        <div x-show="tab === 'domains'" class="space-y-2">
                            @forelse ($domainBreakdown as $domain => $total)
                                <div class="flex items-center justify-between rounded-2xl border border-gray-100 px-4 py-3 dark:border-gray-800">
                                    <span class="text-sm text-gray-700 dark:text-gray-200">{{ $domain }}</span>
                                    <span class="text-sm text-gray-500">{{ $total }}</span>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400">No referrers yet.</p>
                            @endforelse
                        </div>
                        <div x-show="tab === 'urls'" class="space-y-2">
                            @forelse ($referers as $ref => $total)
                                <div class="flex items-center justify-between rounded-2xl border border-gray-100 px-4 py-3 dark:border-gray-800">
                                    <span class="text-sm text-gray-700 dark:text-gray-200 truncate max-w-xs">{{ $ref }}</span>
                                    <span class="text-sm text-gray-500">{{ $total }}</span>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400">No referrers yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start">
                    <div class="flex-1 min-w-[360px]">
                        <div class="mb-3 flex items-center justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-gray-400">Geographic</p>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Distribution Map</h3>
                            </div>
                        </div>
                        <div id="world-map" class="w-full rounded-lg border-2 border-gray-200 dark:border-gray-700" style="height: 420px;"></div>
                        <div class="mt-3 flex items-center justify-between text-[10px] text-gray-600 dark:text-gray-400">
                            <span class="font-medium">Low Traffic</span>
                            <div class="flex gap-0.5 items-center">
                                <div class="w-8 h-3 rounded-sm border border-gray-300" style="background: #e2e8f0;"><span class="text-[8px] pl-0.5">0</span></div>
                                <div class="w-8 h-3 rounded-sm border border-gray-300" style="background: #bfdbfe;"></div>
                                <div class="w-8 h-3 rounded-sm border border-gray-300" style="background: #60a5fa;"></div>
                                <div class="w-8 h-3 rounded-sm border border-gray-300" style="background: #2563eb;"></div>
                                <div class="w-8 h-3 rounded-sm border border-gray-300" style="background: #1d4ed8;"></div>
                            </div>
                            <span class="font-medium">High Traffic</span>
                        </div>
                    </div>
                    <div class="rounded-2xl border border-gray-100 bg-gray-50 p-3 text-sm dark:border-slate-800 dark:bg-slate-900/80 flex-1 min-w-[360px]">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.25em] text-gray-400 dark:text-slate-400">Overview</p>
                                <p class="text-base font-semibold text-gray-900 dark:text-white">Geo Summary</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500 dark:text-slate-400">Total clicks</p>
                                <p class="text-lg font-bold text-blue-600 dark:text-blue-300">{{ number_format($totalClicks) }}</p>
                            </div>
                        </div>
                        <div class="mt-2 grid grid-cols-2 gap-2 text-xs">
                            <div class="rounded-xl border border-gray-200 bg-white p-2.5 dark:border-slate-700 dark:bg-slate-800">
                                <div class="space-y-0.5 leading-tight">
                                    <p class="text-gray-500 dark:text-slate-400">Top country</p>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $countries->keys()->first() ?? '—' }}</p>
                                    <p class="text-gray-500 dark:text-slate-400 truncate">{{ $countries->values()->first() ? $countries->values()->first().' clicks' : 'No data' }}</p>
                                </div>
                            </div>
                            <div class="rounded-xl border border-gray-200 bg-white p-2.5 dark:border-slate-700 dark:bg-slate-800">
                                <div class="space-y-0.5 leading-tight">
                                    <p class="text-gray-500 dark:text-slate-400">Countries detected</p>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ count($mapData) }}</p>
                                    <p class="text-gray-500 dark:text-slate-400 truncate">Click to focus</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <p class="text-xs uppercase tracking-[0.25em] text-gray-400 dark:text-slate-400">Top locations</p>
                            <ul class="mt-2 space-y-1.5">
                                @foreach(collect($countries)->take(6) as $country => $total)
                                    <li>
                                        <button type="button" class="flex w-full items-center justify-between rounded-xl border border-gray-200 px-3 py-2 text-left hover:bg-gray-100 dark:border-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700/80"
                                            onclick="window.focusCountry('{{ $country }}')">
                                            <span class="flex items-center gap-2 text-gray-800 dark:text-gray-100">
                                                {!! country_flag($country) !!} {{ country_name($country) }}
                                            </span>
                                            <span class="text-gray-500 dark:text-slate-400">{{ $total }} clicks</span>
                                        </button>
                                    </li>
                                @endforeach
                                @if(collect($countries)->isEmpty())
                                    <li class="text-gray-500 dark:text-slate-400">No geo data</li>
                                @endif
                            </ul>
                        </div>
                        <div class="mt-3 rounded-xl border border-gray-200 bg-white p-3 dark:border-slate-700 dark:bg-slate-800">
                            <p class="text-xs font-semibold text-gray-500 dark:text-slate-400">Selected country</p>
                            <p id="map-country-name" class="text-lg font-bold text-gray-900 dark:text-white mt-1">Click a country</p>
                            <p id="map-country-clicks" class="text-sm text-gray-500 dark:text-slate-400">—</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid gap-6 lg:grid-cols-2" x-data="{ networkPage: 0 }">
                <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900" x-data="{ tab: 'countries', countriesPage: 0, citiesPage: 0, regionsPage: 0 }">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-gray-400">Locations</p>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Where people click</h3>
                        </div>
                        <div class="flex gap-2 text-xs font-semibold text-gray-500 dark:text-gray-400">
                            <button type="button" @click="tab = 'countries'" :class="tab === 'countries' ? 'text-blue-600 dark:text-blue-300' : ''">Countries</button>
                            <button type="button" @click="tab = 'cities'" :class="tab === 'cities' ? 'text-blue-600 dark:text-blue-300' : ''">Cities</button>
                            <button type="button" @click="tab = 'regions'" :class="tab === 'regions' ? 'text-blue-600 dark:text-blue-300' : ''">Regions</button>
                        </div>
                    </div>
                    <div class="mt-4" x-show="tab === 'countries'">
                        <canvas id="countriesChart" height="200"></canvas>
                    </div>
                    @php
                        $countriesArray = collect($countries)->take(50)->toArray();
                        $countriesPerPage = 8;
                        $totalCountriesPages = ceil(count($countriesArray) / $countriesPerPage);
                    @endphp
                    <div class="mt-4" x-show="tab === 'countries'">
                        @foreach(array_chunk($countriesArray, $countriesPerPage, true) as $pageIndex => $countriesChunk)
                            <ul class="space-y-2" x-show="countriesPage === {{ $pageIndex }}">
                                @foreach($countriesChunk as $country => $total)
                                    <li class="flex items-center justify-between rounded-2xl border border-gray-100 px-4 py-3 text-sm dark:border-gray-800">
                                        <span>{!! country_flag($country) !!} {{ country_name($country) }}</span>
                                        <span class="text-gray-500">{{ $total }} clicks</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endforeach
                    </div>
                    @php
                        $citiesArray = collect($cities)->take(50)->toArray();
                        $citiesPerPage = 8;
                        $totalCitiesPages = ceil(count($citiesArray) / $citiesPerPage);
                    @endphp
                    <div class="mt-4" x-show="tab === 'cities'">
                        @foreach(array_chunk($citiesArray, $citiesPerPage) as $pageIndex => $citiesChunk)
                            <ul class="space-y-2" x-show="citiesPage === {{ $pageIndex }}">
                                @foreach($citiesChunk as $item)
                                    <li class="flex items-center justify-between rounded-2xl border border-gray-100 px-4 py-3 text-sm dark:border-gray-800">
                                        <span>{!! country_flag($item['country']) !!} {{ $item['city'] }}, {{ country_name($item['country']) }}</span>
                                        <span class="text-gray-500">{{ $item['total'] }} clicks</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endforeach
                    </div>
                    @if($totalCitiesPages > 1)
                    <div class="flex items-center justify-center gap-2 mt-4 pt-4 border-t border-gray-200 dark:border-gray-700" x-show="tab === 'cities'" x-cloak>
                        <button @click="citiesPage = Math.max(0, citiesPage - 1)" :disabled="citiesPage === 0" class="p-2 rounded-lg border border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800 disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </button>
                        <span class="text-sm text-gray-600 dark:text-gray-400" x-text="`Page ${citiesPage + 1} of {{ $totalCitiesPages }}`"></span>
                        <button @click="citiesPage = Math.min({{ $totalCitiesPages - 1 }}, citiesPage + 1)" :disabled="citiesPage === {{ $totalCitiesPages - 1 }}" class="p-2 rounded-lg border border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800 disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    </div>
                    @endif
                    @php
                        $regionsArray = collect($regions)->take(50)->toArray();
                        $regionsPerPage = 8;
                        $totalRegionsPages = ceil(count($regionsArray) / $regionsPerPage);
                    @endphp
                    <div class="mt-4" x-show="tab === 'regions'">
                        @foreach(array_chunk($regionsArray, $regionsPerPage) as $pageIndex => $regionsChunk)
                            <ul class="space-y-2" x-show="regionsPage === {{ $pageIndex }}">
                                @foreach($regionsChunk as $item)
                                    <li class="flex items-center justify-between rounded-2xl border border-gray-100 px-4 py-3 text-sm dark:border-gray-800">
                                        <span>{!! country_flag($item['country']) !!} {{ $item['region'] }}, {{ country_name($item['country']) }}</span>
                                        <span class="text-gray-500">{{ $item['total'] }} clicks</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endforeach
                    </div>
                    @if($totalRegionsPages > 1)
                    <div class="flex items-center justify-center gap-2 mt-4 pt-4 border-t border-gray-200 dark:border-gray-700" x-show="tab === 'regions'" x-cloak>
                        <button @click="regionsPage = Math.max(0, regionsPage - 1)" :disabled="regionsPage === 0" class="p-2 rounded-lg border border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800 disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </button>
                        <span class="text-sm text-gray-600 dark:text-gray-400" x-text="`Page ${regionsPage + 1} of {{ $totalRegionsPages }}`"></span>
                        <button @click="regionsPage = Math.min({{ $totalRegionsPages - 1 }}, regionsPage + 1)" :disabled="regionsPage === {{ $totalRegionsPages - 1 }}" class="p-2 rounded-lg border border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800 disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    </div>
                    @endif
                </div>
                <div class="space-y-10">
                    <div class="rounded-3xl border-2 border-gray-300 bg-white p-6 shadow-lg dark:border-gray-600 dark:bg-gray-900 relative z-10">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-gray-400">Network</p>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Internet Providers</h3>
                            </div>
                        </div>
                        @php 
                            $ispsPerPage = 5;
                            $ispsArray = collect($isps)->take(20)->toArray();
                            $totalNetworkPages = ceil(count($ispsArray) / $ispsPerPage);
                        @endphp
                        <div class="mt-4" style="height: 280px; display: flex; flex-direction: column;">
                            <div style="flex: 1; min-height: 0;">
                                @foreach(array_chunk($ispsArray, $ispsPerPage) as $pageIndex => $ispsChunk)
                                    <ul class="space-y-2" x-show="networkPage === {{ $pageIndex }}">
                                        @foreach($ispsChunk as $item)
                                            @php $item = (object) $item; @endphp
                                            <li class="flex items-center justify-between rounded-2xl border border-gray-100 px-4 py-3 text-sm dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                                <span class="truncate mr-2">{{ $item->isp }}</span>
                                                <span class="text-gray-500 whitespace-nowrap">{{ $item->total }} clicks</span>
                                            </li>
                                        @endforeach
                                        @for($i = count($ispsChunk); $i < $ispsPerPage; $i++)
                                            <li class="flex items-center justify-between rounded-2xl border border-transparent px-4 py-3 text-sm" style="visibility: hidden;">
                                                <span>Placeholder</span>
                                            </li>
                                        @endfor
                                    </ul>
                                @endforeach
                            </div>
                        @if($totalNetworkPages > 1)
                        <div class="flex items-center justify-center gap-2 mt-6 pt-5 border-t border-gray-200 dark:border-gray-700">
                            <button @click="networkPage = Math.max(0, networkPage - 1)" :disabled="networkPage === 0" class="p-2 rounded-lg border border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800 disabled:opacity-50 disabled:cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                            </button>
                            <span class="text-sm text-gray-600 dark:text-gray-400" x-text="`Page ${networkPage + 1} of {{ $totalNetworkPages }}`"></span>
                            <button @click="networkPage = Math.min({{ $totalNetworkPages - 1 }}, networkPage + 1)" :disabled="networkPage === {{ $totalNetworkPages - 1 }}" class="p-2 rounded-lg border border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800 disabled:opacity-50 disabled:cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </button>
                        </div>
                        @endif
                    </div>
                    <div class="rounded-3xl border-2 border-gray-300 bg-white p-6 shadow-lg dark:border-gray-600 dark:bg-gray-900" x-data="{ tab: 'devices' }">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-gray-400">Technology</p>
                                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Device & Platform</h3>
                            </div>
                            <div class="flex gap-2 text-xs font-semibold text-gray-500 dark:text-gray-400">
                                <button type="button" @click="tab = 'devices'" :class="tab === 'devices' ? 'text-blue-600 dark:text-blue-300' : ''">Devices</button>
                                <button type="button" @click="tab = 'browsers'" :class="tab === 'browsers' ? 'text-blue-600 dark:text-blue-300' : ''">Browsers</button>
                                <button type="button" @click="tab = 'os'" :class="tab === 'os' ? 'text-blue-600 dark:text-blue-300' : ''">OS</button>
                            </div>
                        </div>
                        <div x-show="tab === 'devices'">
                            <canvas id="devicesChart" height="140"></canvas>
                        </div>
                    <div class="mt-4 space-y-2" x-show="tab === 'devices'">
                        @forelse ($devices as $device => $total)
                            @php
                                $percentage = $totalClicks > 0 ? round(($total / $totalClicks) * 100, 1) : 0;
                            @endphp
                            <div class="rounded-2xl border border-gray-100 px-4 py-3 dark:border-gray-800">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-700 dark:text-gray-200">{{ $device }}</span>
                                    <span class="text-gray-500">{{ $total }} · {{ $percentage }}%</span>
                                </div>
                                <div class="mt-2 h-2 rounded-full bg-gray-100 dark:bg-gray-800">
                                    <div class="h-2 rounded-full bg-blue-500" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400">No device data.</p>
                        @endforelse
                    </div>
                    <div class="mt-4 space-y-2" x-show="tab === 'browsers'">
                        @php
                            $totalBrowserClicks = array_sum($browserStats);
                        @endphp
                        @forelse ($browserStats as $browser => $count)
                            @php
                                $percentage = $totalBrowserClicks > 0 ? round(($count / $totalBrowserClicks) * 100, 1) : 0;
                            @endphp
                            <div class="rounded-2xl border border-gray-100 px-4 py-3 dark:border-gray-800">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-700 dark:text-gray-200">{{ $browser }}</span>
                                    <span class="text-gray-500">{{ $count }} · {{ $percentage }}%</span>
                                </div>
                                <div class="mt-2 h-2 rounded-full bg-gray-100 dark:bg-gray-800">
                                    <div class="h-2 rounded-full bg-purple-500" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400">No browser data available.</p>
                        @endforelse
                    </div>
                    <div class="mt-4 space-y-2" x-show="tab === 'os'">
                        @php
                            $totalOsClicks = array_sum($osStats);
                        @endphp
                        @forelse ($osStats as $os => $count)
                            @php
                                $percentage = $totalOsClicks > 0 ? round(($count / $totalOsClicks) * 100, 1) : 0;
                            @endphp
                            <div class="rounded-2xl border border-gray-100 px-4 py-3 dark:border-gray-800">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-700 dark:text-gray-200">{{ $os }}</span>
                                    <span class="text-gray-500">{{ $count }} · {{ $percentage }}%</span>
                                </div>
                                <div class="mt-2 h-2 rounded-full bg-gray-100 dark:bg-gray-800">
                                    <div class="h-2 rounded-full bg-green-500" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400">No OS data available.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-xs uppercase tracking-[0.3em] text-gray-400">Security</p>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Visitor IPs (last 20)</h3>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Filtered by your current link/time/device filters</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-500 dark:text-gray-400">
                            <th class="py-2 pr-4">IP Address</th>
                            <th class="py-2 pr-4">Location</th>
                            <th class="py-2">Time</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        @forelse($recentIps as $hit)
                            <tr class="text-gray-800 dark:text-gray-100">
                                <td class="py-3 pr-4 font-mono">{{ $hit->ip_address }}</td>
                                <td class="py-3 pr-4 flex items-center gap-2">
                                    {!! country_flag($hit->country) !!}
                                    <span>{{ $hit->city ?? '—' }}, {{ country_name($hit->country) }}</span>
                                </td>
                                <td class="py-3 text-gray-500 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($hit->created_at)->diffForHumans() }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-4 text-gray-500 dark:text-gray-400">No IP data available for this filter.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom@2.0.1/dist/chartjs-plugin-zoom.min.js"></script>
        <style>
            .leaflet-popup-content-wrapper { padding: 5px 7px; border-radius: 10px; }
            .leaflet-popup-content { margin: 0; width: 80px !important; }
            .leaflet-popup-tip { width: 10px; height: 10px; }
        </style>
        <script>
            // Timeline Chart with Zoom
            const analyticsChart = document.getElementById('analyticsChart');
            const labels = {!! json_encode(array_values($timeline->keys()->toArray())) !!};
            const dataset = {!! json_encode(array_values($timeline->values()->toArray())) !!};
            new Chart(analyticsChart, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        label: 'Clicks',
                        data: dataset,
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79,70,229,0.15)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 2,
                        pointRadius: 3,
                        pointBackgroundColor: '#4f46e5',
                        pointHoverRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            padding: 12,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' clicks';
                                }
                            }
                        },
                        zoom: {
                            zoom: {
                                wheel: {
                                    enabled: true,
                                },
                                pinch: {
                                    enabled: true
                                },
                                mode: 'x',
                            },
                            pan: {
                                enabled: true,
                                mode: 'x',
                            }
                        }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            ticks: { precision: 0 },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: { 
                            ticks: { maxRotation: 0 },
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
            // Leaflet Choropleth Map (replacing jVectorMap)
            const mapData = {!! json_encode($mapData) !!};
            const maxClicks = Math.max(...Object.values(mapData), 1);
            const iso3To2 = {"ATG":"AG","BTN":"BT","ITA":"IT","TUV":"TV","AIA":"AI","AUS":"AU","BLZ":"BZ","VUT":"VU","BLR":"BY","MUS":"MU","LAO":"LA","SEN":"SN","TUR":"TR","BOL":"BO","LKA":"LK","NFK":"NF","CHN":"CN","BES":"BQ","GGY":"GG","SDN":"SD","MYT":"YT","BLM":"BL","VAT":"VA","TCA":"TC","CUW":"CW","BWA":"BW","BEN":"BJ","LTU":"LT","MSR":"MS","VGB":"VG","BDI":"BI","UMI":"UM","IRL":"IE","SLB":"SB","BMU":"BM","FIN":"FI","PER":"PE","BGD":"BD","DNK":"DK","VCT":"VC","DOM":"DO","MDA":"MD","BGR":"BG","CRI":"CR","NAM":"NA","SJM":"SJ","LUX":"LU","RUS":"RU","ARE":"AE","SXM":"SX","BHS":"BS","JPN":"JP","NGA":"NG","GHA":"GH","SLE":"SL","SPM":"PM","ALB":"AL","TKL":"TK","SHN":"SH","TON":"TO","TKM":"TM","DJI":"DJ","CAF":"CF","LBN":"LB","LVA":"LV","CCK":"CC","GMB":"GM","HND":"HN","NIU":"NU","MRT":"MR","UNK":"XK","WLF":"WF","SGS":"GS","PYF":"PF","TGO":"TG","BEL":"BE","ZMB":"ZM","CYM":"KY","PCN":"PN","COK":"CK","MDG":"MG","MNE":"ME","KOR":"KR","ETH":"ET","MNG":"MN","SVK":"SK","CUB":"CU","ATA":"AQ","GTM":"GT","GUF":"GF","NOR":"NO","GRD":"GD","REU":"RE","CHL":"CL","COL":"CO","SAU":"SA","ISR":"IL","DEU":"DE","NZL":"NZ","GRL":"GL","KGZ":"KG","SLV":"SV","FRO":"FO","PLW":"PW","MLT":"MT","SYR":"SY","TLS":"TL","HRV":"HR","PNG":"PG","NLD":"NL","LBR":"LR","SOM":"SO","VEN":"VE","HTI":"HT","DZA":"DZ","MNP":"MP","MAF":"MF","HMD":"HM","ABW":"AW","EGY":"EG","MWI":"MW","GNQ":"GQ","VIR":"VI","ECU":"EC","UZB":"UZ","GAB":"GA","SSD":"SS","IRN":"IR","KAZ":"KZ","NIC":"NI","ISL":"IS","SVN":"SI","GLP":"GP","CMR":"CM","ARG":"AR","AZE":"AZ","UGA":"UG","NER":"NE","CXR":"CX","MMR":"MM","POL":"PL","JOR":"JO","HKG":"HK","COD":"CD","ERI":"ER","KIR":"KI","MHL":"MH","BFA":"BF","ZWE":"ZW","KEN":"KE","COM":"KM","GIB":"GI","BRN":"BN","SWE":"SE","LSO":"LS","IMN":"IM","FSM":"FM","TZA":"TZ","CPV":"CV","AFG":"AF","AND":"AD","GRC":"GR","VNM":"VN","ATF":"TF","IRQ":"IQ","LBY":"LY","PRT":"PT","PAK":"PK","MDV":"MV","MAR":"MA","BIH":"BA","WSM":"WS","PSE":"PS","OMN":"OM","BHR":"BH","USA":"US","PRI":"PR","IOT":"IO","JEY":"JE","MKD":"MK","TUN":"TN","TTO":"TT","EST":"EE","SGP":"SG","PAN":"PA","CHE":"CH","URY":"UY","TJK":"TJ","TWN":"TW","ZAF":"ZA","LIE":"LI","BRA":"BR","ARM":"AM","GEO":"GE","ALA":"AX","QAT":"QA","DMA":"DM","UKR":"UA","GIN":"GN","MAC":"MO","ESH":"EH","CZE":"CZ","AUT":"AT","KNA":"KN","LCA":"LC","YEM":"YE","RWA":"RW","MCO":"MC","STP":"ST","COG":"CG","PRY":"PY","BVT":"BV","MOZ":"MZ","FRA":"FR","SWZ":"SZ","BRB":"BB","ESP":"ES","THA":"TH","GNB":"GW","AGO":"AO","IND":"IN","MTQ":"MQ","NCL":"NC","SYC":"SC","FLK":"FK","GBR":"GB","FJI":"FJ","SMR":"SM","MLI":"ML","CAN":"CA","JAM":"JM","NRU":"NR","IDN":"ID","GUM":"GU","CIV":"CI","KWT":"KW","PHL":"PH","GUY":"GY","HUN":"HU","MEX":"MX","PRK":"KP","ROU":"RO","SUR":"SR","ASM":"AS","NPL":"NP","TCD":"TD","SRB":"RS","KHM":"KH","MYS":"MY","CYP":"CY"};
            const normalizeCountryCode = (code) => {
                if (!code) return '';
                const upper = String(code).toUpperCase();
                if (upper.length === 2) return upper;
                return iso3To2[upper] || upper;
            };
            const countryLayers = {};
            window.focusCountry = (countryCode) => {
                const normalized = normalizeCountryCode(countryCode);
                if (countryLayers[normalized] && countryLayers[normalized].getBounds) {
                    map.fitBounds(countryLayers[normalized].getBounds());
                    countryLayers[normalized].openPopup();
                    const clicks = mapData[normalized] || 0;
                    const name = countryLayers[normalized].feature?.properties?.name || normalized;
                    setSelectedCountry(name, clicks);
                }
            };
            const setSelectedCountry = (name, clicks) => {
                document.getElementById('map-country-name').textContent = name;
                document.getElementById('map-country-clicks').textContent = clicks ? `${clicks.toLocaleString()} clicks` : 'No clicks yet';
            };
            let map;
            try {
                map = L.map('world-map', {
                    center: [20, 0],
                    zoom: 2,
                    minZoom: 1,
                    maxZoom: 6,
                    zoomControl: true,
                    scrollWheelZoom: false,
                    worldCopyJump: true
                });
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    noWrap: false
                }).addTo(map);
                fetch('https://raw.githubusercontent.com/johan/world.geo.json/master/countries.geo.json')
                    .then(response => response.json())
                    .then(geoData => {
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
                            layer.setStyle({ weight: 2, color: '#475569', fillOpacity: 0.95 });
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
                                    setSelectedCountry(countryName, clicks);
                                }
                            });
                        }
                        const geojson = L.geoJSON(geoData, {
                            style: style,
                            onEachFeature: onEachFeature
                        }).addTo(map);
                    })
                    .catch(error => {
                        console.error('❌ Map error:', error);
                        document.getElementById('world-map').innerHTML = 
                            '<div class="flex items-center justify-center h-full text-red-600"><p>Failed to load map</p></div>';
                    });
            } catch (error) {
                console.error('❌ Map init error:', error);
            }
            // Countries Bar Chart
            const countriesData = {!! json_encode($countries) !!};
            if (Object.keys(countriesData).length > 0) {
                const countriesLabels = Object.keys(countriesData).slice(0, 10);
                const countriesValues = Object.values(countriesData).slice(0, 10);
                new Chart(document.getElementById('countriesChart'), {
                    type: 'bar',
                    data: {
                        labels: countriesLabels,
                        datasets: [{
                            label: 'Clicks',
                            data: countriesValues,
                            backgroundColor: 'rgba(59, 130, 246, 0.8)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                displayColors: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { precision: 0 }
                            }
                        }
                    }
                });
            }
            // Devices Doughnut Chart
            const devicesData = {!! json_encode($devices) !!};
            if (Object.keys(devicesData).length > 0) {
                const devicesLabels = Object.keys(devicesData);
                const devicesValues = Object.values(devicesData);
                new Chart(document.getElementById('devicesChart'), {
                    type: 'doughnut',
                    data: {
                        labels: devicesLabels,
                        datasets: [{
                            data: devicesValues,
                            backgroundColor: [
                                'rgba(59, 130, 246, 0.8)',
                                'rgba(16, 185, 129, 0.8)',
                                'rgba(251, 146, 60, 0.8)',
                                'rgba(168, 85, 247, 0.8)'
                            ],
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 15,
                                    font: { size: 12 }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                displayColors: true
                            }
                        }
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>
