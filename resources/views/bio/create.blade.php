<x-app-layout>
    <x-slot name="pageTitle">Create Link in Bio Page</x-slot>
    <x-slot name="header">
        <x-page-header title="Create Your Bio Page" subtitle="Share all your important links in one place">
            <x-slot name="actions">
                <a href="{{ route('bio.index') }}" class="rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                    Back to Bio Pages
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>
    <div class="py-10" x-data="{ notification: { show: false, message: '', type: 'success' } }" @notify="notification.show = true; notification.message = $event.detail.message; notification.type = $event.detail.type || 'success'; setTimeout(() => notification.show = false, 3000)">
        <!-- Notification Toast -->
        <div x-show="notification.show" x-transition 
             x-cloak
             class="fixed top-24 right-4 z-50 rounded-lg px-4 py-3 text-white shadow-lg"
             :class="notification.type === 'success' ? 'bg-green-600' : 'bg-red-600'">
            <div class="flex items-center gap-2">
                <svg x-show="notification.type === 'success'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <svg x-show="notification.type === 'error'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                <span x-text="notification.message" class="font-medium"></span>
            </div>
        </div>
        <div class="mx-auto max-w-6xl sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 bg-white p-8 dark:border-slate-800 dark:bg-slate-900">
                    <form action="{{ route('bio.store') }}" method="POST" x-data="slugChecker()">
                        @csrf
                        <input type="hidden" name="qr_settings" id="qr_settings_input">
                        <div class="space-y-6">
                            <div>
                                <label for="slug" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                    Choose Your URL <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-2 flex items-center gap-2">
                                    <span class="text-sm text-slate-500 dark:text-slate-400">hel.ink/b/</span>
                                    <input 
                                        type="text" 
                                        name="slug" 
                                        id="slug" 
                                        required
                                        value="{{ old('slug') }}"
                                        placeholder="yourname"
                                        pattern="[a-zA-Z0-9]+"
                                        minlength="3"
                                        maxlength="20"
                                        x-model="slug"
                                        @input.debounce.500ms="checkSlug()"
                                        class="flex-1 rounded-lg border border-slate-300 px-4 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                                    >
                                </div>
                                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Only letters and numbers (3-20 characters)</p>
                                <div class="mt-2 text-sm" x-show="checking">
                                    <span class="text-blue-600 dark:text-blue-400">Checking availability...</span>
                                </div>
                                <div class="mt-2 text-sm" x-show="!checking && available === true" x-cloak>
                                    <span class="text-green-600 dark:text-green-400">✓ Available!</span>
                                </div>
                                <div class="mt-2 text-sm" x-show="!checking && available === false" x-cloak>
                                    <span class="text-red-600 dark:text-red-400">✗ Already taken</span>
                                </div>
                                @error('slug')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="title" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                    Page Title <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="title" 
                                    id="title" 
                                    required
                                    value="{{ old('title') }}"
                                    placeholder="Your Name or Brand"
                                    maxlength="45"
                                    class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                                >
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="bio" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                    Bio Description
                                </label>
                                <textarea 
                                    name="bio" 
                                    id="bio" 
                                    rows="3"
                                    placeholder="Tell your visitors about yourself..."
                                    maxlength="155"
                                    class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                                >{{ old('bio') }}</textarea>
                                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Maximum 155 characters</p>
                                @error('bio')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-8 flex justify-end gap-3">
                            <a href="{{ route('bio.index') }}" class="rounded-lg border border-slate-300 px-6 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-800">
                                Cancel
                            </a>
                            <button 
                                type="submit" 
                                :disabled="available === false"
                                class="rounded-lg bg-blue-600 px-6 py-3 font-medium text-white hover:bg-blue-700 disabled:bg-slate-400 disabled:cursor-not-allowed transition-all"
                            >
                                Create Bio Page
                            </button>
                        </div>
                    </form>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900">
                    <h2 class="mb-4 text-lg font-semibold text-slate-900 dark:text-white">QR Code Preview</h2>
                    <div x-data="qrGenerator()">
                        <div class="relative flex items-center justify-center rounded-lg bg-slate-50 p-4 dark:bg-slate-800 mb-4">
                            <div class="relative w-[200px] h-[200px]">
                                <div 
                                    x-ref="qrContainer" 
                                    class="w-full h-full overflow-hidden rounded-lg bg-white p-2 flex items-center justify-center"
                                    :class="{ 'blur-sm': !hasSlug }"
                                >
                                    <template x-if="!hasSlug">
                                        <div class="flex h-full items-center justify-center text-slate-300">
                                            <svg class="h-16 w-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                            </svg>
                                        </div>
                                    </template>
                                </div>
                                <template x-if="!hasSlug">
                                    <div class="absolute inset-0 flex items-center justify-center rounded-lg bg-slate-900/50 backdrop-blur-sm">
                                        <p class="text-xs font-medium text-white">Enter a slug</p>
                                    </div>
                                </template>
                                <template x-if="generating">
                                    <div class="absolute inset-0 flex items-center justify-center rounded-lg bg-slate-900/50">
                                        <svg class="h-6 w-6 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <label class="mb-2 block text-xs font-medium text-slate-700 dark:text-slate-300">
                                     Logo
                                </label>
                                <div class="flex items-center gap-2">
                                    <input 
                                        type="file" 
                                        accept="image/*"
                                        @change="uploadLogo($event)"
                                        class="block w-full text-xs text-slate-500 file:mr-2 file:rounded file:border-0 file:bg-blue-50 file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900/30 dark:file:text-blue-400"
                                    >
                                    <button 
                                        type="button" 
                                        x-show="logoUrl" 
                                        @click="removeLogo()"
                                        class="rounded bg-red-100 px-2 py-1.5 text-xs text-red-700 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-400"
                                    >
                                        Remove
                                    </button>
                                </div>
                            </div>
                            <div x-data="{ openColors: false }" class="border border-slate-200 rounded-lg dark:border-slate-700">
                                <button 
                                    type="button"
                                    @click="openColors = !openColors"
                                    class="w-full flex items-center justify-between px-3 py-2 text-xs font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg transition-colors"
                                >
                                    <span>Colors (Dots & Background)</span>
                                    <svg 
                                        class="h-4 w-4 transition-transform" 
                                        :class="{ 'rotate-180': openColors }"
                                        fill="none" 
                                        stroke="currentColor" 
                                        viewBox="0 0 24 24"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div x-show="openColors" x-collapse class="px-3 pb-3 space-y-4">
                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-slate-600 dark:text-slate-400">
                                            Dots Color
                                        </label>
                                        <div class="flex flex-wrap gap-2">
                                            <button type="button" @click="setColor('#000000')" :class="{ 'ring-2 ring-blue-500 ring-offset-2': qrColor === '#000000' }" class="h-10 w-10 rounded-lg border-2 border-slate-300 hover:scale-110 transition-all shadow-sm" style="background-color: #000000;"></button>
                                            <button type="button" @click="setColor('#1E40AF')" :class="{ 'ring-2 ring-blue-500 ring-offset-2': qrColor === '#1E40AF' }" class="h-10 w-10 rounded-lg border-2 border-slate-300 hover:scale-110 transition-all shadow-sm" style="background-color: #1E40AF;"></button>
                                            <button type="button" @click="setColor('#EF4444')" :class="{ 'ring-2 ring-blue-500 ring-offset-2': qrColor === '#EF4444' }" class="h-10 w-10 rounded-lg border-2 border-slate-300 hover:scale-110 transition-all shadow-sm" style="background-color: #EF4444;"></button>
                                            <button type="button" @click="setColor('#10B981')" :class="{ 'ring-2 ring-blue-500 ring-offset-2': qrColor === '#10B981' }" class="h-10 w-10 rounded-lg border-2 border-slate-300 hover:scale-110 transition-all shadow-sm" style="background-color: #10B981;"></button>
                                            <button type="button" @click="setColor('#8B5CF6')" :class="{ 'ring-2 ring-blue-500 ring-offset-2': qrColor === '#8B5CF6' }" class="h-10 w-10 rounded-lg border-2 border-slate-300 hover:scale-110 transition-all shadow-sm" style="background-color: #8B5CF6;"></button>
                                            <div class="relative" x-data="{ showColorPicker: false }">
                                                <button type="button" @click="showColorPicker = !showColorPicker" class="flex h-10 w-10 items-center justify-center rounded-lg border-2 border-slate-300 bg-gradient-to-br from-red-500 via-yellow-500 to-blue-500 hover:scale-110 transition-all shadow-sm">
                                                    <svg class="h-5 w-5 text-white drop-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>
                                                </button>
                                                <div x-show="showColorPicker" @click.away="showColorPicker = false" class="absolute left-0 top-12 z-10 rounded border border-slate-300 bg-white p-2 shadow-xl dark:border-slate-600 dark:bg-slate-800">
                                                    <input type="color" x-model="customColor" @change="setColor(customColor)" class="h-10 w-24 cursor-pointer rounded">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-slate-600 dark:text-slate-400">
                                            Background Color
                                        </label>
                                        <div class="flex flex-wrap gap-2">
                                            <button type="button" @click="updateBgColor('#ffffff')" :class="{ 'ring-2 ring-blue-500 ring-offset-2': bgColor === '#ffffff' }" class="h-10 w-10 rounded-lg border-2 border-slate-300 hover:scale-110 transition-transform shadow-sm" style="background-color: #ffffff;"></button>
                                            <button type="button" @click="updateBgColor('#F3F4F6')" :class="{ 'ring-2 ring-blue-500 ring-offset-2': bgColor === '#F3F4F6' }" class="h-10 w-10 rounded-lg border-2 border-slate-300 hover:scale-110 transition-transform shadow-sm" style="background-color: #F3F4F6;"></button>
                                            <button type="button" @click="updateBgColor('#FEF3C7')" :class="{ 'ring-2 ring-blue-500 ring-offset-2': bgColor === '#FEF3C7' }" class="h-10 w-10 rounded-lg border-2 border-slate-300 hover:scale-110 transition-transform shadow-sm" style="background-color: #FEF3C7;"></button>
                                            <button type="button" @click="updateBgColor('#DBEAFE')" :class="{ 'ring-2 ring-blue-500 ring-offset-2': bgColor === '#DBEAFE' }" class="h-10 w-10 rounded-lg border-2 border-slate-300 hover:scale-110 transition-transform shadow-sm" style="background-color: #DBEAFE;"></button>
                                            <button type="button" @click="updateBgColor('#FEE2E2')" :class="{ 'ring-2 ring-blue-500 ring-offset-2': bgColor === '#FEE2E2' }" class="h-10 w-10 rounded-lg border-2 border-slate-300 hover:scale-110 transition-transform shadow-sm" style="background-color: #FEE2E2;"></button>
                                            <div class="relative" x-data="{ showBgPicker: false }">
                                                <button type="button" @click="showBgPicker = !showBgPicker" class="flex h-10 w-10 items-center justify-center rounded-lg border-2 border-slate-300 bg-gradient-to-br from-pink-500 via-purple-500 to-indigo-500 hover:scale-110 transition-all shadow-sm">
                                                    <svg class="h-5 w-5 text-white drop-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>
                                                </button>
                                                <div x-show="showBgPicker" @click.away="showBgPicker = false" class="absolute left-0 top-12 z-10 rounded border border-slate-300 bg-white p-2 shadow-xl dark:border-slate-600 dark:bg-slate-800">
                                                    <input type="color" x-model="bgColor" @change="updateBgColor(bgColor)" class="h-10 w-24 cursor-pointer rounded">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div x-data="{ openStyles: false }" class="border border-slate-200 rounded-lg dark:border-slate-700">
                                <button 
                                    type="button"
                                    @click="openStyles = !openStyles"
                                    class="w-full flex items-center justify-between px-3 py-2 text-xs font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg transition-colors"
                                >
                                    <span>Styles (Dots & Corners)</span>
                                    <svg 
                                        class="h-4 w-4 transition-transform" 
                                        :class="{ 'rotate-180': openStyles }"
                                        fill="none" 
                                        stroke="currentColor" 
                                        viewBox="0 0 24 24"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div x-show="openStyles" x-collapse class="px-3 pb-3 space-y-4">
                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-slate-600 dark:text-slate-400">
                                            Dots Style
                                        </label>
                                        <div class="grid grid-cols-5 gap-2">
                                            <button type="button" @click="updateDotsType('rounded')" :class="dotsType === 'rounded' ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-700 hover:bg-slate-300 dark:bg-slate-700 dark:text-slate-300'" class="rounded-lg px-3 py-2 text-xs font-medium transition-all">Round</button>
                                            <button type="button" @click="updateDotsType('dots')" :class="dotsType === 'dots' ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-700 hover:bg-slate-300 dark:bg-slate-700 dark:text-slate-300'" class="rounded-lg px-3 py-2 text-xs font-medium transition-all">Dots</button>
                                            <button type="button" @click="updateDotsType('classy')" :class="dotsType === 'classy' ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-700 hover:bg-slate-300 dark:bg-slate-700 dark:text-slate-300'" class="rounded-lg px-3 py-2 text-xs font-medium transition-all">Classy</button>
                                            <button type="button" @click="updateDotsType('square')" :class="dotsType === 'square' ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-700 hover:bg-slate-300 dark:bg-slate-700 dark:text-slate-300'" class="rounded-lg px-3 py-2 text-xs font-medium transition-all">Square</button>
                                            <button type="button" @click="updateDotsType('extra-rounded')" :class="dotsType === 'extra-rounded' ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-700 hover:bg-slate-300 dark:bg-slate-700 dark:text-slate-300'" class="rounded-lg px-3 py-2 text-xs font-medium transition-all">Extra</button>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-slate-600 dark:text-slate-400">
                                                Corner Square
                                            </label>
                                            <div class="grid grid-cols-3 gap-2">
                                                <button type="button" @click="updateCornerSquare('dot')" :class="cornerSquareType === 'dot' ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-700 hover:bg-slate-300 dark:bg-slate-700 dark:text-slate-300'" class="rounded-lg px-2 py-2 text-xs font-medium transition-all">Dot</button>
                                                <button type="button" @click="updateCornerSquare('square')" :class="cornerSquareType === 'square' ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-700 hover:bg-slate-300 dark:bg-slate-700 dark:text-slate-300'" class="rounded-lg px-2 py-2 text-xs font-medium transition-all">Sqr</button>
                                                <button type="button" @click="updateCornerSquare('extra-rounded')" :class="cornerSquareType === 'extra-rounded' ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-700 hover:bg-slate-300 dark:bg-slate-700 dark:text-slate-300'" class="rounded-lg px-2 py-2 text-xs font-medium transition-all">Ext</button>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-slate-600 dark:text-slate-400">
                                                Corner Dot
                                            </label>
                                            <div class="grid grid-cols-2 gap-2">
                                                <button type="button" @click="updateCornerDot('dot')" :class="cornerDotType === 'dot' ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-700 hover:bg-slate-300 dark:bg-slate-700 dark:text-slate-300'" class="rounded-lg px-3 py-2 text-xs font-medium transition-all">Dot</button>
                                                <button type="button" @click="updateCornerDot('square')" :class="cornerDotType === 'square' ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-700 hover:bg-slate-300 dark:bg-slate-700 dark:text-slate-300'" class="rounded-lg px-3 py-2 text-xs font-medium transition-all">Square</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Set QR Code Button -->
                            <div class="mt-4">
                                <button 
                                    type="button" 
                                    @click="syncSettings(); $dispatch('notify', { message: 'QR Settings Applied', type: 'success' })"
                                    class="w-full rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors shadow-sm flex items-center justify-center gap-2"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Set QR Code
                                </button>
                                <p class="mt-2 text-xs text-center text-slate-500 dark:text-slate-400">
                                    Click to ensure your QR design is saved before creating the page.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function slugChecker() {
            return {
                slug: '',
                checking: false,
                available: null,
                checkSlug() {
                    if (this.slug.length < 3) {
                        this.available = null;
                        return;
                    }
                    this.checking = true;
                    fetch(`/bio/check-slug?slug=${this.slug}`)
                        .then(response => response.json())
                        .then(data => {
                            this.available = data.available;
                            this.checking = false;
                            if (data.available) {
                                window.dispatchEvent(new CustomEvent('slug-available', { 
                                    detail: { slug: this.slug } 
                                }));
                            }
                        });
                }
            };
        }
        function qrGenerator() {
            return {
                currentSlug: '',
                hasSlug: false,
                generating: false,
                qrColor: '#000000',
                customColor: '#000000',
                logoUrl: null,
                logoFile: null,
                qrCodeInstance: null,
                dotsType: 'rounded',
                bgColor: '#ffffff',
                cornerSquareType: 'extra-rounded',
                cornerSquareColor: '#000000',
                cornerDotType: 'dot',
                cornerDotColor: '#000000',
                init() {
                    window.addEventListener('slug-available', (e) => {
                        this.currentSlug = e.detail.slug;
                        this.hasSlug = true;
                        this.generateQR();
                    });
                    this.syncSettings();
                },
                syncSettings() {
                    const settings = {
                        dotsOptions: { color: this.qrColor, type: this.dotsType },
                        backgroundOptions: { color: this.bgColor },
                        cornersSquareOptions: { color: this.cornerSquareColor, type: this.cornerSquareType },
                        cornersDotOptions: { color: this.cornerDotColor, type: this.cornerDotType },
                        image: this.logoUrl
                    };
                    const input = document.getElementById('qr_settings_input');
                    if (input) input.value = JSON.stringify(settings);
                },
                setColor(color) {
                    this.qrColor = color;
                    this.customColor = color;
                    this.cornerSquareColor = color;
                    this.cornerDotColor = color;
                    this.syncSettings();
                    if (this.hasSlug) {
                        this.updateQR();
                    }
                },
                uploadLogo(event) {
                    const file = event.target.files[0];
                    if (!file) return;
                    if (file.size > 1024 * 1024) {
                        const notification = document.createElement('div');
                        notification.className = 'fixed top-4 right-4 z-50 rounded-lg bg-red-600 p-4 text-white shadow-2xl';
                        notification.innerHTML = '<p class="font-medium">⚠ File size must be less than 1MB</p>';
                        document.body.appendChild(notification);
                        setTimeout(() => notification.remove(), 3000);
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.logoUrl = e.target.result;
                        this.syncSettings();
                        if (this.hasSlug) {
                            this.updateQR();
                        }
                    };
                    reader.readAsDataURL(file);
                },
                removeLogo() {
                    this.logoUrl = null;
                    this.syncSettings();
                    if (this.hasSlug) {
                        this.updateQR();
                    }
                },
                updateDotsType(type) {
                    this.dotsType = type;
                    this.syncSettings();
                    if (this.hasSlug) {
                        this.updateQR();
                    }
                },
                updateBgColor(color) {
                    this.bgColor = color;
                    this.syncSettings();
                    if (this.hasSlug) {
                        this.updateQR();
                    }
                },
                updateCornerSquare(type, color) {
                    if (type) this.cornerSquareType = type;
                    if (color) this.cornerSquareColor = color;
                    this.syncSettings();
                    if (this.hasSlug) {
                        this.updateQR();
                    }
                },
                updateCornerDot(type, color) {
                    if (type) this.cornerDotType = type;
                    if (color) this.cornerDotColor = color;
                    this.syncSettings();
                    if (this.hasSlug) {
                        this.updateQR();
                    }
                },
                updateQR() {
                    if (!this.qrCodeInstance || !this.hasSlug) return;
                    this.qrCodeInstance.update({
                        dotsOptions: {
                            color: this.qrColor,
                            type: this.dotsType
                        },
                        backgroundOptions: {
                            color: this.bgColor,
                        },
                        cornersSquareOptions: {
                            color: this.cornerSquareColor,
                            type: this.cornerSquareType
                        },
                        cornersDotOptions: {
                            color: this.cornerDotColor,
                            type: this.cornerDotType
                        },
                        image: this.logoUrl || undefined,
                    });
                },
                generateQR() {
                    if (!this.currentSlug) return;
                    this.generating = true;
                    const qrContainer = this.$refs.qrContainer;
                    qrContainer.innerHTML = '<div class="flex items-center justify-center h-full"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div></div>';
                    const url = `{{ config('app.url') }}/b/${this.currentSlug}`;
                    setTimeout(() => {
                        qrContainer.innerHTML = '';
                        const qrCode = new QRCodeStyling({
                            width: 180,
                            height: 180,
                            type: "canvas",
                            data: url,
                            image: this.logoUrl || undefined,
                            dotsOptions: {
                                color: this.qrColor,
                                type: this.dotsType
                            },
                            backgroundOptions: {
                                color: this.bgColor,
                            },
                            imageOptions: {
                                crossOrigin: "anonymous",
                                margin: 8,
                                imageSize: 0.3,
                                hideBackgroundDots: true
                            },
                            cornersSquareOptions: {
                                color: this.cornerSquareColor,
                                type: this.cornerSquareType
                            },
                            cornersDotOptions: {
                                color: this.cornerDotColor,
                                type: this.cornerDotType
                            },
                            qrOptions: {
                                errorCorrectionLevel: "H"
                            }
                        });
                        this.qrCodeInstance = qrCode;
                        qrCode.append(qrContainer);
                        this.generating = false;
                    }, 100);
                }
            };
        }
    </script>
</x-app-layout>
