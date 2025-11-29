<x-app-layout>
    <x-slot name="pageTitle">IP Watchlist</x-slot>
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white">IP Watchlist</h1>
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Suspicious IP monitoring</p>
    </div>
    <div class="py-4">
        <div class="mx-auto max-w-7xl space-y-4 px-4 md:px-6">
            @if(session('status'))
                <div class="rounded-2xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-emerald-900 dark:border-emerald-900/30 dark:bg-emerald-900/20 dark:text-emerald-200">
                    {{ session('status') }}
                </div>
            @endif

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="border-b border-slate-200 px-6 py-4 dark:border-slate-800">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Watched IP Addresses</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400">{{ $watchlist->total() }} IPs being monitored</p>
                        </div>
                        <button @click="$dispatch('open-add-ip-modal')" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-500 flex items-center gap-2">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Add IP Address
                        </button>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 dark:bg-slate-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">IP Address</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Reason</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Attempts</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Last Attempt</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                            @forelse($watchlist as $item)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-mono text-sm text-slate-900 dark:text-white">{{ $item->ip_address }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($item->user)
                                            <div class="text-sm">
                                                <div class="font-medium text-slate-900 dark:text-white">{{ $item->user->name }}</div>
                                                <div class="text-slate-500 dark:text-slate-400">{{ $item->user->email }}</div>
                                            </div>
                                        @else
                                            <span class="text-sm text-slate-500 dark:text-slate-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="max-w-xs">
                                            <div class="text-sm text-slate-900 dark:text-white">{{ $item->reason }}</div>
                                            @if($item->notes)
                                                <div class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $item->notes }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center rounded-full bg-amber-100 dark:bg-amber-900/30 px-2.5 py-0.5 text-xs font-medium text-amber-800 dark:text-amber-300">
                                            {{ $item->attempt_count }} {{ Str::plural('attempt', $item->attempt_count) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                                        @if($item->last_attempt_at)
                                            {{ $item->last_attempt_at->diffForHumans() }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <form method="POST" action="{{ route('admin.ip-watchlist.destroy', $item) }}" onsubmit="return confirm('Remove this IP from watchlist?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-sm text-slate-500 dark:text-slate-400">
                                        No IP addresses on watchlist
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($watchlist->hasPages())
                    <div class="border-t border-slate-200 px-6 py-4 dark:border-slate-800">
                        {{ $watchlist->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Add IP Modal --}}
    <div x-data="{ open: false }" 
         @open-add-ip-modal.window="open = true"
         x-show="open" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display: none;">
        
        <div class="fixed inset-0 bg-black/50" @click="open = false"></div>
        
        <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-xl dark:bg-slate-900">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Add IP to Watchlist</h3>
                <button @click="open = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.ip-watchlist.store') }}" class="space-y-4">
                @csrf
                
                <div>
                    <label for="ip_address" class="block text-sm font-medium text-slate-700 dark:text-slate-300">IP Address(es)</label>
                    <textarea name="ip_address" 
                              id="ip_address" 
                              rows="3"
                              required
                              placeholder="Enter one or multiple IP addresses (one per line)&#10;192.168.1.100&#10;10.0.0.50&#10;172.16.0.1"
                              class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-4 py-2 text-slate-900 focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white font-mono text-sm"></textarea>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">💡 Add multiple IPs by entering one per line</p>
                </div>

                <div>
                    <label for="reason" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Reason</label>
                    <input type="text" 
                           name="reason" 
                           id="reason" 
                           placeholder="Why is this IP suspicious?"
                           class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-4 py-2 text-slate-900 focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Notes (optional)</label>
                    <textarea name="notes" 
                              id="notes" 
                              rows="3"
                              placeholder="Additional details..."
                              class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-4 py-2 text-slate-900 focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white"></textarea>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" 
                            @click="open = false"
                            class="flex-1 rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-800">
                        Cancel
                    </button>
                    <button type="submit"
                            class="flex-1 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-500">
                        Add to Watchlist
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
