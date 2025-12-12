<x-app-layout>
    <x-slot name="pageTitle">HEL.ink - Dashboard</x-slot>
    <x-slot name="header">
        <x-page-header title="Dashboard" subtitle="Short links workspace">
            <x-slot name="actions">
                <button type="button" onclick="window.openLinkPanel && window.openLinkPanel()" class="create-link-trigger rounded-full bg-blue-600 px-5 md:px-8 py-2 text-sm md:text-base font-semibold text-white shadow-lg shadow-blue-500/30 hover:bg-blue-500 transition-all">
                    <span class="hidden md:inline">+ Create Link</span>
                    <span class="md:hidden">+</span>
                </button>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="rounded-2xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-emerald-900 dark:border-emerald-900/30 dark:bg-emerald-900/20 dark:text-emerald-200">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('shortlink'))
                @php
                    $newLink = session('shortlink');
                @endphp
                <div class="rounded-2xl border border-blue-300 bg-blue-50 p-6 dark:border-blue-900/30 dark:bg-blue-900/20">
                    <div class="flex items-start gap-4">
                        <div class="rounded-full bg-blue-600 p-3 text-white">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100">Link created successfully! 🎉</h3>
                            <p class="mt-1 text-sm text-blue-800 dark:text-blue-200">Your new shortlink is ready to use:</p>
                            <div class="mt-3 flex items-center gap-3 rounded-xl bg-white p-4 dark:bg-slate-900">
                                <a href="{{ $newLink->short_url }}" target="_blank" class="flex-1 font-mono text-lg font-semibold text-blue-600 hover:underline dark:text-blue-400">
                                    {{ $newLink->short_url }}
                                </a>
                                <button onclick="navigator.clipboard.writeText('{{ $newLink->short_url }}'); this.innerHTML='✓ Copied!'; setTimeout(() => this.innerHTML='Copy', 2000)" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-500">
                                    Copy
                                </button>
                            </div>
                            <div class="mt-3 flex gap-3 text-sm">
                                <a href="{{ route('links.show', $newLink) }}" class="text-blue-700 hover:underline dark:text-blue-300">View Analytics</a>
                                <span class="text-blue-400">•</span>
                                <a href="{{ route('links.edit', $newLink) }}" class="text-blue-700 hover:underline dark:text-blue-300">Edit Link</a>
                                <span class="text-blue-400">•</span>
                                <span class="text-blue-800 dark:text-blue-200">→ {{ Str::limit($newLink->target_url, 60) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @php
                $dailyPercent = $quota['daily']['limit'] > 0 ? min(100, round(($quota['daily']['used'] / $quota['daily']['limit']) * 100)) : 0;
                $activePercent = $quota['active']['limit'] > 0 ? min(100, round(($quota['active']['used'] / $quota['active']['limit']) * 100)) : 0;
                $bioPercent = $quota['bio']['limit'] > 0 ? min(100, round(($quota['bio']['used'] / $quota['bio']['limit']) * 100)) : 0;
            @endphp
            
            <div class="grid gap-6 lg:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Usage & quotas</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Reset daily to keep things fair.</p>
                        </div>
                        <a href="{{ route('help') }}#usage" class="text-xs text-blue-600 hover:underline">Details</a>
                    </div>
                    <dl class="mt-4 space-y-4 text-sm">
                        <div>
                            <div class="flex items-center justify-between">
                                <dt class="text-slate-500 dark:text-slate-300">Daily creations</dt>
                                <dd class="font-semibold text-slate-900 dark:text-white">{{ $quota['daily']['used'] }} / {{ $quota['daily']['limit'] }}</dd>
                            </div>
                            <div class="mt-2 h-3 rounded-full bg-slate-100 dark:bg-slate-800">
                                <div class="h-3 rounded-full bg-blue-500" style="width: {{ $dailyPercent }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between">
                                <dt class="text-slate-500 dark:text-slate-300">Active links</dt>
                                <dd class="font-semibold text-slate-900 dark:text-white">{{ $quota['active']['used'] }} / {{ $quota['active']['limit'] }}</dd>
                            </div>
                            <div class="mt-2 h-3 rounded-full bg-slate-100 dark:bg-slate-800">
                                <div class="h-3 rounded-full bg-emerald-500" style="width: {{ $activePercent }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between">
                                <dt class="text-slate-500 dark:text-slate-300">Link in Bio Pages</dt>
                                <dd class="font-semibold text-slate-900 dark:text-white">{{ $quota['bio']['used'] }} / {{ $quota['bio']['limit'] }}</dd>
                            </div>
                            <div class="mt-2 h-3 rounded-full bg-slate-100 dark:bg-slate-800">
                                <div class="h-3 rounded-full bg-purple-500" style="width: {{ $bioPercent }}%"></div>
                            </div>
                        </div>
                    </dl>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="grid gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Latest link</p>
                            @if ($highlightLatest)
                                <p class="mt-2 font-semibold text-blue-600 dark:text-blue-400 text-sm truncate">{{ $highlightLatest->short_url }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 break-words">{{ $highlightLatest->target_url }}</p>
                                <p class="text-xs text-slate-400">{{ $highlightLatest->created_at->diffForHumans() }}</p>
                            @else
                                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">No links yet.</p>
                            @endif
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Top clicked</p>
                            @if ($highlightTop)
                                <p class="mt-2 font-semibold text-slate-900 dark:text-white text-sm truncate">{{ $highlightTop->short_url }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $highlightTop->clicks }} clicks</p>
                                <p class="text-xs text-slate-400 break-words">{{ $highlightTop->target_url }}</p>
                            @else
                                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">No analytics yet.</p>
                            @endif
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Top location</p>
                            @if ($topCountry)
                                <p class="mt-2 font-semibold text-slate-900 dark:text-white">{{ $topCountry->country ?? 'Unknown' }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $topCountry->total }} clicks</p>
                            @else
                                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">No geo data yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="grid gap-4 md:grid-cols-3">
                <div class="rounded-2xl bg-white px-6 py-4 shadow-sm dark:bg-slate-900">
                    <p class="text-sm text-slate-500 dark:text-slate-400">Total Links</p>
                    <p class="text-3xl font-semibold text-slate-900 dark:text-white">{{ $stats['total_links'] ?? 0 }}</p>
                </div>
                <div class="rounded-2xl bg-white px-6 py-4 shadow-sm dark:bg-slate-900">
                    <p class="text-sm text-slate-500 dark:text-slate-400">Total Clicks</p>
                    <p class="text-3xl font-semibold text-slate-900 dark:text-white">{{ $stats['total_clicks'] ?? 0 }}</p>
                </div>
                <div class="rounded-2xl bg-white px-6 py-4 shadow-sm dark:bg-slate-900">
                    <p class="text-sm text-slate-500 dark:text-slate-400">Active Links</p>
                    <p class="text-3xl font-semibold text-slate-900 dark:text-white">{{ $stats['active_links'] ?? 0 }}</p>
                </div>
            </div>
            
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Recent Links</h3>
                    <a href="{{ route('links.index') }}" class="text-sm text-blue-600 hover:underline dark:text-blue-400">View All →</a>
                </div>
                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-800">
                        <thead class="text-left text-slate-500 dark:text-slate-300">
                            <tr>
                                <th class="py-3">Shortlink</th>
                                <th class="py-3">Destination</th>
                                <th class="py-3">Clicks</th>
                                <th class="py-3">Status</th>
                                <th class="py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse ($latestLinks as $link)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/40">
                                    <td class="py-3">
                                        <div class="font-semibold text-blue-600 dark:text-blue-400">{{ $link->short_url }}</div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400">{{ $link->folder->name ?? 'No folder' }}</div>
                                    </td>
                                    <td class="py-3 max-w-md truncate text-slate-600 dark:text-slate-300">{{ $link->target_url }}</td>
                                    <td class="py-3">{{ $link->clicks }}</td>
                                    <td class="py-3 space-y-1">
                                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $link->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600 dark:bg-slate-800 dark:text-slate-300' }}">{{ ucfirst($link->status) }}</span>
                                        @if ($link->isExpired())
                                            <span class="block rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-700">Expired</span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-right">
                                        <div class="flex items-center justify-end gap-2 text-xs">
                                            <a href="{{ route('links.show', $link) }}" class="rounded-full border border-slate-200 px-3 py-1 text-slate-500 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">Analytics</a>
                                            <a href="{{ route('links.edit', $link) }}" class="rounded-full border border-slate-200 px-3 py-1 text-slate-500 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">Edit</a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-slate-500 dark:text-slate-400">No links available yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Link Creation Modal Component --}}
    <x-link-creation-modal :folders="$folders" :tags="$tags" :isAdmin="auth()->user()->isSuperAdmin()" />
</x-app-layout>
