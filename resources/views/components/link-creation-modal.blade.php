@props(['folders', 'tags', 'isAdmin' => false])
<div
    x-data="linkCreationModal()"
    x-init="init()"
    x-cloak
    x-show="panel"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-40 flex items-center justify-center p-4"
    @keydown.escape.window="if (!showOgEditor && !showFolderModal && !showTagModal && !showQrEditor) { panel = false; }"
>
    <div @click="if (!showOgEditor && !showFolderModal && !showTagModal && !showQrEditor) { closePanel(); }" class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm"></div>
    <div
        x-show="!showOgEditor && !showFolderModal && !showTagModal && !showQrEditor"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="relative z-10 w-full max-w-5xl max-h-[90vh] rounded-2xl bg-white shadow-2xl dark:bg-slate-900 flex flex-col"
        role="dialog"
        aria-modal="true"
        @click.stop
    >
        <div x-show="createdLink" class="flex flex-col h-full">
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4 dark:border-slate-800">
                <div>
                    <h3 class="text-lg font-semibold text-emerald-600 dark:text-emerald-400">🎉 Link Created Successfully!</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Your shortlink is ready to use</p>
                </div>
                <button @click="closePanel()" class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-800 dark:hover:text-white">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto p-6">
                <div class="max-w-2xl mx-auto space-y-4">
                    <div class="rounded-xl border-2 border-emerald-300 bg-emerald-50 p-5 dark:border-emerald-800 dark:bg-emerald-900/20">
                        <label class="block text-sm font-medium text-emerald-800 dark:text-emerald-300 mb-2">Your Short URL</label>
                        <div class="flex items-center gap-3">
                            <input 
                                type="text" 
                                :value="createdLink?.short_url" 
                                readonly 
                                class="flex-1 rounded-lg border-0 bg-white px-4 py-3 font-mono text-lg font-semibold text-emerald-700 dark:bg-slate-800 dark:text-emerald-400"
                            >
                            <button 
                                @click="copyToClipboard(createdLink?.short_url, $el)"
                                class="rounded-lg bg-emerald-600 px-6 py-3 font-semibold text-white hover:bg-emerald-500 transition-colors whitespace-nowrap"
                            >
                                📋 Copy
                            </button>
                        </div>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-white p-4 dark:border-slate-700 dark:bg-slate-900" x-show="createdLink?.qr_code_url">
                        <h4 class="text-sm font-semibold text-slate-900 dark:text-white mb-3">QR Code</h4>
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <img :src="createdLink?.qr_code_url" alt="QR Code" class="w-[150px] h-[150px] object-contain rounded-lg border border-slate-200 dark:border-slate-700 bg-white">
                            </div>
                            <div class="flex-1 space-y-4">
                                <p class="text-xs text-slate-500 dark:text-slate-400">Download your custom QR code in multiple formats</p>
                                <div class="flex flex-wrap gap-2">
                                    <a 
                                        :href="`/links/${createdLink?.id}/qr/png`" 
                                        download 
                                        class="inline-flex items-center gap-1.5 rounded-lg bg-slate-900 px-4 py-2 text-xs font-semibold text-white hover:bg-slate-700 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-slate-200 transition-colors"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        PNG
                                    </a>
                                    <a 
                                        :href="`/links/${createdLink?.id}/qr/jpg`" 
                                        download 
                                        class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-4 py-2 text-xs font-semibold text-white hover:bg-blue-500 transition-colors"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        JPG
                                    </a>
                                    <a 
                                        :href="`/links/${createdLink?.id}/qr/svg`" 
                                        download 
                                        class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-500 transition-colors"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        SVG
                                    </a>
                                </div>
                                <p class="text-xs text-slate-400 dark:text-slate-500">💡 SVG format is scalable for print quality</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <button 
                            @click="resetForm()"
                            class="flex-1 rounded-lg border border-slate-300 px-4 py-3 font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-800"
                        >
                            Create Another Link
                        </button>
                        <button 
                            @click="closePanel(); location.reload()"
                            class="flex-1 rounded-lg bg-blue-600 px-4 py-3 font-semibold text-white hover:bg-blue-500"
                        >
                            View Dashboard
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div x-show="!createdLink" class="flex flex-col h-full">
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4 dark:border-slate-800">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Create New Link</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Shorten and customize your URL</p>
                </div>
                <button @click="closePanel()" class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-800 dark:hover:text-white">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="flex-1 overflow-hidden">
                <div class="grid lg:grid-cols-2 h-full overflow-y-auto">
                    <div class="border-r border-slate-200 dark:border-slate-800">
                        <div class="p-6">
                            <form @submit.prevent="submitForm()" class="space-y-5">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    Destination URL
                                    <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="url" 
                                    x-model="formData.target_url"
                                    @input="debouncedFetchOg(); if(!formData.slug) { debouncedGenerateSlug(); }"
                                    placeholder="https://example.com"
                                    required
                                    class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-blue-500 focus:ring-blue-500"
                                >
                                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Enter the long URL you want to shorten</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    Short Link
                                </label>
                                <div class="flex gap-2">
                                    <input 
                                        type="text" 
                                        x-model="formData.slug"
                                        @input="generateQrPreview()"
                                        placeholder="custom-slug"
                                        pattern="[a-zA-Z0-9_-]+"
                                        class="flex-1 rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-blue-500 focus:ring-blue-500"
                                    >
                                    <button 
                                        type="button"
                                        @click="generateRandomSlug()"
                                        class="px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-sm font-medium"
                                    >
                                        🎲 Random
                                    </button>
                                </div>
                                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Leave empty for auto-generated slug</p>
                            </div>
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                            Tags
                                        </label>
                                        <span x-show="tagSuccess" x-transition class="text-xs text-emerald-600 dark:text-emerald-400">✓ Tag created!</span>
                                    </div>
                                    <button 
                                        type="button"
                                        @click="showTagModal = true"
                                        class="text-xs text-blue-600 dark:text-blue-400 hover:underline"
                                    >
                                        + New Tag
                                    </button>
                                </div>
                                <div x-data="{ isOpen: false, search: '' }" class="relative" @click.away="isOpen = false; search = ''">
                                    <button 
                                        type="button"
                                        @click="isOpen = !isOpen"
                                        class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-blue-500 focus:ring-blue-500 px-3 py-2 text-left flex items-center justify-between border"
                                    >
                                        <span class="text-sm" x-text="formData.tags.length === 0 ? 'No Tags Selected' : formData.tags.length + ' tag(s) selected'"></span>
                                        <svg class="w-4 h-4 transition-transform" :class="isOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <div 
                                        x-show="isOpen"
                                        x-transition
                                        class="absolute z-50 w-full mt-1 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg shadow-xl overflow-hidden"
                                    >
                                        <div class="p-2 border-b border-slate-200 dark:border-slate-700">
                                            <input 
                                                type="text" 
                                                x-model="search"
                                                placeholder="Search tags..."
                                                class="w-full px-3 py-1.5 text-sm rounded border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white focus:border-blue-500 focus:ring-blue-500"
                                                @click.stop
                                            >
                                        </div>
                                        <div class="overflow-y-auto" style="max-height: 160px;">
                                            <template x-if="tags.length === 0">
                                                <p class="text-xs text-slate-500 dark:text-slate-400 text-center py-4">No tags yet</p>
                                            </template>
                                            <template x-for="tag in tags.filter(t => !search || t.name.toLowerCase().includes(search.toLowerCase()))" :key="tag.id">
                                                <label class="flex items-center justify-between px-3 py-2.5 hover:bg-slate-100 dark:hover:bg-slate-700 cursor-pointer border-b border-slate-100 dark:border-slate-700 last:border-b-0">
                                                    <span class="text-sm text-slate-700 dark:text-slate-300" x-text="tag.name"></span>
                                                    <input 
                                                        type="checkbox" 
                                                        :value="tag.id"
                                                        :checked="formData.tags.includes(tag.id)"
                                                        @change="
                                                            if ($event.target.checked) {
                                                                if (!formData.tags.includes(tag.id)) formData.tags.push(tag.id);
                                                            } else {
                                                                formData.tags = formData.tags.filter(id => id !== tag.id);
                                                            }
                                                        "
                                                        class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 dark:border-slate-600"
                                                    >
                                                </label>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    Comments
                                </label>
                                <textarea 
                                    x-model="formData.comment"
                                    rows="3"
                                    placeholder="Add internal notes..."
                                    class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-blue-500 focus:ring-blue-500"
                                ></textarea>
                            </div>
                            <div>
                                <button 
                                    type="button"
                                    @click="showAdvanced = !showAdvanced"
                                    class="flex items-center gap-2 text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white"
                                >
                                    <svg class="h-4 w-4 transition-transform" :class="{ 'rotate-90': showAdvanced }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                    Advanced Options
                                </button>
                            </div>
                            <div x-show="showAdvanced" x-collapse class="space-y-4 pt-2">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        Expiration Date
                                    </label>
                                    <input 
                                        type="datetime-local" 
                                        x-model="formData.expires_at"
                                        class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-blue-500 focus:ring-blue-500"
                                    >
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        Password Protection
                                    </label>
                                    <input 
                                        type="password" 
                                        x-model="formData.password"
                                        placeholder="Leave empty for no password"
                                        class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-blue-500 focus:ring-blue-500"
                                    >
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        Redirect Type
                                    </label>
                                    <select 
                                        x-model="formData.redirect_type"
                                        class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-blue-500 focus:ring-blue-500"
                                    >
                                        <option value="302">302 (Temporary)</option>
                                        <option value="301">301 (Permanent)</option>
                                        <option value="307">307 (Temporary, Keep Method)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="pt-4">
                                <button 
                                    type="submit"
                                    :disabled="isLoading || !formData.target_url"
                                    class="w-full rounded-lg bg-blue-600 px-4 py-3 font-semibold text-white hover:bg-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                >
                                    <span x-show="!isLoading">Create Link</span>
                                    <span x-show="isLoading" class="flex items-center justify-center gap-2">
                                        <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Creating...
                                    </span>
                                </button>
                            </div>
                        </form>
                        </div>
                    </div>
                    <div class="h-full overflow-y-auto bg-white dark:bg-slate-800">
                        <div class="p-6 space-y-6">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                            Folder
                                        </label>
                                        <span x-show="folderSuccess" x-transition class="text-xs text-emerald-600 dark:text-emerald-400">✓ Folder created!</span>
                                    </div>
                                    <button 
                                        type="button"
                                        @click="showFolderModal = true"
                                        class="text-xs text-blue-600 dark:text-blue-400 hover:underline"
                                    >
                                        + New Folder
                                    </button>
                                </div>
                                <div x-data="{ isOpen: false, search: '' }" class="relative" @click.away="isOpen = false; search = ''">
                                    <button 
                                        type="button"
                                        @click="isOpen = !isOpen"
                                        class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-blue-500 focus:ring-blue-500 px-3 py-2 text-left flex items-center justify-between border"
                                    >
                                        <span class="text-sm" x-text="folders.find(f => f.id == formData.folder_id)?.name || 'Select Folder'"></span>
                                        <svg class="w-4 h-4 transition-transform" :class="isOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <div 
                                        x-show="isOpen"
                                        x-transition
                                        class="absolute z-50 w-full mt-1 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg shadow-xl overflow-hidden"
                                    >
                                        <div class="p-2 border-b border-slate-200 dark:border-slate-700">
                                            <input 
                                                type="text" 
                                                x-model="search"
                                                placeholder="Search folders..."
                                                class="w-full px-3 py-1.5 text-sm rounded border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white focus:border-blue-500 focus:ring-blue-500"
                                                @click.stop
                                            >
                                        </div>
                                        <div class="overflow-y-auto" style="max-height: 160px;">
                                            <template x-for="folder in folders.filter(f => !search || f.name.toLowerCase().includes(search.toLowerCase()))" :key="folder.id">
                                                <button
                                                    type="button"
                                                    @click="formData.folder_id = folder.id; isOpen = false; search = ''"
                                                    class="w-full text-left px-3 py-2.5 hover:bg-slate-100 dark:hover:bg-slate-700 border-b border-slate-100 dark:border-slate-700 last:border-b-0"
                                                    :class="formData.folder_id == folder.id ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-slate-700 dark:text-slate-300'"
                                                >
                                                    <span class="text-sm" x-text="folder.name"></span>
                                                </button>
                                            </template>
                                            <template x-if="folders.filter(f => !search || f.name.toLowerCase().includes(search.toLowerCase())).length === 0">
                                                <p class="text-xs text-slate-500 dark:text-slate-400 text-center py-4">No folders found</p>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                        QR Code Preview
                                    </label>
                                </div>
                                <div class="space-y-3">
                                    <button 
                                        type="button"
                                        @click="qrSettings.generateQr = !qrSettings.generateQr; if(qrSettings.generateQr) generateQrPreview();"
                                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg border transition-all duration-200"
                                        :class="qrSettings.generateQr 
                                            ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400' 
                                            : 'border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:border-emerald-400 hover:bg-emerald-50 dark:hover:bg-slate-700'"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="qrSettings.generateQr">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="!qrSettings.generateQr">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        <span class="text-sm font-medium" x-text="qrSettings.generateQr ? 'QR Enabled' : 'Generate QR'"></span>
                                    </button>
                                    <button 
                                        type="button"
                                        @click.prevent="showQrEditor = true"
                                        x-show="qrSettings.generateQr"
                                        class="w-full flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors border border-blue-200 dark:border-blue-800"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                                        </svg>
                                        Customize QR
                                    </button>
                                </div>
                                <div class="rounded-xl border-2 border-dashed border-slate-300 dark:border-slate-600 p-4 text-center bg-white dark:bg-slate-800" :style="'background-color: ' + qrSettings.bgColor" x-show="qrSettings.generateQr">
                                    <div x-show="formData.slug" class="space-y-2">
                                        <div class="relative inline-block">
                                            <img :src="qrPreviewUrl" alt="QR Preview" class="w-32 h-32 mx-auto rounded-lg border border-slate-200 dark:border-slate-700">
                                            <div x-show="qrSettings.showLogo && qrSettings.logo" class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                                <div class="w-11 h-11 rounded-lg bg-white dark:bg-slate-900 p-1 shadow-lg border border-slate-200 dark:border-slate-700">
                                                    <img :src="qrSettings.logo" alt="Logo" class="w-full h-full object-contain rounded">
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">QR will be generated after link creation</p>
                                    </div>
                                    <div x-show="!formData.slug" class="py-8">
                                        <svg class="w-16 h-16 mx-auto text-slate-300 dark:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                        </svg>
                                        <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">Enter URL and slug to preview QR</p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                        Link Preview
                                    </label>
                                    <button 
                                        type="button"
                                        @click.prevent="showOgEditor = true"
                                        class="flex items-center gap-1 px-2 py-1 rounded-md text-xs font-medium text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors"
                                    >
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                        Custom Preview
                                    </button>
                                </div>
                                <div class="flex gap-1 mb-3 p-1 bg-slate-700 dark:bg-slate-700 rounded-lg">
                                    <button 
                                        type="button"
                                        @click="previewPlatform = 'default'"
                                        :class="previewPlatform === 'default' ? 'bg-blue-600 text-white shadow' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white'"
                                        class="flex-1 px-3 py-1.5 rounded-md text-xs font-medium transition-colors"
                                    >
                                        🌐 Default
                                    </button>
                                    <button 
                                        type="button"
                                        @click="previewPlatform = 'twitter'"
                                        :class="previewPlatform === 'twitter' ? 'bg-blue-600 text-white shadow' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white'"
                                        class="flex-1 px-3 py-1.5 rounded-md text-xs font-medium transition-colors"
                                    >
                                        𝕏 Twitter
                                    </button>
                                    <button 
                                        type="button"
                                        @click="previewPlatform = 'linkedin'"
                                        :class="previewPlatform === 'linkedin' ? 'bg-blue-600 text-white shadow' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white'"
                                        class="flex-1 px-3 py-1.5 rounded-md text-xs font-medium transition-colors"
                                    >
                                        💼 LinkedIn
                                    </button>
                                    <button 
                                        type="button"
                                        @click="previewPlatform = 'facebook'"
                                        :class="previewPlatform === 'facebook' ? 'bg-blue-600 text-white shadow' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white'"
                                        class="flex-1 px-3 py-1.5 rounded-md text-xs font-medium transition-colors"
                                    >
                                        📘 Facebook
                                    </button>
                                </div>
                                <div class="rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden bg-white dark:bg-slate-800">
                                    <div x-show="isLoading && !ogData.title" class="p-8 text-center">
                                        <svg class="animate-spin h-8 w-8 mx-auto text-blue-600" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Fetching preview...</p>
                                    </div>
                                    <div x-show="!isLoading || ogData.title">
                                        <div class="px-3 py-2 bg-slate-50 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700">
                                            <p class="text-xs text-slate-600 dark:text-slate-400" x-text="getPreviewSize(previewPlatform).label"></p>
                                        </div>
                                        <div x-show="ogData.image" class="bg-slate-100 dark:bg-slate-900 flex items-center justify-center" :style="`width: 400px; height: ${400 * getPreviewSize(previewPlatform).height / getPreviewSize(previewPlatform).width}px; margin: 0 auto; overflow: hidden;`">
                                            <img :src="ogData.image" :alt="ogData.title" class="max-w-full max-h-full object-contain">
                                        </div>
                                        <div class="p-4">
                                            <p class="text-sm font-semibold text-slate-900 dark:text-white line-clamp-2" x-text="ogData.title || 'Link Title'"></p>
                                            <p class="text-xs text-slate-600 dark:text-slate-400 mt-2 line-clamp-3" x-text="ogData.description || 'Link description will appear here when you enter a URL or add custom text'"></p>
                                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-3">hel.ink</p>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-2 text-xs text-center text-slate-500 dark:text-slate-400">
                                    How your link will appear on <span x-text="previewPlatform === 'default' ? 'social platforms' : previewPlatform.charAt(0).toUpperCase() + previewPlatform.slice(1)"></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div
        x-show="showOgEditor"
        x-transition
        class="fixed inset-0 z-[200] flex items-center justify-center p-4"
        @click="showOgEditor = false"
        style="display: none;"
    >
        <div class="absolute inset-0 bg-black/90 backdrop-blur-sm"></div>
        <div 
            @click.stop
            class="relative z-[210] w-full max-w-4xl rounded-xl bg-white dark:bg-slate-800 shadow-2xl max-h-[85vh] overflow-hidden"
        >
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-white dark:bg-slate-800">
                <h4 class="text-lg font-semibold text-slate-900 dark:text-white">Custom Link Preview</h4>
                <button @click="showOgEditor = false" class="rounded-lg p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-700 dark:hover:text-white">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="grid grid-cols-2 gap-6 p-6 max-h-[calc(85vh-80px)] overflow-y-auto">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Image URL
                        </label>
                        <input 
                            type="url" 
                            x-model="ogData.image"
                            placeholder="https://example.com/image.jpg"
                            class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white focus:border-blue-500 focus:ring-blue-500 text-sm"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Title <span class="text-slate-400 font-normal text-xs">(<span x-text="ogData.title.length"></span>/120)</span>
                        </label>
                        <input 
                            type="text" 
                            x-model="ogData.title"
                            maxlength="120"
                            placeholder="Enter a custom title"
                            class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white focus:border-blue-500 focus:ring-blue-500 text-sm"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Description <span class="text-slate-400 font-normal text-xs">(<span x-text="ogData.description.length"></span>/240)</span>
                        </label>
                        <textarea 
                            x-model="ogData.description"
                            maxlength="240"
                            rows="4"
                            placeholder="Enter a custom description"
                            class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white focus:border-blue-500 focus:ring-blue-500 text-sm"
                        ></textarea>
                    </div>
                    <div class="flex gap-3 pt-4">
                        <button 
                            type="button"
                            @click="resetOgData()"
                            class="flex-1 rounded-lg border border-slate-300 dark:border-slate-600 px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors"
                        >
                            Reset
                        </button>
                        <button 
                            type="button"
                            @click="showOgEditor = false"
                            class="flex-1 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-500 transition-colors"
                        >
                            Save Changes
                        </button>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Live Preview</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400" x-text="getPreviewSize(previewPlatform).label"></p>
                    </div>
                    <div class="rounded-xl border-2 border-slate-200 dark:border-slate-700 overflow-hidden bg-white dark:bg-slate-900">
                        <div class="bg-slate-100 dark:bg-slate-900 flex items-center justify-center" :style="`width: 100%; height: ${450 * getPreviewSize(previewPlatform).height / getPreviewSize(previewPlatform).width}px; max-width: 450px; margin: 0 auto;`">
                            <img 
                                x-show="ogData.image" 
                                :src="ogData.image" 
                                alt="Preview" 
                                class="max-w-full max-h-full object-contain"
                            >
                            <div x-show="!ogData.image" class="text-slate-400 dark:text-slate-600 text-sm">
                                No image
                            </div>
                        </div>
                        <div class="p-4 bg-white dark:bg-slate-800">
                            <p class="text-sm font-semibold text-slate-900 dark:text-white line-clamp-2 mb-2" x-text="ogData.title || 'Link Title'"></p>
                            <p class="text-xs text-slate-600 dark:text-slate-400 line-clamp-3" x-text="ogData.description || 'Link description will appear here'"></p>
                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-3">hel.ink</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div
        x-show="showFolderModal"
        x-transition
        class="fixed inset-0 z-[200] flex items-center justify-center p-4"
        @click="showFolderModal = false"
        style="display: none;"
    >
        <div class="absolute inset-0 bg-black/90 backdrop-blur-sm"></div>
        <div 
            @click.stop
            class="relative z-[210] w-full rounded-xl bg-white dark:bg-slate-800 shadow-2xl p-5"
            style="max-width: 400px;"
        >
            <h4 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Create New Folder</h4>
            <form @submit.prevent="createFolder()">
                <input 
                    type="text" 
                    x-model="newFolderName"
                    placeholder="Folder name"
                    required
                    class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white focus:border-blue-500 focus:ring-blue-500 mb-4"
                >
                <div class="flex gap-3">
                    <button 
                        type="button"
                        @click="showFolderModal = false"
                        class="flex-1 rounded-lg border border-slate-300 dark:border-slate-600 px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit"
                        class="flex-1 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-500"
                    >
                        Create
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div
        x-show="showTagModal"
        x-transition
        class="fixed inset-0 z-[200] flex items-center justify-center p-4"
        @click="showTagModal = false"
        style="display: none;"
    >
        <div class="absolute inset-0 bg-black/90 backdrop-blur-sm"></div>
        <div 
            @click.stop
            class="relative z-[210] w-full rounded-xl bg-white dark:bg-slate-800 shadow-2xl p-5"
            style="max-width: 400px;"
        >
            <h4 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Create New Tag</h4>
            <form @submit.prevent="createTag()">
                <input 
                    type="text" 
                    x-model="newTagName"
                    placeholder="Tag name"
                    required
                    class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white focus:border-blue-500 focus:ring-blue-500 mb-4"
                >
                <div class="flex gap-3">
                    <button 
                        type="button"
                        @click="showTagModal = false"
                        class="flex-1 rounded-lg border border-slate-300 dark:border-slate-600 px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit"
                        class="flex-1 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-500"
                    >
                        Create
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div
        x-show="showQrEditor"
        x-transition
        class="fixed inset-0 z-[200] flex items-center justify-center p-4"
        @click="showQrEditor = false"
        style="display: none;"
    >
        <div class="absolute inset-0 bg-black/90 backdrop-blur-sm"></div>
        <div 
            @click.stop
            class="relative z-[210] w-full max-w-md rounded-2xl bg-white dark:bg-slate-800 shadow-2xl overflow-hidden max-h-[85vh]"
        >
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 px-6 py-4">
                <div>
                    <h4 class="text-lg font-semibold text-slate-900 dark:text-white">QR Code Design</h4>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Customize your QR code appearance</p>
                </div>
                <button @click="showQrEditor = false" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-700 dark:hover:text-white transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-5 space-y-4 overflow-y-auto max-h-[70vh]">
                <div class="text-center">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-3">Preview</label>
                    <div class="relative inline-block">
                        <div class="w-32 h-32 mx-auto rounded-xl border-2 border-slate-200 dark:border-slate-700 p-2" :style="'background-color: ' + qrSettings.bgColor">
                            <div class="relative w-full h-full flex items-center justify-center">
                                <img 
                                    :src="generateQrUrl()" 
                                    alt="QR Preview" 
                                    class="w-full h-full object-contain"
                                    x-show="formData.slug"
                                >
                                <div x-show="!formData.slug" class="text-slate-400 dark:text-slate-500 text-sm">
                                    Enter slug first
                                </div>
                                <div x-show="qrSettings.showLogo && qrSettings.logo" class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                    <div class="w-10 h-10 rounded-lg bg-white dark:bg-slate-900 p-1 shadow-lg border border-slate-200 dark:border-slate-700">
                                        <img :src="qrSettings.logo" alt="Logo" class="w-full h-full object-contain rounded">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-3">
                        QR Code Color
                    </label>
                    <div class="flex items-center gap-2 mb-3 flex-wrap">
                        <template x-for="color in qrColorPresets" :key="color">
                            <button
                                type="button"
                                @click="qrSettings.fgColor = color"
                                :class="qrSettings.fgColor === color ? 'ring-2 ring-offset-2 ring-blue-500 dark:ring-offset-slate-800' : ''"
                                class="w-10 h-10 rounded-lg border-2 border-slate-300 dark:border-slate-600 transition-all hover:scale-110"
                                :style="'background-color: ' + color"
                            ></button>
                        </template>
                        <button
                            type="button"
                            @click="showColorPicker = !showColorPicker"
                            class="w-10 h-10 rounded-lg border-2 border-slate-300 dark:border-slate-600 flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
                        >
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </button>
                    </div>
                    <div x-show="showColorPicker" class="flex items-center gap-2">
                        <input 
                            type="color" 
                            x-model="qrSettings.fgColor"
                            class="h-10 w-20 rounded border border-slate-300 dark:border-slate-600 cursor-pointer"
                        >
                        <input 
                            type="text" 
                            x-model="qrSettings.fgColor"
                            placeholder="#000000"
                            class="flex-1 rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white focus:border-blue-500 focus:ring-blue-500 text-sm"
                        >
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Background Color
                    </label>
                    <div class="flex items-center gap-2">
                        <input 
                            type="color" 
                            x-model="qrSettings.bgColor"
                            class="h-10 w-20 rounded border border-slate-300 dark:border-slate-600 cursor-pointer"
                        >
                        <input 
                            type="text" 
                            x-model="qrSettings.bgColor"
                            placeholder="#ffffff"
                            class="flex-1 rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white focus:border-blue-500 focus:ring-blue-500 text-sm"
                        >
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                            Add Logo <span class="text-xs text-slate-500 dark:text-slate-400">(optional)</span>
                        </label>
                        <button 
                            type="button"
                            @click="qrSettings.showLogo = !qrSettings.showLogo"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border transition-all duration-200"
                            :class="qrSettings.showLogo 
                                ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400' 
                                : 'border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:border-blue-400 hover:bg-blue-50 dark:hover:bg-slate-700'"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="qrSettings.showLogo">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="!qrSettings.showLogo">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span class="text-sm font-medium" x-text="qrSettings.showLogo ? 'Logo Enabled' : 'Add Logo'"></span>
                        </button>
                    </div>
                    <div x-show="qrSettings.showLogo" x-transition class="mt-2">
                        <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                            Logo URL
                        </label>
                        <input 
                            type="url" 
                            x-model="qrSettings.logo"
                            placeholder="https://example.com/logo.png"
                            class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white focus:border-blue-500 focus:ring-blue-500 text-sm"
                        >
                        <p class="mt-1.5 text-xs text-slate-500 dark:text-slate-400">
                            💡 Logo will be overlaid in the center of QR code. Use a square image with transparent background for best results.
                        </p>
                    </div>
                </div>
                <div class="flex gap-3 pt-4 border-t border-slate-200 dark:border-slate-700">
                    <button 
                        type="button"
                        @click="resetQrSettings()"
                        class="flex-1 rounded-lg border border-slate-300 dark:border-slate-600 px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors"
                    >
                        Reset
                    </button>
                    <button 
                        type="button"
                        @click="showQrEditor = false; generateQrPreview();"
                        class="flex-1 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-500 transition-colors shadow-lg shadow-blue-500/30"
                    >
                        Apply Design
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function linkCreationModal() {
    return {
        panel: false,
        showAdvanced: false,
        showOgEditor: false,
        showQrEditor: false,
        showFolderModal: false,
        showTagModal: false,
        createdLink: null,
        isLoading: false,
        previewPlatform: 'default',
        qrPreviewUrl: null,
        newFolderName: '',
        newTagName: '',
        ogFetchTimeout: null,
        slugGenerateTimeout: null,
        showColorPicker: false,
        qrColorPresets: ['#000000', '#1e40af', '#dc2626', '#059669', '#7c3aed', '#db2777'],
        folderSuccess: false,
        tagSuccess: false,
        folders: @json($folders),
        tags: @json($tags),
        qrSettings: {
            fgColor: '#000000',
            bgColor: '#ffffff',
            logo: '',
            showLogo: false,
            generateQr: false
        },
        formData: {
            target_url: '',
            slug: '',
            folder_id: '',
            tags: [],
            comment: '',
            status: 'active',
            expires_at: '',
            password: '',
            redirect_type: '302'
        },
        ogData: {
            title: '',
            description: '',
            image: ''
        },
        init() {
            // Set default folder (Default folder)
            const defaultFolder = this.folders.find(f => f.is_default);
            if (defaultFolder) {
                this.formData.folder_id = defaultFolder.id;
            }
            // Expose function immediately
            window.openLinkPanel = () => {
                this.panel = true;
                this.resetForm();
            };
        },
        closePanel() {
            this.panel = false;
            setTimeout(() => {
                this.resetForm();
            }, 300);
        },
        resetForm() {
            this.createdLink = null;
            // Set default folder again on reset
            const defaultFolder = this.folders.find(f => f.is_default);
            this.formData = {
                target_url: '',
                slug: '',
                folder_id: defaultFolder ? defaultFolder.id : '',
                tags: [],
                comment: '',
                status: 'active',
                expires_at: '',
                password: '',
                redirect_type: '302'
            };
            this.ogData = {
                title: '',
                description: '',
                image: ''
            };
            this.qrPreviewUrl = null;
            this.showAdvanced = false;
        },
        async submitForm() {
            if (!this.formData.target_url) {
                alert('Please enter a destination URL');
                return;
            }
            this.isLoading = true;
            const payload = {
                target_url: this.formData.target_url,
                slug: this.formData.slug || '',
                status: this.formData.status || 'active',
                folder_id: this.formData.folder_id || null,
                tags: this.formData.tags || [],
                expires_at: this.formData.expires_at || null,
                password: this.formData.password || null,
                redirect_type: this.formData.redirect_type || '302',
                comment: this.formData.comment || null,
                custom_title: this.ogData.title || null,
                custom_description: this.ogData.description || null,
                custom_image_url: this.ogData.image || null,
                qr_fg_color: this.qrSettings.generateQr ? (this.qrSettings.fgColor || '#000000') : null,
                qr_bg_color: this.qrSettings.generateQr ? (this.qrSettings.bgColor || '#ffffff') : null,
                qr_logo_url: this.qrSettings.generateQr && this.qrSettings.showLogo && this.qrSettings.logo ? this.qrSettings.logo : null,
                generate_qr: this.qrSettings.generateQr
            };
            try {
                const response = await fetch('{{ route('links.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(payload)
                });
                if (!response.ok) {
                    const errorData = await response.json().catch(async () => {
                        const errorText = await response.text();
                        console.error('Server error (text):', errorText);
                        return { error: errorText };
                    });
                    console.error('Server error (JSON):', errorData);
                    const errorMsg = errorData.message || errorData.error || `Server error: ${response.status}`;
                    alert(errorMsg);
                    this.isLoading = false;
                    this.isLoading = false;
                    return;
                }
                const result = await response.json();
                if (result.success) {
                    this.createdLink = result.link;
                } else {
                    const errorMsg = result.error || result.message || 'Failed to create link';
                    console.error('Creation failed:', errorMsg);
                    alert(errorMsg);
                }
            } catch (error) {
                console.error('Error creating link:', error);
                alert('An error occurred while creating the link. Check console for details.');
            } finally {
                this.isLoading = false;
            }
        },
        debouncedFetchOg() {
            clearTimeout(this.ogFetchTimeout);
            this.ogFetchTimeout = setTimeout(() => {
                // Auto-add https if missing
                if (this.formData.target_url && !this.formData.target_url.match(/^https?:\/\//)) {
                    this.formData.target_url = 'https://' + this.formData.target_url;
                }
                this.fetchOgData();
            }, 1000);
        },
        debouncedGenerateSlug() {
            clearTimeout(this.slugGenerateTimeout);
            this.slugGenerateTimeout = setTimeout(() => {
                this.generateRandomSlug();
            }, 1000);
        },
        detectPlatform(url) {
            if (!url) return 'default';
            const lowerUrl = url.toLowerCase();
            if (lowerUrl.includes('instagram.com')) return 'instagram';
            if (lowerUrl.includes('facebook.com') || lowerUrl.includes('fb.com')) return 'facebook';
            if (lowerUrl.includes('twitter.com') || lowerUrl.includes('x.com')) return 'twitter';
            if (lowerUrl.includes('linkedin.com')) return 'linkedin';
            if (lowerUrl.includes('tiktok.com')) return 'tiktok';
            return 'default';
        },
        getPreviewSize(platform) {
            const sizes = {
                'instagram': { width: 1080, height: 1080, label: 'Instagram Square (1080×1080)' },
                'facebook': { width: 1200, height: 630, label: 'Facebook Share (1200×630)' },
                'twitter': { width: 1200, height: 675, label: 'Twitter Card (1200×675)' },
                'linkedin': { width: 1200, height: 627, label: 'LinkedIn Share (1200×627)' },
                'tiktok': { width: 1080, height: 1920, label: 'TikTok Vertical (1080×1920)' },
                'default': { width: 1200, height: 630, label: 'Default OG (1200×630)' }
            };
            return sizes[platform] || sizes.default;
        },
        async fetchOgData() {
            if (!this.formData.target_url || this.formData.target_url.length < 10) return;
            this.isLoading = true;
            try {
                const response = await fetch('{{ route('links.fetch-og') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ url: this.formData.target_url })
                });
                const result = await response.json();
                if (result.success && result.data) {
                    this.ogData.title = result.data.title || '';
                    this.ogData.description = result.data.description || '';
                    this.ogData.image = result.data.image || '';
                }
            } catch (error) {
            } finally {
                this.isLoading = false;
            }
        },
        generateRandomSlug() {
            const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let slug = '';
            for (let i = 0; i < 6; i++) {
                slug += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            this.formData.slug = slug;
            this.generateQrPreview();
        },
        generateQrPreview() {
            if (this.formData.slug) {
                const baseUrl = '{{ url('') }}';
                const shortUrl = `${baseUrl}/${this.formData.slug}`;
                const fgColor = this.qrSettings.fgColor.replace('#', '');
                const bgColor = this.qrSettings.bgColor.replace('#', '');
                this.qrPreviewUrl = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(shortUrl)}&color=${fgColor}&bgcolor=${bgColor}`;
            } else {
                this.qrPreviewUrl = null;
            }
        },
        resetOgData() {
            this.fetchOgData();
        },
        async createFolder() {
            if (!this.newFolderName) return;
            try {
                const response = await fetch('{{ route('folders.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ name: this.newFolderName })
                });
                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({}));
                    const errorMsg = errorData.message || errorData.error || `Server error: ${response.status}`;
                    console.error('Folder creation failed:', errorData);
                    alert(errorMsg);
                    return;
                }
                const result = await response.json();
                if (result.success && result.folder) {
                    // Ensure folders array exists
                    if (!Array.isArray(this.folders)) {
                        this.folders = [];
                    }
                    // Add new folder to list dynamically
                    this.folders.push(result.folder);
                    // Set as selected
                    this.formData.folder_id = result.folder.id;
                    // Close modal
                    this.showFolderModal = false;
                    this.newFolderName = '';
                    // Show success message
                    this.folderSuccess = true;
                    setTimeout(() => { this.folderSuccess = false; }, 3000);
                } else {
                    alert(result.error || result.message || 'Failed to create folder');
                }
            } catch (error) {
                console.error('Error creating folder:', error);
                alert('Network error: ' + error.message);
            }
        },
        async createTag() {
            if (!this.newTagName) return;
            try {
                const response = await fetch('{{ route('tags.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ name: this.newTagName })
                });
                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({}));
                    const errorMsg = errorData.message || errorData.error || `Server error: ${response.status}`;
                    console.error('Tag creation failed:', errorData);
                    alert(errorMsg);
                    return;
                }
                const result = await response.json();
                if (result.success && result.tag) {
                    // Ensure tags array exists
                    if (!Array.isArray(this.tags)) {
                        this.tags = [];
                    }
                    // Add new tag to list dynamically
                    this.tags.push(result.tag);
                    // Set as selected
                    this.formData.tag_id = result.tag.id;
                    // Close modal
                    this.showTagModal = false;
                    this.newTagName = '';
                    // Show success message
                    this.tagSuccess = true;
                    setTimeout(() => { this.tagSuccess = false; }, 3000);
                } else {
                    alert(result.error || result.message || 'Failed to create tag');
                }
            } catch (error) {
                console.error('Error creating tag:', error);
                alert('Network error: ' + error.message);
            }
        },
        copyToClipboard(text, button) {
            navigator.clipboard.writeText(text);
            const original = button.innerHTML;
            button.innerHTML = '✓ Copied!';
            setTimeout(() => {
                button.innerHTML = original;
            }, 2000);
        },
        generateQrUrl() {
            if (!this.formData.slug) return '';
            const baseUrl = '{{ url('') }}';
            const shortUrl = `${baseUrl}/${this.formData.slug}`;
            const fgColor = this.qrSettings.fgColor.replace('#', '');
            const bgColor = this.qrSettings.bgColor.replace('#', '');
            return `https://api.qrserver.com/v1/create-qr-code/?size=400x400&data=${encodeURIComponent(shortUrl)}&color=${fgColor}&bgcolor=${bgColor}`;
        },
        resetQrSettings() {
            this.qrSettings = {
                fgColor: '#000000',
                bgColor: '#ffffff',
                logo: '',
                showLogo: false,
                generateQr: false
            };
            this.showColorPicker = false;
        }
    };
}
</script>
