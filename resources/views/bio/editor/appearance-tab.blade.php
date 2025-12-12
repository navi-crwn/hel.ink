<!-- Appearance Tab Content - Enhanced Layout -->
<div class="grid grid-cols-2 gap-4">
    <!-- Left Column: Theme Selection -->
    <div class="editor-card">
        <div class="editor-card-header pb-3">
            <h3 class="editor-card-title text-base font-semibold">Theme</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Applying a theme will reset all button custom colors</p>
        </div>
        <div class="grid grid-cols-4 gap-2">
            <template x-for="(theme, id) in themes" :key="id">
                <div class="relative group">
                    <button @click="bioPage.theme = id; applyTheme(id)" 
                            :class="bioPage.theme === id ? 'ring-2 ring-blue-500' : ''"
                            class="w-full p-1.5 rounded-lg border border-slate-200 dark:[border-color:var(--editor-border)] hover:border-blue-500 transition-all">
                        <div class="w-full aspect-[3/4] rounded-lg overflow-hidden" 
                             :style="{ background: theme.background }">
                            <div class="h-full flex flex-col items-center justify-center p-2">
                                <div class="w-5 h-5 rounded-full bg-white/30 mb-1"></div>
                                <div class="w-8 h-1 rounded" :style="{ backgroundColor: theme.text }"></div>
                                <div class="w-full mt-2 space-y-1">
                                    <div class="w-full h-2 rounded" :style="{ backgroundColor: theme.link_bg }"></div>
                                    <div class="w-full h-2 rounded" :style="{ backgroundColor: theme.link_bg }"></div>
                                </div>
                            </div>
                        </div>
                        <p class="text-xs font-medium text-slate-600 dark:text-slate-300 text-center mt-1 truncate" x-text="theme.name"></p>
                    </button>
                    <!-- Copy Style Dropdown (only for non-default themes) -->
                    <template x-if="id !== 'default'">
                        <div x-data="{ showCopyMenu: false }" class="absolute top-1 right-1 z-10">
                            <button @click.stop="showCopyMenu = !showCopyMenu" 
                                    class="w-6 h-6 rounded-full bg-black/60 backdrop-blur-sm flex items-center justify-center text-white hover:bg-black/80 transition-colors"
                                    title="Copy style to Default theme">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            </button>
                            <div x-show="showCopyMenu" @click.away="showCopyMenu = false" 
                                 x-transition
                                 class="absolute right-0 mt-1 w-36 rounded-lg shadow-xl py-1 text-sm z-50"
                                 style="background: var(--editor-panel-bg); border: 1px solid var(--editor-border);">
                                <button @click.stop="copyThemeStyle(id, 'button'); showCopyMenu = false" 
                                        class="w-full px-3 py-1.5 text-left hover:bg-slate-100 dark:hover:bg-slate-700 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/>
                                    </svg>
                                    Button Style
                                </button>
                                <button @click.stop="copyThemeStyle(id, 'background'); showCopyMenu = false" 
                                        class="w-full px-3 py-1.5 text-left hover:bg-slate-100 dark:hover:bg-slate-700 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Background
                                </button>
                                <button @click.stop="copyThemeStyle(id, 'all'); showCopyMenu = false" 
                                        class="w-full px-3 py-1.5 text-left hover:bg-slate-100 dark:hover:bg-slate-700 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                    Copy All
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
        </div>
    </div>
    <!-- Right Column: Design & Background - Stacked Layout -->
    <div class="editor-card">
        <div class="editor-card-header pb-3">
            <h3 class="editor-card-title text-base font-semibold">Design & Background</h3>
        </div>
        <div class="space-y-5">
            <!-- Font Section -->
            <div class="pb-4 border-b border-slate-200 dark:[border-color:var(--editor-border)]">
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16"/></svg>
                    <h4 class="text-sm font-semibold">Font</h4>
                </div>
                <div class="space-y-3">
                    <div class="relative" x-data="{ showFontPicker: false }">
                        <label class="form-label text-xs mb-1">Font Family</label>
                        <button @click="showFontPicker = !showFontPicker" type="button" class="w-full form-input text-sm py-2 text-left flex items-center justify-between">
                            <span :style="{ fontFamily: getFontFamily(bioPage.font_family || 'inter') }" x-text="getFontDisplayName(bioPage.font_family || 'inter')"></span>
                            <svg class="w-4 h-4 editor-text-muted" :class="{ 'rotate-180': showFontPicker }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="showFontPicker" x-transition @click.away="showFontPicker = false" class="absolute z-50 mt-1 w-full rounded-lg shadow-xl max-h-48 overflow-y-auto" style="background: var(--editor-panel-bg);">
                            <template x-for="font in ['inter', 'poppins', 'roboto', 'montserrat', 'nunito', 'open-sans', 'dm-sans', 'manrope', 'space-grotesk', 'oswald', 'playfair-display']">
                                <button @click="bioPage.font_family = font; showFontPicker = false" :class="bioPage.font_family === font ? 'bg-blue-600 text-white' : 'hover:bg-slate-100 dark:hover:bg-slate-700'" class="w-full px-3 py-2 text-left text-sm transition-colors" :style="{ fontFamily: getFontFamily(font) }">
                                    <span x-text="getFontDisplayName(font)"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="form-label text-xs mb-1">Title Color</label>
                            <div class="flex items-center gap-2">
                                <input type="color" :value="bioPage.title_color || '#1e293b'" @input="bioPage.title_color = $event.target.value" class="color-picker-input w-8 h-8">
                                <input type="text" x-model="bioPage.title_color" class="form-input flex-1 text-xs py-1.5" placeholder="#1e293b" autocomplete="off" data-lpignore="true" data-1p-ignore="true">
                            </div>
                        </div>
                        <div>
                            <label class="form-label text-xs mb-1">Bio Color</label>
                            <div class="flex items-center gap-2">
                                <input type="color" :value="bioPage.bio_color || '#64748b'" @input="bioPage.bio_color = $event.target.value" class="color-picker-input w-8 h-8">
                                <input type="text" x-model="bioPage.bio_color" class="form-input flex-1 text-xs py-1.5" placeholder="#64748b" autocomplete="off" data-lpignore="true" data-1p-ignore="true">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Buttons Section -->
            <div class="pb-4 border-b border-slate-200 dark:[border-color:var(--editor-border)]">
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/></svg>
                    <h4 class="text-sm font-semibold">Buttons</h4>
                </div>
                <div class="space-y-3">
                    <!-- Shape -->
                    <div>
                        <label class="form-label text-xs mb-2">Button Shape</label>
                        <div class="grid grid-cols-3 gap-2">
                            <button @click="bioPage.block_shape = 'rounded'" :class="bioPage.block_shape === 'rounded' || !bioPage.block_shape ? 'ring-2 ring-blue-500 border-blue-500' : 'border-slate-200 dark:[border-color:var(--editor-border)]'" class="p-2 rounded-lg border flex flex-col items-center gap-1">
                                <div class="w-full h-4 bg-slate-300 dark:bg-slate-600 rounded-lg"></div>
                                <span class="text-[10px] font-medium">Rounded</span>
                            </button>
                            <button @click="bioPage.block_shape = 'pill'" :class="bioPage.block_shape === 'pill' ? 'ring-2 ring-blue-500 border-blue-500' : 'border-slate-200 dark:[border-color:var(--editor-border)]'" class="p-2 rounded-lg border flex flex-col items-center gap-1">
                                <div class="w-full h-4 bg-slate-300 dark:bg-slate-600 rounded-full"></div>
                                <span class="text-[10px] font-medium">Pill</span>
                            </button>
                            <button @click="bioPage.block_shape = 'square'" :class="bioPage.block_shape === 'square' ? 'ring-2 ring-blue-500 border-blue-500' : 'border-slate-200 dark:[border-color:var(--editor-border)]'" class="p-2 rounded-lg border flex flex-col items-center gap-1">
                                <div class="w-full h-4 bg-slate-300 dark:bg-slate-600 rounded-sm"></div>
                                <span class="text-[10px] font-medium">Square</span>
                            </button>
                        </div>
                    </div>
                    <!-- Shadow -->
                    <div>
                        <label class="form-label text-xs mb-2">Button Shadow</label>
                        <div class="grid grid-cols-5 gap-1">
                            <button @click="bioPage.block_shadow = 'none'" :class="bioPage.block_shadow === 'none' ? 'ring-2 ring-blue-500' : ''" class="p-2 rounded-lg border border-slate-200 dark:[border-color:var(--editor-border)] text-center">
                                <div class="w-full h-3 bg-slate-300 dark:bg-slate-600 rounded mx-auto"></div>
                                <span class="text-[9px] font-medium block mt-1">None</span>
                            </button>
                            <button @click="bioPage.block_shadow = 'sm'" :class="bioPage.block_shadow === 'sm' ? 'ring-2 ring-blue-500' : ''" class="p-2 rounded-lg border border-slate-200 dark:[border-color:var(--editor-border)] text-center">
                                <div class="w-full h-3 bg-slate-300 dark:bg-slate-600 rounded shadow-sm mx-auto"></div>
                                <span class="text-[9px] font-medium block mt-1">S</span>
                            </button>
                            <button @click="bioPage.block_shadow = 'md'" :class="bioPage.block_shadow === 'md' ? 'ring-2 ring-blue-500' : ''" class="p-2 rounded-lg border border-slate-200 dark:[border-color:var(--editor-border)] text-center">
                                <div class="w-full h-3 bg-slate-300 dark:bg-slate-600 rounded shadow-md mx-auto"></div>
                                <span class="text-[9px] font-medium block mt-1">M</span>
                            </button>
                            <button @click="bioPage.block_shadow = 'lg'" :class="bioPage.block_shadow === 'lg' ? 'ring-2 ring-blue-500' : ''" class="p-2 rounded-lg border border-slate-200 dark:[border-color:var(--editor-border)] text-center">
                                <div class="w-full h-3 bg-slate-300 dark:bg-slate-600 rounded shadow-lg mx-auto"></div>
                                <span class="text-[9px] font-medium block mt-1">L</span>
                            </button>
                            <button @click="bioPage.block_shadow = 'xl'" :class="bioPage.block_shadow === 'xl' ? 'ring-2 ring-blue-500' : ''" class="p-2 rounded-lg border border-slate-200 dark:[border-color:var(--editor-border)] text-center">
                                <div class="w-full h-3 bg-slate-300 dark:bg-slate-600 rounded shadow-xl mx-auto"></div>
                                <span class="text-[9px] font-medium block mt-1">XL</span>
                            </button>
                        </div>
                    </div>
                    <!-- Hover Effect -->
                    <div>
                        <label class="form-label text-xs mb-2">Hover Effect</label>
                        <style>
                            @keyframes hover-preview-scale { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.15); } }
                            @keyframes hover-preview-glow { 0%, 100% { box-shadow: 0 0 0 rgba(59, 130, 246, 0); } 50% { box-shadow: 0 0 12px rgba(59, 130, 246, 0.6); } }
                            @keyframes hover-preview-lift { 0%, 100% { transform: translateY(0); box-shadow: 0 1px 2px rgba(0,0,0,0.1); } 50% { transform: translateY(-4px); box-shadow: 0 6px 12px rgba(0,0,0,0.15); } }
                            @keyframes hover-preview-glossy { 0%, 100% { background-position: 100% 0; } 50% { background-position: 0% 0; } }
                            @keyframes hover-preview-color { 0%, 100% { background-color: rgb(148 163 184); } 50% { background-color: rgb(96 165 250); } }
                            /* Animations only run on hover OR when selected */
                            .hover-btn:hover .hover-preview-item.scale-item, .hover-btn.selected .hover-preview-item.scale-item { animation: hover-preview-scale 1.5s ease-in-out infinite; }
                            .hover-btn:hover .hover-preview-item.glow-item, .hover-btn.selected .hover-preview-item.glow-item { animation: hover-preview-glow 1.5s ease-in-out infinite; }
                            .hover-btn:hover .hover-preview-item.lift-item, .hover-btn.selected .hover-preview-item.lift-item { animation: hover-preview-lift 1.5s ease-in-out infinite; }
                            .hover-btn:hover .hover-preview-item.glossy-item, .hover-btn.selected .hover-preview-item.glossy-item { 
                                background: linear-gradient(90deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.5) 50%, rgba(255,255,255,0.1) 100%), linear-gradient(to bottom, #94a3b8, #64748b) !important;
                                background-size: 200% 100%, 100% 100% !important;
                                animation: hover-preview-glossy 2s ease-in-out infinite;
                            }
                            .hover-btn:hover .hover-preview-item.color-item, .hover-btn.selected .hover-preview-item.color-item { animation: hover-preview-color 1.5s ease-in-out infinite; }
                            .hover-preview-item.glossy-item { 
                                background: linear-gradient(to bottom, #94a3b8, #64748b);
                            }
                        </style>
                        <div class="grid grid-cols-3 gap-2">
                            <button @click="bioPage.hover_effect = 'none'" 
                                    :class="(bioPage.hover_effect === 'none' || !bioPage.hover_effect) ? 'ring-2 ring-blue-500 border-blue-500 selected' : 'border-slate-200 dark:[border-color:var(--editor-border)]'" 
                                    class="hover-btn p-2 rounded-lg border flex flex-col items-center gap-1 transition-all">
                                <div class="w-full h-4 bg-slate-300 dark:bg-slate-600 rounded-lg"></div>
                                <span class="text-[9px] font-medium">None</span>
                            </button>
                            <button @click="bioPage.hover_effect = 'scale'" 
                                    :class="bioPage.hover_effect === 'scale' ? 'ring-2 ring-blue-500 border-blue-500 selected' : 'border-slate-200 dark:[border-color:var(--editor-border)]'" 
                                    class="hover-btn p-2 rounded-lg border flex flex-col items-center gap-1 transition-all">
                                <div class="hover-preview-item scale-item w-full h-4 bg-slate-400 dark:bg-slate-500 rounded-lg"></div>
                                <span class="text-[9px] font-medium">Scale</span>
                            </button>
                            <button @click="bioPage.hover_effect = 'glow'" 
                                    :class="bioPage.hover_effect === 'glow' ? 'ring-2 ring-blue-500 border-blue-500 selected' : 'border-slate-200 dark:[border-color:var(--editor-border)]'" 
                                    class="hover-btn p-2 rounded-lg border flex flex-col items-center gap-1 transition-all">
                                <div class="hover-preview-item glow-item w-full h-4 bg-slate-400 dark:bg-slate-500 rounded-lg"></div>
                                <span class="text-[9px] font-medium">Glow</span>
                            </button>
                            <button @click="bioPage.hover_effect = 'lift'" 
                                    :class="bioPage.hover_effect === 'lift' ? 'ring-2 ring-blue-500 border-blue-500 selected' : 'border-slate-200 dark:[border-color:var(--editor-border)]'" 
                                    class="hover-btn p-2 rounded-lg border flex flex-col items-center gap-1 transition-all">
                                <div class="hover-preview-item lift-item w-full h-4 bg-slate-400 dark:bg-slate-500 rounded-lg"></div>
                                <span class="text-[9px] font-medium">Lift</span>
                            </button>
                            <button @click="bioPage.hover_effect = 'glossy'" 
                                    :class="bioPage.hover_effect === 'glossy' ? 'ring-2 ring-blue-500 border-blue-500 selected' : 'border-slate-200 dark:[border-color:var(--editor-border)]'" 
                                    class="hover-btn p-2 rounded-lg border flex flex-col items-center gap-1 transition-all">
                                <div class="hover-preview-item glossy-item w-full h-4 rounded-lg bg-slate-400 dark:bg-slate-500"></div>
                                <span class="text-[9px] font-medium">Glossy</span>
                            </button>
                            <button @click="bioPage.hover_effect = 'color-shift'" 
                                    :class="bioPage.hover_effect === 'color-shift' ? 'ring-2 ring-blue-500 border-blue-500 selected' : 'border-slate-200 dark:[border-color:var(--editor-border)]'" 
                                    class="hover-btn p-2 rounded-lg border flex flex-col items-center gap-1 transition-all">
                                <div class="hover-preview-item color-item w-full h-4 rounded-lg bg-slate-400 dark:bg-slate-500"></div>
                                <span class="text-[9px] font-medium">Color Shift</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Background Section - Only show for Default theme -->
            <div x-show="bioPage.theme === 'default'" x-transition>
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <h4 class="text-sm font-semibold">Background</h4>
                </div>
                <div class="space-y-3">
                    <!-- Background Type Tabs -->
                    <div class="flex gap-1 bg-slate-100 dark:bg-slate-700 rounded-lg p-1">
                        <button @click="bioPage.background_type = 'solid'; if(!bioPage.background_value || bioPage.background_value.includes('gradient') || bioPage.background_value.includes('/')) bioPage.background_value = '#ffffff';" 
                                :class="bioPage.background_type === 'solid' ? 'bg-white dark:bg-slate-600 shadow-sm' : ''"
                                class="flex-1 py-1.5 px-3 rounded-md text-xs font-medium transition-all">Color</button>
                        <button @click="bioPage.background_type = 'gradient'; if(!bioPage.background_value || !bioPage.background_value.includes('gradient')) bioPage.background_value = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';" 
                                :class="bioPage.background_type === 'gradient' ? 'bg-white dark:bg-slate-600 shadow-sm' : ''"
                                class="flex-1 py-1.5 px-3 rounded-md text-xs font-medium transition-all">Gradient</button>
                        <button @click="bioPage.background_type = 'image'" 
                                :class="bioPage.background_type === 'image' ? 'bg-white dark:bg-slate-600 shadow-sm' : ''"
                                class="flex-1 py-1.5 px-3 rounded-md text-xs font-medium transition-all">Image</button>
                    </div>
                    <!-- Solid Color -->
                    <div x-show="bioPage.background_type === 'solid'" class="flex items-center gap-2">
                        <input type="color" x-model="bioPage.background_value" class="color-picker-input w-10 h-10">
                        <input type="text" x-model="bioPage.background_value" class="form-input flex-1 text-sm py-2" placeholder="#ffffff" autocomplete="off" data-lpignore="true" data-1p-ignore="true">
                    </div>
                    <!-- Gradient -->
                    <div x-show="bioPage.background_type === 'gradient'" class="grid grid-cols-4 gap-2">
                        <button @click="bioPage.background_value = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)'" class="h-10 rounded-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%)"></button>
                        <button @click="bioPage.background_value = 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)'" class="h-10 rounded-lg" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%)"></button>
                        <button @click="bioPage.background_value = 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)'" class="h-10 rounded-lg" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)"></button>
                        <button @click="bioPage.background_value = 'linear-gradient(135deg, #0f172a 0%, #1e293b 100%)'" class="h-10 rounded-lg" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%)"></button>
                        <button @click="bioPage.background_value = 'linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%)'" class="h-10 rounded-lg" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%)"></button>
                        <button @click="bioPage.background_value = 'linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)'" class="h-10 rounded-lg" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)"></button>
                        <button @click="bioPage.background_value = 'linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%)'" class="h-10 rounded-lg" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%)"></button>
                        <button @click="bioPage.background_value = 'linear-gradient(135deg, #000000 0%, #434343 100%)'" class="h-10 rounded-lg" style="background: linear-gradient(135deg, #000000 0%, #434343 100%)"></button>
                    </div>
                    <!-- Image -->
                    <div x-show="bioPage.background_type === 'image'">
                        <input type="file" accept="image/*" class="hidden" x-ref="bgImageInput" @change="uploadBackgroundImage($event)">
                        <button @click="$refs.bgImageInput.click()" class="btn btn-secondary w-full text-sm py-2">Upload Image</button>
                        <template x-if="bioPage.background_value && bioPage.background_type === 'image' && bioPage.background_value.length > 0">
                            <div class="mt-2 relative rounded-lg overflow-hidden">
                                <img :src="bioPage.background_value" 
                                     class="w-full h-16 object-cover" 
                                     alt="Background"
                                     x-on:error="$el.style.display='none'; $el.parentElement.querySelector('.bg-error')?.classList.remove('hidden')"
                                     x-on:load="$el.style.display='block'; $el.parentElement.querySelector('.bg-error')?.classList.add('hidden')">
                                <div class="bg-error hidden absolute inset-0 flex items-center justify-center bg-slate-100 dark:bg-slate-800 text-xs text-slate-500">
                                    <span>Image not found</span>
                                </div>
                                <button @click="bioPage.background_value = ''; bioPage.background_image = ''" class="absolute top-1 right-1 p-1 bg-red-500 text-white rounded-full hover:bg-red-600">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
            <!-- Background Animation Section - Available for all themes -->
            <div x-transition>
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                    <h4 class="text-sm font-semibold">Background Animation</h4>
                </div>
                <div class="grid grid-cols-4 gap-2">
                    <button @click="bioPage.background_animation = 'none'" 
                            :class="(bioPage.background_animation === 'none' || !bioPage.background_animation) ? 'ring-2 ring-blue-500 border-blue-500' : 'border-slate-200 dark:[border-color:var(--editor-border)]'" 
                            class="p-3 rounded-lg border flex flex-col items-center gap-1.5 transition-all">
                        <div class="w-8 h-8 flex items-center justify-center text-slate-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                        </div>
                        <span class="text-xs font-medium">None</span>
                    </button>
                    <button @click="bioPage.background_animation = 'snow'" 
                            :class="bioPage.background_animation === 'snow' ? 'ring-2 ring-blue-500 border-blue-500' : 'border-slate-200 dark:[border-color:var(--editor-border)]'" 
                            class="p-3 rounded-lg border flex flex-col items-center gap-1.5 transition-all">
                        <div class="w-8 h-8 flex items-center justify-center text-xl">❄️</div>
                        <span class="text-xs font-medium">Snow</span>
                    </button>
                    <button @click="bioPage.background_animation = 'leaves'" 
                            :class="bioPage.background_animation === 'leaves' ? 'ring-2 ring-blue-500 border-blue-500' : 'border-slate-200 dark:[border-color:var(--editor-border)]'" 
                            class="p-3 rounded-lg border flex flex-col items-center gap-1.5 transition-all">
                        <div class="w-8 h-8 flex items-center justify-center text-xl">🍂</div>
                        <span class="text-xs font-medium">Leaves</span>
                    </button>
                    <button @click="bioPage.background_animation = 'rain'" 
                            :class="bioPage.background_animation === 'rain' ? 'ring-2 ring-blue-500 border-blue-500' : 'border-slate-200 dark:[border-color:var(--editor-border)]'" 
                            class="p-3 rounded-lg border flex flex-col items-center gap-1.5 transition-all">
                        <div class="w-8 h-8 flex items-center justify-center text-xl">🌧️</div>
                        <span class="text-xs font-medium">Rain</span>
                    </button>
                    <button @click="bioPage.background_animation = 'stars'" 
                            :class="bioPage.background_animation === 'stars' ? 'ring-2 ring-blue-500 border-blue-500' : 'border-slate-200 dark:[border-color:var(--editor-border)]'" 
                            class="p-3 rounded-lg border flex flex-col items-center gap-1.5 transition-all">
                        <div class="w-8 h-8 flex items-center justify-center text-xl">✨</div>
                        <span class="text-xs font-medium">Stars</span>
                    </button>
                    <button @click="bioPage.background_animation = 'hearts'" 
                            :class="bioPage.background_animation === 'hearts' ? 'ring-2 ring-blue-500 border-blue-500' : 'border-slate-200 dark:[border-color:var(--editor-border)]'" 
                            class="p-3 rounded-lg border flex flex-col items-center gap-1.5 transition-all">
                        <div class="w-8 h-8 flex items-center justify-center text-xl">💕</div>
                        <span class="text-xs font-medium">Hearts</span>
                    </button>
                    <button @click="bioPage.background_animation = 'confetti'" 
                            :class="bioPage.background_animation === 'confetti' ? 'ring-2 ring-blue-500 border-blue-500' : 'border-slate-200 dark:[border-color:var(--editor-border)]'" 
                            class="p-3 rounded-lg border flex flex-col items-center gap-1.5 transition-all">
                        <div class="w-8 h-8 flex items-center justify-center text-xl">🎊</div>
                        <span class="text-xs font-medium">Confetti</span>
                    </button>
                    <button @click="bioPage.background_animation = 'particles'" 
                            :class="bioPage.background_animation === 'particles' ? 'ring-2 ring-blue-500 border-blue-500' : 'border-slate-200 dark:[border-color:var(--editor-border)]'" 
                            class="p-3 rounded-lg border flex flex-col items-center gap-1.5 transition-all">
                        <div class="w-8 h-8 flex items-center justify-center text-xl">🔮</div>
                        <span class="text-xs font-medium">Particles</span>
                    </button>
                    <button @click="bioPage.background_animation = 'matrix'" 
                            :class="bioPage.background_animation === 'matrix' ? 'ring-2 ring-blue-500 border-blue-500' : 'border-slate-200 dark:[border-color:var(--editor-border)]'" 
                            class="p-3 rounded-lg border flex flex-col items-center gap-1.5 transition-all"
                            x-show="bioPage.theme === 'neon' || bioPage.theme === 'matrix'">
                        <div class="w-8 h-8 flex items-center justify-center text-green-500 font-mono font-bold text-lg">01</div>
                        <span class="text-xs font-medium">Matrix</span>
                    </button>
                </div>
            </div>
            <!-- Theme Background Info (for non-default themes) -->
            <div x-show="bioPage.theme !== 'default'" x-transition class="pt-2">
                <div class="flex items-start gap-2 text-xs text-slate-500 dark:text-slate-400 p-2 rounded-lg bg-slate-100 dark:bg-slate-800">
                    <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Background is locked for this theme. Click the <strong>copy icon</strong> on any theme to copy its style to Default for full customization.</span>
                </div>
            </div>
        </div>
    </div>
</div>