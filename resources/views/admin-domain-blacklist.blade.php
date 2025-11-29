<x-app-layout>
    <x-slot name="pageTitle">Domain Blacklist</x-slot>
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Domain Blacklist</h1>
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Blocked domains</p>
    </div>
    <div class="py-4">
        <div class="mx-auto max-w-7xl space-y-4 px-4 md:px-6">
            @if(session('status'))
                <div class="rounded-2xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-emerald-900 dark:border-emerald-900/30 dark:bg-emerald-900/20 dark:text-emerald-200">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="rounded-2xl border border-red-300 bg-red-50 px-4 py-3 text-red-900 dark:border-red-900/30 dark:bg-red-900/20 dark:text-red-200">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="border-b border-slate-200 px-6 py-4 dark:border-slate-800">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Blocked Domains</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400">{{ $domains->total() }} domains blocked</p>
                        </div>
                        <button @click="$dispatch('open-add-domain-modal')" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-500 flex items-center gap-2">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Add Domain
                        </button>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 dark:bg-slate-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Domain</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Match Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Notes</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Added</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                            @forelse($domains as $domain)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-mono text-sm text-slate-900 dark:text-white">{{ $domain->domain }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $domain->match_type === 'wildcard' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' }}">
                                            {{ $domain->match_type === 'wildcard' ? '🌐 Wildcard' : '🎯 Exact' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($domain->category)
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ 
                                                $domain->category === 'phishing' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' : 
                                                ($domain->category === 'porn' ? 'bg-pink-100 text-pink-800 dark:bg-pink-900/30 dark:text-pink-300' : 
                                                ($domain->category === 'malware' ? 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300' : 
                                                'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-300'))
                                            }}">
                                                {{ ucfirst($domain->category) }}
                                            </span>
                                        @else
                                            <span class="text-sm text-slate-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($domain->notes)
                                            <p class="text-sm text-slate-600 dark:text-slate-400 max-w-xs truncate" title="{{ $domain->notes }}">{{ $domain->notes }}</p>
                                        @else
                                            <span class="text-sm text-slate-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                                        {{ $domain->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <form method="POST" action="{{ route('admin.domain-blacklist.destroy', $domain) }}" onsubmit="return confirm('Remove this domain from blacklist?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-sm text-slate-500 dark:text-slate-400">
                                        No domains blocked yet
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($domains->hasPages())
                    <div class="border-t border-slate-200 px-6 py-4 dark:border-slate-800">
                        {{ $domains->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div x-data="{ open: false }" 
         @open-add-domain-modal.window="open = true"
         x-show="open" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display: none;">
        
        <div class="fixed inset-0 bg-black/50" @click="open = false"></div>
        
        <div class="relative w-full max-w-lg rounded-2xl bg-white p-6 shadow-xl dark:bg-slate-900">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Add Domain to Blacklist</h3>
                <button @click="open = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.domain-blacklist.store') }}" class="space-y-4">
                @csrf
                
                <div>
                    <label for="domain" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Domain(s)</label>
                    <textarea name="domain" 
                              id="domain" 
                              rows="3"
                              required
                              placeholder="Enter one or multiple domains (one per line)&#10;example.com&#10;badsite.org&#10;*.spam.com"
                              class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-4 py-2 text-slate-900 focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white font-mono text-sm"></textarea>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">💡 Enter domains without http:// or www. Add multiple by entering one per line</p>
                </div>

                <div>
                    <label for="match_type" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Match Type</label>
                    <select name="match_type" 
                            id="match_type" 
                            required
                            class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-4 py-2 text-slate-900 focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                        <option value="exact">🎯 Exact Match (example.com only)</option>
                        <option value="wildcard">🌐 Wildcard (*.example.com - all subdomains)</option>
                    </select>
                </div>

                <div>
                    <label for="category" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Category</label>
                    <select name="category" 
                            id="category"
                            class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-4 py-2 text-slate-900 focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                        <option value="">Select category (optional)</option>
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Notes (optional)</label>
                    <textarea name="notes" 
                              id="notes" 
                              rows="3"
                              placeholder="Why is this domain blocked?"
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
                        Add to Blacklist
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
