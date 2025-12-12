<x-app-layout>
    <x-slot name="pageTitle">My Links</x-slot>
    <div class="py-4">
        <div class="mx-auto max-w-7xl space-y-4 px-4 md:px-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">My Links</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">Manage your personal shortened links</p>
            </div>
        </div>
        <form method="GET" class="flex flex-wrap gap-3">
            <input type="text" 
                   name="q" 
                   value="{{ $search }}" 
                   placeholder="Search links..." 
                   class="flex-1 min-w-[200px] rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
            <select name="folder_id" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                <option value="">All Folders</option>
                @foreach($folders as $folder)
                    <option value="{{ $folder->id }}" {{ $folderId == $folder->id ? 'selected' : '' }}>{{ $folder->name }}</option>
                @endforeach
            </select>
            <select name="tag_id" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                <option value="">All Tags</option>
                @foreach($tags as $tag)
                    <option value="{{ $tag->id }}" {{ $tagId == $tag->id ? 'selected' : '' }}>{{ $tag->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="rounded-lg bg-blue-600 px-6 py-2 text-sm font-semibold text-white hover:bg-blue-500">
                Filter
            </button>
        </form>
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead class="bg-slate-50 dark:bg-slate-800">
                        <tr>
                            <th class="px-3 py-2 text-left text-[10px] font-medium text-slate-500 dark:text-slate-400 uppercase">Short URL</th>
                            <th class="px-3 py-2 text-left text-[10px] font-medium text-slate-500 dark:text-slate-400 uppercase">Destination</th>
                            <th class="px-3 py-2 text-left text-[10px] font-medium text-slate-500 dark:text-slate-400 uppercase">Folder</th>
                            <th class="px-3 py-2 text-left text-[10px] font-medium text-slate-500 dark:text-slate-400 uppercase">Tags</th>
                            <th class="px-3 py-2 text-left text-[10px] font-medium text-slate-500 dark:text-slate-400 uppercase">Clicks</th>
                            <th class="px-3 py-2 text-left text-[10px] font-medium text-slate-500 dark:text-slate-400 uppercase">Status</th>
                            <th class="px-3 py-2 text-left text-[10px] font-medium text-slate-500 dark:text-slate-400 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                        @forelse($links as $link)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <a href="{{ $link->short_url }}" target="_blank" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">
                                        {{ $link->slug }}
                                    </a>
                                </td>
                                <td class="px-3 py-2">
                                    <div class="text-xs text-slate-900 dark:text-white truncate max-w-xs" title="{{ $link->target_url }}">
                                        {{ Str::limit($link->target_url, 40) }}
                                    </div>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap">
                                    @if($link->folder)
                                        <span class="inline-flex items-center rounded-full bg-purple-100 dark:bg-purple-900/30 px-2.5 py-0.5 text-xs font-medium text-purple-800 dark:text-purple-300">
                                            {{ $link->folder->name }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-3 py-2">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($link->tags as $tag)
                                            <span class="inline-flex items-center rounded-full px-1.5 py-0.5 text-[10px] font-medium" style="background-color: {{ $tag->color }}20; color: {{ $tag->color }}">
                                                {{ $tag->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-xs text-slate-900 dark:text-white">
                                    {{ number_format($link->clicks) }}
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-medium {{ $link->status === 'active' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' }}">
                                        {{ ucfirst($link->status) }}
                                    </span>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-xs">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('links.show', $link) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">Analytics</a>
                                        <a href="{{ route('links.edit', $link) }}" class="text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white">Edit</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-3 py-6 text-center text-xs text-slate-500 dark:text-slate-400">
                                    No links found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($links->hasPages())
                <div class="border-t border-slate-200 px-3 py-2 dark:border-slate-800">
                    {{ $links->links() }}
                </div>
            @endif
        </div>
        </div>
    </div>
</x-app-layout>
