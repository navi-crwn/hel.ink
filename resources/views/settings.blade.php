<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Settings" subtitle="Manage your account and preferences">
            <x-slot name="actions">
                <a href="{{ route('dashboard') }}" class="rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">Dashboard</a>
            </x-slot>
        </x-page-header>
    </x-slot>
    <div class="py-8" x-data="{ 
        tab: '{{ session('active_tab', 'account') }}',
        init() {
            // Check URL hash for tab
            if (window.location.hash) {
                const hashTab = window.location.hash.substring(1);
                if (['account', '2fa', 'backup', 'notifications', 'activity', 'login', 'api'].includes(hashTab)) {
                    this.tab = hashTab;
                }
            }
        }
    }" @tab-changed.window="tab = $event.detail">
        <div class="mx-auto max-w-5xl space-y-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="rounded-xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-emerald-900 dark:border-emerald-900/30 dark:bg-emerald-900/20 dark:text-emerald-200">
                    {{ session('status') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="rounded-xl border border-red-300 bg-red-50 px-4 py-3 text-red-900 dark:border-red-900/30 dark:bg-red-900/10 dark:text-red-200">
                    <ul class="list-disc pl-4 space-y-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex overflow-x-auto border-b border-slate-200 dark:border-slate-800">
                    <button @click="tab = 'account'" :class="tab === 'account' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200'" class="whitespace-nowrap border-b-2 px-6 py-3 text-sm font-medium">
                        Account
                    </button>
                    <button @click="tab = '2fa'" :class="tab === '2fa' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200'" class="whitespace-nowrap border-b-2 px-6 py-3 text-sm font-medium">
                        2FA Security
                    </button>
                    <button @click="tab = 'backup'" :class="tab === 'backup' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200'" class="whitespace-nowrap border-b-2 px-6 py-3 text-sm font-medium">
                        Backup & Export
                    </button>
                    <button @click="tab = 'notifications'" :class="tab === 'notifications' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200'" class="whitespace-nowrap border-b-2 px-6 py-3 text-sm font-medium">
                        Notifications
                    </button>
                    <button @click="tab = 'activity'" :class="tab === 'activity' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200'" class="whitespace-nowrap border-b-2 px-6 py-3 text-sm font-medium">
                        Activity Logs
                    </button>
                    <button @click="tab = 'login'" :class="tab === 'login' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200'" class="whitespace-nowrap border-b-2 px-6 py-3 text-sm font-medium">
                        Login History
                    </button>
                    <button @click="tab = 'api'" :class="tab === 'api' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200'" class="whitespace-nowrap border-b-2 px-6 py-3 text-sm font-medium">
                        API & Integrations
                    </button>
                </div>
                <div x-show="tab === 'account'" class="p-6 space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Account Information</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Update your name and email address</p>
                    </div>
                    <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                        @csrf
                        @method('PATCH')
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Name</label>
                            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="mt-1 w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Email</label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="mt-1 w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white" required>
                        </div>
                        <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-500">
                            Update Account
                        </button>
                    </form>
                    <hr class="border-slate-200 dark:border-slate-800">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Change Password</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Ensure your account is using a long, random password</p>
                    </div>
                    <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                        @csrf
                        @method('PUT')
                        @if ($errors->updatePassword->any())
                            <div class="rounded-lg bg-red-50 p-4 dark:bg-red-900/20">
                                <div class="flex items-start">
                                    <svg class="h-5 w-5 text-red-600 dark:text-red-400 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div class="flex-1">
                                        <h3 class="text-sm font-semibold text-red-800 dark:text-red-200">
                                            There were some errors with your submission
                                        </h3>
                                        <ul class="mt-2 list-disc list-inside text-sm text-red-700 dark:text-red-300">
                                            @foreach ($errors->updatePassword->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Current Password</label>
                            <input type="password" name="current_password" class="mt-1 w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">New Password</label>
                            <input type="password" name="password" class="mt-1 w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="mt-1 w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white" required>
                        </div>
                        <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-500">
                            Update Password
                        </button>
                    </form>
                    @unless(auth()->user()->isSuperAdmin())
                        <hr class="border-slate-200 dark:border-slate-800">
                        <div x-data="{ 
                            showModal: false, 
                            password: '', 
                            countdown: 10,
                            canDelete: false,
                            startCountdown() {
                                this.countdown = 10;
                                this.canDelete = false;
                                const interval = setInterval(() => {
                                    this.countdown--;
                                    if (this.countdown <= 0) {
                                        this.canDelete = true;
                                        clearInterval(interval);
                                    }
                                }, 1000);
                            }
                        }">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-red-600 dark:text-red-400">Danger Zone</h3>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">Permanently delete your account and all data</p>
                                </div>
                                <button 
                                    @click="showModal = true; $nextTick(() => startCountdown())" 
                                    class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 transition"
                                >
                                    Delete My Account
                                </button>
                            </div>
                            <div x-show="showModal" 
                                x-cloak
                                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
                                @click.self="showModal = false; password = ''"
                            >
                                <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-xl dark:bg-slate-800" @click.away="showModal = false; password = ''">
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0 text-3xl">⚠️</div>
                                        <div class="flex-1">
                                            <h3 class="text-lg font-bold text-red-600 dark:text-red-400">Delete Account</h3>
                                            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                                                This action is <strong>permanent and cannot be undone</strong>. All your links, folders, tags, and analytics data will be permanently deleted.
                                            </p>
                                            <form method="POST" action="{{ route('profile.destroy') }}" class="mt-4 space-y-4">
                                                @csrf
                                                @method('DELETE')
                                                <div>
                                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Confirm your password</label>
                                                    <input 
                                                        type="password" 
                                                        name="password" 
                                                        x-model="password"
                                                        class="mt-1 w-full rounded-lg border-slate-200 focus:border-red-500 focus:ring-red-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white" 
                                                        required
                                                    >
                                                </div>
                                                <div class="rounded-lg bg-red-50 p-3 dark:bg-red-900/20">
                                                    <p class="text-center text-sm font-medium text-red-800 dark:text-red-200">
                                                        <span x-show="!canDelete">
                                                            Please wait <span x-text="countdown" class="font-bold text-lg"></span> seconds before deleting
                                                        </span>
                                                        <span x-show="canDelete" class="text-red-600 dark:text-red-400">
                                                            You can now proceed with deletion
                                                        </span>
                                                    </p>
                                                </div>
                                                <div class="flex gap-3">
                                                    <button 
                                                        type="button" 
                                                        @click="showModal = false; password = ''" 
                                                        class="flex-1 rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800"
                                                    >
                                                        Cancel
                                                    </button>
                                                    <button 
                                                        type="submit" 
                                                        :disabled="!canDelete || !password"
                                                        :class="canDelete && password ? 'bg-red-600 hover:bg-red-700' : 'bg-red-400 cursor-not-allowed'"
                                                        class="flex-1 rounded-lg px-4 py-2 text-sm font-semibold text-white transition"
                                                    >
                                                        Yes, Delete My Account
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endunless
                </div>
                <div x-show="tab === '2fa'" class="p-6 space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Two-Factor Authentication</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Add an extra layer of security to your account</p>
                    </div>
                    @if(!auth()->user()->two_factor_secret)
                        <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800">
                            <h4 class="font-medium text-slate-900 dark:text-white">Enable Two-Factor Authentication</h4>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
                                Secure your account by requiring a verification code from your authenticator app when logging in.
                            </p>
                            <form method="POST" action="{{ route('settings.2fa.enable') }}" class="mt-4 space-y-3">
                                @csrf
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Confirm your password</label>
                                    <input type="password" name="password" class="mt-1 w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white" required>
                                </div>
                                <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-500">
                                    Enable 2FA
                                </button>
                            </form>
                        </div>
                    @else
                        @if(!auth()->user()->two_factor_confirmed_at)
                            <div class="rounded-lg border border-blue-200 bg-blue-50 p-4 dark:border-blue-900/30 dark:bg-blue-900/20">
                                <h4 class="font-medium text-blue-900 dark:text-blue-100">Complete Setup</h4>
                                <p class="mt-1 text-sm text-blue-800 dark:text-blue-200">
                                    Scan the QR code below with your authenticator app (Google Authenticator, Authy, etc.)
                                </p>
                                <div class="mt-4 flex items-center justify-center bg-white p-4 rounded-lg">
                                    @php
                                        $google2fa = new \PragmaRX\Google2FA\Google2FA();
                                        $secret = decrypt(auth()->user()->two_factor_secret);
                                        $qrCodeUrl = $google2fa->getQRCodeUrl(
                                            config('app.name'),
                                            auth()->user()->email,
                                            $secret
                                        );
                                    @endphp
                                    {!! QrCode::size(200)->generate($qrCodeUrl) !!}
                                </div>
                                <div class="mt-4 p-3 bg-slate-800 rounded-lg">
                                    <p class="text-xs text-slate-400 text-center">Secret Key (manual entry)</p>
                                    <p class="text-sm font-mono text-white text-center">{{ $secret }}</p>
                                </div>
                                <form method="POST" action="{{ route('settings.2fa.confirm') }}" class="mt-4 space-y-3">
                                    @csrf
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Enter 6-digit code from your app</label>
                                        <input type="text" name="code" maxlength="6" pattern="[0-9]{6}" class="mt-1 w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white" required>
                                    </div>
                                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-500">
                                        Confirm & Activate
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="rounded-lg border border-green-200 bg-green-50 p-4 dark:border-green-900/30 dark:bg-green-900/20">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <h4 class="font-medium text-green-900 dark:text-green-100">Two-Factor Authentication is Active</h4>
                                </div>
                                <p class="mt-1 text-sm text-green-800 dark:text-green-200">
                                    Your account is protected with two-factor authentication.
                                </p>
                            </div>
                            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800">
                                <h4 class="font-medium text-slate-900 dark:text-white">Recovery Codes</h4>
                                <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
                                    Store these codes in a safe place. You can use them to access your account if you lose your authenticator device.
                                </p>
                                @php
                                    $recoveryCodes = json_decode(decrypt(auth()->user()->two_factor_recovery_codes), true);
                                @endphp
                                <div class="mt-4 grid grid-cols-2 gap-2 p-3 bg-slate-800 rounded-lg">
                                    @foreach($recoveryCodes as $code)
                                        <div class="text-sm font-mono text-white">{{ $code }}</div>
                                    @endforeach
                                </div>
                                <form method="POST" action="{{ route('settings.2fa.recovery-codes') }}" class="mt-4" onsubmit="return confirm('Are you sure you want to regenerate recovery codes? Your old codes will no longer work.')">
                                    @csrf
                                    <input type="hidden" name="password" value="">
                                    <button type="button" onclick="this.previousElementSibling.value = prompt('Enter your password to regenerate codes:'); if(this.previousElementSibling.value) this.form.submit();" class="rounded-lg bg-slate-600 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-500">
                                        Regenerate Codes
                                    </button>
                                </form>
                            </div>
                            <div class="rounded-lg border border-red-200 bg-red-50 p-4 dark:border-red-900/30 dark:bg-red-900/20">
                                <h4 class="font-medium text-red-900 dark:text-red-100">Disable Two-Factor Authentication</h4>
                                <p class="mt-1 text-sm text-red-800 dark:text-red-200">
                                    Remove two-factor authentication from your account. This will make your account less secure.
                                </p>
                                <form method="POST" action="{{ route('settings.2fa.disable') }}" class="mt-4" onsubmit="return confirm('Are you sure you want to disable two-factor authentication?')">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="password" value="">
                                    <button type="button" onclick="this.previousElementSibling.value = prompt('Enter your password to disable 2FA:'); if(this.previousElementSibling.value) this.form.submit();" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                                        Disable 2FA
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endif
                </div>
                <div x-show="tab === 'backup'" class="p-6 space-y-6" x-data="{ showFilters: false }">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Backup & Export</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Download your complete link data with analytics in CSV format</p>
                    </div>
                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-medium text-slate-900 dark:text-white">Export All Data</h4>
                                <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
                                    Export links with complete analytics and statistics
                                </p>
                            </div>
                            <button type="button" @click="showFilters = !showFilters" class="text-sm text-blue-600 hover:text-blue-500 dark:text-blue-400">
                                <span x-text="showFilters ? 'Hide Filters' : 'Show Filters'"></span>
                            </button>
                        </div>
                        <form method="POST" action="{{ route('settings.export') }}" class="mt-4 space-y-4">
                            @csrf
                            <div x-show="showFilters" x-cloak class="space-y-4 p-4 bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-700">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">From Date</label>
                                        <input type="date" name="date_from" class="mt-1 w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">To Date</label>
                                        <input type="date" name="date_to" class="mt-1 w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Minimum Clicks</label>
                                        <input type="number" name="min_clicks" min="0" placeholder="e.g., 10" class="mt-1 w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Only links with this many clicks or more</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Sort By</label>
                                        <select name="sort_by" class="mt-1 w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                                            <option value="newest">Newest First</option>
                                            <option value="oldest">Oldest First</option>
                                            <option value="most_clicked">Most Clicked First</option>
                                            <option value="least_clicked">Least Clicked First</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Folder</label>
                                        <select name="folder_id" class="mt-1 w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                                            <option value="">All Folders</option>
                                            <option value="default">Default (No Folder)</option>
                                            @foreach($folders as $folder)
                                                <option value="{{ $folder->id }}">{{ $folder->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Tag</label>
                                        <select name="tag_id" class="mt-1 w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                                            <option value="">All Tags</option>
                                            @foreach($tags as $tag)
                                                <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-500">
                                    <svg class="inline-block w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Download Complete Export
                                </button>
                                <button type="button" @click="showFilters = false; $el.closest('form').reset();" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-200 dark:hover:bg-slate-600">
                                    Reset Filters
                                </button>
                            </div>
                        </form>
                        <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <p class="text-xs text-blue-800 dark:text-blue-200">
                                <strong>Complete export includes:</strong> Short URL, Destination URL, Redirect type, Folder, Tags, Password status, Status, Total clicks, Unique visitors, Created date, Expiration date, Last clicked date
                            </p>
                        </div>
                    </div>
                </div>
                <div x-show="tab === 'notifications'" class="p-6 space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Notification Preferences</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Manage how you receive notifications</p>
                    </div>
                    <div class="rounded-lg border border-yellow-200 bg-yellow-50 p-4 dark:border-yellow-900/30 dark:bg-yellow-900/20">
                        <p class="text-sm text-yellow-800 dark:text-yellow-200">
                            📧 Email notifications will be available once the emailing feature is configured. You'll be able to receive alerts for:
                        </p>
                        <ul class="mt-2 text-sm text-yellow-800 dark:text-yellow-200 space-y-1">
                            <li>• New link clicks</li>
                            <li>• Suspicious activity</li>
                            <li>• Account security alerts</li>
                            <li>• Weekly/monthly reports</li>
                        </ul>
                    </div>
                </div>
                <div x-show="tab === 'activity'" class="p-6 space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Activity Logs</h3>
                            <p class="text-sm text-slate-600 dark:text-slate-400">Recent actions performed on your account (max 1 month)</p>
                        </div>
                    </div>
                    <form method="GET" action="{{ route('settings') }}#activity" class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800">
                        <input type="hidden" name="tab" value="activity">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">From Date</label>
                                <input type="date" name="activity_from" value="{{ request('activity_from') }}" class="mt-1 w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">To Date</label>
                                <input type="date" name="activity_to" value="{{ request('activity_to') }}" class="mt-1 w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                            </div>
                        </div>
                        <div class="mt-3 flex gap-2">
                            <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-500">
                                Apply Filter
                            </button>
                            <a href="{{ route('settings') }}#activity" class="inline-flex items-center rounded-lg border border-slate-300 bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">
                                Clear
                            </a>
                        </div>
                    </form>
                    <div class="space-y-2">
                        @forelse ($activityLogs as $log)
                            <div class="rounded-lg border border-slate-200 p-3 dark:border-slate-700">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $log->description }}</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ \Carbon\Carbon::parse($log->created_at)->format('M d, Y H:i:s') }}</p>
                                    </div>
                                    <span class="rounded-full bg-slate-100 px-2 py-1 text-xs text-slate-600 dark:bg-slate-800 dark:text-slate-400">
                                        {{ ucfirst($log->action) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500 dark:text-slate-400">No activity logs found for the selected date range.</p>
                        @endforelse
                    </div>
                </div>
                <div x-show="tab === 'login'" class="p-6 space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Login History</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Last 5 login sessions to your account</p>
                    </div>
                    @forelse($loginHistories as $index => $history)
                        <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-sm font-medium text-slate-600 dark:bg-slate-700 dark:text-slate-300">
                                        {{ $index + 1 }}
                                    </span>
                                    <div>
                                        <p class="text-sm font-medium text-slate-900 dark:text-white">
                                            {{ \Carbon\Carbon::parse($history->logged_in_at)->format('M d, Y - H:i:s') }}
                                        </p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ \Carbon\Carbon::parse($history->logged_in_at)->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if($history->is_proxy)
                                        <span class="rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-700 dark:bg-red-900/30 dark:text-red-400" title="Proxy confidence: {{ $history->proxy_confidence }}%">
                                            ⚠ {{ ucfirst($history->proxy_type) }}
                                        </span>
                                    @endif
                                    @if($index === 0)
                                        <span class="rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                            Current
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="mt-3 grid grid-cols-2 md:grid-cols-4 gap-3 text-xs">
                                <div>
                                    <p class="font-medium text-slate-500 dark:text-slate-400">IP Address</p>
                                    <p class="text-slate-900 dark:text-white">{{ $history->ip_address ?? 'Unknown' }}</p>
                                </div>
                                <div>
                                    <p class="font-medium text-slate-500 dark:text-slate-400">Location</p>
                                    <p class="text-slate-900 dark:text-white">
                                        @if($history->city && $history->country)
                                            {!! country_flag($history->country) !!} {{ $history->city }}, {{ country_name($history->country) }}
                                        @elseif($history->country)
                                            {!! country_flag($history->country) !!} {{ country_name($history->country) }}
                                        @else
                                            Unknown
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <p class="font-medium text-slate-500 dark:text-slate-400">Provider</p>
                                    <p class="text-slate-900 dark:text-white truncate" title="{{ $history->isp ?? $history->provider ?? 'Unknown' }}">
                                        {{ \Illuminate\Support\Str::limit($history->isp ?? $history->provider ?? 'Unknown', 20) }}
                                    </p>
                                </div>
                                <div>
                                    <p class="font-medium text-slate-500 dark:text-slate-400">Device</p>
                                    <p class="text-slate-900 dark:text-white">{{ $history->device ?? 'Desktop' }}</p>
                                </div>
                                <div>
                                    <p class="font-medium text-slate-500 dark:text-slate-400">Browser</p>
                                    <p class="text-slate-900 dark:text-white">{{ $history->browser ?? 'Unknown' }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500 dark:text-slate-400">No login history available yet.</p>
                    @endforelse
                </div>
                </div>
                <!-- API & Integrations Tab -->
                <div x-show="tab === 'api'" x-data="apiSettings()" class="p-6 space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">API Tokens</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Create API tokens for ShareX, CLI tools, and custom integrations</p>
                    </div>
                    <!-- Create New Token Form -->
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/50">
                        <h4 class="text-sm font-semibold text-slate-900 dark:text-white mb-3">Create New Token</h4>
                        <form method="POST" action="{{ route('api-tokens.store') }}" @submit.prevent="createToken" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Token Name</label>
                                <input 
                                    type="text" 
                                    name="name" 
                                    x-model="newTokenName"
                                    placeholder="ShareX Desktop, My CLI Tool, etc." 
                                    required
                                    class="w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm"
                                >
                                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Give your token a memorable name</p>
                            </div>
                            <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-500">
                                <span x-show="!isCreating">🔑 Generate Token</span>
                                <span x-show="isCreating">⏳ Generating...</span>
                            </button>
                        </form>
                    </div>
                    <!-- Show Newly Created Token (one time) -->
                    <div x-show="newlyCreatedToken" x-cloak class="rounded-xl border border-emerald-300 bg-emerald-50 p-4 dark:border-emerald-700 dark:bg-emerald-900/20">
                        <h4 class="text-sm font-semibold text-emerald-900 dark:text-emerald-200 mb-2">✅ Token Created Successfully!</h4>
                        <p class="text-xs text-emerald-800 dark:text-emerald-300 mb-3">⚠️ Copy this token now. You won't be able to see it again!</p>
                        <div class="flex gap-2">
                            <input 
                                type="text" 
                                :value="newlyCreatedToken" 
                                readonly 
                                class="flex-1 rounded-lg border-emerald-300 bg-white px-3 py-2 text-sm font-mono text-emerald-900 dark:border-emerald-600 dark:bg-emerald-950 dark:text-emerald-100"
                            >
                            <button 
                                @click="copyToken(newlyCreatedToken)" 
                                class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-500"
                            >
                                <span x-show="!copied">📋 Copy</span>
                                <span x-show="copied">✅ Copied!</span>
                            </button>
                        </div>
                    </div>
                    <!-- Existing Tokens List -->
                    <div>
                        <h4 class="text-sm font-semibold text-slate-900 dark:text-white mb-3">Your API Tokens</h4>
                        <div class="space-y-3">
                            @forelse(auth()->user()->apiTokens as $token)
                                <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-white p-4 dark:border-slate-700 dark:bg-slate-800">
                                    <div class="flex-1">
                                        <h5 class="font-semibold text-slate-900 dark:text-white">{{ $token->name }}</h5>
                                        <div class="mt-1 flex items-center gap-4 text-xs text-slate-500 dark:text-slate-400">
                                            <span>Created: {{ $token->created_at->format('M d, Y') }}</span>
                                            @if($token->last_used_at)
                                                <span>Last used: {{ $token->last_used_at->diffForHumans() }}</span>
                                            @else
                                                <span class="text-amber-600 dark:text-amber-400">Never used</span>
                                            @endif
                                            <span>Rate limit: {{ $token->rate_limit }}/hour</span>
                                        </div>
                                    </div>
                                    <form method="POST" action="{{ route('api-tokens.destroy', $token) }}" onsubmit="return confirm('Are you sure you want to revoke this token? This action cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg border border-red-300 px-3 py-1.5 text-sm font-medium text-red-600 hover:bg-red-50 dark:border-red-700 dark:text-red-400 dark:hover:bg-red-900/20">
                                            🗑️ Revoke
                                        </button>
                                    </form>
                                </div>
                            @empty
                                <p class="text-sm text-slate-500 dark:text-slate-400">No API tokens yet. Create one to get started!</p>
                            @endforelse
                        </div>
                    </div>
                    <!-- API Documentation Link -->
                    <div class="rounded-xl border border-blue-200 bg-blue-50 p-4 dark:border-blue-800 dark:bg-blue-900/20">
                        <h4 class="text-sm font-semibold text-blue-900 dark:text-blue-200 mb-2">📚 API Documentation</h4>
                        <p class="text-sm text-blue-800 dark:text-blue-300 mb-3">Learn how to integrate hel.ink with ShareX, CLI tools, and more.</p>
                        <a href="{{ route('api-docs') }}" class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-500">
                            View API Docs →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    function apiSettings() {
        return {
            newTokenName: '',
            newlyCreatedToken: '',
            isCreating: false,
            copied: false,
            async createToken(event) {
                this.isCreating = true;
                try {
                    const formData = new FormData(event.target);
                    const response = await fetch('{{ route("api-tokens.store") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: formData
                    });
                    const data = await response.json();
                    if (response.ok && data.token) {
                        this.newlyCreatedToken = data.token;
                        this.newTokenName = '';
                        // Reload page after 10 seconds to show new token in list
                        setTimeout(() => {
                            window.location.reload();
                        }, 10000);
                    } else {
                        alert('Failed to create token: ' + (data.message || 'Unknown error'));
                    }
                } catch (error) {
                    console.error('Error creating token:', error);
                    alert('An error occurred while creating the token');
                } finally {
                    this.isCreating = false;
                }
            },
            copyToken(token) {
                navigator.clipboard.writeText(token).then(() => {
                    this.copied = true;
                    setTimeout(() => {
                        this.copied = false;
                    }, 2000);
                });
            }
        }
    }
    </script>
</x-app-layout>
