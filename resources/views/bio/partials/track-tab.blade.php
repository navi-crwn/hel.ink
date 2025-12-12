<div class="space-y-6">
    <!-- Stats Overview -->
    <div class="grid gap-5 sm:grid-cols-3">
        <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-slate-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-base font-medium text-slate-600 dark:text-slate-400">Total Views</p>
                    <p class="mt-2.5 text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($bioPage->view_count) }}</p>
                </div>
                <div class="rounded-full bg-blue-100 p-3.5 dark:bg-blue-900/30">
                    <svg class="h-7 w-7 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-slate-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-base font-medium text-slate-600 dark:text-slate-400">Total Clicks</p>
                    <p class="mt-2.5 text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($bioPage->links->sum('click_count')) }}</p>
                </div>
                <div class="rounded-full bg-green-100 p-3.5 dark:bg-green-900/30">
                    <svg class="h-7 w-7 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-slate-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-base font-medium text-slate-600 dark:text-slate-400">Active Blocks</p>
                    <p class="mt-2.5 text-3xl font-bold text-slate-900 dark:text-white">{{ $bioPage->links->where('is_active', true)->count() }}</p>
                </div>
                <div class="rounded-full bg-purple-100 p-3.5 dark:bg-purple-900/30">
                    <svg class="h-7 w-7 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    <!-- Block Performance -->
    <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-slate-800">
        <h3 class="mb-5 text-lg font-semibold text-slate-900 dark:text-white">Block Performance</h3>
        @if($bioPage->links->count() > 0)
            <div class="space-y-4">
                @foreach($bioPage->links->sortByDesc('click_count') as $link)
                    <div class="flex items-center justify-between rounded-lg border border-slate-200 p-4 dark:border-slate-700">
                        <div class="flex items-center gap-3">
                            @if($link->type === 'link')
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/30">
                                    <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                    </svg>
                                </div>
                            @elseif($link->type === 'image')
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 dark:bg-green-900/30">
                                    <svg class="h-5 w-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @else
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-100 dark:bg-purple-900/30">
                                    <svg class="h-5 w-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                            @endif
                            <div>
                                <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $link->title ?? 'Untitled Block' }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ ucfirst($link->type) }} Block</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-slate-900 dark:text-white">{{ number_format($link->click_count) }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">clicks</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="py-8 text-center text-slate-500 dark:text-slate-400">
                <svg class="mx-auto h-10 w-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <p class="mt-2 text-xs">No blocks yet. Add blocks in the Build tab to track performance.</p>
            </div>
        @endif
    </div>
    <!-- Recent Activity -->
    <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-slate-800">
        <h3 class="mb-5 text-lg font-semibold text-slate-900 dark:text-white">Recent Activity</h3>
        <div class="space-y-3 text-base">
            <p class="text-slate-600 dark:text-slate-400">Last updated: {{ $bioPage->updated_at->diffForHumans() }}</p>
            <p class="text-slate-600 dark:text-slate-400">Created: {{ $bioPage->created_at->format('M d, Y') }}</p>
            <p class="text-slate-600 dark:text-slate-400">Status: 
                @if($bioPage->is_published)
                    <span class="font-medium text-green-600 dark:text-green-400">Published</span>
                @else
                    <span class="font-medium text-gray-600 dark:text-gray-400">Draft</span>
                @endif
            </p>
        </div>
    </div>
</div>
