<x-guest-layout>
    <div class="mb-4 text-sm text-slate-600 dark:text-slate-400">
        {{ __('Please confirm access to your account by entering the authentication code provided by your authenticator application.') }}
    </div>
    <form method="POST" action="{{ route('two-factor.verify') }}">
        @csrf
        <div>
            <x-input-label for="code" :value="__('Code')" />
            <x-text-input id="code" class="block mt-1 w-full" type="text" name="code" required autofocus autocomplete="one-time-code" />
            <x-input-error :messages="$errors->get('code')" class="mt-2" />
        </div>
        <div class="flex items-center justify-between mt-4">
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="underline text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-slate-800">
                    {{ __('Use a different account') }}
                </button>
            </form>
            <x-primary-button class="ml-3">
                {{ __('Verify') }}
            </x-primary-button>
        </div>
        <div class="mt-4 p-3 bg-slate-100 dark:bg-slate-800 rounded-lg">
            <p class="text-xs text-slate-600 dark:text-slate-400">
                <strong>Lost your device?</strong> You can use one of your recovery codes to access your account.
            </p>
        </div>
    </form>
</x-guest-layout>
