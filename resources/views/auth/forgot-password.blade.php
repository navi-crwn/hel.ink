<x-guest-layout>
    <div class="mb-6" x-data="{ method: 'catchphrase' }">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Reset Your Password</h2>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                Choose your preferred method to reset your password
            </p>
        </div>
        <!-- Method Selection Tabs -->
        <div class="flex gap-2 p-1 bg-slate-100 dark:bg-slate-800 rounded-lg mb-6">
            <button 
                @click="method = 'catchphrase'"
                :class="method === 'catchphrase' ? 'bg-white dark:bg-slate-700 shadow-sm' : 'hover:bg-slate-50 dark:hover:bg-slate-700/50'"
                class="flex-1 px-4 py-2.5 text-sm font-semibold rounded-md transition"
            >
                <div class="flex items-center justify-center gap-2">
                    <span>🔐</span>
                    <span :class="method === 'catchphrase' ? 'text-slate-900 dark:text-slate-100' : 'text-slate-600 dark:text-slate-400'">By Catchphrase</span>
                </div>
            </button>
            <button 
                @click="method = 'email'"
                :class="method === 'email' ? 'bg-white dark:bg-slate-700 shadow-sm' : 'hover:bg-slate-50 dark:hover:bg-slate-700/50'"
                class="flex-1 px-4 py-2.5 text-sm font-semibold rounded-md transition"
            >
                <div class="flex items-center justify-center gap-2">
                    <span>📧</span>
                    <span :class="method === 'email' ? 'text-slate-900 dark:text-slate-100' : 'text-slate-600 dark:text-slate-400'">By Email</span>
                </div>
            </button>
        </div>
        <x-auth-session-status class="mb-4" :status="session('status')" />
        <!-- Catchphrase Method -->
        <form x-show="method === 'catchphrase'" method="POST" action="{{ route('password.email') }}" class="space-y-4" x-cloak>
            @csrf
            <input type="hidden" name="method" value="catchphrase">
            <div class="rounded-lg border border-amber-200 bg-amber-50 p-3 dark:border-amber-800 dark:bg-amber-900/20">
                <div class="flex gap-2">
                    <span class="text-lg">🔐</span>
                    <div class="text-xs text-amber-800 dark:text-amber-200">
                        <strong>Use your catchphrase</strong> - the 3-word phrase you received when you registered. This is the fastest way to reset your password.
                    </div>
                </div>
            </div>
            <div>
                <x-input-label for="email_catch" :value="__('Email')" />
                <x-text-input id="email_catch" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="catchphrase" :value="__('Catchphrase')" />
                <x-text-input id="catchphrase" class="block mt-1 w-full" type="text" name="catchphrase" required placeholder="e.g., Swift River Tiger" />
                <x-input-error :messages="$errors->get('catchphrase')" class="mt-2" />
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Enter the 3-word catchphrase from your registration</p>
            </div>
            <div class="flex items-center justify-between mt-6">
                <a href="{{ route('login') }}" class="text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 underline">
                    {{ __('Back to Login') }}
                </a>
                <x-primary-button>
                    {{ __('Reset Password') }}
                </x-primary-button>
            </div>
        </form>
        <!-- Email Method -->
        <form x-show="method === 'email'" method="POST" action="{{ route('password.email-only') }}" class="space-y-4" x-cloak>
            @csrf
            <input type="hidden" name="method" value="email">
            <div class="rounded-lg border border-blue-200 bg-blue-50 p-3 dark:border-blue-800 dark:bg-blue-900/20">
                <div class="flex gap-2">
                    <span class="text-lg">📧</span>
                    <div class="text-xs text-blue-800 dark:text-blue-200">
                        <strong>Reset via email</strong> - we'll send a password reset link to your email address. Check your inbox and spam folder.
                    </div>
                </div>
            </div>
            <div>
                <x-input-label for="email_only" :value="__('Email')" />
                <x-text-input id="email_only" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">We'll send a password reset link to this email</p>
            </div>
            <div class="flex items-center justify-between mt-6">
                <a href="{{ route('login') }}" class="text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 underline">
                    {{ __('Back to Login') }}
                </a>
                <x-primary-button>
                    {{ __('Send Reset Link') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
