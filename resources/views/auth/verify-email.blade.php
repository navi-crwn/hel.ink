<x-guest-layout>
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 dark:bg-blue-900/30 mb-4">
            <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Verify Your Email Address</h2>
    </div>
    <div class="mb-6 text-sm text-gray-600 dark:text-gray-400 space-y-2">
        <p>Thanks for signing up! Before getting started, please verify your email address by clicking on the link we just sent to:</p>
        <p class="font-semibold text-gray-900 dark:text-white">{{ auth()->user()->email }}</p>
        <p>If you didn't receive the email, we will gladly send you another.</p>
    </div>
    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 p-4">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="font-medium text-sm text-green-800 dark:text-green-200">
                    A new verification link has been sent to your email address!
                </p>
            </div>
        </div>
    @endif
    @if(session('user_catchphrase'))
    <div class="mb-6 rounded-lg border border-amber-500/50 bg-amber-50 p-4 dark:border-amber-500/30 dark:bg-amber-900/20" x-data="{ copied: false }">
        <div class="flex items-start gap-2">
            <div class="flex-shrink-0 text-xl">🔐</div>
            <div class="flex-1">
                <h3 class="text-sm font-bold text-amber-900 dark:text-amber-100">Your Recovery Catchphrase</h3>
                <p class="mt-1 text-xs text-amber-800 dark:text-amber-200">
                    Save this catchphrase! You'll need it to reset your password. This appears only once.
                </p>
                <div class="mt-3 rounded-lg bg-slate-900 p-3 shadow-inner">
                    <div class="flex items-center justify-between gap-3">
                        <code class="flex-1 text-center font-mono text-sm font-bold text-emerald-400">{{ session('user_catchphrase') }}</code>
                        <button 
                            @click="navigator.clipboard.writeText('{{ session('user_catchphrase') }}'); copied = true; setTimeout(() => copied = false, 2000)"
                            class="flex-shrink-0 rounded-lg bg-slate-700 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-slate-600"
                            x-text="copied ? 'Copied!' : 'Copy'"
                        ></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
        <form method="POST" action="{{ route('verification.send') }}" class="w-full sm:w-auto">
            @csrf
            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Resend Verification Email
            </button>
        </form>
        <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
            @csrf
            <button type="submit" class="w-full sm:w-auto underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                Log Out
            </button>
        </form>
    </div>
    <div class="mt-6 text-xs text-center text-gray-500 dark:text-gray-400">
        <p>📧 Check your inbox and spam folder for the verification email from <strong>no-reply@hel.ink</strong></p>
    </div>
</x-guest-layout>
