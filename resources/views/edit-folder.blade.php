<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Manage Folder" subtitle="Focus on the links inside {{ $folder->name }}">
            <x-slot name="actions">
                <a href="{{ route('folders.index') }}" class="rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">Back to folders</a>
                <button type="button" onclick="window.openLinkPanel && window.openLinkPanel()" class="rounded-full bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-blue-500/30 hover:bg-blue-500">+ Create link</button>
            </x-slot>
        </x-page-header>
    </x-slot>
    <div class="py-10">
        <div class="mx-auto max-w-6xl space-y-6 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="rounded-2xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-emerald-900 dark:border-emerald-900/30 dark:bg-emerald-900/20 dark:text-emerald-200">
                    {{ session('status') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="rounded-2xl border border-red-300 bg-red-50 px-4 py-3 text-red-900 dark:border-red-900/30 dark:bg-red-900/10 dark:text-red-200">
                    <ul class="list-disc space-y-1 pl-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="grid gap-6 md:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Folder</p>
                    <div class="mt-2 flex items-center gap-3">
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-white">{{ $folder->name }}</h2>
                        @if ($folder->is_default)
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600 dark:bg-slate-800 dark:text-slate-200">Default</span>
                        @endif
                    </div>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Created {{ optional($folder->created_at)->diffForHumans() }} · {{ $links->total() }} links</p>
                    @if (! $folder->is_default)
                        <form method="POST" action="{{ route('folders.update', $folder) }}" class="mt-4 space-y-3">
                            @csrf
                            @method('PUT')
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-200" for="folder-name">Rename folder</label>
                            <input id="folder-name" type="text" name="name" value="{{ $folder->name }}" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                            <button class="w-full rounded-2xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-500">Save name</button>
                        </form>
                    @else
                        <div class="mt-4 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">
                            Folder default tidak bisa direname.
                        </div>
                    @endif
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Quick actions</p>
                    <div class="mt-4 space-y-3 text-sm">
                        <a href="{{ route('dashboard', ['folder' => $folder->id]) }}" class="flex items-center justify-between rounded-2xl border border-slate-200 px-4 py-3 text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                            <span>View in main dashboard</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                        <a href="{{ route('folders.index') }}#folder-{{ $folder->id }}" class="flex items-center justify-between rounded-2xl border border-slate-200 px-4 py-3 text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                            <span>Scroll to card</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                        <form method="POST" action="{{ route('folders.destroy', $folder) }}" onsubmit="return confirm('Delete this folder?')" class="rounded-2xl border border-rose-200 px-4 py-3 text-rose-600 dark:border-rose-900/30 dark:text-rose-200">
                            @csrf
                            @method('DELETE')
                            <div class="flex items-center justify-between text-sm">
                                <span>Delete folder</span>
                                <button class="font-semibold">Delete</button>
                            </div>
                            <p class="mt-1 text-xs text-rose-500 dark:text-rose-200/80">Make sure the folder is empty before deleting.</p>
                        </form>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Links inside {{ $folder->name }}</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Search and manage every link assigned to this folder.</p>
                    </div>
                    <form method="GET" class="flex w-full gap-2 md:w-80">
                        <input type="text" name="q" value="{{ $search }}" placeholder="Search shortlinks..." class="flex-1 rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                        <button class="rounded-full bg-slate-100 px-4 py-2 text-sm text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-200">Go</button>
                    </form>
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
                            @forelse ($links as $link)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/40">
                                    <td class="py-3 align-top">
                                        <div class="font-semibold text-blue-600 dark:text-blue-400">{{ $link->short_url }}</div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400">Created {{ optional($link->created_at)->diffForHumans() }}</div>
                                    </td>
                                    <td class="py-3 align-top text-slate-600 dark:text-slate-300">{{ \Illuminate\Support\Str::limit($link->target_url, 80) }}</td>
                                    <td class="py-3 align-top">{{ $link->clicks }}</td>
                                    <td class="py-3 align-top space-y-1">
                                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $link->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600 dark:bg-slate-800 dark:text-slate-300' }}">{{ ucfirst($link->status) }}</span>
                                        @if ($link->isExpired())
                                            <span class="block rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-700">Expired</span>
                                        @endif
                                        @if ($link->requiresPassword())
                                            <span class="block rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700">Password</span>
                                        @endif
                                    </td>
                                    <td class="py-3 align-top text-right">
                                        <div class="flex items-center justify-end gap-2 text-xs">
                                            <a href="{{ route('links.show', $link) }}" class="rounded-full border border-slate-200 px-3 py-1 text-slate-500 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">Analytics</a>
                                            <a href="{{ route('links.edit', $link) }}" class="rounded-full border border-slate-200 px-3 py-1 text-slate-500 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">Edit</a>
                                            <form method="POST" action="{{ route('links.destroy', $link) }}" onsubmit="return confirm('Delete this link?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="rounded-full border border-rose-200 px-3 py-1 text-rose-600 hover:bg-rose-50 dark:border-rose-900/30 dark:text-rose-200 dark:hover:bg-rose-900/20">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-slate-500 dark:text-slate-400">No links inside this folder yet.</td>
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
</x-app-layout>
