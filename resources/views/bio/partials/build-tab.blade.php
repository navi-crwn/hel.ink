<style>
    [contenteditable][placeholder]:empty:before {
        content: attr(placeholder);
        color: #9ca3af;
        pointer-events: none;
    }
    [contenteditable][placeholder]:empty:focus:before {
        content: attr(placeholder);
    }
    [contenteditable]:focus {
        outline: none;
    }
</style>
<div class="space-y-6 transition-all duration-200">
    <!-- Profile & QR Section - Compact -->
    <div class="rounded-lg bg-white p-5 shadow-sm transition-all duration-200 hover:shadow-md dark:bg-slate-800 border-l-4 border-blue-600 dark:border-blue-400" x-data="{ editingQR: false, ...qrGenerator() }">
        <div class="mb-4 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Profile & QR Code</h3>
            <button 
                @click="editingQR = !editingQR" 
                type="button"
                class="text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300"
            >
                <span x-text="editingQR ? 'Close' : 'Edit QR'"></span>
            </button>
        </div>
        <div class="grid gap-5 md:grid-cols-2">
            <!-- Left: Profile Info -->
            <div class="space-y-4">
                <div class="flex items-center gap-4">
                    @if($bioPage->avatar_url)
                        <img src="{{ Storage::url($bioPage->avatar_url) }}" x-ref="avatarPreview" class="h-14 w-14 flex-shrink-0 rounded-full object-cover" alt="Avatar">
                    @else
                        <div x-ref="avatarPreview" class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 text-xl font-bold text-white">
                            {{ substr($bioPage->title, 0, 1) }}
                        </div>
                    @endif
                    <input type="file" accept="image/*" @change="uploadAvatar($event)" class="flex-1 text-sm text-slate-600 dark:text-slate-400 file:mr-3 file:rounded file:border-0 file:bg-blue-50 file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-blue-700 hover:file:bg-blue-100">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                        Title <span class="text-xs text-slate-400" x-text="'(' + (bioPage.title?.length || 0) + '/30)'"></span>
                    </label>
                    <input type="text" 
                           x-model="bioPage.title" 
                           maxlength="30"
                           placeholder="Your name or brand"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                        Bio <span class="text-xs text-slate-400" x-text="'(' + (bioPage.bio?.length || 0) + '/80)'"></span>
                    </label>
                    <textarea x-model="bioPage.bio" 
                              rows="2" 
                              maxlength="80"
                              placeholder="Short description"
                              class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-900 dark:text-white"></textarea>
                </div>
            </div>
            <!-- Right: QR Code -->
            <div class="flex flex-col items-center">
                <!-- QR Preview (Hidden until Edit QR clicked) -->
                <div x-show="editingQR" x-collapse class="relative flex items-center justify-center rounded-lg bg-slate-50 p-4 dark:bg-slate-900">
                    <div class="relative w-[180px] h-[180px]">
                        <div 
                            x-ref="qrContainer" 
                            class="w-full h-full flex items-center justify-center overflow-hidden rounded-lg bg-white"
                        ></div>
                        <!-- Loading spinner -->
                        <template x-if="generating">
                            <div class="absolute inset-0 flex items-center justify-center rounded-lg bg-slate-900/50">
                                <svg class="h-5 w-5 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                        </template>
                    </div>
                </div>
                <!-- QR Customization (Collapsible) -->
                <div x-show="editingQR" x-collapse class="mt-4 w-full space-y-3">
                    <!-- Logo Upload -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">Add Logo</label>
                        <div class="flex items-center gap-2">
                            <input 
                                type="file" 
                                accept="image/*"
                                @change="uploadLogo($event)"
                                class="block w-full text-sm text-slate-500 file:mr-2 file:rounded file:border-0 file:bg-blue-50 file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-blue-700 hover:file:bg-blue-100"
                            >
                            <button 
                                type="button" 
                                x-show="logoUrl" 
                                @click="removeLogo()"
                                class="rounded bg-red-100 px-2 py-1.5 text-xs text-red-700 hover:bg-red-200"
                            >Remove</button>
                        </div>
                    </div>
                    <!-- Colors -->
                    <div x-data="{ openColors: false }" class="border border-slate-200 rounded-lg dark:border-slate-700">
                        <button 
                            type="button"
                            @click="openColors = !openColors"
                            class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg"
                        >
                            <span>Colors</span>
                            <svg class="h-4 w-4 transition-transform" :class="{ 'rotate-180': openColors }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="openColors" x-collapse class="px-3 pb-3 space-y-3">
                            <!-- Dots Color -->
                            <div>
                                <label class="mb-2 block text-xs font-medium text-slate-600 dark:text-slate-400">Dots</label>
                                <div class="flex flex-wrap gap-2">
                                    <button type="button" @click="setColor('#000000')" :class="{ 'ring-2 ring-blue-500 ring-offset-1': qrColor === '#000000' }" class="h-8 w-8 rounded border border-slate-300 hover:scale-110 transition-all" style="background-color: #000000;"></button>
                                    <button type="button" @click="setColor('#1E40AF')" :class="{ 'ring-2 ring-blue-500 ring-offset-1': qrColor === '#1E40AF' }" class="h-8 w-8 rounded border border-slate-300 hover:scale-110 transition-all" style="background-color: #1E40AF;"></button>
                                    <button type="button" @click="setColor('#EF4444')" :class="{ 'ring-2 ring-blue-500 ring-offset-1': qrColor === '#EF4444' }" class="h-8 w-8 rounded border border-slate-300 hover:scale-110 transition-all" style="background-color: #EF4444;"></button>
                                    <button type="button" @click="setColor('#10B981')" :class="{ 'ring-2 ring-blue-500 ring-offset-1': qrColor === '#10B981' }" class="h-8 w-8 rounded border border-slate-300 hover:scale-110 transition-all" style="background-color: #10B981;"></button>
                                    <button type="button" @click="setColor('#8B5CF6')" :class="{ 'ring-2 ring-blue-500 ring-offset-1': qrColor === '#8B5CF6' }" class="h-8 w-8 rounded border border-slate-300 hover:scale-110 transition-all" style="background-color: #8B5CF6;"></button>
                                    <div class="relative" x-data="{ showColorPicker: false }">
                                        <button type="button" @click="showColorPicker = !showColorPicker" class="flex h-8 w-8 items-center justify-center rounded border border-slate-300 bg-gradient-to-br from-red-500 via-yellow-500 to-blue-500 hover:scale-110 transition-all">
                                            <svg class="h-4 w-4 text-white drop-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>
                                        </button>
                                        <div x-show="showColorPicker" @click.away="showColorPicker = false" class="absolute left-0 top-10 z-10 rounded border bg-white p-2 shadow-xl dark:bg-slate-800">
                                            <input type="color" x-model="customColor" @change="setColor(customColor)" class="h-8 w-20 cursor-pointer rounded">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Background Color -->
                            <div>
                                <label class="mb-2 block text-xs font-medium text-slate-600 dark:text-slate-400">Background</label>
                                <div class="flex flex-wrap gap-2">
                                    <button type="button" @click="updateBgColor('#ffffff')" :class="{ 'ring-2 ring-blue-500 ring-offset-1': bgColor === '#ffffff' }" class="h-8 w-8 rounded border border-slate-300 hover:scale-110 transition-transform" style="background-color: #ffffff;"></button>
                                    <button type="button" @click="updateBgColor('#F3F4F6')" :class="{ 'ring-2 ring-blue-500 ring-offset-1': bgColor === '#F3F4F6' }" class="h-8 w-8 rounded border border-slate-300 hover:scale-110 transition-transform" style="background-color: #F3F4F6;"></button>
                                    <button type="button" @click="updateBgColor('#FEF3C7')" :class="{ 'ring-2 ring-blue-500 ring-offset-1': bgColor === '#FEF3C7' }" class="h-8 w-8 rounded border border-slate-300 hover:scale-110 transition-transform" style="background-color: #FEF3C7;"></button>
                                    <button type="button" @click="updateBgColor('#DBEAFE')" :class="{ 'ring-2 ring-blue-500 ring-offset-1': bgColor === '#DBEAFE' }" class="h-8 w-8 rounded border border-slate-300 hover:scale-110 transition-transform" style="background-color: #DBEAFE;"></button>
                                    <button type="button" @click="updateBgColor('#FEE2E2')" :class="{ 'ring-2 ring-blue-500 ring-offset-1': bgColor === '#FEE2E2' }" class="h-8 w-8 rounded border border-slate-300 hover:scale-110 transition-transform" style="background-color: #FEE2E2;"></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Save & Download QR -->
                    <div class="flex gap-2" x-data="{ saved: false }">
                        <button type="button" 
                                @click="saved = true; $dispatch('qr-saved'); setTimeout(() => saved = false, 3000)"
                                class="flex-1 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                            <span x-show="!saved">💾 Save</span>
                            <span x-show="saved" x-cloak>✓ Saved!</span>
                        </button>
                        <button type="button" 
                                x-show="saved"
                                x-cloak
                                @click="qrCodeInstance && qrCodeInstance.download({ name: 'qr-code', extension: 'png' })"
                                class="flex-1 rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">
                            ⬇️ Download
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Blocks Section - Compact -->
    <div class="rounded-lg bg-white p-5 shadow-sm transition-all duration-200 hover:shadow-md dark:bg-slate-800 border-l-4 border-purple-600 dark:border-purple-400">
        <div class="mb-4 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Blocks</h3>
            <div class="relative" x-data="{ showMenu: false }">
                <button @click="showMenu = !showMenu" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                    + Add Block
                </button>
                <div x-show="showMenu" @click.away="showMenu = false" x-cloak x-transition class="absolute right-0 top-10 z-10 w-44 rounded-lg bg-white shadow-lg dark:bg-slate-700">
                    <button @click="addBlock('link'); showMenu = false" class="flex w-full items-center gap-3 px-4 py-2.5 text-left text-sm hover:bg-slate-100 dark:hover:bg-slate-600">
                        <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                        <span class="text-slate-900 dark:text-white">Link</span>
                    </button>
                    <button @click="addBlock('image'); showMenu = false" class="flex w-full items-center gap-3 px-4 py-2.5 text-left text-sm hover:bg-slate-100 dark:hover:bg-slate-600">
                        <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-slate-900 dark:text-white">Image</span>
                    </button>
                    <button @click="addBlock('text'); showMenu = false" class="flex w-full items-center gap-3 px-4 py-2.5 text-left text-sm hover:bg-slate-100 dark:hover:bg-slate-600">
                        <svg class="h-4 w-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="text-slate-900 dark:text-white">Text</span>
                    </button>
                </div>
            </div>
        </div>
        <div id="sortable-blocks" class="space-y-2" style="isolation: isolate;">
            <template x-for="(block, index) in blocks" :key="block.id">
                <div class="block-sortable-item group rounded-lg border border-slate-200 bg-slate-50 px-2 py-2 transition-all hover:border-blue-300 hover:shadow-md dark:border-slate-700 dark:bg-slate-900" :data-block-id="block.id" style="position: relative; z-index: 1;" x-data="{ isMinimized: (block.type === 'link' || block.type === 'image') ? (block.id < 1000000000) : (block.content && block.id < 1000000000) }">
                    <div class="flex items-center gap-2">
                        <div class="block-drag-handle cursor-move text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 flex-shrink-0 p-1">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <!-- Link Block - Improved UX -->
                            <template x-if="block.type === 'link'">
                                <div x-data="linkBlock(block)" x-init="init()" @toggle-block-minimize.window="if($event.detail.blockId === block.id) minimized = !minimized">
                                    <!-- Minimized View -->
                                    <template x-if="minimized">
                                        <div class="flex items-center gap-2 cursor-pointer py-0.5" @click="minimized = false">
                                            <div class="h-8 w-8 flex items-center justify-center rounded-full border border-slate-300 bg-white dark:border-slate-600 dark:bg-slate-800 overflow-hidden flex-shrink-0">
                                                <template x-if="iconUrl">
                                                    <img :src="iconUrl.startsWith('http') ? iconUrl : '/storage/' + iconUrl" alt="Icon" class="h-full w-full object-cover">
                                                </template>
                                                <template x-if="!iconUrl">
                                                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                                </template>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-slate-800 dark:text-white truncate" x-text="block.title || 'Untitled Link'"></p>
                                                <p class="text-xs text-slate-500 truncate" x-text="block.url || 'No URL'"></p>
                                            </div>
                                        </div>
                                    </template>
                                    <!-- Expanded View (editing) -->
                                    <template x-if="!minimized">
                                        <div class="space-y-4">
                                            <!-- Icon + Title Row -->
                                            <div class="flex items-center gap-4">
                                                <button type="button" @click="overlayOpen = true" class="h-16 w-16 flex items-center justify-center rounded-full border-2 border-dashed border-slate-300 bg-white text-slate-700 hover:border-blue-400 hover:bg-blue-50 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:border-blue-500 overflow-hidden transition-all flex-shrink-0">
                                                    <template x-if="iconUrl">
                                                        <img :src="iconUrl.startsWith('http') ? iconUrl : '/storage/' + iconUrl" alt="Icon" class="h-full w-full object-cover">
                                                    </template>
                                                    <template x-if="!iconUrl">
                                                        <div class="text-center">
                                                            <svg class="h-6 w-6 mx-auto text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                            <span class="text-xs text-slate-400">Icon</span>
                                                        </div>
                                                    </template>
                                                </button>
                                                <input type="text" x-model="block.title" @input.debounce.500ms="emitSavePreview()" placeholder="Link Title" class="flex-1 rounded-lg border border-slate-300 px-4 py-3 text-base transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white dark:focus:border-blue-500 dark:focus:ring-blue-900/50">
                                            </div>
                                            <!-- Mode Toggle -->
                                            <div class="flex items-center gap-4">
                                                <button type="button" @click="mode='new'" :class="mode==='new' ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-300'" class="h-8 w-8 rounded-full text-sm font-semibold transition-all">A</button>
                                                <span class="text-sm">Create new</span>
                                                <button type="button" @click="mode='library'" :class="mode==='library' ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-300'" class="h-8 w-8 rounded-full text-sm font-semibold transition-all">B</button>
                                                <span class="text-sm">Library</span>
                                            </div>
                                            <!-- Mode: Create New -->
                                            <div x-show="mode==='new'" x-collapse class="space-y-3">
                                                <input type="url" x-model="urlInput" @blur="normalizeUrl()" @input.debounce.600ms="emitSavePreview()" placeholder="ex. https://longurl.com" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-base transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white dark:focus:border-blue-500 dark:focus:ring-blue-900/50">
                                                <div class="flex items-center justify-between">
                                                    <p class="text-xs text-slate-500">
                                                        <span x-show="block.url && block.url.includes('hel.ink')" class="text-green-600 font-medium">✓ <span x-text="block.url"></span></span>
                                                        <span x-show="!block.url || !block.url.includes('hel.ink')" class="text-slate-400">No shortlink yet</span>
                                                    </p>
                                                    <button type="button" @click="saveLink()" :disabled="saving || !urlInput || !block.title" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 disabled:bg-slate-400 disabled:cursor-not-allowed transition-all">
                                                        <span x-show="!saving" class="flex items-center gap-2">
                                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                            Save
                                                        </span>
                                                        <span x-show="saving" class="flex items-center gap-2">
                                                            <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                                            Saving...
                                                        </span>
                                                    </button>
                                                </div>
                                            </div>
                                            <!-- Mode: Library -->
                                            <div x-show="mode==='library'" x-collapse class="space-y-3">
                                                <div class="relative">
                                                    <input type="text" x-model="query" @input.debounce.300ms="searchLibrary()" placeholder="Search your links…" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-base transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white dark:focus:border-blue-500 dark:focus:ring-blue-900/50">
                                                    <div x-show="searching" class="absolute right-3 top-3">
                                                        <svg class="h-5 w-5 animate-spin text-blue-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                                    </div>
                                                    <div x-show="results.length > 0" x-cloak class="absolute left-0 right-0 top-14 z-20 max-h-60 overflow-y-auto rounded-lg border border-slate-200 bg-white shadow-xl dark:border-slate-700 dark:bg-slate-800">
                                                        <template x-for="item in results" :key="item.id">
                                                            <button type="button" @click="selectLibrary(item)" class="flex w-full items-center gap-3 px-4 py-3 text-left hover:bg-slate-100 dark:hover:bg-slate-700 border-b border-slate-100 dark:border-slate-700 last:border-0">
                                                                <div class="h-8 w-8 rounded bg-slate-100 dark:bg-slate-700 flex items-center justify-center flex-shrink-0">
                                                                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                                                </div>
                                                                <div class="flex-1 min-w-0">
                                                                    <p class="font-medium text-slate-800 dark:text-white truncate" x-text="item.title || 'Untitled'"></p>
                                                                    <p class="text-xs text-slate-500 truncate" x-text="item.short_url || item.url"></p>
                                                                </div>
                                                            </button>
                                                        </template>
                                                    </div>
                                                    <div x-show="query && !searching && results.length === 0" class="absolute left-0 right-0 top-14 z-20 rounded-lg border border-slate-200 bg-white p-4 text-center text-slate-500 shadow-xl dark:border-slate-700 dark:bg-slate-800">
                                                        No links found
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    <!-- Icon Upload Overlay -->
                                    <div x-show="overlayOpen" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center bg-black/40" @click.self="overlayOpen = false">
                                        <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-2xl dark:bg-slate-800" @click.stop>
                                            <h4 class="mb-4 text-lg font-semibold text-slate-800 dark:text-white">Add Link Icon</h4>
                                            <div class="space-y-4">
                                                <!-- Preview -->
                                                <div class="flex justify-center">
                                                    <div class="h-24 w-24 rounded-full border-2 border-dashed border-slate-300 bg-slate-50 dark:border-slate-600 dark:bg-slate-700 flex items-center justify-center overflow-hidden">
                                                        <template x-if="iconUrl">
                                                            <img :src="iconUrl.startsWith('http') ? iconUrl : '/storage/' + iconUrl" alt="Preview" class="h-full w-full object-cover">
                                                        </template>
                                                        <template x-if="!iconUrl">
                                                            <svg class="h-10 w-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                        </template>
                                                    </div>
                                                </div>
                                                <!-- Upload File -->
                                                <div>
                                                    <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Upload Image</label>
                                                    <input type="file" accept="image/*" @change="uploadIcon($event)" class="mt-1 w-full text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900/30 dark:file:text-blue-400">
                                                </div>
                                                <!-- Or URL -->
                                                <div class="relative">
                                                    <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-200 dark:border-slate-700"></div></div>
                                                    <div class="relative flex justify-center text-sm"><span class="bg-white px-2 text-slate-500 dark:bg-slate-800">or</span></div>
                                                </div>
                                                <div>
                                                    <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Image URL</label>
                                                    <input type="url" x-model="iconUrlInput" @input.debounce.500ms="setIconFromUrl()" placeholder="https://example.com/image.png" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 text-sm dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                                                </div>
                                                <!-- Actions -->
                                                <div class="flex gap-3 pt-2">
                                                    <button type="button" @click="iconUrl = ''; iconUrlInput = ''" class="flex-1 rounded-lg bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-200 dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-600">Clear</button>
                                                    <button type="button" @click="overlayOpen = false; applyIcon()" class="flex-1 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Done</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <!-- Image Block - File & URL Support -->
                            <template x-if="block.type === 'image'">
                                <div x-data="imageBlock(block)" x-init="init()" @toggle-block-minimize.window="if($event.detail.blockId === block.id) minimized = !minimized">
                                    <!-- Minimized View -->
                                    <template x-if="minimized && previewUrl">
                                        <div @click="minimized = false" class="cursor-pointer flex items-center gap-2 py-0.5">
                                            <div class="flex-shrink-0 w-10 h-10 rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-700">
                                                <img :src="previewUrl" class="w-full h-full object-cover" alt="Image preview">
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-slate-700 dark:text-slate-200 truncate" x-text="block.title || 'Untitled Image'"></p>
                                                <p class="text-xs text-slate-500 dark:text-slate-400">Click to edit</p>
                                            </div>
                                        </div>
                                    </template>
                                    <!-- Expanded View -->
                                    <template x-if="!minimized || !previewUrl">
                                        <div class="space-y-3">
                                            <!-- Title Input -->
                                            <input type="text" x-model="block.title" @input.debounce.500ms="emitSave()" placeholder="Image Title (optional)" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                                            <!-- Upload Type Toggle -->
                                            <div class="flex gap-1 rounded-lg bg-slate-100 p-0.5 dark:bg-slate-800">
                                                <button type="button" @click="uploadMode = 'file'" :class="uploadMode === 'file' ? 'bg-white shadow text-slate-800 dark:bg-slate-700 dark:text-white' : 'text-slate-500'" class="flex-1 rounded-md px-3 py-1.5 text-xs font-medium transition-all">
                                                    <svg class="h-3 w-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                    Upload
                                                </button>
                                                <button type="button" @click="uploadMode = 'url'" :class="uploadMode === 'url' ? 'bg-white shadow text-slate-800 dark:bg-slate-700 dark:text-white' : 'text-slate-500'" class="flex-1 rounded-md px-3 py-1.5 text-xs font-medium transition-all">
                                            <svg class="h-3 w-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                            URL
                                        </button>
                                    </div>
                                    <!-- File Upload Mode -->
                                    <div x-show="uploadMode === 'file'" x-collapse>
                                        <div class="relative">
                                            <input type="file" accept="image/*" @change="uploadFile($event)" :disabled="uploading" class="hidden" x-ref="fileInput">
                                            <div @click="$refs.fileInput.click()" class="flex cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-slate-300 bg-slate-50 px-4 py-4 transition-all hover:border-blue-400 hover:bg-blue-50 dark:border-slate-600 dark:bg-slate-800/50 dark:hover:border-blue-500">
                                                <template x-if="!uploading">
                                                    <div class="text-center">
                                                        <svg class="mx-auto h-6 w-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                                        <p class="mt-1 text-xs text-slate-600 dark:text-slate-400">Click to upload (max 2MB)</p>
                                                    </div>
                                                </template>
                                                <template x-if="uploading">
                                                    <div class="text-center">
                                                        <svg class="mx-auto h-5 w-5 animate-spin text-blue-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                                        <p class="mt-1 text-xs text-blue-600">Uploading...</p>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- URL Input Mode -->
                                    <div x-show="uploadMode === 'url'" x-collapse class="space-y-3">
                                        <div class="flex gap-2">
                                            <input type="url" x-model="imageUrl" @keyup.enter="applyUrl()" placeholder="https://example.com/image.jpg" class="flex-1 rounded-lg border border-slate-300 px-4 py-3 text-base transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                                            <button type="button" @click="applyUrl()" :disabled="!imageUrl" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 disabled:bg-slate-400 disabled:cursor-not-allowed">
                                                Apply
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Image Preview -->
                                    <template x-if="previewUrl">
                                        <div class="relative rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700">
                                            <img :src="previewUrl" class="w-full object-contain max-h-64" alt="Image preview" x-on:error="previewUrl = ''">
                                            <button @click="clearImage()" type="button" class="absolute top-2 right-2 rounded-full bg-red-500/90 p-2 text-white hover:bg-red-600 shadow-lg transition-all">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent px-4 py-3">
                                                <p class="text-sm text-white truncate" x-text="block.title || 'Untitled Image'"></p>
                                            </div>
                                        </div>
                                    </template>
                                    <!-- Save Button -->
                                    <div class="flex justify-end mt-3">
                                        <button type="button" @click="saveImage()" :disabled="saving || !previewUrl" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 disabled:bg-slate-400 disabled:cursor-not-allowed transition-all">
                                            <span x-show="!saving" class="flex items-center gap-2">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                Save
                                            </span>
                                            <span x-show="saving" class="flex items-center gap-2">
                                                <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                                Saving...
                                            </span>
                                        </button>
                                    </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                            <!-- Text Block - Advanced Rich Text Editor -->
                            <template x-if="block.type === 'text'">
                                <div x-data="textEditor(block)" x-init="init()" @toggle-block-minimize.window="if($event.detail.blockId === block.id) { if(minimized) expand(); else minimize(); }">
                                    <!-- Minimized View -->
                                    <template x-if="minimized">
                                        <div @click="expand()" class="cursor-pointer flex items-center gap-2 py-0.5">
                                            <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-slate-700 dark:text-slate-200 truncate" x-text="block.title || 'Text Block'"></p>
                                                <p class="text-xs text-slate-500 dark:text-slate-400 truncate" x-text="getPlainText().substring(0, 50) + (getPlainText().length > 50 ? '...' : '')"></p>
                                            </div>
                                        </div>
                                    </template>
                                    <!-- Expanded View -->
                                    <template x-if="!minimized">
                                        <div class="space-y-3">
                                    <!-- Rich Text Toolbar with Keyboard Shortcuts -->
                                    <div class="flex flex-wrap items-center gap-2 rounded-lg border border-slate-200 bg-slate-50 p-2 dark:border-slate-700 dark:bg-slate-900">
                                        <button type="button" @click="formatBold()" :class="isBold ? 'bg-blue-600 text-white' : 'bg-white text-slate-700 dark:bg-slate-800 dark:text-slate-300'" class="rounded px-3 py-1.5 text-sm font-bold transition-all hover:scale-105" title="Bold (Ctrl+B)">B</button>
                                        <button type="button" @click="formatItalic()" :class="isItalic ? 'bg-blue-600 text-white' : 'bg-white text-slate-700 dark:bg-slate-800 dark:text-slate-300'" class="rounded px-3 py-1.5 text-sm italic transition-all hover:scale-105" title="Italic (Ctrl+I)">I</button>
                                        <button type="button" @click="formatUnderline()" :class="isUnderline ? 'bg-blue-600 text-white' : 'bg-white text-slate-700 dark:bg-slate-800 dark:text-slate-300'" class="rounded px-3 py-1.5 text-sm underline transition-all hover:scale-105" title="Underline (Ctrl+U)">U</button>
                                        <button type="button" @click="formatStrike()" :class="isStrike ? 'bg-blue-600 text-white' : 'bg-white text-slate-700 dark:bg-slate-800 dark:text-slate-300'" class="rounded px-3 py-1.5 text-sm line-through transition-all hover:scale-105" title="Strikethrough">S</button>
                                        <div class="mx-2 h-6 w-px bg-slate-300 dark:bg-slate-600"></div>
                                        <button type="button" @click="formatAlign('left')" :class="currentAlign === 'left' ? 'bg-blue-600 text-white' : 'bg-white text-slate-700 dark:bg-slate-800 dark:text-slate-300'" class="rounded px-3 py-1.5 text-sm transition-all hover:scale-105" title="Align Left">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h10M4 18h16"/>
                                            </svg>
                                        </button>
                                        <button type="button" @click="formatAlign('center')" :class="currentAlign === 'center' ? 'bg-blue-600 text-white' : 'bg-white text-slate-700 dark:bg-slate-800 dark:text-slate-300'" class="rounded px-3 py-1.5 text-sm transition-all hover:scale-105" title="Align Center">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M7 12h10M4 18h16"/>
                                            </svg>
                                        </button>
                                        <button type="button" @click="formatAlign('right')" :class="currentAlign === 'right' ? 'bg-blue-600 text-white' : 'bg-white text-slate-700 dark:bg-slate-800 dark:text-slate-300'" class="rounded px-3 py-1.5 text-sm transition-all hover:scale-105" title="Align Right">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M10 12h10M4 18h16"/>
                                            </svg>
                                        </button>
                                        <button type="button" @click="formatAlign('justify')" :class="currentAlign === 'justify' ? 'bg-blue-600 text-white' : 'bg-white text-slate-700 dark:bg-slate-800 dark:text-slate-300'" class="rounded px-3 py-1.5 text-sm transition-all hover:scale-105" title="Justify">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                            </svg>
                                        </button>
                                        <div class="mx-2 h-6 w-px bg-slate-300 dark:bg-slate-600"></div>
                                        <select @change="formatFontSize($event.target.value)" class="rounded border border-slate-300 bg-white px-2 py-1 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                                            <option value="1">Small</option>
                                            <option value="3" selected>Normal</option>
                                            <option value="5">Large</option>
                                            <option value="7">Huge</option>
                                        </select>
                                        <div class="relative">
                                            <input type="color" x-model="textColor" @change="formatColor(textColor)" class="h-7 w-7 cursor-pointer rounded border-0" title="Text Color">
                                        </div>
                                    </div>
                                    <!-- Contenteditable Editor -->
                                    <div x-ref="editor"
                                         contenteditable="true"
                                         @input="onInput()"
                                         @keydown="handleKeydown($event)"
                                         @mouseup="checkFormatState()"
                                         @keyup="checkFormatState()"
                                         @focus="checkFormatState()"
                                         class="min-h-[150px] w-full rounded-lg border border-slate-300 px-6 py-4 text-lg transition-all duration-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none dark:border-slate-700 dark:bg-slate-800 dark:text-white dark:focus:border-blue-500 dark:focus:ring-blue-900/50"
                                         :style="{ textAlign: currentAlign }"
                                         placeholder="Enter your text..."></div>
                                    <div class="mt-3 flex items-center justify-end">
                                        <button type="button" @click="saveText()" :disabled="saving" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 disabled:bg-slate-400 disabled:cursor-not-allowed transition-all">
                                            <span x-show="!saving" class="flex items-center gap-2">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                Save
                                            </span>
                                            <span x-show="saving" class="flex items-center gap-2">
                                                <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                                Saving...
                                            </span>
                                        </button>
                                    </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                        <div class="flex items-center gap-1 flex-shrink-0">
                            <button @click="toggleBlock(index)" :class="block.is_active ? 'text-green-600' : 'text-slate-400'" class="p-1.5 rounded hover:bg-slate-200 dark:hover:bg-slate-700 transition-all" title="Toggle visibility">
                                <svg x-show="block.is_active" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="!block.is_active" x-cloak class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                            <button @click="$dispatch('toggle-block-minimize', { blockId: block.id })" class="p-1.5 rounded text-slate-500 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all" title="Minimize/Expand">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                </svg>
                            </button>
                            <button @click="deleteBlock(index)" class="p-1.5 rounded text-red-500 hover:bg-red-100 dark:hover:bg-red-900/30 transition-all" title="Delete">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
        <div x-show="blocks.length === 0" class="py-16 text-center text-slate-500 dark:text-slate-400">
            <svg class="mx-auto h-16 w-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
            <p class="mt-4 text-base">No blocks yet. Add your first block above.</p>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    // Global sortable instance
    let blocksSortable = null;
    // Initialize Sortable - matching social tab pattern
    function initBlocksSortable() {
        const container = document.getElementById('sortable-blocks');
        if (!container) {
            return;
        }
        // Don't reinitialize if already exists and working
        if (blocksSortable) {
            return;
        }
        // Check if there are child elements to sort (direct div children, not template)
        const children = container.querySelectorAll(':scope > div.block-sortable-item');
        if (children.length === 0) {
            return;
        }
        blocksSortable = Sortable.create(container, {
            animation: 120,
            easing: 'ease-out',
            handle: '.block-drag-handle',
            draggable: '.block-sortable-item',
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            forceFallback: false,
            onStart: function(evt) {
                isSorting = true;
                document.body.classList.add('sorting-active');
            },
            onEnd: function(evt) {
                document.body.classList.remove('sorting-active');
                if (evt.oldIndex === evt.newIndex) {
                    isSorting = false;
                    return;
                }
                const bioEditor = getBioEditor();
                if (!bioEditor || !bioEditor.blocks) {
                    console.error('bioEditor not found');
                    return;
                }
                // Create new array with updated order
                const newBlocks = [...bioEditor.blocks];
                const [movedBlock] = newBlocks.splice(evt.oldIndex, 1);
                newBlocks.splice(evt.newIndex, 0, movedBlock);
                // Update order property for all blocks
                newBlocks.forEach((block, idx) => {
                    block.order = idx;
                });
                // Replace entire array to trigger Alpine reactivity
                bioEditor.blocks = newBlocks;
                // Dispatch event for live preview update
                window.dispatchEvent(new CustomEvent('blocks-reordered', { 
                    detail: { blocks: newBlocks } 
                }));
                // Save to server
                bioEditor.saveBlocksOrder();
                // Reset sorting flag after a short delay
                setTimeout(() => { isSorting = false; }, 100);
            }
        });
    }
    // Flag to prevent reinit during sorting
    let isSorting = false;
    // Use MutationObserver to detect when blocks are rendered
    document.addEventListener('DOMContentLoaded', () => {
        const el = document.getElementById('sortable-blocks');
        if (!el) return;
        // Initial attempt with longer delay for Alpine to render
        setTimeout(initBlocksSortable, 500);
        // Watch for changes - but only init if not already initialized
        const observer = new MutationObserver((mutations) => {
            // Don't reinit during sorting or if already initialized
            if (isSorting || blocksSortable) return;
            // Debounce reinit
            clearTimeout(window.sortableReinitTimeout);
            window.sortableReinitTimeout = setTimeout(() => {
                if (!blocksSortable) {
                    initBlocksSortable();
                }
            }, 300);
        });
        observer.observe(el, { childList: true, subtree: false });
    });
    // Also init on Alpine ready
    document.addEventListener('alpine:initialized', () => {
        if (!blocksSortable) {
            setTimeout(initBlocksSortable, 600);
        }
    });
    // Expose for debugging
    window.initBlocksSortable = initBlocksSortable;
