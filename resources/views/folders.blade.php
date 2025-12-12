<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Folders" subtitle="Organize your shortlinks by campaign">
        </x-page-header>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-5xl space-y-6 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="rounded-lg border border-emerald-300 bg-emerald-50 px-4 py-3 text-emerald-900">
                    {{ session('status') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="rounded-lg border border-red-300 bg-red-50 px-4 py-3 text-red-900">
                    <ul class="list-disc pl-4 space-y-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid gap-6 md:grid-cols-2">
                @forelse ($folders as $folder)
                    @php
                        $linkPayload = $folder->links->map(function ($link) {
                            return [
                                'id' => $link->id,
                                'short_url' => $link->short_url,
                                'target_preview' => \Illuminate\Support\Str::limit($link->target_url, 80),
                                'target_url' => $link->target_url,
                                'clicks' => $link->clicks,
                                'created_at' => optional($link->created_at)->diffForHumans(),
                                'edit_url' => route('links.edit', $link),
                                'delete_url' => route('links.destroy', $link),
                            ];
                        });
                    @endphp
                    <div
                        id="folder-{{ $folder->id }}"
                        x-data='{
                            renaming: false,
                            showDropdown: false,
                            search: "",
                            links: @json($linkPayload),
                            filteredLinks() {
                                if (!this.search) { return this.links; }
                                const term = this.search.toLowerCase();
                                return this.links.filter(link => (
                                    (link.short_url || "") + " " + (link.target_url || "")
                                ).toLowerCase().includes(term));
                            }
                        }'
                        :class="showDropdown ? 'z-20' : 'z-0'"
                        class="flex min-h-[260px] flex-col gap-4 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900"
                    >
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-xs uppercase tracking-wide text-gray-400">Folder</p>
                                <div class="mt-1 flex items-center gap-2" x-show="!renaming" x-cloak>
                                    @if ($folder->is_default)
                                        <span class="rounded-full bg-gray-200 px-3 py-1 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-300">Default</span>
                                    @else
                                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $folder->name }}</p>
                                        <button type="button" class="rounded-full p-2 text-gray-500 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800" @click="renaming = true" title="Rename folder">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h2m2 0h6m-6 0v6m0-6l-7 7-4 4v3h3l4-4 7-7" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $folder->links_count }} links total</p>
                            </div>
                            <div class="flex flex-wrap justify-end gap-2 text-sm">
                                <a href="{{ route('folders.manage', $folder) }}" class="rounded-full border border-gray-300 px-4 py-2 text-gray-700 hover:bg-gray-100 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-800">Edit</a>
                                <form method="POST" action="{{ route('folders.destroy', $folder) }}" onsubmit="return confirm('Delete this folder?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="rounded-full border border-red-200 px-4 py-2 text-red-600 hover:bg-red-50 dark:border-red-900/40 dark:text-red-300 dark:hover:bg-red-900/30">Delete</button>
                                </form>
                            </div>
                        </div>

                        @if (! $folder->is_default)
                            <form
                                method="POST"
                                action="{{ route('folders.update', $folder) }}"
                                class="flex flex-wrap gap-2"
                                x-show="renaming"
                                x-cloak
                            >
                                @csrf
                                @method('PUT')
                                <input type="text" name="name" value="{{ $folder->name }}" class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                                <div class="flex gap-2 text-xs">
                                    <button class="rounded-xl bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-500">Save</button>
                                    <button type="button" class="rounded-xl border border-gray-300 px-4 py-2 text-gray-600 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-800" @click="renaming = false">Cancel</button>
                                </div>
                            </form>
                        @endif

                        <div class="flex flex-col gap-3">
                            <div class="relative z-10">
                                <button type="button" @click="showDropdown = !showDropdown" class="flex w-full items-center justify-between rounded-xl border border-gray-200 bg-gray-50 px-4 py-2 text-sm text-gray-700 transition hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                                    <span>View links</span>
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" :class="showDropdown ? 'rotate-180 transition' : 'transition'">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div
                                    x-show="showDropdown"
                                    x-transition
                                    @click.away="showDropdown = false"
                                    @keydown.escape.window="showDropdown = false"
                                    class="absolute left-0 top-full z-50 mt-2 w-full rounded-2xl border border-slate-200 bg-white shadow-2xl dark:border-slate-700 dark:bg-slate-900"
                                    style="display: none;"
                                >
                                    <div class="border-b border-slate-200 p-3 dark:border-slate-800">
                                        <input type="text" x-model="search" placeholder="Search links..." class="w-full rounded-xl border-slate-200 bg-slate-50 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                                    </div>
                                    <div class="space-y-3 overflow-y-auto p-3" style="max-height: 320px;">
                                        <template x-if="filteredLinks().length === 0">
                                            <p class="text-xs text-slate-500 dark:text-slate-400">No links found.</p>
                                        </template>
                                        <template x-for="link in filteredLinks()" :key="link.id">
                                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-3 text-sm shadow-sm dark:border-slate-700 dark:bg-slate-800">
                                                <p class="font-semibold text-blue-600 dark:text-blue-400" x-text="link.short_url"></p>
                                                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400" x-text="link.target_preview"></p>
                                                <p class="mt-1 text-[11px] text-slate-400 dark:text-slate-500" x-text="`${link.clicks} clicks • ${link.created_at || '—'}`"></p>
                                                <div class="mt-2 flex gap-2 text-xs">
                                                    <a :href="link.edit_url" class="rounded-full border border-slate-300 px-3 py-1 text-slate-700 hover:bg-slate-100 dark:border-slate-600 dark:text-slate-200 dark:hover:bg-slate-700">Edit</a>
                                                    <form method="POST" :action="link.delete_url" class="inline" onsubmit="return confirm('Delete this link?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="rounded-full border border-red-200 px-3 py-1 text-red-600 hover:bg-red-50 dark:border-red-900/40 dark:text-red-300 dark:hover:bg-red-900/30">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="rounded-2xl border border-dashed border-gray-300 px-4 py-6 text-sm text-gray-500 dark:border-gray-700 dark:text-gray-400">No folders yet.</p>
                @endforelse
            </div>

            <div class="pt-4">
                {{ $folders->links() }}
            </div>
        </div>
    </div>

    <!-- Create Folder Modal -->
    <div x-data="{ open: false }" 
         x-init="window.openFolderModal = () => { open = true }"
         @keydown.escape.window="open = false"
         x-cloak>
        <div x-show="open" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-screen items-center justify-center p-4">
                <div x-show="open" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-black/50 backdrop-blur-sm" 
                     @click="open = false"></div>
                
                <div x-show="open"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="relative w-full max-w-md transform rounded-2xl bg-white p-6 shadow-2xl dark:bg-slate-800">
                    
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Create New Folder</h3>
                        <button @click="open = false" class="rounded-full p-2 text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    
                    <form method="POST" action="{{ route('folders.store') }}">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="folder-name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Folder Name</label>
                                <input type="text" 
                                       id="folder-name" 
                                       name="name" 
                                       placeholder="e.g., Marketing Campaign" 
                                       class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white"
                                       required
                                       autofocus>
                            </div>
                            
                            <div class="flex justify-end gap-3 pt-2">
                                <button type="button" @click="open = false" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-700">
                                    Cancel
                                </button>
                                <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-500 shadow-lg shadow-blue-500/25">
                                    Create Folder
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
