@props(['show' => false])

<div
    x-show="{{ $show }}"
    x-transition
    class="fixed inset-0 z-[70] flex items-center justify-center p-4"
    @click="showQrEditor = false"
    style="display: none;"
>
    <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm"></div>
    <div 
        @click.stop
        class="relative z-10 w-full max-w-lg rounded-2xl bg-white dark:bg-slate-800 shadow-2xl overflow-hidden"
    >
        
        <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-white dark:bg-slate-800">
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
        
        <div class="p-6 space-y-5 bg-white dark:bg-slate-800">
            
            <div class="text-center">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-3">Preview</label>
                <div class="relative inline-block">
                    <div class="w-56 h-56 mx-auto rounded-xl border-2 border-slate-200 dark:border-slate-700 p-4 bg-white dark:bg-slate-900" :style="'background-color: ' + qrSettings.bgColor">
                        <div class="relative w-full h-full flex items-center justify-center">
                            
                            <img 
                                :src="generateQrUrl()" 
                                alt="QR Preview" 
                                class="w-full h-full object-contain"
                            >
                            
                            <div x-show="qrSettings.logo" class="absolute inset-0 flex items-center justify-center">
                                <div class="w-12 h-12 rounded-lg bg-white dark:bg-slate-900 p-1 shadow-lg border border-slate-200 dark:border-slate-700">
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
                <div class="flex items-center gap-2 mb-3">
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
                        Logo <span class="text-xs text-slate-500 dark:text-slate-400">(optional)</span>
                    </label>
                    <label class="inline-flex items-center cursor-pointer">
                        <input 
                            type="checkbox" 
                            x-model="qrSettings.showLogo"
                            class="sr-only peer"
                        >
                        <div class="relative w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-blue-600"></div>
                    </label>
                </div>
                <div x-show="qrSettings.showLogo">
                    <input 
                        type="url" 
                        x-model="qrSettings.logo"
                        placeholder="https://example.com/logo.png"
                        class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white focus:border-blue-500 focus:ring-blue-500 text-sm"
                    >
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Logo will be displayed in the center of QR code</p>
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
                    @click="showQrEditor = false"
                    class="flex-1 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-500 transition-colors shadow-lg shadow-blue-500/30"
                >
                    Apply Design
                </button>
            </div>
        </div>
    </div>
</div>