</script>
<style>
    .sortable-ghost { 
        opacity: 0.4 !important; 
        background: #e0f2fe !important;
        border: 2px dashed #3b82f6 !important;
    }
    .sortable-chosen {
        box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
        background: white !important;
        outline: 2px solid #a855f7 !important;
    }
    .sortable-drag {
        box-shadow: 0 8px 24px rgba(0,0,0,0.2) !important;
        opacity: 1 !important;
    }
    .sorting-active .block-sortable-item {
        transition: transform 0.1s ease-out !important;
    }
    .dark .sortable-ghost {
        background: #1e293b !important;
    }
    .dark .sortable-chosen {
        background: #334155 !important;
    }
</style>
<script>
    // Helper to get bioEditor Alpine component
    function getBioEditor() {
        const editorEl = document.querySelector('[x-data*="bioEditor"]');
        return editorEl ? Alpine.$data(editorEl) : null;
    }
    function linkBlock(block) {
        return {
            mode: block.mode || 'new',
            overlayOpen: false,
            minimized: block.id ? true : false, // Start minimized if already saved
            urlInput: block.url || '',
            iconUrl: block.thumbnail_url || '',
            iconUrlInput: '',
            saving: false,
            searching: false,
            query: '',
            results: [],
            init() {
                this.urlInput = block.url || '';
                this.iconUrl = block.thumbnail_url || '';
                // If block has both title and url, start minimized
                this.minimized = !!(block.id && block.title && block.url);
            },
            emitSave() {
                this.$dispatch('save-blocks');
            },
            emitSavePreview() {
                block.url = this.urlInput;
                // Don't auto-save on preview, just update local state
            },
            normalizeUrl() {
                if (!this.urlInput) return;
                const trimmed = this.urlInput.trim();
                if (!/^https?:\/\//i.test(trimmed)) {
                    this.urlInput = 'https://' + trimmed.replace(/^\/+/, '');
                }
                block.url = this.urlInput;
            },
            async saveLink() {
                if (!this.urlInput || !block.title) {
                    const bioEditor = getBioEditor();
                    if (bioEditor) bioEditor.showNotification('Please enter both title and URL', 'error');
                    return;
                }
                this.saving = true;
                try {
                    // Normalize URL first
                    this.normalizeUrl();
                    // Apply icon if set
                    if (this.iconUrl) {
                        block.thumbnail_url = this.iconUrl;
                    }
                    // Check if URL is external (not hel.ink) and needs shortlink
                    const url = this.urlInput.toLowerCase();
                    const isHelinkUrl = url.includes('hel.ink') || url.includes('localhost');
                    if (!isHelinkUrl && !block.link_id) {
                        // Create shortlink for external URL
                        try {
                            const shortenRes = await fetch('/bio/shorten', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                                },
                                credentials: 'same-origin',
                                body: JSON.stringify({
                                    url: this.urlInput,
                                    title: block.title
                                })
                            });
                            if (shortenRes.ok) {
                                const shortenData = await shortenRes.json();
                                block.link_id = shortenData.id;
                                // Store original URL and use shortlink as the displayed URL
                                block.original_url = this.urlInput;
                                block.url = shortenData.short_url;
                                this.urlInput = shortenData.short_url;
                                // Show notification
                                const bioEditor = getBioEditor();
                                if (bioEditor) {
                                    bioEditor.showNotification('Shortlink created: ' + shortenData.short_url, 'success');
                                }
                            } else {
                                const errText = await shortenRes.text();
                                console.warn('Shortlink creation failed:', shortenRes.status, errText);
                            }
                        } catch(shortenErr) {
                            console.warn('Could not create shortlink:', shortenErr);
                            // Continue saving even without shortlink
                        }
                    }
                    // Save the block directly
                    const bioEditor = getBioEditor();
                    if (bioEditor) {
                        if (block.id > 1000000000) {
                            await bioEditor.createBlock(block);
                        } else {
                            await bioEditor.updateBlock(block);
                        }
                        bioEditor.showNotification('Link saved successfully!', 'success');
                    }
                    // Minimize after save
                    setTimeout(() => {
                        this.minimized = true;
                    }, 500);
                } catch(e) { 
                    console.error('Save link error:', e);
                    const bioEditor = getBioEditor();
                    if (bioEditor) {
                        bioEditor.showNotification(e.message || 'Failed to save link', 'error');
                    }
                }
                finally { 
                    this.saving = false; 
                }
            },
            async searchLibrary() {
                if (!this.query.trim()) {
                    this.results = [];
                    return;
                }
                this.searching = true;
                try {
                    // Use admin web route for library search
                    const res = await fetch(`/bio/links/search?query=${encodeURIComponent(this.query)}`, {
                        method: 'GET',
                        headers: { 
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        credentials: 'same-origin'
                    });
                    if (!res.ok) {
                        console.warn('Library search failed:', res.status);
                        this.results = [];
                        return;
                    }
                    const data = await res.json();
                    this.results = Array.isArray(data) ? data : (data.data || data.items || []);
                } catch(e) {
                    console.error('Search error:', e);
                    this.results = [];
                } finally {
                    this.searching = false;
                }
            },
            selectLibrary(item) {
                block.url = item.short_url || item.url;
                block.title = item.title || block.title;
                block.link_id = item.id;
                this.urlInput = block.url;
                this.results = [];
                this.emitSave();
                // Minimize after selection
                setTimeout(() => {
                    this.minimized = true;
                }, 300);
            },
            async uploadIcon(e) {
                const file = e.target.files?.[0];
                if (!file) return;
                if (file.size > 2 * 1024 * 1024) {
                    const bioEditor = getBioEditor();
                    if (bioEditor) bioEditor.showNotification('Image must be less than 2MB', 'error');
                    return;
                }
                try {
                    const formData = new FormData();
                    formData.append('image', file);
                    const response = await fetch('{{ route('bio.upload-image', $bioPage) }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });
                    if (response.ok) {
                        const data = await response.json();
                        this.iconUrl = data.url || data.path;
                        block.thumbnail_url = this.iconUrl;
                    }
                } catch(err) {
                    console.error('Upload icon error:', err);
                }
            },
            setIconFromUrl() {
                if (this.iconUrlInput) {
                    this.iconUrl = this.iconUrlInput;
                }
            },
            applyIcon() {
                block.thumbnail_url = this.iconUrl;
                this.emitSave();
            }
        }
    }
    // Text Editor with Partial Formatting & Keyboard Shortcuts
    function textEditor(block) {
        return {
            isBold: false,
            isItalic: false,
            isUnderline: false,
            isStrike: false,
            currentAlign: 'left',
            textColor: '#000000',
            debounceTimer: null,
            saving: false,
            minimized: false,
            init() {
                // Start minimized if already has content (existing block)
                if (block.content && block.id < 1000000000) {
                    this.minimized = true;
                }
                // Load existing content
                this.$nextTick(() => {
                    if (this.$refs.editor && block.content) {
                        this.$refs.editor.innerHTML = block.content;
                    }
                    // Set initial alignment
                    if (block.formatting?.align) {
                        this.currentAlign = block.formatting.align;
                    }
                });
            },
            getPlainText() {
                if (!block.content) return '';
                const temp = document.createElement('div');
                temp.innerHTML = block.content;
                return temp.textContent || temp.innerText || '';
            },
            expand() {
                this.minimized = false;
                this.$nextTick(() => {
                    if (this.$refs.editor && block.content) {
                        this.$refs.editor.innerHTML = block.content;
                    }
                    if (block.formatting?.align) {
                        this.currentAlign = block.formatting.align;
                    }
                });
            },
            minimize() {
                block.content = this.$refs.editor?.innerHTML || block.content;
                this.minimized = true;
            },
            onInput() {
                // Just update block content, don't auto-save
                block.content = this.$refs.editor.innerHTML;
                block.formatting = {
                    align: this.currentAlign
                };
            },
            async saveText() {
                this.saving = true;
                try {
                    // Make sure content is synced
                    block.content = this.$refs.editor.innerHTML;
                    block.formatting = { align: this.currentAlign };
                    // Set default title if empty
                    if (!block.title) {
                        block.title = 'Text Block';
                    }
                    // Save the block directly
                    const bioEditor = getBioEditor();
                    if (bioEditor) {
                        if (block.id > 1000000000) {
                            await bioEditor.createBlock(block);
                        } else {
                            await bioEditor.updateBlock(block);
                        }
                        bioEditor.showNotification('Text saved successfully!', 'success');
                        // Minimize after save
                        this.minimized = true;
                    }
                } catch(e) {
                    console.error('Save text error:', e);
                    const bioEditor = getBioEditor();
                    if (bioEditor) {
                        bioEditor.showNotification(e.message || 'Failed to save text', 'error');
                    }
                } finally {
                    this.saving = false;
                }
            },
            handleKeydown(e) {
                // Keyboard shortcuts
                if (e.ctrlKey || e.metaKey) {
                    switch(e.key.toLowerCase()) {
                        case 'b':
                            e.preventDefault();
                            this.formatBold();
                            break;
                        case 'i':
                            e.preventDefault();
                            this.formatItalic();
                            break;
                        case 'u':
                            e.preventDefault();
                            this.formatUnderline();
                            break;
                    }
                }
            },
            checkFormatState() {
                // Check current selection formatting
                this.isBold = document.queryCommandState('bold');
                this.isItalic = document.queryCommandState('italic');
                this.isUnderline = document.queryCommandState('underline');
                this.isStrike = document.queryCommandState('strikeThrough');
            },
            formatBold() {
                document.execCommand('bold', false, null);
                this.checkFormatState();
                this.$refs.editor.focus();
            },
            formatItalic() {
                document.execCommand('italic', false, null);
                this.checkFormatState();
                this.$refs.editor.focus();
            },
            formatUnderline() {
                document.execCommand('underline', false, null);
                this.checkFormatState();
                this.$refs.editor.focus();
            },
            formatStrike() {
                document.execCommand('strikeThrough', false, null);
                this.checkFormatState();
                this.$refs.editor.focus();
            },
            formatAlign(align) {
                this.currentAlign = align;
                this.$refs.editor.style.textAlign = align;
                // Also apply to selection if any
                const alignCommand = {
                    'left': 'justifyLeft',
                    'center': 'justifyCenter',
                    'right': 'justifyRight',
                    'justify': 'justifyFull'
                };
                document.execCommand(alignCommand[align], false, null);
                this.$refs.editor.focus();
            },
            formatFontSize(size) {
                document.execCommand('fontSize', false, size);
                this.$refs.editor.focus();
            },
            formatColor(color) {
                document.execCommand('foreColor', false, color);
                this.$refs.editor.focus();
            }
        }
    }
    // Image Block with File & URL Support
    function imageBlock(block) {
        return {
            uploadMode: 'file',
            imageUrl: '',
            previewUrl: block.thumbnail_url || '',
            uploading: false,
            saving: false,
            minimized: !!(block.id && block.thumbnail_url), // Start minimized if already has image
            init() {
                if (block.thumbnail_url) {
                    this.previewUrl = block.thumbnail_url.startsWith('http') 
                        ? block.thumbnail_url 
                        : '/storage/' + block.thumbnail_url;
                }
                // Start minimized if already saved with image
                this.minimized = !!(block.id && block.id < 1000000000 && block.thumbnail_url);
            },
            emitSave() {
                // Don't auto-dispatch, use saveImage instead
            },
            async saveImage() {
                if (!this.previewUrl) {
                    const bioEditor = getBioEditor();
                    if (bioEditor) bioEditor.showNotification('Please upload or enter an image URL first', 'error');
                    return;
                }
                this.saving = true;
                try {
                    // Set default title if empty
                    if (!block.title) {
                        block.title = 'Image Block';
                    }
                    // Save the block directly
                    const bioEditor = getBioEditor();
                    if (bioEditor) {
                        if (block.id > 1000000000) {
                            await bioEditor.createBlock(block);
                        } else {
                            await bioEditor.updateBlock(block);
                        }
                        bioEditor.showNotification('Image saved successfully!', 'success');
                        // Minimize after save
                        setTimeout(() => {
                            this.minimized = true;
                        }, 500);
                    }
                } catch(e) {
                    console.error('Save image error:', e);
                    const bioEditor = getBioEditor();
                    if (bioEditor) {
                        bioEditor.showNotification(e.message || 'Failed to save image', 'error');
                    }
                } finally {
                    this.saving = false;
                }
            },
            async uploadFile(e) {
                const file = e.target.files?.[0];
                if (!file) return;
                if (file.size > 2 * 1024 * 1024) {
                    const bioEditor = getBioEditor();
                    if (bioEditor) bioEditor.showNotification('Image must be less than 2MB', 'error');
                    return;
                }
                this.uploading = true;
                try {
                    const formData = new FormData();
                    formData.append('image', file);
                    const response = await fetch('{{ route('bio.upload-image', $bioPage) }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });
                    if (response.ok) {
                        const data = await response.json();
                        const url = data.url || data.path;
                        block.thumbnail_url = url;
                        this.previewUrl = url.startsWith('http') ? url : '/storage/' + url;
                        // Don't auto-save, user will click Save button
                        const bioEditor = getBioEditor();
                        if (bioEditor) bioEditor.showNotification('Image uploaded successfully!', 'success');
                    } else {
                        const bioEditor = getBioEditor();
                        if (bioEditor) bioEditor.showNotification('Upload failed. Please try again.', 'error');
                    }
                } catch(err) {
                    console.error('Upload error:', err);
                    const bioEditor = getBioEditor();
                    if (bioEditor) bioEditor.showNotification('Upload failed. Please try again.', 'error');
                } finally {
                    this.uploading = false;
                }
            },
            applyUrl() {
                if (this.imageUrl) {
                    block.thumbnail_url = this.imageUrl;
                    this.previewUrl = this.imageUrl;
                    // Don't auto-save, user will click Save button
                }
            },
            clearImage() {
                block.thumbnail_url = null;
                this.previewUrl = '';
                this.imageUrl = '';
            }
        }
    }
</script>
