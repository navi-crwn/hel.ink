<x-app-layout>
    <x-slot name="pageTitle">HEL.ink - Abuse reports</x-slot>
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Abuse Reports</h1>
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Review reported links</p>
    </div>
    <div class="py-4">
        <div class="mx-auto max-w-7xl space-y-4 px-4 md:px-6">
            @if (session('status'))
                <div class="rounded-lg border border-emerald-300 bg-emerald-50 px-4 py-3 text-emerald-900 dark:border-emerald-900/40 dark:bg-emerald-900/20 dark:text-emerald-200">
                    {{ session('status') }}
                </div>
            @endif
            <div class="rounded-3xl border border-gray-100 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800 text-sm">
                    <thead class="text-left text-gray-500 dark:text-gray-300">
                        <tr>
                            <th class="px-4 py-3">Slug / URL</th>
                            <th class="px-4 py-3">Reason</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        @foreach ($reports as $report)
                            <tr>
                                <td class="px-4 py-3">
                                    <p class="font-semibold">{{ $report->slug ?? 'n/a' }}</p>
                                    <p class="text-xs text-gray-500 break-all">{{ $report->url }}</p>
                                    <p class="text-xs text-gray-400">IP: {{ $report->ip_address }}</p>
                                </td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-200">{{ $report->reason }}</td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 dark:bg-slate-800 dark:text-slate-200">{{ ucfirst($report->status) }}</span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <form method="POST" action="{{ route('admin.abuse.update', $report) }}" class="inline-flex gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="rounded-full border-gray-200 bg-white px-3 py-1 text-xs dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                                            @foreach (['open','investigating','closed'] as $option)
                                                <option value="{{ $option }}" @selected($report->status === $option)>{{ ucfirst($option) }}</option>
                                            @endforeach
                                        </select>
                                        <button class="rounded-full bg-blue-600 px-3 py-1 text-xs font-semibold text-white hover:bg-blue-500">Update</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $reports->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
