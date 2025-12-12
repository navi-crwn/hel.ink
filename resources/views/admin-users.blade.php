<x-app-layout>
    <x-slot name="pageTitle">HEL.ink - Manage users</x-slot>
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Manage Users</h1>
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">User accounts and permissions</p>
    </div>
    <div class="py-4">
        <div class="mx-auto max-w-7xl space-y-4 px-4 md:px-6">
            @if (session('status'))
                <div class="rounded-lg border border-emerald-300 bg-emerald-50 px-4 py-3 text-emerald-900">
                    {{ session('status') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="rounded-lg border border-red-300 bg-red-50 px-4 py-3 text-red-900">
                    <ul class="list-disc pl-5 space-y-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="GET" class="flex flex-wrap items-center gap-3 rounded-2xl border border-gray-100 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <input type="text" name="q" value="{{ $search }}" placeholder="Search name or email..." class="flex-1 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white" />
                <button class="rounded-xl bg-blue-600 px-4 py-2 text-white hover:bg-blue-500">Search</button>
                <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 hover:underline">Reset</a>
            </form>
            @if ($viewerIsSuperAdmin)
                <div class="rounded-2xl border border-emerald-200 bg-white p-6 shadow-sm dark:border-emerald-900/40 dark:bg-gray-900">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Create User</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Create a regular user account without going through registration.</p>
                    <form method="POST" action="{{ route('admin.users.store') }}" class="mt-4 grid gap-4 md:grid-cols-2">
                        @csrf
                        <div class="md:col-span-1">
                            <label class="text-sm text-gray-600 dark:text-gray-300">Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white" autocomplete="off">
                            @error('name')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-1">
                            <label class="text-sm text-gray-600 dark:text-gray-300">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white" autocomplete="off">
                            @error('email')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-1">
                            <label class="text-sm text-gray-600 dark:text-gray-300">Password</label>
                            <input type="password" name="password" required class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white" autocomplete="new-password">
                            @error('password')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-1">
                            <label class="text-sm text-gray-600 dark:text-gray-300">Confirm password</label>
                            <input type="password" name="password_confirmation" required class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white" autocomplete="new-password">
                        </div>
                        <div class="md:col-span-2 flex justify-end">
                            <button type="submit" class="rounded-full bg-emerald-600 px-6 py-2 text-sm font-semibold text-white hover:bg-emerald-500">Create User</button>
                        </div>
                    </form>
                </div>
            @endif
            <div class="rounded-2xl border border-gray-100 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800 text-sm">
                        <thead class="bg-gray-50 text-left text-gray-600 dark:bg-gray-900 dark:text-gray-300">
                            <tr>
                                <th class="px-4 py-3">User</th>
                                <th class="px-4 py-3">Role</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Activity</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-800 text-gray-700 dark:text-gray-200">
                            @forelse ($users as $user)
                                @php
                                    $isSuperAdminRow = $user->isSuperAdmin();
                                @endphp
                                <tr>
                                    <td class="px-4 py-3">
                                        <p class="font-semibold">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if ($isSuperAdminRow)
                                            <span class="inline-flex items-center rounded-full bg-purple-100 px-2.5 py-0.5 text-xs font-medium text-purple-800 dark:bg-purple-900/30 dark:text-purple-300">Superadmin</span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">User</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">{{ ucfirst($user->status) }}</td>
                                    <td class="px-4 py-3">
                                        <p class="text-xs text-gray-500">Last login: {{ $user->last_login_at?->diffForHumans() ?? 'Never' }}</p>
                                        @if ($user->last_login_ip)
                                            <p class="text-xs text-gray-500">IP: {{ $user->last_login_ip }} {{ $user->last_login_country ? '('.$user->last_login_country.')' : '' }}</p>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right space-y-2">
                                        <div class="flex flex-col items-end gap-2">
                                            <a href="{{ route('admin.users.inspect', $user) }}" class="rounded-lg bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-200 dark:bg-slate-700 dark:text-slate-200 dark:hover:bg-slate-600">
                                                👁️ Inspect
                                            </a>
                                        @if ($viewerIsSuperAdmin && ! $isSuperAdminRow)
                                            <form method="POST" action="{{ route('admin.users.status', $user) }}" class="inline-flex items-center gap-2">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" class="rounded-lg border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                                                    <option value="active" @selected($user->status === 'active')>Active</option>
                                                    <option value="suspended" @selected($user->status === 'suspended')>Suspended</option>
                                                </select>
                                                <button class="rounded-lg bg-indigo-600 px-3 py-1 text-xs font-semibold text-white hover:bg-indigo-500">Update status</button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this account and all links?')" class="inline-flex items-center gap-2">
                                                @csrf
                                                @method('DELETE')
                                                <button class="rounded-lg border border-rose-200 px-3 py-1 text-xs font-semibold text-rose-600 hover:bg-rose-50 dark:border-rose-900/30 dark:text-rose-200 dark:hover:bg-rose-900/30">Delete user</button>
                                            </form>
                                        @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">No users found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="border-t border-gray-100 px-4 py-3 dark:border-gray-800">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
