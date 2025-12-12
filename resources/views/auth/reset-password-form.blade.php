<x-guest-layout>
    <div class="mb-4">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Reset Your Password</h2>
        <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
            Enter your new password below.
        </p>
    </div>
    <form method="POST" action="{{ route('password.reset.update') }}">
        @csrf
        <div>
            <x-input-label for="password" :value="__('New Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Minimum 8 characters</p>
        </div>
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm New Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>
        <div class="flex items-center justify-between mt-6">
            <a href="{{ route('password.request') }}" class="text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 underline">
                {{ __('Back') }}
            </a>
            <x-primary-button>
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
