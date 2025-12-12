<x-app-layout>
    <x-slot name="pageTitle">{{ $bioPage->title }} - Bio Details</x-slot>

    <div class="py-6">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <a href="{{ route('admin.bio.index') }}" class="mb-2 inline-flex items-center text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400">
                    <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Bio Pages
                </a>
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white">{{ $bioPage->title }}</h1>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Created {{ $bioPage->created_at->diffForHumans() }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('bio.public.show', $bioPage->slug) }}" target="_blank" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                    View Public Page
                </a>
                <form action="{{ route('admin.bio.toggle', $bioPage) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-700">
                        {{ $bioPage->is_published ? 'Unpublish' : 'Publish' }}
                    </button>
                </form>
                <form action="{{ route('admin.bio.destroy', $bioPage) }}" method="POST" class="inline" onsubmit="return confirm('Delete this bio page permanently?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-lg border border-red-300 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 dark:border-red-700 dark:text-red-400 dark:hover:bg-red-900/20">
                        Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <div class="rounded-lg bg-white p-6 shadow dark:bg-slate-800">
                    <h2 class="mb-4 text-lg font-semibold text-slate-900 dark:text-white">Bio Page Information</h2>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Title</label>
                            <p class="mt-1 text-slate-900 dark:text-white">{{ $bioPage->title }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Slug</label>
                            <p class="mt-1 text-slate-900 dark:text-white">{{ $bioPage->slug }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Status</label>
                            <p class="mt-1">
                                @if($bioPage->is_published)
                                    <span class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-800 dark:bg-green-900/30 dark:text-green-400">Published</span>
                                @else
                                    <span class="inline-flex rounded-full bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-800 dark:bg-gray-900/30 dark:text-gray-400">Draft</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-300">View Count</label>
                            <p class="mt-1 text-slate-900 dark:text-white">{{ number_format($bioPage->view_count) }}</p>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Description</label>
                            <p class="mt-1 text-slate-600 dark:text-slate-400">{{ $bioPage->description ?: 'No description' }}</p>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Public URL</label>
                            <a href="{{ route('bio.public.show', $bioPage->slug) }}" target="_blank" class="mt-1 block text-blue-600 hover:underline dark:text-blue-400">
                                {{ config('app.url') }}/b/{{ $bioPage->slug }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="mt-6 rounded-lg bg-white p-6 shadow dark:bg-slate-800">
                    <h2 class="mb-4 text-lg font-semibold text-slate-900 dark:text-white">Links ({{ $bioPage->links->count() }})</h2>
                    @if($bioPage->links->count() > 0)
                        <div class="space-y-3">
                            @foreach($bioPage->links as $link)
                                <div class="flex items-start gap-3 rounded-lg border border-slate-200 p-4 dark:border-slate-700">
                                    @if($link->thumbnail_url)
                                        <img src="{{ Storage::url($link->thumbnail_url) }}" class="h-12 w-12 rounded object-cover" alt="{{ $link->title }}">
                                    @else
                                        <div class="flex h-12 w-12 items-center justify-center rounded bg-slate-100 dark:bg-slate-700">
                                            <svg class="h-6 w-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <div class="flex items-start justify-between">
                                            <div>
                                                <h3 class="font-medium text-slate-900 dark:text-white">{{ $link->title }}</h3>
                                                <a href="{{ $link->url }}" target="_blank" class="mt-1 text-sm text-blue-600 hover:underline dark:text-blue-400">
                                                    {{ Str::limit($link->url, 50) }}
                                                </a>
                                            </div>
                                            <div class="flex items-center gap-3 text-sm">
                                                <div class="text-right">
                                                    <p class="text-slate-500 dark:text-slate-400">Clicks</p>
                                                    <p class="font-semibold text-slate-900 dark:text-white">{{ number_format($link->click_count) }}</p>
                                                </div>
                                                @if($link->is_active)
                                                    <span class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-800 dark:bg-green-900/30 dark:text-green-400">Active</span>
                                                @else
                                                    <span class="inline-flex rounded-full bg-red-100 px-2 py-1 text-xs font-semibold text-red-800 dark:bg-red-900/30 dark:text-red-400">Inactive</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-8 text-center">
                            <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                            </svg>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">No links added yet</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-lg bg-white p-6 shadow dark:bg-slate-800">
                    <h2 class="mb-4 text-lg font-semibold text-slate-900 dark:text-white">Owner</h2>
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 text-lg font-bold text-white">
                            {{ substr($bioPage->user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-medium text-slate-900 dark:text-white">{{ $bioPage->user->name }}</p>
                            <p class="text-sm text-slate-500 dark:text-slate-400">{{ $bioPage->user->email }}</p>
                        </div>
                    </div>
                    <div class="mt-4 space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-600 dark:text-slate-400">User ID:</span>
                            <span class="font-medium text-slate-900 dark:text-white">#{{ $bioPage->user->id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-600 dark:text-slate-400">Joined:</span>
                            <span class="font-medium text-slate-900 dark:text-white">{{ $bioPage->user->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg bg-white p-6 shadow dark:bg-slate-800">
                    <h2 class="mb-4 text-lg font-semibold text-slate-900 dark:text-white">Recent Activity</h2>
                    @if($recentClicks->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentClicks->take(10) as $click)
                                <div class="border-b border-slate-100 pb-3 last:border-0 dark:border-slate-700">
                                    <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $click->bioLink->title }}</p>
                                    <div class="mt-1 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                                        <span>{{ $click->created_at->diffForHumans() }}</span>
                                        @if($click->country)
                                            <span>{{ $click->country }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-8 text-center">
                            <svg class="mx-auto h-8 w-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">No activity yet</p>
                        </div>
                    @endif
                </div>

                <div class="rounded-lg bg-white p-6 shadow dark:bg-slate-800">
                    <h2 class="mb-4 text-lg font-semibold text-slate-900 dark:text-white">Statistics</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-slate-600 dark:text-slate-400">Total Views:</span>
                            <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ number_format($bioPage->view_count) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-slate-600 dark:text-slate-400">Total Links:</span>
                            <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ $bioPage->links->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-slate-600 dark:text-slate-400">Total Clicks:</span>
                            <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ number_format($bioPage->links->sum('click_count')) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-slate-600 dark:text-slate-400">Active Links:</span>
                            <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ $bioPage->links->where('is_active', true)->count() }}</span>
                        </div>
                        <div class="flex justify-between border-t border-slate-100 pt-3 dark:border-slate-700">
                            <span class="text-sm text-slate-600 dark:text-slate-400">Created:</span>
                            <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ $bioPage->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-slate-600 dark:text-slate-400">Last Updated:</span>
                            <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ $bioPage->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
