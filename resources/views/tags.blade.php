<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Tags" subtitle="Label your shortlinks for faster filtering">
            <x-slot name="actions">
                <form method="POST" action="{{ route('tags.store') }}" class="flex gap-2">
                    @csrf
                    <input type="text" name="name" placeholder="Tag name..." class="rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white" required>
                    <button class="rounded-xl bg-blue-600 px-4 py-2 text-white hover:bg-blue-500">Create</button>
                </form>
            </x-slot>
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
                @forelse ($tags as $tag)
                    @php
                        $linkPayload = $tag->links->map(function ($link) {
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
                        id="tag-{{ $tag->id }}"
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
                                <p class="text-xs uppercase tracking-wide text-gray-400">Tag</p>
                                <div class="mt-1 flex items-center gap-2" x-show="!renaming" x-cloak>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $tag->name }}</p>
                                    <button type="button" class="rounded-full p-2 text-gray-500 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800" @click="renaming = true" title="Rename tag">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h2m2 0h6m-6 0v6m0-6l-7 7-4 4v3h3l4-4 7-7" />
                                        </svg>
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $tag->links_count }} links total</p>
                            </div>
                            <div class="flex flex-wrap justify-end gap-2 text-sm">
                                <a href="{{ route('tags.manage', $tag) }}" class="rounded-full border border-gray-300 px-4 py-2 text-gray-700 hover:bg-gray-100 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-800">Edit</a>
                                <form method="POST" action="{{ route('tags.destroy', $tag) }}" onsubmit="return confirm('Delete this tag?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="rounded-full border border-red-200 px-4 py-2 text-red-600 hover:bg-red-50 dark:border-red-900/40 dark:text-red-300 dark:hover:bg-red-900/30">Delete</button>
                                </form>
                            </div>
                        </div>

                        <form
                            method="POST"
                            action="{{ route('tags.update', $tag) }}"
                            class="flex flex-wrap gap-2"
                            x-show="renaming"
                            x-cloak
                        >
                            @csrf
                            @method('PUT')
                            <input type="text" name="name" value="{{ $tag->name }}" class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                            <div class="flex gap-2 text-xs">
                                <button class="rounded-xl bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-500">Save</button>
                                <button type="button" class="rounded-xl border border-gray-300 px-4 py-2 text-gray-600 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-800" @click="renaming = false">Cancel</button>
                            </div>
                        </form>

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
                    <p class="rounded-2xl border border-dashed border-gray-300 px-4 py-6 text-sm text-gray-500 dark:border-gray-700 dark:text-gray-400">No tags yet.</p>
                @endforelse
            </div>

            <div class="pt-4">
                {{ $tags->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
