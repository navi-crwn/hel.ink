<section>
    @php
        $isGoogleUser = auth()->user()->google_id;
        $hasPassword = !empty(auth()->user()->password);
        $isCreatingPassword = $isGoogleUser && !$hasPassword;
    @endphp
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            @if($isCreatingPassword)
                {{ __('Create Your Password') }}
            @else
                {{ __('Update Password') }}
            @endif
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            @if($isCreatingPassword)
                {{ __('You logged in with Google. Set a password to also login with email and password.') }}
            @else
                {{ __('Ensure your account is using a long, random password to stay secure.') }}
            @endif
        </p>
    </header>
    @if($isGoogleUser && !$hasPassword)
        <!-- Google user creating first password -->
        <form method="post" action="{{ route('password.create') }}" class="mt-6 space-y-6" x-data="{ showPass: false, showConf: false }">
            @csrf
            <div class="rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 p-3 mb-4">
                <div class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-xs text-blue-800 dark:text-blue-200">
                        After setting a password, you can login using either your Google account or email/password.
                    </p>
                </div>
            </div>
            <div>
                <x-input-label for="password" :value="__('New Password')" />
                <div class="relative mt-1">
                    <x-text-input 
                        id="password" 
                        name="password" 
                        x-bind:type="showPass ? 'text' : 'password'"
                        class="block w-full pr-10" 
                        autocomplete="new-password" 
                        required
                    />
                    <button 
                        type="button"
                        @click="showPass = !showPass"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-xs text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200"
                    >
                        <span x-show="!showPass">Show</span>
                        <span x-show="showPass" x-cloak>Hide</span>
                    </button>
                </div>
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <div class="relative mt-1">
                    <x-text-input 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        x-bind:type="showConf ? 'text' : 'password'"
                        class="block w-full pr-10" 
                        autocomplete="new-password" 
                        required
                    />
                    <button 
                        type="button"
                        @click="showConf = !showConf"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-xs text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200"
                    >
                        <span x-show="!showConf">Show</span>
                        <span x-show="showConf" x-cloak>Hide</span>
                    </button>
                </div>
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>
            <div class="flex items-center gap-4">
                <x-primary-button>{{ __('Create Password') }}</x-primary-button>
                @if (session('status') === 'password-created')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 3000)"
                        class="text-sm text-green-600 dark:text-green-400"
                    >{{ __('Password created successfully!') }}</p>
                @endif
            </div>
        </form>
    @else
        <!-- Regular password update form -->
        <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
            @csrf
            @method('put')
            <div>
                <x-input-label for="update_password_current_password" :value="__('Current Password')" />
                <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="update_password_password" :value="__('New Password')" />
                <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>
            <div class="flex items-center gap-4">
                <x-primary-button>{{ __('Save') }}</x-primary-button>
                @if (session('status') === 'password-updated')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-gray-600 dark:text-gray-400"
                    >{{ __('Saved.') }}</p>
                @endif
            </div>
        </form>
    @endif
</section>
