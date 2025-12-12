<x-app-layout>
    <x-slot name="pageTitle">HEL.ink - My Links</x-slot>
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white">My Links</h1>
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Manage all your short links</p>
    </div>
    <div class="py-10" x-data="linksManager()">
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
                        </div>
                    </div>
                </div>
            @endif
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">All Links</h3>
                    <button type="button" onclick="window.openLinkPanel && window.openLinkPanel()" class="create-link-trigger rounded-full bg-blue-600 px-5 md:px-8 py-2 text-sm md:text-base font-semibold text-white shadow-lg shadow-blue-500/30 hover:bg-blue-500 transition-all">
                        <span class="hidden md:inline">+ Create Link</span>
                        <span class="md:hidden">+</span>
                    </button>
                </div>
                <form method="GET" class="mt-4 flex flex-wrap gap-3">
                    <input type="text" name="q" value="{{ $filters['q'] }}" placeholder="Search shortlinks..." class="flex-1 rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                    <select name="folder" class="rounded-full border border-slate-200 bg-slate-50 px-4 py-2 pr-12 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                        <option value="">All folders</option>
                        @foreach ($folders as $folder)
                            <option value="{{ $folder->id }}" @selected($filters['folder'] == $folder->id)>{{ $folder->name }}</option>
                        @endforeach
                    </select>
                    <select name="tag" class="rounded-full border border-slate-200 bg-slate-50 px-4 py-2 pr-12 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                        <option value="">All tags</option>
                        @foreach ($tags as $tag)
                            <option value="{{ $tag->id }}" @selected($filters['tag'] == $tag->id)>{{ $tag->name }}</option>
                        @endforeach
                    </select>
                    <select name="sort" class="rounded-full border border-slate-200 bg-slate-50 px-4 py-2 pr-12 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                        <option value="created_desc" @selected($filters['sort'] == 'created_desc')>Newest first</option>
                        <option value="created_asc" @selected($filters['sort'] == 'created_asc')>Oldest first</option>
                        <option value="clicks_desc" @selected($filters['sort'] == 'clicks_desc')>Most clicks</option>
                        <option value="clicks_asc" @selected($filters['sort'] == 'clicks_asc')>Least clicks</option>
                        <option value="slug_asc" @selected($filters['sort'] == 'slug_asc')>A-Z</option>
                        <option value="slug_desc" @selected($filters['sort'] == 'slug_desc')>Z-A</option>
                    </select>
                    <button class="rounded-full bg-slate-100 px-4 py-2 text-sm text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-200">Apply</button>
                </form>
                <div class="mt-4 overflow-x-auto">
                    <!-- Bulk Actions Bar -->
                    <div x-show="selectedLinks.length > 0" x-cloak class="mb-4 flex items-center gap-4 rounded-lg bg-blue-50 dark:bg-blue-900/20 p-3 border border-blue-200 dark:border-blue-800">
                        <span class="text-sm font-medium text-blue-700 dark:text-blue-300" x-text="selectedLinks.length + ' selected'"></span>
                        <button @click="bulkDelete()" class="rounded-full bg-rose-600 px-4 py-1.5 text-xs font-semibold text-white hover:bg-rose-500">Delete Selected</button>
                        <button @click="selectedLinks = []; selectAll = false" class="text-xs text-slate-600 dark:text-slate-400 hover:underline">Clear</button>
                    </div>
                    <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-800">
                        <thead class="text-left text-slate-500 dark:text-slate-300">
                            <tr>
                                <th class="py-3 pr-2 w-10">
                                    <input type="checkbox" x-model="selectAll" @change="toggleSelectAll()" class="rounded border-slate-300 dark:border-slate-600">
                                </th>
                                <th class="py-3">Shortlink</th>
                                <th class="py-3">Destination</th>
                                <th class="py-3">Clicks</th>
                                <th class="py-3">Created</th>
                                <th class="py-3">Status</th>
                                <th class="py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse ($links as $link)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/40" x-show="!deletedLinks.includes({{ $link->id }})">
                                    <td class="py-3 pr-2">
                                        <input type="checkbox" value="{{ $link->id }}" x-model="selectedLinks" class="rounded border-slate-300 dark:border-slate-600">
                                    </td>
                                    <td class="py-3">
                                        <div class="font-semibold text-blue-600 dark:text-blue-400">{{ $link->short_url }}</div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400">
                                            {{ $link->folder->name ?? 'No folder' }}
                                            @if ($link->tags->count())
                                                <span class="mx-1">•</span>
                                                @foreach ($link->tags as $tag)
                                                    <span class="inline-block rounded-full bg-slate-100 px-2 py-0.5 dark:bg-slate-800">{{ $tag->name }}</span>
                                                @endforeach
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-3 max-w-md">
                                        <div class="truncate text-slate-600 dark:text-slate-300">{{ $link->target_url }}</div>
                                        @if ($link->title)
                                            <div class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $link->title }}</div>
                                        @endif
                                    </td>
                                    <td class="py-3 font-semibold">{{ number_format($link->clicks) }}</td>
                                    <td class="py-3 text-slate-500 dark:text-slate-400">
                                        <div class="text-xs">{{ $link->created_at->format('M d, Y') }}</div>
                                        <div class="text-xs">{{ $link->created_at->format('H:i') }}</div>
                                    </td>
                                    <td class="py-3 space-y-1">
                                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $link->status === 'active' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-400' : 'bg-slate-200 text-slate-600 dark:bg-slate-800 dark:text-slate-300' }}">{{ ucfirst($link->status) }}</span>
                                        @if ($link->isExpired())
                                            <span class="block rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-700 dark:bg-rose-900/20 dark:text-rose-400">Expired</span>
                                        @endif
                                        @if ($link->requiresPassword())
                                            <span class="block rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700 dark:bg-indigo-900/20 dark:text-indigo-400">🔒</span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-right">
                                        <div class="flex items-center justify-end gap-2 text-xs">
                                            <a href="{{ route('links.show', $link) }}" class="rounded-full border border-slate-200 px-3 py-1 text-slate-500 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">Analytics</a>
                                            <a href="{{ route('links.edit', $link) }}" class="rounded-full border border-slate-200 px-3 py-1 text-slate-500 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">Edit</a>
                                            <button @click="deleteLink({{ $link->id }})" class="rounded-full border border-rose-200 px-3 py-1 text-rose-600 hover:bg-rose-50 dark:border-rose-900/30 dark:text-rose-200 dark:hover:bg-rose-900/20">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center">
                                        <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                        </svg>
                                        <p class="mt-2 text-slate-500 dark:text-slate-400">No links available yet.</p>
                                        <button type="button" onclick="window.openLinkPanel && window.openLinkPanel()" class="mt-4 rounded-full bg-blue-600 px-6 py-2 text-sm font-semibold text-white hover:bg-blue-500">
                                            Create your first link
                                        </button>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $links->links() }}
                </div>
            </div>
        </div>
    </div>
    <script>
        function linksManager() {
            return {
                selectedLinks: [],
                selectAll: false,
                deletedLinks: [],
                linkIds: @json($links->pluck('id')->toArray()),
                toggleSelectAll() {
                    if (this.selectAll) {
                        this.selectedLinks = this.linkIds.filter(id => !this.deletedLinks.includes(id)).map(String);
                    } else {
                        this.selectedLinks = [];
                    }
                },
                async deleteLink(id) {
                    if (!confirm('Delete this link permanently?')) return;
                    try {
                        const response = await fetch(`/links/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        });
                        if (response.ok) {
                            this.deletedLinks.push(id);
                            this.selectedLinks = this.selectedLinks.filter(lid => lid != id);
                        } else {
                            alert('Failed to delete link');
                        }
                    } catch (e) {
                        console.error('Delete error:', e);
                        alert('Failed to delete link');
                    }
                },
                async bulkDelete() {
                    if (!confirm(`Delete ${this.selectedLinks.length} links permanently?`)) return;
                    for (const id of this.selectedLinks) {
                        try {
                            await fetch(`/links/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            });
                            this.deletedLinks.push(parseInt(id));
                        } catch (e) {
                            console.error('Delete error for', id, e);
                        }
                    }
                    this.selectedLinks = [];
                    this.selectAll = false;
                }
            };
        }
    </script>
    {{-- Link Creation Modal Component --}}
    <x-link-creation-modal :folders="$folders" :tags="$tags" :isAdmin="auth()->user()->isSuperAdmin()" />
</x-app-layout>
