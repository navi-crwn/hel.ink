<div class="space-y-4">
    <!-- Theme Selector (Simplified) -->
    <div class="rounded-lg bg-white p-4 shadow-sm dark:bg-slate-800">
        <div class="flex items-center gap-4">
            <label class="text-sm font-semibold text-slate-900 dark:text-white whitespace-nowrap">Theme</label>
            <select x-model="bioPage.theme" class="flex-1 rounded-lg border border-slate-300 px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                <option value="none">Default</option>
                <option value="classic-light">Classic Light</option>
                <option value="classic-dark">Classic Dark</option>
                <option value="modern-light">Modern Light</option>
                <option value="modern-dark">Modern Dark</option>
            </select>
        </div>
    </div>
    <!-- Row 2: Page Layout (Left) + Background (Right) -->
    <div class="grid grid-cols-2 gap-4">
        <!-- Page Layout -->
        <div class="rounded-lg bg-white p-4 shadow-sm dark:bg-slate-800">
            <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-3">Page Layout</h3>
            <div class="grid grid-cols-3 gap-2">
                <button @click="bioPage.layout = 'centered'" :class="bioPage.layout === 'centered' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-slate-300 dark:border-slate-700'" class="rounded border-2 p-2 text-center transition-all">
                    <div class="mx-auto h-8 w-6 rounded bg-slate-200 dark:bg-slate-700"></div>
                    <p class="mt-1 text-xs text-slate-900 dark:text-white">Center</p>
                </button>
                <button @click="bioPage.layout = 'left'" :class="bioPage.layout === 'left' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-slate-300 dark:border-slate-700'" class="rounded border-2 p-2 text-center transition-all">
                    <div class="h-8 w-6 rounded bg-slate-200 dark:bg-slate-700"></div>
                    <p class="mt-1 text-xs text-slate-900 dark:text-white">Left</p>
                </button>
                <button @click="bioPage.layout = 'wide'" :class="bioPage.layout === 'wide' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-slate-300 dark:border-slate-700'" class="rounded border-2 p-2 text-center transition-all">
                    <div class="mx-auto h-8 w-full rounded bg-slate-200 dark:bg-slate-700"></div>
                    <p class="mt-1 text-xs text-slate-900 dark:text-white">Wide</p>
                </button>
            </div>
            <!-- Header Background for Wide Layout -->
            <div x-show="bioPage.layout === 'wide'" x-cloak class="mt-3 border-t border-slate-200 dark:border-slate-700 pt-3">
                <label class="text-xs text-slate-600 dark:text-slate-400 mb-2 block">Header Background</label>
                <div class="flex gap-2">
                    <input type="color" x-model="bioPage.header_bg_color" class="h-8 w-12 cursor-pointer rounded border border-slate-300 dark:border-slate-700">
                    <input type="text" x-model="bioPage.header_bg_color" placeholder="#667eea" class="flex-1 rounded border border-slate-300 px-2 py-1 text-xs dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                </div>
            </div>
        </div>
        <!-- Background -->
        <div class="rounded-lg bg-white p-4 shadow-sm dark:bg-slate-800">
            <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-3">Background</h3>
            <div class="mb-3 flex gap-2">
                <button @click="bioPage.background_type = 'color'" :class="bioPage.background_type === 'color' ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300'" class="rounded px-2 py-1 text-xs font-medium">Color</button>
                <button @click="bioPage.background_type = 'gradient'" :class="bioPage.background_type === 'gradient' ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300'" class="rounded px-2 py-1 text-xs font-medium">Gradient</button>
                <button @click="bioPage.background_type = 'image'" :class="bioPage.background_type === 'image' ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300'" class="rounded px-2 py-1 text-xs font-medium">Image</button>
            </div>
            <div x-show="bioPage.background_type === 'color'">
                <input type="color" x-model="bioPage.background_value" class="h-8 w-full cursor-pointer rounded">
            </div>
            <div x-show="bioPage.background_type === 'gradient'" x-cloak>
                <div class="grid grid-cols-4 gap-2">
                    <button @click="bioPage.background_value = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)'" class="h-8 rounded bg-gradient-to-br from-purple-500 to-purple-700"></button>
                    <button @click="bioPage.background_value = 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)'" class="h-8 rounded bg-gradient-to-br from-pink-400 to-red-500"></button>
                    <button @click="bioPage.background_value = 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)'" class="h-8 rounded bg-gradient-to-br from-blue-500 to-cyan-400"></button>
                    <button @click="bioPage.background_value = 'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)'" class="h-8 rounded bg-gradient-to-br from-green-500 to-teal-400"></button>
                </div>
            </div>
            <div x-show="bioPage.background_type === 'image'" x-cloak x-data="{ uploading: false }">
                <input type="file" 
                       accept="image/*" 
                       @change="async (e) => {
                           const file = e.target.files[0];
                           if (!file) return;
                           uploading = true;
                           const reader = new FileReader();
                           reader.onload = (event) => {
                               bioPage.background_value = 'url(' + event.target.result + ')';
                               bioPage.background_image = event.target.result;
                           };
                           reader.readAsDataURL(file);
                           uploading = false;
                       }"
                       class="w-full text-xs file:mr-2 file:rounded file:border-0 file:bg-blue-50 file:px-2 file:py-1 file:text-xs file:font-medium file:text-blue-700">
                <p class="mt-1 text-xs text-green-600" x-show="bioPage.background_image && !uploading" x-cloak>✓ Loaded</p>
            </div>
        </div>
    </div>
    <!-- Row 3: Text Colors (Left) + Font & Block (Right) -->
    <div class="grid grid-cols-2 gap-4">
        <!-- Text Colors -->
        <div class="rounded-lg bg-white p-4 shadow-sm dark:bg-slate-800">
            <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-3">Text Colors</h3>
            <div class="space-y-3">
                <div>
                    <label class="mb-1 block text-xs text-slate-600 dark:text-slate-400">Title & Bio</label>
                    <div class="flex gap-2">
                        <input type="color" x-model="bioPage.title_color" class="h-8 w-10 cursor-pointer rounded">
                        <input type="text" x-model="bioPage.title_color" placeholder="#000" class="flex-1 rounded border border-slate-300 px-2 py-1 text-xs dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                    </div>
                </div>
                <div>
                    <label class="mb-1 block text-xs text-slate-600 dark:text-slate-400">Link Text</label>
                    <div class="flex gap-2">
                        <input type="color" x-model="bioPage.link_text_color" class="h-8 w-10 cursor-pointer rounded">
                        <input type="text" x-model="bioPage.link_text_color" placeholder="#000" class="flex-1 rounded border border-slate-300 px-2 py-1 text-xs dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                    </div>
                </div>
            </div>
        </div>
        <!-- Font & Block Style (Right Column) -->
        <div class="space-y-4">
            <!-- Font Family -->
            <div class="rounded-lg bg-white p-4 shadow-sm dark:bg-slate-800">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-2">Font</h3>
                <select x-model="bioPage.font_family" class="w-full rounded border border-slate-300 px-2 py-1.5 text-sm dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                    <optgroup label="Sans-serif">
                        <option value="inter">Inter</option>
                        <option value="poppins">Poppins</option>
                        <option value="roboto">Roboto</option>
                        <option value="montserrat">Montserrat</option>
                        <option value="nunito">Nunito</option>
                        <option value="open-sans">Open Sans</option>
                        <option value="lato">Lato</option>
                        <option value="raleway">Raleway</option>
                        <option value="source-sans-pro">Source Sans Pro</option>
                        <option value="ubuntu">Ubuntu</option>
                        <option value="quicksand">Quicksand</option>
                        <option value="work-sans">Work Sans</option>
                        <option value="dm-sans">DM Sans</option>
                        <option value="manrope">Manrope</option>
                        <option value="plus-jakarta-sans">Plus Jakarta Sans</option>
                        <option value="lexend">Lexend</option>
                        <option value="space-grotesk">Space Grotesk</option>
                    </optgroup>
                    <optgroup label="Display">
                        <option value="oswald">Oswald</option>
                    </optgroup>
                    <optgroup label="Serif">
                        <option value="playfair-display">Playfair Display</option>
                        <option value="merriweather">Merriweather</option>
                    </optgroup>
                </select>
            </div>
            <!-- Block Style -->
            <div class="rounded-lg bg-white p-4 shadow-sm dark:bg-slate-800">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-2">Block Style</h3>
                <div class="space-y-3">
                    <!-- Shape -->
                    <div>
                        <label class="text-xs text-slate-500 dark:text-slate-400 mb-1 block">Shape</label>
                        <div class="grid grid-cols-3 gap-1">
                            <button @click="bioPage.block_shape = 'rounded'" :class="bioPage.block_shape === 'rounded' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-slate-300 dark:border-slate-700'" class="rounded border p-1.5 text-center">
                                <div class="mx-auto h-4 w-full rounded bg-slate-200 dark:bg-slate-700"></div>
                            </button>
                            <button @click="bioPage.block_shape = 'square'" :class="bioPage.block_shape === 'square' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-slate-300 dark:border-slate-700'" class="rounded border p-1.5 text-center">
                                <div class="mx-auto h-4 w-full bg-slate-200 dark:bg-slate-700"></div>
                            </button>
                            <button @click="bioPage.block_shape = 'pill'" :class="bioPage.block_shape === 'pill' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-slate-300 dark:border-slate-700'" class="rounded border p-1.5 text-center">
                                <div class="mx-auto h-4 w-full rounded-full bg-slate-200 dark:bg-slate-700"></div>
                            </button>
                        </div>
                    </div>
                    <!-- Shadow -->
                    <div>
                        <label class="text-xs text-slate-500 dark:text-slate-400 mb-1 block">Shadow</label>
                        <div class="grid grid-cols-4 gap-1">
                            <button @click="bioPage.block_shadow = 'none'" :class="bioPage.block_shadow === 'none' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-slate-300 dark:border-slate-700'" class="rounded border p-1 text-center text-xs">-</button>
                            <button @click="bioPage.block_shadow = 'sm'" :class="bioPage.block_shadow === 'sm' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-slate-300 dark:border-slate-700'" class="rounded border p-1 text-center text-xs shadow-sm">S</button>
                            <button @click="bioPage.block_shadow = 'md'" :class="bioPage.block_shadow === 'md' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-slate-300 dark:border-slate-700'" class="rounded border p-1 text-center text-xs shadow-md">M</button>
                            <button @click="bioPage.block_shadow = 'lg'" :class="bioPage.block_shadow === 'lg' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-slate-300 dark:border-slate-700'" class="rounded border p-1 text-center text-xs shadow-lg">L</button>
                        </div>
                    </div>
                    <!-- Block Background Color -->
                    <div>
                        <label class="text-xs text-slate-500 dark:text-slate-400 mb-1 block">Block Background</label>
                        <div class="flex gap-2">
                            <input type="color" x-model="bioPage.link_bg_color" class="h-8 w-12 cursor-pointer rounded border border-slate-300 dark:border-slate-700">
                            <input type="text" x-model="bioPage.link_bg_color" class="flex-1 rounded border border-slate-300 px-2 py-1 text-xs dark:border-slate-700 dark:bg-slate-900 dark:text-white" placeholder="#ffffff">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
