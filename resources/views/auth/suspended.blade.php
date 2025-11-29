<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/20">
            <svg class="h-8 w-8 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Account Suspended</h2>
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
            Your account has been suspended and cannot be accessed.
        </p>
    </div>

    <div class="rounded-xl bg-slate-50 p-6 dark:bg-slate-800/50">
        <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-3">Why was my account suspended?</h3>
        <ul class="space-y-2 text-sm text-slate-600 dark:text-slate-400">
            <li class="flex items-start gap-2">
                <span class="mt-1 text-red-600">•</span>
                <span>Violation of our Terms of Service</span>
            </li>
            <li class="flex items-start gap-2">
                <span class="mt-1 text-red-600">•</span>
                <span>Creating links to malicious or prohibited content</span>
            </li>
            <li class="flex items-start gap-2">
                <span class="mt-1 text-red-600">•</span>
                <span>Suspicious or abusive activity detected</span>
            </li>
            <li class="flex items-start gap-2">
                <span class="mt-1 text-red-600">•</span>
                <span>Multiple reports from other users</span>
            </li>
        </ul>
    </div>

    <div class="mt-6 rounded-xl border border-blue-200 bg-blue-50 p-4 dark:border-blue-900/30 dark:bg-blue-900/20">
        <h3 class="text-sm font-semibold text-blue-900 dark:text-blue-100 mb-2">Need help?</h3>
        <p class="text-sm text-blue-800 dark:text-blue-200">
            If you believe this is a mistake, please contact our support team at 
            <a href="mailto:support@hel.ink" class="font-medium underline hover:no-underline">support@hel.ink</a> 
            with your account email and we'll review your case.
        </p>
    </div>

    <div class="mt-6 text-center">
        <a href="{{ route('login') }}" class="text-sm text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white">
            ← Back to login
        </a>
    </div>
</x-guest-layout>
