<!-- Socials Tab Content -->
<div class="space-y-4">
    <!-- Social Icons Position -->
    <div class="editor-card">
        <div class="editor-card-header">
            <h3 class="editor-card-title">Position</h3>
        </div>
        <div class="p-4">
            <div class="grid grid-cols-2 gap-3">
                <button @click="bioPage.social_icons_position = 'below_bio'" 
                        :class="bioPage.social_icons_position === 'below_bio' || !bioPage.social_icons_position ? 'border-blue-500 bg-blue-900/20' : '[border-color:var(--editor-border)]'"
                        class="flex flex-col items-center gap-2 p-4 rounded-xl border-2 transition-all">
                    <svg class="w-8 h-8" :class="bioPage.social_icons_position === 'below_bio' || !bioPage.social_icons_position ? 'text-blue-400' : 'editor-text-muted'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="text-sm font-medium" :class="bioPage.social_icons_position === 'below_bio' || !bioPage.social_icons_position ? 'text-blue-400' : 'editor-text-muted'">Below Bio</span>
                </button>
                <button @click="bioPage.social_icons_position = 'bottom_page'" 
                        :class="bioPage.social_icons_position === 'bottom_page' ? 'border-blue-500 bg-blue-900/20' : '[border-color:var(--editor-border)]'"
                        class="flex flex-col items-center gap-2 p-4 rounded-xl border-2 transition-all">
                    <svg class="w-8 h-8" :class="bioPage.social_icons_position === 'bottom_page' ? 'text-blue-400' : 'editor-text-muted'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                    <span class="text-sm font-medium" :class="bioPage.social_icons_position === 'bottom_page' ? 'text-blue-400' : 'editor-text-muted'">Bottom</span>
                </button>
            </div>
        </div>
    </div>
    <!-- Active Social Icons -->
    <div class="editor-card">
        <div class="editor-card-header">
            <h3 class="editor-card-title">Social Icons</h3>
            <span class="text-xs editor-text-muted" x-text="(bioPage.social_links || []).length + '/5'"></span>
        </div>
        <div class="p-4">
            <!-- Social Links List -->
            <div id="socials-list" class="space-y-2 mb-4" x-show="bioPage.social_links && bioPage.social_links.length > 0">
                <template x-for="(item, idx) in (bioPage.social_links || [])" :key="item.platform + '-' + idx">
                    <div class="block-item" x-show="item">
                        <!-- Drag Handle -->
                        <div class="social-drag-handle block-drag-handle">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                            </svg>
                        </div>
                        <!-- Icon -->
                        <div class="block-icon" :style="{ backgroundColor: getPlatformColor(item.platform) }">
                            <img :src="getPlatformIcon(item.platform)" alt="" class="w-5 h-5" style="filter: brightness(0) invert(1);" onerror="this.style.display='none'">
                        </div>
                        <!-- Input -->
                        <div class="block-content">
                            <input type="text" 
                                   x-model="item.value" 
                                   :placeholder="getSocialPlaceholder(item.platform)"
                                   @blur="validateSocialInput(idx)"
                                   autocomplete="off"
                                   data-lpignore="true"
                                   data-form-type="other"
                                   class="w-full bg-transparent border-none focus:outline-none text-sm text-white placeholder-slate-500">
                            <div class="text-xs editor-text-muted" x-text="platforms[item.platform]?.name || item.platform"></div>
                        </div>
                        <!-- Actions -->
                        <div class="block-actions">
                            <label class="toggle-switch">
                                <input type="checkbox" x-model="item.enabled">
                                <span class="toggle-slider"></span>
                            </label>
                            <button @click="removeSocial(idx)" class="block-action-btn delete">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
            <!-- Empty State -->
            <div x-show="!bioPage.social_links || bioPage.social_links.length === 0" class="text-center py-8">
                <svg class="w-12 h-12 mx-auto editor-text-muted mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <p class="editor-text-muted mb-1">No social icons yet</p>
                <p class="text-sm editor-text-muted">Add your social profiles</p>
            </div>
            <!-- Add Social Button -->
            <button @click="showSocialPicker = true" 
                    :disabled="bioPage.social_links && bioPage.social_links.length >= 5"
                    class="w-full py-3 bg-purple-600 hover:bg-purple-700 disabled:bg-slate-300 dark:disabled:bg-slate-600 disabled:cursor-not-allowed rounded-xl text-white font-medium transition-colors flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <span x-text="(bioPage.social_links && bioPage.social_links.length >= 5) ? 'Maximum 5 icons reached' : 'Add Social Icon'"></span>
            </button>
        </div>
    </div>
</div>
