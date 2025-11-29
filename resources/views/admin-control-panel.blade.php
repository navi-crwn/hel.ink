<x-app-layout>
    <x-slot name="pageTitle">HEL.ink - Admin Control Panel</x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Admin Control Panel</h1>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Platform moderation & system health</p>
        </div>
    </div>

    <div
        x-data="{ 
            panel: @json($errors->any()),
            showAdvanced: false,
            showOgPreview: true,
            targetUrl: '{{ old('target_url') }}',
            ogTitle: '{{ old('custom_title') }}',
            ogDescription: '{{ old('custom_description') }}',
            ogImage: '{{ old('custom_image_url') }}',
            createdLink: null,
            showOgEditor: false,
            showFolderModal: false,
            showTagModal: false,
            newFolderName: '',
            newTagName: '',
            isLoading: false,
            ogFetchTimeout: null,
            async fetchOgData() {
                if (!this.targetUrl || this.targetUrl.length < 5) return;

                clearTimeout(this.ogFetchTimeout);
                this.ogFetchTimeout = setTimeout(async () => {
                    this.isLoading = true;
                    try {
                        const response = await fetch('{{ route('links.fetch-og') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ url: this.targetUrl })
                        });
                        const result = await response.json();

                        if (result.success && result.data) {
                            this.ogTitle = result.data.title || '';
                            this.ogDescription = result.data.description || '';
                            this.ogImage = result.data.image || '';
                        }
                    } catch (e) {
                        console.log('Could not fetch OG data:', e);
                    }
                    this.isLoading = false;
                }, 600);
            }
        }"
        x-init="
            document.body.classList.toggle('overflow-hidden', panel);
            $watch('panel', value => document.body.classList.toggle('overflow-hidden', value));
            $watch('targetUrl', () => { fetchOgData(); });
            window.openLinkPanel = () => { panel = true; createdLink = null; };
        "
        x-on:keydown.window.escape="panel = false"
    >

    <div class="py-4">
        <div class="mx-auto max-w-7xl space-y-4 px-4 md:px-6">
            @if (session('status'))
                <div class="rounded-2xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-emerald-900 dark:border-emerald-900/30 dark:bg-emerald-900/20 dark:text-emerald-200">
                    {{ session('status') }}
                </div>
            @endif

            <div class="grid gap-4 md:grid-cols-4">
                <div class="rounded-2xl bg-white px-6 py-4 shadow-sm dark:bg-slate-900">
                    <p class="text-sm text-slate-500 dark:text-slate-400">Total Users</p>
                    <p class="text-3xl font-semibold text-slate-900 dark:text-white">{{ $adminStats['total_users'] }}</p>
                </div>
                <div class="rounded-2xl bg-white px-6 py-4 shadow-sm dark:bg-slate-900">
                    <p class="text-sm text-slate-500 dark:text-slate-400">Platform Links</p>
                    <p class="text-3xl font-semibold text-slate-900 dark:text-white">{{ $adminStats['total_platform_links'] }}</p>
                </div>
                <div class="rounded-2xl bg-white px-6 py-4 shadow-sm dark:bg-slate-900">
                    <p class="text-sm text-slate-500 dark:text-slate-400">Total Clicks</p>
                    <p class="text-3xl font-semibold text-slate-900 dark:text-white">{{ $adminStats['total_platform_clicks'] }}</p>
                </div>
                <div class="rounded-2xl bg-white px-6 py-4 shadow-sm dark:bg-slate-900">
                    <p class="text-sm text-slate-500 dark:text-slate-400">Clicks Today</p>
                    <p class="text-3xl font-semibold text-slate-900 dark:text-white">{{ $adminStats['today_platform_clicks'] }}</p>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">System Health</h3>
                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-700" x-data="{ restarting: false }">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Queue Worker</p>
                                    <p class="text-2xl font-semibold {{ $adminStats['queue_heartbeat'] ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                        {{ $adminStats['queue_heartbeat'] ? 'Online' : 'Stale' }}
                                    </p>
                                    @if($adminStats['queue_heartbeat'])
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                            Last ping {{ \Carbon\Carbon::parse($adminStats['queue_heartbeat'])->diffForHumans() }}
                                        </p>
                                    @else
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">No heartbeat recorded</p>
                                    @endif
                                </div>
                                <form method="POST" action="{{ route('admin.queue.restart') }}" class="ml-2" @submit="restarting = true">
                                    @csrf
                                    <button type="submit" 
                                            :disabled="restarting"
                                            class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900 disabled:opacity-50 disabled:cursor-not-allowed"
                                            title="Restart queue worker">
                                        <svg class="h-4 w-4" :class="{ 'animate-spin': restarting }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-700">
                            <p class="text-sm text-slate-500 dark:text-slate-400">IP Bans</p>
                            <p class="text-2xl font-semibold text-slate-900 dark:text-white">{{ $adminStats['ip_bans'] }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Active rules guarding public form</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-700">
                            <a href="{{ route('admin.ip-watchlist.index') }}" class="block hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg transition-colors">
                                <p class="text-sm text-slate-500 dark:text-slate-400">IP Watchlist</p>
                                <p class="text-2xl font-semibold text-blue-600 dark:text-blue-400">{{ \App\Models\IpWatchlist::count() }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Suspicious IPs monitored</p>
                            </a>
                        </div>
                        <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-700">
                            <p class="text-sm text-slate-500 dark:text-slate-400">Suspended Users</p>
                            <p class="text-2xl font-semibold text-slate-900 dark:text-white">{{ $adminStats['suspended_users'] }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Accounts currently locked</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-700">
                            <p class="text-sm text-slate-500 dark:text-slate-400">Inactive Links</p>
                            <p class="text-2xl font-semibold text-slate-900 dark:text-white">{{ $adminStats['inactive_links'] }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Links disabled by owners or moderators</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Recent Platform Links</h3>
                        <a href="{{ route('admin.links.index') }}" class="text-sm text-blue-600 hover:underline">View All</a>
                    </div>
                    <div class="space-y-3">
                        @forelse ($recentPlatformLinks as $link)
                            <div class="rounded-xl border border-slate-200 px-4 py-3 dark:border-slate-700">
                                <p class="font-semibold text-blue-600 dark:text-blue-400 truncate">{{ $link->short_url }}</p>
                                <p class="text-sm text-slate-500 dark:text-slate-400 truncate">{{ $link->target_url }}</p>
                                <p class="text-xs text-slate-400">
                                    By: {{ $link->user->name ?? 'Guest' }} · 
                                    Status: {{ ucfirst($link->status) }} · 
                                    {{ $link->created_at->diffForHumans() }}
                                </p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500 dark:text-slate-400">No recent links</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Recent Users</h3>
                        <a href="{{ route('admin.users.index') }}" class="text-sm text-blue-600 hover:underline">Manage Users</a>
                    </div>
                    <div class="space-y-3">
                        @forelse ($recentUsers as $user)
                            <div class="rounded-xl border border-slate-200 px-4 py-3 dark:border-slate-700">
                                <p class="font-semibold text-slate-900 dark:text-white">{{ $user->name }}</p>
                                <p class="text-sm text-slate-500 dark:text-slate-400">{{ $user->email }}</p>
                                <p class="text-xs text-slate-400">
                                    Role: {{ $user->isSuperAdmin() ? 'Superadmin' : ucfirst($user->role) }} · 
                                    Status: {{ ucfirst($user->status) }}
                                </p>
                                <p class="text-xs text-slate-500">
                                    Last login: {{ $user->last_login_at?->diffForHumans() ?? 'Never' }}
                                    @if($user->last_login_ip)
                                        ({{ $user->last_login_ip }})
                                    @endif
                                </p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500 dark:text-slate-400">No users yet</p>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Abuse Reports</h3>
                        <a href="{{ route('admin.abuse.index') }}" class="text-sm text-rose-600 hover:underline">View All</a>
                    </div>
                    <div class="space-y-3">
                        @forelse ($openReports as $report)
                            <div class="rounded-xl border border-slate-200 px-4 py-3 dark:border-slate-700">
                                <p class="font-semibold text-slate-900 dark:text-white truncate">{{ $report->slug ?? 'URL' }}</p>
                                <p class="text-xs text-slate-400 break-all">{{ $report->url }}</p>
                                <p class="text-xs text-slate-400">
                                    Status: {{ ucfirst($report->status) }} · 
                                    {{ $report->created_at->diffForHumans() }}
                                </p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500 dark:text-slate-400">No pending reports</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Top Countries</h3>
                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-5">
                    @forelse ($topPlatformCountries as $country)
                        <div class="rounded-xl border border-slate-200 px-4 py-3 dark:border-slate-700">
                            <p class="font-semibold text-slate-900 dark:text-white">{{ $country->country ?? 'Unknown' }}</p>
                            <p class="text-sm text-slate-500 dark:text-slate-400">{{ $country->total }} clicks</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500 dark:text-slate-400">No country data yet</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div
            x-cloak
            x-show="panel"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
            id="admin-dashboard-modal"
        >
            <div @click="panel = false" class="absolute inset-0 bg-slate-950/60 backdrop-blur"></div>
            <div
                class="relative z-10 w-full max-w-2xl max-h-[90vh] flex flex-col bg-white dark:bg-slate-900 rounded-3xl shadow-2xl overflow-hidden"
                x-transition:enter="transition ease-out duration-200 delay-100"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                role="dialog"
                aria-modal="true"
                @click.stop
            >
                <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 px-6 py-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Links</p>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">New Link</h3>
                    </div>
                    <button @click="panel = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="flex-1 overflow-y-auto px-6 py-6">
                    <form method="POST" action="{{ route('links.store') }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="status" value="active">
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                Destination URL <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="url" 
                                name="target_url" 
                                value="{{ old('target_url') }}" 
                                required 
                                class="w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3 text-slate-900 dark:text-white placeholder-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-shadow"
                                placeholder="https://example.com/your-long-url"
                            >
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Custom Slug</label>
                                <div class="flex rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-500/20 transition-shadow">
                                    <span class="inline-flex items-center px-3 text-sm text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-800/50 border-r border-slate-200 dark:border-slate-700">
                                        hel.ink/
                                    </span>
                                    <input 
                                        type="text" 
                                        name="slug" 
                                        value="{{ old('slug') }}" 
                                        class="flex-1 border-0 bg-transparent px-3 py-3 text-slate-900 dark:text-white placeholder-slate-400 focus:ring-0"
                                        placeholder="my-link"
                                    >
                                </div>
                                <p class="mt-1 text-xs text-slate-500">Leave empty for random slug</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Folder</label>
                                <select 
                                    name="folder_id" 
                                    class="w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3 text-slate-900 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-shadow"
                                >
                                    <option value="">📁 No Folder</option>
                                    @foreach ($folders as $folder)
                                        <option value="{{ $folder->id }}" @selected(old('folder_id') == $folder->id)>
                                            📁 {{ $folder->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Tags</label>
                            <div class="flex flex-wrap gap-2">
                                @forelse ($tags as $tag)
                                    <label class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm text-slate-700 dark:text-slate-300 cursor-pointer hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors">
                                        <input 
                                            type="checkbox" 
                                            name="tags[]" 
                                            value="{{ $tag->id }}" 
                                            @checked(collect(old('tags', []))->contains($tag->id))
                                            class="rounded border-slate-300 dark:border-slate-600 text-blue-600 focus:ring-blue-500"
                                        >
                                        <span>{{ $tag->name }}</span>
                                    </label>
                                @empty
                                    <p class="text-sm text-slate-500">No tags available</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="border-t border-slate-200 dark:border-slate-800 pt-4">
                            <button 
                                type="button"
                                @click="showAdvanced = !showAdvanced"
                                class="flex items-center gap-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                            >
                                <svg 
                                    class="w-4 h-4 transition-transform duration-200" 
                                    :class="{ 'rotate-90': showAdvanced }"
                                    fill="none" 
                                    stroke="currentColor" 
                                    viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                <span>Advanced Options</span>
                            </button>
                        </div>

                        <div x-show="showAdvanced" x-collapse>
                            <div class="space-y-4 pt-2">
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Redirect Type</label>
                                        <select 
                                            name="redirect_type" 
                                            class="w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3 text-slate-900 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                        >
                                            @foreach (['302' => '302 (Temporary)', '301' => '301 (Permanent)', '307' => '307 (Preserve Method)'] as $value => $label)
                                                <option value="{{ $value }}" @selected(old('redirect_type', '302') === $value)>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Expiry Date</label>
                                        <input 
                                            type="datetime-local" 
                                            name="expires_at" 
                                            value="{{ old('expires_at') }}" 
                                            class="w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3 text-slate-900 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                        >
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Password Protection</label>
                                    <input 
                                        type="password" 
                                        name="password" 
                                        class="w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3 text-slate-900 dark:text-white placeholder-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                        placeholder="Leave empty for public access"
                                    >
                                </div>

                                <div class="space-y-3">
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                        OpenGraph Preview (for social media)
                                    </label>
                                    <input 
                                        type="text" 
                                        name="custom_title" 
                                        value="{{ old('custom_title') }}"
                                        x-model="ogTitle"
                                        class="w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3 text-slate-900 dark:text-white placeholder-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                        placeholder="Custom title for social preview"
                                    >
                                    <textarea 
                                        name="custom_description" 
                                        rows="2"
                                        x-model="ogDescription"
                                        class="w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3 text-slate-900 dark:text-white placeholder-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                        placeholder="Description for social preview"
                                    >{{ old('custom_description') }}</textarea>
                                    <input 
                                        type="url" 
                                        name="custom_image_url" 
                                        value="{{ old('custom_image_url') }}"
                                        x-model="ogImage"
                                        class="w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3 text-slate-900 dark:text-white placeholder-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                        placeholder="https://example.com/image.jpg"
                                    >
                                </div>

                                <div>
                                    <button 
                                        type="button"
                                        @click="showOgPreview = !showOgPreview"
                                        class="flex items-center gap-2 text-sm text-blue-600 dark:text-blue-400 hover:underline"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <span x-text="showOgPreview ? 'Hide Preview' : 'Show Preview'"></span>
                                    </button>
                                </div>

                                <div x-show="showOgPreview" x-collapse>
                                    <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 p-4">
                                        <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-2">Preview</p>
                                        <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 overflow-hidden">
                                            <div x-show="ogImage" class="aspect-[1.91/1] bg-slate-100 dark:bg-slate-800">
                                                <img :src="ogImage" alt="Preview" class="w-full h-full object-cover" x-show="ogImage">
                                            </div>
                                            <div class="p-3">
                                                <p class="text-sm font-semibold text-slate-900 dark:text-white" x-text="ogTitle || 'Link Title'"></p>
                                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1" x-text="ogDescription || 'Link description will appear here'"></p>
                                                <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">hel.ink</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Internal Notes</label>
                                    <textarea 
                                        name="comment" 
                                        rows="2"
                                        class="w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3 text-slate-900 dark:text-white placeholder-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                        placeholder="Private notes about this link (not visible to visitors)"
                                    >{{ old('comment') }}</textarea>
                                </div>
                            </div>
                        </div>

                        @if ($errors->any())
                            <div class="rounded-2xl border border-rose-300 dark:border-rose-900/30 bg-rose-50 dark:bg-rose-900/20 px-4 py-3">
                                <ul class="space-y-1 text-sm text-rose-600 dark:text-rose-400">
                                    @foreach ($errors->all() as $error)
                                        <li>• {{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="flex items-center justify-between pt-4 border-t border-slate-200 dark:border-slate-800">
                            <p class="text-xs text-slate-500">Slug must be unique</p>
                            <div class="flex gap-3">
                                <button 
                                    type="button" 
                                    @click="panel = false" 
                                    class="px-5 py-2.5 rounded-2xl border border-slate-200 dark:border-slate-700 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors"
                                >
                                    Cancel
                                </button>
                                <button 
                                    type="submit" 
                                    class="px-6 py-2.5 rounded-2xl bg-gradient-to-r from-blue-600 to-blue-500 text-sm font-semibold text-white hover:from-blue-500 hover:to-blue-400 shadow-lg shadow-blue-500/30 transition-all"
                                >
                                    Create Link
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Link Creation Modal Component --}}
    <x-link-creation-modal :folders="$folders" :tags="$tags" :isAdmin="true" />
</x-app-layout>
