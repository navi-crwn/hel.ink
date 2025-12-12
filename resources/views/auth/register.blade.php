<x-guest-layout>
    @if(session('error'))
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 dark:border-red-800 dark:bg-red-900/20">
            <div class="flex items-start gap-3">
                <svg class="h-5 w-5 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-red-900 dark:text-red-100">{{ session('error') }}</p>
                    @if(Str::contains(session('error'), 'exists') || Str::contains(session('error'), 'login'))
                        <a href="{{ route('login') }}" class="mt-2 inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-500">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            Login Instead
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endif
    @php
        $turnstileKey = config('services.turnstile.site_key');
    @endphp
    <div x-data="{ tosAccepted: false, showTosError: false }">
        <!-- Google Sign In Button -->
        <div class="mb-6">
            <a href="{{ route('auth.google', ['from_register' => 1]) }}" 
               @click.prevent="console.log('Google clicked, TOS:', tosAccepted); if (!tosAccepted) { showTosError = true; setTimeout(() => window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' }), 100); } else { window.location.href = $el.href; }"
               class="w-full inline-flex items-center justify-center gap-3 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all shadow-sm">
                <svg class="h-5 w-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                <span>Continue with Google</span>
            </a>
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="bg-white dark:bg-gray-900 px-4 text-gray-500 dark:text-gray-400">Or register with email</span>
                </div>
            </div>
        </div>
        <form method="POST" action="{{ route('register') }}" 
              @if($turnstileKey) 
              x-data="window.turnstileForm ? window.turnstileForm('{{ $turnstileKey }}', {{ $errors->has('turnstile') ? 'true' : 'false' }}) : {}"
              x-init="if (!window.turnstileForm) { let interval = setInterval(() => { if (window.turnstileForm) { Object.assign($data, window.turnstileForm('{{ $turnstileKey }}', {{ $errors->has('turnstile') ? 'true' : 'false' }})); clearInterval(interval); } }, 50); }"
              x-on:submit="handleSubmit($event)"
              @endif>
        @csrf
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div class="mt-4" x-data="{ showPassword: false }">
            <x-input-label for="password" :value="__('Password')" />
            <div class="relative">
                <x-text-input id="password" class="block mt-1 w-full pr-10"
                                type="password"
                                x-bind:type="showPassword ? 'text' : 'password'"
                                name="password"
                                required autocomplete="new-password" />
                <button
                    type="button"
                    @click="showPassword = !showPassword"
                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-xs text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200"
                    tabindex="-1"
                >
                    <span x-show="!showPassword">Show</span>
                    <span x-show="showPassword" x-cloak>Hide</span>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <div class="mt-4" x-data="{ showConfirm: false }">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <div class="relative">
                <x-text-input id="password_confirmation" class="block mt-1 w-full pr-10"
                                type="password"
                                x-bind:type="showConfirm ? 'text' : 'password'"
                                name="password_confirmation" required autocomplete="new-password" />
                <button
                    type="button"
                    @click="showConfirm = !showConfirm"
                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-xs text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200"
                    tabindex="-1"
                >
                    <span x-show="!showConfirm">Show</span>
                    <span x-show="showConfirm" x-cloak>Hide</span>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>
        @if ($turnstileKey)
            <div class="mt-4" x-cloak x-show="showCaptcha">
                <div x-ref="captcha"></div>
                <x-input-error :messages="$errors->get('turnstile')" class="mt-2" />
            </div>
            <input type="hidden" name="cf-turnstile-response" :value="token">
        @endif
        <input type="hidden" name="tos_accepted" x-bind:value="tosAccepted ? '1' : '0'">
        <div class="mt-4">
            <label class="flex items-start space-x-3 cursor-pointer group">
                <input 
                    type="checkbox" 
                    x-model="tosAccepted"
                    @change="showTosError = false"
                    class="mt-1 rounded border-gray-300 dark:border-gray-700 text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 dark:bg-gray-800 cursor-pointer"
                    required
                >
                <span class="text-sm text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-gray-200">
                    I agree to the 
                    <a href="{{ route('tos') }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">
                        Terms of Service
                    </a>
                    and understand the acceptable use policy, prohibited activities, and my rights and obligations.
                </span>
            </label>
            <x-input-error :messages="$errors->get('tos_accepted')" class="mt-2" />
            <p x-show="showTosError" x-cloak class="mt-2 text-sm text-red-600 dark:text-red-400">
                ⚠️ Please agree to the Terms of Service below to continue.
            </p>
        </div>
        <div class="flex items-center justify-between mt-4">
            <div class="flex items-center gap-3">
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>
                <button type="button" @click="darkMode = !darkMode" class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 dark:bg-gray-700 px-3 py-1.5 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all" title="Switch Theme">
                    <svg x-show="!darkMode" class="h-4 w-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                    <svg x-show="darkMode" x-cloak class="h-4 w-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span class="text-xs font-medium text-gray-700 dark:text-gray-200">Theme</span>
                </button>
            </div>
            <button
                type="submit"
                class="ms-4 inline-flex items-center rounded-md border border-transparent px-4 py-2 text-xs font-semibold uppercase tracking-widest transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                x-bind:class="tosAccepted {{ $turnstileKey ? '&& (!showCaptcha || token)' : '' }} ? 'bg-slate-800 hover:bg-slate-700 active:bg-slate-900 dark:bg-blue-600 dark:hover:bg-blue-700 text-white cursor-pointer' : 'bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 cursor-not-allowed'"
                x-bind:disabled="!tosAccepted {{ $turnstileKey ? '|| (showCaptcha && !token)' : '' }}"
            >
                {{ __('Register') }}
            </button>
        </div>
    </form>
    </div>
</x-guest-layout>
