<x-app-layout>
    <x-slot name="pageTitle">Inspect User - {{ $user->name }}</x-slot>
        </x-page-header>
    </x-slot>
    <div class="py-4">
        <div class="mx-auto max-w-7xl space-y-4 px-4 md:px-6">
            <div class="grid gap-6 lg:grid-cols-4">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <p class="text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Links</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">{{ $user->links->count() }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <p class="text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Clicks</p>
                    <p class="mt-2 text-3xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($totalClicks) }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <p class="text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400">Folders</p>
                    <p class="mt-2 text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $user->folders->count() }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <p class="text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400">Tags</p>
                    <p class="mt-2 text-3xl font-bold text-emerald-600 dark:text-emerald-400">{{ $user->tags->count() }}</p>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">User Information</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Account details and status</p>
                    </div>
                    <div class="flex gap-2">
                        @if($user->status === \App\Models\User::STATUS_ACTIVE)
                            <form method="POST" action="{{ route('admin.users.status', $user) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="{{ \App\Models\User::STATUS_SUSPENDED }}">
                                <button type="submit" onclick="return confirm('Suspend this user account?')" class="px-4 py-2 rounded-lg bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium">
                                    Suspend Account
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.users.status', $user) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="{{ \App\Models\User::STATUS_ACTIVE }}">
                                <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium">
                                    Activate Account
                                </button>
                            </form>
                        @endif
                        @if(!$user->isSuperAdmin())
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Permanently delete this user and all their data?')" class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-medium">
                                    Delete Account
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                <dl class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="text-slate-500 dark:text-slate-400">Email</dt>
                        <dd class="mt-1 font-medium text-slate-900 dark:text-white">{{ $user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500 dark:text-slate-400">Role</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium {{ $user->isSuperAdmin() ? 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300' : ($user->isAdmin() ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' : 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-300') }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-slate-500 dark:text-slate-400">Status</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium {{ $user->status === \App\Models\User::STATUS_ACTIVE ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-slate-500 dark:text-slate-400">Member Since</dt>
                        <dd class="mt-1 font-medium text-slate-900 dark:text-white">{{ $user->created_at->format('M d, Y') }}</dd>
                    </div>
                </dl>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="border-b border-slate-200 px-6 py-4 dark:border-slate-800">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">User Links ({{ $user->links->count() }})</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 dark:bg-slate-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Short URL</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Destination</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Folder</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tags</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Clicks</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Created</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                            @forelse($user->links as $link)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ $link->short_url }}" target="_blank" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">
                                            {{ $link->slug }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-slate-900 dark:text-white truncate max-w-md" title="{{ $link->target_url }}">
                                            {{ Str::limit($link->target_url, 50) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($link->folder)
                                            <span class="inline-flex items-center rounded-full bg-purple-100 dark:bg-purple-900/30 px-2.5 py-0.5 text-xs font-medium text-purple-800 dark:text-purple-300">
                                                {{ $link->folder->name }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($link->tags as $tag)
                                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium" style="background-color: {{ $tag->color }}20; color: {{ $tag->color }}">
                                                    {{ $tag->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-white">
                                        {{ number_format($link->clicks) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $link->status === 'active' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' }}">
                                            {{ ucfirst($link->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                                        {{ $link->created_at->format('M d, Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-sm text-slate-500 dark:text-slate-400">
                                        No links found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="grid gap-6 lg:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Folders ({{ $user->folders->count() }})</h3>
                    <div class="space-y-2">
                        @forelse($user->folders as $folder)
                            <div class="flex items-center justify-between p-3 rounded-lg bg-slate-50 dark:bg-slate-800">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                    </svg>
                                    <span class="text-sm font-medium text-slate-900 dark:text-white">{{ $folder->name }}</span>
                                    @if($folder->is_default)
                                        <span class="text-xs text-slate-500 dark:text-slate-400">(Default)</span>
                                    @endif
                                </div>
                                <span class="text-xs text-slate-500 dark:text-slate-400">
                                    {{ $folder->links()->count() }} links
                                </span>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500 dark:text-slate-400 text-center py-4">No folders</p>
                        @endforelse
                    </div>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Tags ({{ $user->tags->count() }})</h3>
                    <div class="flex flex-wrap gap-2">
                        @forelse($user->tags as $tag)
                            <span class="inline-flex items-center gap-1 rounded-full px-3 py-1.5 text-sm font-medium" style="background-color: {{ $tag->color }}20; color: {{ $tag->color }}">
                                {{ $tag->name }}
                                <span class="text-xs opacity-75">{{ $tag->links()->count() }}</span>
                            </span>
                        @empty
                            <p class="text-sm text-slate-500 dark:text-slate-400 text-center w-full py-4">No tags</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
