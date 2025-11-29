<div 
    x-data="{ 
        showBanner: !localStorage.getItem('cookie_consent'),
        acceptCookies() {
            localStorage.setItem('cookie_consent', 'accepted');
            this.showBanner = false;
        },
        declineCookies() {
            localStorage.setItem('cookie_consent', 'declined');
            this.showBanner = false;
        }
    }"
    x-show="showBanner"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="translate-y-full opacity-0"
    x-transition:enter-end="translate-y-0 opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="translate-y-0 opacity-100"
    x-transition:leave-end="translate-y-full opacity-0"
    class="fixed bottom-0 left-0 right-0 z-50 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 shadow-lg"
    style="display: none;"
    x-cloak
>
    <div class="max-w-6xl mx-auto px-4 py-3">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <div class="flex items-start space-x-3 flex-1">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-xs text-gray-600 dark:text-gray-300 leading-snug">
                        We use cookies to enhance your experience. 
                        <a href="{{ route('privacy') }}" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">Learn more</a>
                    </p>
                </div>
            </div>
            
            <div class="flex gap-2 sm:flex-shrink-0">
                <button 
                    @click="declineCookies()"
                    class="px-3 py-1.5 text-xs font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded transition-colors"
                >
                    Decline
                </button>
                <button 
                    @click="acceptCookies()"
                    class="px-3 py-1.5 text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 rounded transition-colors"
                >
                    Accept
                </button>
            </div>
        </div>
    </div>
</div>
