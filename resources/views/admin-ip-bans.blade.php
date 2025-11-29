<x-app-layout>
    <x-slot name="pageTitle">HEL.ink - IP bans</x-slot>
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white">IP Bans</h1>
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Blocked IP addresses</p>
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
                    <ul class="list-disc pl-4 space-y-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.ip-bans.store') }}" class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900 grid gap-4 md:grid-cols-2">
                @csrf
                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-300">IP Address</label>
                    <input type="text" name="ip_address" class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white" placeholder="217.15.164.195" required>
                </div>
                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-300">Expires at (optional)</label>
                    <input type="datetime-local" name="expires_at" class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                </div>
                <div class="md:col-span-2">
                    <label class="text-sm text-gray-600 dark:text-gray-300">Reason</label>
                    <input type="text" name="reason" class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white" placeholder="Spam or abuse">
                </div>
                <div class="md:col-span-2 flex justify-end">
                    <button class="rounded-xl bg-rose-600 px-4 py-2 text-white">Add IP ban</button>
                </div>
            </form>

            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800 text-sm">
                        <thead class="text-left text-gray-600 dark:text-gray-300">
                            <tr>
                                <th class="px-4 py-3">IP</th>
                                <th class="px-4 py-3">Reason</th>
                                <th class="px-4 py-3">Expires</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                            @forelse ($bans as $ban)
                                <tr>
                                    <td class="px-4 py-3 font-semibold">{{ $ban->ip_address }}</td>
                                    <td class="px-4 py-3">{{ $ban->reason ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ optional($ban->expires_at)->format('d M Y H:i') ?? 'No expiry' }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <form method="POST" action="{{ route('admin.ip-bans.destroy', $ban) }}" onsubmit="return confirm('Remove this IP ban?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="rounded-lg bg-gray-200 px-3 py-1 text-xs font-semibold text-gray-800 hover:bg-gray-300 dark:bg-gray-800 dark:text-gray-100">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">No IP bans yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $bans->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
