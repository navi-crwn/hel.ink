@props(['message' => 'This domain is blocked', 'category' => null])
<div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-data="{ show: true }" x-show="show" x-transition>
    <div class="relative w-full max-w-md">
        <div class="flex justify-center mb-4">
            <div class="relative">
                <div class="absolute inset-0 bg-red-500 rounded-full opacity-20 animate-ping"></div>
                <div class="relative bg-red-600 rounded-full p-6">
                    <svg class="w-16 h-16 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border-4 border-red-500 p-6">
            <div class="text-center">
                <h3 class="text-2xl font-bold text-red-600 dark:text-red-400 mb-2">
                    ⚠️ Domain Blocked
                </h3>
                @if($category)
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium mb-3 {{ 
                        $category === 'phishing' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' : 
                        ($category === 'porn' ? 'bg-pink-100 text-pink-800 dark:bg-pink-900/30 dark:text-pink-300' : 
                        ($category === 'malware' ? 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300' : 
                        'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-300'))
                    }}">
                        {{ ucfirst($category) }} Content
                    </div>
                @endif
                <p class="text-slate-700 dark:text-slate-300 mb-6">
                    {{ $message }}
                </p>
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-4 text-sm text-left">
                    <p class="text-red-800 dark:text-red-300 font-medium mb-2">🛡️ Security Notice:</p>
                    <ul class="text-red-700 dark:text-red-400 space-y-1 text-xs">
                        <li>• This domain has been flagged by our security system</li>
                        <li>• Links to this domain cannot be created</li>
                        <li>• Contact admin if you believe this is an error</li>
                    </ul>
                </div>
                <button @click="show = false" class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-3 px-6 rounded-lg transition-colors">
                    I Understand
                </button>
            </div>
        </div>
    </div>
</div>
