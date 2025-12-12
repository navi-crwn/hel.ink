<!-- Settings Tab Content -->
<!-- SEO Settings with Google Preview -->
<div class="editor-card">
    <div class="editor-card-header">
        <h3 class="editor-card-title">SEO Settings</h3>
    </div>
    <div class="space-y-4">
        <!-- Google Preview -->
        <div class="bg-white border border-slate-200 rounded-lg p-4 dark:bg-slate-900 dark:[border-color:var(--editor-border)]">
            <p class="text-xs editor-text-muted mb-3 uppercase tracking-wide font-medium">Google Search Preview</p>
            <div class="space-y-1">
                <p class="text-sm text-blue-700 dark:text-blue-400 truncate" x-text="'{{ url("/") }}/' + bioPage.slug"></p>
                <h4 class="text-lg text-blue-800 dark:text-blue-300 font-medium truncate hover:underline cursor-pointer" 
                    x-text="(bioPage.seo_title || bioPage.title || 'Your Page Title') + ' | By HEL.ink'"></h4>
                <p class="text-sm text-slate-600 dark:text-slate-400 line-clamp-2" 
                   x-text="bioPage.seo_description || bioPage.bio || 'Your page description will appear here. Write a compelling description to improve click-through rates from search results.'"></p>
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Page Title</label>
            <input type="text" x-model="bioPage.seo_title" class="form-input" placeholder="Leave empty to use display name" maxlength="47" autocomplete="off" data-lpignore="true" data-1p-ignore="true" data-form-type="other">
            <div class="flex justify-between mt-1">
                <p class="text-xs editor-text-muted">Appears in browser tab and search results (+ " | By HEL.ink")</p>
                <p class="text-xs" :class="(bioPage.seo_title || '').length > 40 ? 'text-orange-500' : 'editor-text-muted'" x-text="(bioPage.seo_title || '').length + '/47'"></p>
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Meta Description</label>
            <textarea x-model="bioPage.seo_description" class="form-input form-textarea" placeholder="Brief description for search engines" rows="2" maxlength="160" autocomplete="off" data-lpignore="true" data-1p-ignore="true"></textarea>
            <div class="flex justify-between mt-1">
                <p class="text-xs editor-text-muted">Recommended: 120-160 characters</p>
                <p class="text-xs" :class="(bioPage.seo_description || '').length > 150 ? 'text-orange-500' : 'editor-text-muted'" x-text="(bioPage.seo_description || '').length + '/160'"></p>
            </div>
        </div>
    </div>
</div>
<!-- Privacy & Visibility -->
<div class="editor-card">
    <div class="editor-card-header">
        <h3 class="editor-card-title">Privacy & Visibility</h3>
    </div>
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="font-medium text-slate-900 dark:text-white">Public Page</p>
                <p class="text-sm editor-text-muted">Anyone with the link can view your page</p>
            </div>
            <label class="toggle-switch">
                <input type="checkbox" x-model="bioPage.is_published">
                <span class="toggle-slider"></span>
            </label>
        </div>
        <div class="flex items-center justify-between">
            <div>
                <p class="font-medium text-slate-900 dark:text-white">Search Engine Indexing</p>
                <p class="text-sm editor-text-muted">Allow search engines to index this page</p>
            </div>
            <label class="toggle-switch">
                <input type="checkbox" x-model="bioPage.allow_indexing">
                <span class="toggle-slider"></span>
            </label>
        </div>
        <!-- 18+ Adult Content -->
        <div class="flex items-center justify-between">
            <div>
                <p class="font-medium text-slate-900 dark:text-white flex items-center gap-2">
                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    18+ Zone
                </p>
                <p class="text-sm editor-text-muted">Visitors must confirm age before viewing</p>
            </div>
            <label class="toggle-switch">
                <input type="checkbox" x-model="bioPage.is_adult_content">
                <span class="toggle-slider"></span>
            </label>
        </div>
        <!-- Password Protection -->
        <div class="border-t [border-color:var(--editor-border)] pt-4">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="font-medium text-slate-900 dark:text-white flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Password Protection
                    </p>
                    <p class="text-sm editor-text-muted">Require a password to view this page</p>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" x-model="bioPage.password_enabled" @change="if(!$event.target.checked) bioPage.password = ''">
                    <span class="toggle-slider"></span>
                </label>
            </div>
            <div x-show="bioPage.password_enabled" x-collapse>
                <div class="form-group mb-0">
                    <label class="form-label">Password</label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" 
                               x-model="bioPage.password" 
                               class="form-input pr-10" 
                               placeholder="Enter password for your page"
                               maxlength="50"
                               autocomplete="new-password"
                               data-lpignore="true"
                               data-1p-ignore="true"
                               data-form-type="other">
                        <button type="button" @click="showPassword = !showPassword" 
                                class="absolute right-3 top-1/2 -translate-y-1/2 editor-text-muted hover:editor-text">
                            <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showPassword" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    <p class="text-xs editor-text-muted mt-1">Visitors must enter this password to access your page</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Analytics -->
<div class="editor-card">
    <div class="editor-card-header">
        <h3 class="editor-card-title">Analytics</h3>
    </div>
    <div class="space-y-4">
        <div class="form-group">
            <label class="form-label">Google Analytics ID</label>
            <input type="text" x-model="bioPage.google_analytics_id" class="form-input" placeholder="G-XXXXXXXXXX" pattern="G-[A-Z0-9]+" autocomplete="off" data-lpignore="true" data-1p-ignore="true" data-form-type="other" inputmode="text">
            <p class="text-xs editor-text-muted mt-1">Format: G-XXXXXXXXXX (e.g., G-ABC123DEF4)</p>
        </div>
        <div class="form-group">
            <label class="form-label">Facebook Pixel ID</label>
            <input type="text" x-model="bioPage.facebook_pixel_id" class="form-input" placeholder="123456789012345" pattern="\d{10,20}" autocomplete="off" data-lpignore="true" data-1p-ignore="true" data-form-type="other" inputmode="numeric">
            <p class="text-xs editor-text-muted mt-1">Format: 15 digit number (e.g., 123456789012345)</p>
        </div>
        <div class="form-group">
            <label class="form-label">TikTok Pixel ID</label>
            <input type="text" x-model="bioPage.tiktok_pixel_id" class="form-input" placeholder="CXXXXXXXXXXXXXXXXXX" pattern="[A-Z0-9]{20,30}" autocomplete="off" data-lpignore="true" data-1p-ignore="true" data-form-type="other" inputmode="text">
            <p class="text-xs editor-text-muted mt-1">Format: 20-30 character ID (e.g., C123ABC456DEF789GHI)</p>
        </div>
    </div>
</div>
<!-- Danger Zone -->
<div class="editor-card border-red-200 dark:border-red-900">
    <div class="editor-card-header">
        <h3 class="editor-card-title text-red-600">Danger Zone</h3>
    </div>
    <div class="space-y-4">
        <div class="flex items-center justify-between p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
            <div>
                <p class="font-medium text-red-800 dark:text-red-300">Delete Page</p>
                <p class="text-sm text-red-600 dark:text-red-400">Permanently delete this bio page and all its data</p>
            </div>
            <button @click="confirmDelete()" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition-colors">
                Delete Page
            </button>
        </div>
    </div>
</div>
