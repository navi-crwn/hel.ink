<x-app-layout>
    <x-slot name="pageTitle">HEL.ink - Manage links</x-slot>
    <div class="py-4">
        <div class="mx-auto max-w-7xl space-y-4 px-4 md:px-6">
            <div class="mb-4">
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Global Links</h1>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">View and manage all platform links</p>
            </div>
            @if (session('status'))
                <div class="rounded-lg border border-emerald-300 bg-emerald-50 px-4 py-3 text-emerald-900">
                    {{ session('status') }}
                </div>
            @endif
            <form method="GET" class="grid gap-2 rounded-xl border border-gray-100 bg-white p-3 shadow-sm dark:border-gray-800 dark:bg-gray-900 md:grid-cols-5">
                <input type="text" name="q" value="{{ $search }}" placeholder="Search slug or destination..." class="rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white md:col-span-2" />
                <select name="status" class="rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    <option value="">All statuses</option>
                    <option value="active" @selected($status === 'active')>Active</option>
                    <option value="inactive" @selected($status === 'inactive')>Inactive</option>
                </select>
                <select name="user_id" class="rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    <option value="">All users</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" @selected(($filters['user_id'] ?? null) == $user->id)>{{ $user->name }}</option>
                    @endforeach
                </select>
                <select name="folder_id" class="rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    <option value="">All folders</option>
                    @foreach ($folders as $folder)
                        <option value="{{ $folder->id }}" @selected(($filters['folder_id'] ?? null) == $folder->id)>{{ $folder->name }}</option>
                    @endforeach
                </select>
                <select name="tag_id" class="rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    <option value="">All tags</option>
                    @foreach ($tags as $tag)
                        <option value="{{ $tag->id }}" @selected(($filters['tag_id'] ?? null) == $tag->id)>{{ $tag->name }}</option>
                    @endforeach
                </select>
                <div class="md:col-span-5 flex items-center gap-3">
                    <button class="rounded-xl bg-blue-600 px-4 py-2 text-white hover:bg-blue-500">Filter</button>
                    <a href="{{ route('admin.links.index') }}" class="text-sm text-gray-500 hover:underline">Reset</a>
                </div>
            </form>
            <div class="rounded-xl border border-gray-100 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900" x-data="bulkActions()">
                <form method="POST" action="{{ route('admin.links.bulk') }}" id="bulk-form">
                    @csrf
                    <div class="flex flex-wrap items-center gap-2 border-b border-gray-100 px-3 py-2 dark:border-gray-800">
                        <select name="action" class="w-40 rounded-full border-gray-200 bg-gray-50 px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                            <option value="disable">Disable Selected</option>
                            <option value="delete">Delete Selected</option>
                        </select>
                        <button type="submit" class="rounded-full bg-rose-100 px-4 py-2 text-sm font-semibold text-rose-600 hover:bg-rose-200 dark:bg-rose-900/30 dark:text-rose-200">Apply to selected</button>
                    </div>
                    <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800 text-xs">
                        <thead class="bg-gray-50 text-left text-gray-600 dark:bg-gray-900 dark:text-gray-300">
                            <tr>
                                <th class="px-3 py-2">
                                    <input type="checkbox" @click="toggleAll($event)" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </th>
                                <th class="px-3 py-2">Slug</th>
                                <th class="px-3 py-2">Owner</th>
                                <th class="px-3 py-2">Folder</th>
                                <th class="px-3 py-2">Tag</th>
                                <th class="px-3 py-2">Status</th>
                                <th class="px-3 py-2 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-800 text-gray-700 dark:text-gray-200">
                            @forelse ($links as $link)
                                <tr>
                                    <td class="px-3 py-2">
                                        <input type="checkbox" name="ids[]" value="{{ $link->id }}" class="link-check rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    </td>
                                    <td class="px-3 py-2">
                                        <p class="font-semibold text-blue-600 dark:text-blue-400 text-xs">{{ $link->short_url }}</p>
                                        <p class="text-[10px] text-gray-500 break-all">{{ Str::limit($link->target_url, 50) }}</p>
                                        <p class="text-[10px] text-gray-400">{{ $link->clicks }} clicks</p>
                                    </td>
                                    <td class="px-3 py-2">
                                        @if($link->user)
                                            <p class="font-semibold">{{ $link->user->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $link->user->email }}</p>
                                        @else
                                            <span class="text-xs text-gray-500">Guest</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2 text-xs">{{ $link->folder->name ?? '-' }}</td>
                                    <td class="px-3 py-2">
                                        @if ($link->tags->isEmpty())
                                            <span class="text-xs text-gray-400">-</span>
                                        @else
                                            <div class="flex flex-wrap gap-1">
                                                @foreach ($link->tags as $tag)
                                                    <span class="rounded-full bg-slate-800/60 px-2 py-0.5 text-[11px]">{{ $tag->name }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2 text-xs">{{ ucfirst($link->status) }}</td>
                                    <td class="px-3 py-2">
                                        <div class="flex justify-end gap-1">
                                            <a href="{{ route('links.edit', $link) }}" class="rounded bg-blue-600 px-2 py-1 text-[10px] font-semibold text-white hover:bg-blue-500">
                                                Edit
                                            </a>
                                            <button type="button" @click="updateStatus({{ $link->id }}, '{{ $link->status }}')" class="rounded bg-indigo-600 px-2 py-1 text-[10px] font-semibold text-white hover:bg-indigo-500">
                                                Status
                                            </button>
                                            <button type="button" @click="deleteLink({{ $link->id }})" class="rounded bg-red-600 px-2 py-1 text-[10px] font-semibold text-white hover:bg-red-500">
                                                Del
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-3 py-4 text-center text-xs text-gray-500 dark:text-gray-400">No links found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="border-t border-gray-100 px-3 py-2 dark:border-gray-800">
                    {{ $links->links() }}
                </div>
                </form>
            </div>
            <script>
                function bulkActions() {
                    return {
                        toggleAll(event) {
                            document.querySelectorAll('.link-check').forEach(cb => cb.checked = event.target.checked);
                        },
                        updateStatus(linkId, currentStatus) {
                            const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
                            if (!confirm(`Change status to ${newStatus}?`)) return;
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = `/admin/links/${linkId}/status`;
                            const csrf = document.createElement('input');
                            csrf.type = 'hidden';
                            csrf.name = '_token';
                            csrf.value = document.querySelector('meta[name="csrf-token"]').content;
                            const method = document.createElement('input');
                            method.type = 'hidden';
                            method.name = '_method';
                            method.value = 'PATCH';
                            const status = document.createElement('input');
                            status.type = 'hidden';
                            status.name = 'status';
                            status.value = newStatus;
                            form.appendChild(csrf);
                            form.appendChild(method);
                            form.appendChild(status);
                            document.body.appendChild(form);
                            form.submit();
                        },
                        deleteLink(linkId) {
                            if (!confirm('Delete this link permanently?')) return;
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = `/admin/links/${linkId}`;
                            const csrf = document.createElement('input');
                            csrf.type = 'hidden';
                            csrf.name = '_token';
                            csrf.value = document.querySelector('meta[name="csrf-token"]').content;
                            const method = document.createElement('input');
                            method.type = 'hidden';
                            method.name = '_method';
                            method.value = 'DELETE';
                            form.appendChild(csrf);
                            form.appendChild(method);
                            document.body.appendChild(form);
                            form.submit();
                        }
                    }
                }
            </script>
        </div>
    </div>
</x-app-layout>
                       *** End Patch
