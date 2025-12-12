<!-- Block Editor Modal Content -->

<div class="space-y-6">
    <!-- Block Type Display -->
    <div class="flex items-center gap-3 pb-4 border-b border-slate-200 dark:[border-color:var(--editor-border)]">
        <div class="w-12 h-12 rounded-lg flex items-center justify-content" :style="{ background: editingBlock.brand ? getBrandColor(editingBlock.brand) : '#6366f1' }">
            <template x-if="editingBlock.brand">
                <img :src="'/images/brands/' + editingBlock.brand + '.svg'" class="w-6 h-6 mx-auto" :alt="editingBlock.brand">
            </template>
            <template x-if="!editingBlock.brand">
                <svg class="w-6 h-6 mx-auto text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                </svg>
            </template>
        </div>
        <div>
            <p class="font-medium text-slate-900 dark:text-white capitalize" x-text="editingBlock.type + ' Block'"></p>
            <p class="text-sm editor-text-muted" x-text="editingBlock.brand ? 'Platform: ' + editingBlock.brand : 'Custom link'"></p>
        </div>
    </div>
    
    <!-- Link Block Fields -->
    <template x-if="editingBlock.type === 'link'">
        <div class="space-y-4">
            <!-- Title -->
            <div class="form-group">
                <label class="form-label">Button Title</label>
                <input type="text" x-model="editingBlock.title" class="form-input" placeholder="Enter button title" autocomplete="off" data-lpignore="true" data-1p-ignore="true" data-form-type="other">
            </div>
            
            <!-- URL -->
            <div class="form-group">
                <label class="form-label">URL</label>
                <input type="url" x-model="editingBlock.url" class="form-input" placeholder="https://example.com" autocomplete="off" data-lpignore="true" data-1p-ignore="true" data-form-type="other">
            </div>
            
            <!-- Animation Options for Links -->
            <div class="form-group" x-data="{ showAnimations: false }">
                <button type="button" @click="showAnimations = !showAnimations" 
                        class="w-full flex items-center justify-between p-3 rounded-lg border border-slate-200 dark:[border-color:var(--editor-border)] hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                        <span class="text-sm font-medium">Animation Effects</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': showAnimations }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                
                <div x-show="showAnimations" x-collapse class="mt-3 space-y-4 p-3 rounded-lg bg-slate-50 dark:bg-slate-900">
                    <!-- Entrance Animation -->
                    <div>
                        <label class="form-label text-xs mb-2 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            Entrance Animation
                        </label>
                        <div class="grid grid-cols-4 gap-1.5">
                            <button type="button" @click="editingBlock.entrance_animation = 'none'" 
                                    :class="(editingBlock.entrance_animation === 'none' || !editingBlock.entrance_animation) ? 'ring-2 ring-blue-500 bg-blue-50 dark:bg-blue-900/30' : 'bg-white dark:bg-slate-800'" 
                                    class="p-1.5 rounded border border-slate-200 dark:[border-color:var(--editor-border)] text-center transition-all">
                                <span class="text-[9px] font-medium">None</span>
                            </button>
                            <button type="button" @click="editingBlock.entrance_animation = 'fade'" 
                                    :class="editingBlock.entrance_animation === 'fade' ? 'ring-2 ring-blue-500 bg-blue-50 dark:bg-blue-900/30' : 'bg-white dark:bg-slate-800'" 
                                    class="p-1.5 rounded border border-slate-200 dark:[border-color:var(--editor-border)] text-center transition-all">
                                <span class="text-[9px] font-medium">Fade</span>
                            </button>
                            <button type="button" @click="editingBlock.entrance_animation = 'slide-up'" 
                                    :class="editingBlock.entrance_animation === 'slide-up' ? 'ring-2 ring-blue-500 bg-blue-50 dark:bg-blue-900/30' : 'bg-white dark:bg-slate-800'" 
                                    class="p-1.5 rounded border border-slate-200 dark:[border-color:var(--editor-border)] text-center transition-all">
                                <span class="text-[9px] font-medium">Slide Up</span>
                            </button>
                            <button type="button" @click="editingBlock.entrance_animation = 'slide-down'" 
                                    :class="editingBlock.entrance_animation === 'slide-down' ? 'ring-2 ring-blue-500 bg-blue-50 dark:bg-blue-900/30' : 'bg-white dark:bg-slate-800'" 
                                    class="p-1.5 rounded border border-slate-200 dark:[border-color:var(--editor-border)] text-center transition-all">
                                <span class="text-[9px] font-medium">Slide Down</span>
                            </button>
                            <button type="button" @click="editingBlock.entrance_animation = 'pop'" 
                                    :class="editingBlock.entrance_animation === 'pop' ? 'ring-2 ring-blue-500 bg-blue-50 dark:bg-blue-900/30' : 'bg-white dark:bg-slate-800'" 
                                    class="p-1.5 rounded border border-slate-200 dark:[border-color:var(--editor-border)] text-center transition-all">
                                <span class="text-[9px] font-medium">Pop</span>
                            </button>
                            <button type="button" @click="editingBlock.entrance_animation = 'bounce'" 
                                    :class="editingBlock.entrance_animation === 'bounce' ? 'ring-2 ring-blue-500 bg-blue-50 dark:bg-blue-900/30' : 'bg-white dark:bg-slate-800'" 
                                    class="p-1.5 rounded border border-slate-200 dark:[border-color:var(--editor-border)] text-center transition-all">
                                <span class="text-[9px] font-medium">Bounce</span>
                            </button>
                            <button type="button" @click="editingBlock.entrance_animation = 'flip'" 
                                    :class="editingBlock.entrance_animation === 'flip' ? 'ring-2 ring-blue-500 bg-blue-50 dark:bg-blue-900/30' : 'bg-white dark:bg-slate-800'" 
                                    class="p-1.5 rounded border border-slate-200 dark:[border-color:var(--editor-border)] text-center transition-all">
                                <span class="text-[9px] font-medium">Flip</span>
                            </button>
                            <button type="button" @click="editingBlock.entrance_animation = 'stagger'" 
                                    :class="editingBlock.entrance_animation === 'stagger' ? 'ring-2 ring-blue-500 bg-blue-50 dark:bg-blue-900/30' : 'bg-white dark:bg-slate-800'" 
                                    class="p-1.5 rounded border border-slate-200 dark:[border-color:var(--editor-border)] text-center transition-all">
                                <span class="text-[9px] font-medium">Stagger</span>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Attention Animation -->
                    <div>
                        <label class="form-label text-xs mb-2 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            Attention Animation
                            <span class="text-[9px] text-slate-400">(continuous)</span>
                        </label>
                        <div class="grid grid-cols-4 gap-1.5">
                            <button type="button" @click="editingBlock.attention_animation = 'none'" 
                                    :class="(editingBlock.attention_animation === 'none' || !editingBlock.attention_animation) ? 'ring-2 ring-blue-500 bg-blue-50 dark:bg-blue-900/30' : 'bg-white dark:bg-slate-800'" 
                                    class="p-1.5 rounded border border-slate-200 dark:[border-color:var(--editor-border)] text-center transition-all">
                                <span class="text-[9px] font-medium">None</span>
                            </button>
                            <button type="button" @click="editingBlock.attention_animation = 'pulse'" 
                                    :class="editingBlock.attention_animation === 'pulse' ? 'ring-2 ring-blue-500 bg-blue-50 dark:bg-blue-900/30' : 'bg-white dark:bg-slate-800'" 
                                    class="p-1.5 rounded border border-slate-200 dark:[border-color:var(--editor-border)] text-center transition-all">
                                <span class="text-[9px] font-medium">Pulse</span>
                            </button>
                            <button type="button" @click="editingBlock.attention_animation = 'shake'" 
                                    :class="editingBlock.attention_animation === 'shake' ? 'ring-2 ring-blue-500 bg-blue-50 dark:bg-blue-900/30' : 'bg-white dark:bg-slate-800'" 
                                    class="p-1.5 rounded border border-slate-200 dark:[border-color:var(--editor-border)] text-center transition-all">
                                <span class="text-[9px] font-medium">Shake</span>
                            </button>
                            <button type="button" @click="editingBlock.attention_animation = 'glow'" 
                                    :class="editingBlock.attention_animation === 'glow' ? 'ring-2 ring-blue-500 bg-blue-50 dark:bg-blue-900/30' : 'bg-white dark:bg-slate-800'" 
                                    class="p-1.5 rounded border border-slate-200 dark:[border-color:var(--editor-border)] text-center transition-all">
                                <span class="text-[9px] font-medium">Glow</span>
                            </button>
                            <button type="button" @click="editingBlock.attention_animation = 'wiggle'" 
                                    :class="editingBlock.attention_animation === 'wiggle' ? 'ring-2 ring-blue-500 bg-blue-50 dark:bg-blue-900/30' : 'bg-white dark:bg-slate-800'" 
                                    class="p-1.5 rounded border border-slate-200 dark:[border-color:var(--editor-border)] text-center transition-all">
                                <span class="text-[9px] font-medium">Wiggle</span>
                            </button>
                            <button type="button" @click="editingBlock.attention_animation = 'heartbeat'" 
                                    :class="editingBlock.attention_animation === 'heartbeat' ? 'ring-2 ring-blue-500 bg-blue-50 dark:bg-blue-900/30' : 'bg-white dark:bg-slate-800'" 
                                    class="p-1.5 rounded border border-slate-200 dark:[border-color:var(--editor-border)] text-center transition-all">
                                <span class="text-[9px] font-medium">Heartbeat</span>
                            </button>
                            <button type="button" @click="editingBlock.attention_animation = 'rainbow'" 
                                    :class="editingBlock.attention_animation === 'rainbow' ? 'ring-2 ring-blue-500 bg-blue-50 dark:bg-blue-900/30' : 'bg-white dark:bg-slate-800'" 
                                    class="p-1.5 rounded border border-slate-200 dark:[border-color:var(--editor-border)] text-center transition-all">
                                <span class="text-[9px] font-medium">Rainbow</span>
                            </button>
                            <button type="button" @click="editingBlock.attention_animation = 'bounce'" 
                                    :class="editingBlock.attention_animation === 'bounce' ? 'ring-2 ring-blue-500 bg-blue-50 dark:bg-blue-900/30' : 'bg-white dark:bg-slate-800'" 
                                    class="p-1.5 rounded border border-slate-200 dark:[border-color:var(--editor-border)] text-center transition-all">
                                <span class="text-[9px] font-medium">Bounce</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Platform Selection -->
            <div class="form-group">
                <label class="form-label">Button Style</label>
                <div class="grid grid-cols-6 gap-2 max-h-48 overflow-y-auto p-2 bg-slate-50 dark:bg-slate-900 rounded-lg">
                    <template x-for="(brand, brandId) in $store.brands" :key="brandId">
                        <button 
                            @click="editingBlock.brand = brandId" 
                            class="p-2 rounded-lg border-2 transition-all hover:scale-105"
                            :class="editingBlock.brand === brandId ? 'border-blue-500 bg-blue-50' : 'border-transparent hover:border-slate-300'"
                            :style="{ background: brand.bgColor + '20' }"
                        >
                            <img :src="'/images/brands/' + brandId + '.svg'" class="w-6 h-6 mx-auto" :alt="brand.label">
                        </button>
                    </template>
                </div>
                <button @click="editingBlock.brand = null" class="mt-2 text-sm text-blue-600 hover:underline">Use custom style</button>
            </div>
            
            <!-- Thumbnail -->
            <div class="form-group" x-data="{ iconInputMode: 'upload', iconUrlInput: '' }">
                <label class="form-label">Thumbnail (Optional)</label>
                
                <!-- Current Icon Preview -->
                <template x-if="editingBlock.thumbnail_url">
                    <div class="flex items-center gap-3 mb-3 p-2 rounded-lg" style="background: var(--editor-input-bg);">
                        <img :src="editingBlock.thumbnail_url" class="w-12 h-12 rounded-lg object-cover border border-slate-600">
                        <div class="flex-1 text-xs editor-text-muted truncate" x-text="editingBlock.thumbnail_url"></div>
                        <button @click="editingBlock.thumbnail_url = null; editingBlock.custom_icon = null" 
                                class="w-6 h-6 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center text-xs">×</button>
                    </div>
                </template>
                
                <!-- Input Mode Toggle -->
                <div class="flex gap-2 mb-3">
                    <button @click="iconInputMode = 'upload'" 
                            :class="iconInputMode === 'upload' ? 'bg-blue-600 text-white' : 'bg-slate-700 text-slate-300'"
                            class="flex-1 py-1.5 px-3 rounded-lg text-xs font-medium transition-colors">
                        Upload File
                    </button>
                    <button @click="iconInputMode = 'url'" 
                            :class="iconInputMode === 'url' ? 'bg-blue-600 text-white' : 'bg-slate-700 text-slate-300'"
                            class="flex-1 py-1.5 px-3 rounded-lg text-xs font-medium transition-colors">
                        From URL
                    </button>
                </div>
                
                <!-- Upload File -->
                <div x-show="iconInputMode === 'upload'" class="flex items-center gap-2">
                    <input type="file" @change="uploadThumbnail($event)" accept="image/*" class="flex-1 text-sm editor-text-muted file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-blue-600 file:text-white file:font-medium hover:file:bg-blue-700">
                </div>
                
                <!-- URL Input -->
                <div x-show="iconInputMode === 'url'" class="space-y-2">
                    <div class="flex gap-2">
                        <input type="url" x-model="iconUrlInput" 
                               class="form-input flex-1 text-sm" 
                               placeholder="https://example.com/icon.png"
                               autocomplete="off" data-lpignore="true" data-1p-ignore="true">
                        <button @click="if(iconUrlInput) { editingBlock.thumbnail_url = iconUrlInput; editingBlock.custom_icon = iconUrlInput; iconUrlInput = ''; }" 
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors">
                            Set
                        </button>
                    </div>
                    <p class="text-xs editor-text-muted">Enter direct link to an image (PNG, JPG, SVG, WebP)</p>
                </div>
            </div>
        </div>
    </template>
    
    <!-- Text Block Fields -->
    <template x-if="editingBlock.type === 'text'">
        <div class="space-y-4">
            <div class="form-group">
                <label class="form-label">Text Content</label>
                <textarea x-model="editingBlock.content" class="form-input form-textarea" rows="5" placeholder="Enter your text..." style="min-height: 120px;"></textarea>
            </div>
        </div>
    </template>
    
    <!-- Video Block Fields -->
    <template x-if="editingBlock.type === 'video'">
        <div class="space-y-4">
            <div class="form-group">
                <label class="form-label">Video Title</label>
                <input type="text" x-model="editingBlock.title" class="form-input" placeholder="My Video" autocomplete="off" data-lpignore="true" data-1p-ignore="true">
            </div>
            <div class="form-group">
                <label class="form-label">Video URL</label>
                <input type="url" x-model="editingBlock.embed_url" class="form-input" placeholder="https://youtube.com/watch?v=... or https://vimeo.com/..." autocomplete="off" data-lpignore="true" data-1p-ignore="true">
                <p class="text-xs editor-text-muted mt-1">Supports YouTube and Vimeo links</p>
            </div>
        </div>
    </template>
    
    <!-- Music Block Fields -->
    <template x-if="editingBlock.type === 'music'">
        <div class="space-y-4">
            <div class="form-group">
                <label class="form-label">Music Title</label>
                <input type="text" x-model="editingBlock.title" class="form-input" placeholder="My Playlist" autocomplete="off" data-lpignore="true" data-1p-ignore="true">
            </div>
            <div class="form-group">
                <label class="form-label">Music URL</label>
                <input type="url" x-model="editingBlock.embed_url" class="form-input" placeholder="https://open.spotify.com/... or https://soundcloud.com/..." autocomplete="off" data-lpignore="true" data-1p-ignore="true">
                <p class="text-xs editor-text-muted mt-1">Supports Spotify and SoundCloud links</p>
            </div>
        </div>
    </template>
    
    <!-- VCard Block Fields -->
    <template x-if="editingBlock.type === 'vcard'">
        <div class="space-y-4">
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" x-model="editingBlock.vcard_name" class="form-input" placeholder="John Doe" autocomplete="off" data-lpignore="true" data-1p-ignore="true" data-form-type="other">
            </div>
            <div class="form-group">
                <label class="form-label">Phone Number</label>
                <input type="tel" x-model="editingBlock.vcard_phone" class="form-input" placeholder="+1 234 567 8900" autocomplete="off" data-lpignore="true" data-1p-ignore="true" data-form-type="other">
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" x-model="editingBlock.vcard_email" class="form-input" placeholder="john@example.com" autocomplete="off" data-lpignore="true" data-1p-ignore="true" data-form-type="other">
            </div>
            <div class="form-group">
                <label class="form-label">Company (Optional)</label>
                <input type="text" x-model="editingBlock.vcard_company" class="form-input" placeholder="Company Name" autocomplete="off" data-lpignore="true" data-1p-ignore="true" data-form-type="other">
            </div>
        </div>
    </template>
    
    <!-- HTML Block Fields -->
    <template x-if="editingBlock.type === 'html'">
        <div class="space-y-4">
            <div class="form-group">
                <label class="form-label">Block Title</label>
                <input type="text" x-model="editingBlock.title" class="form-input" placeholder="Custom Widget" autocomplete="off" data-lpignore="true" data-1p-ignore="true">
            </div>
            <div class="form-group">
                <label class="form-label">HTML Code</label>
                <textarea x-model="editingBlock.html_content" class="form-input form-textarea font-mono text-sm" rows="8" placeholder="<div>Your custom HTML...</div>" style="min-height: 150px;"></textarea>
                <p class="text-xs editor-text-muted mt-1">⚠️ Only safe HTML tags are allowed. Scripts will be removed.</p>
            </div>
        </div>
    </template>
    
    <!-- Countdown Block Fields -->
    <template x-if="editingBlock.type === 'countdown'">
        <div class="space-y-4">
            <div class="form-group">
                <label class="form-label">Countdown Label</label>
                <input type="text" x-model="editingBlock.countdown_label" class="form-input" placeholder="Event starts in..." autocomplete="off" data-lpignore="true" data-1p-ignore="true">
            </div>
            <div class="form-group">
                <label class="form-label">Target Date & Time</label>
                <input type="datetime-local" x-model="editingBlock.countdown_date" class="form-input" autocomplete="off">
            </div>
            <div class="form-group">
                <label class="form-label">Title (Optional)</label>
                <input type="text" x-model="editingBlock.title" class="form-input" placeholder="Countdown Timer" autocomplete="off" data-lpignore="true" data-1p-ignore="true">
            </div>
        </div>
    </template>
    
    <!-- YouTube Block Fields -->
    <template x-if="editingBlock.type === 'youtube'">
        <div class="space-y-4">
            <div class="form-group">
                <label class="form-label">YouTube Video URL</label>
                <input type="url" x-model="editingBlock.embed_url" class="form-input" placeholder="https://youtube.com/watch?v=..." autocomplete="off" data-lpignore="true" data-1p-ignore="true">
                <p class="text-xs editor-text-muted mt-1">Paste the full YouTube video URL</p>
            </div>
            <div class="form-group">
                <label class="form-label">Title (Optional)</label>
                <input type="text" x-model="editingBlock.title" class="form-input" placeholder="My Video" autocomplete="off" data-lpignore="true" data-1p-ignore="true">
            </div>
        </div>
    </template>
    
    <!-- Spotify Block Fields -->
    <template x-if="editingBlock.type === 'spotify'">
        <div class="space-y-4">
            <div class="form-group">
                <label class="form-label">Spotify URL</label>
                <input type="url" x-model="editingBlock.embed_url" class="form-input" placeholder="https://open.spotify.com/track/..." autocomplete="off" data-lpignore="true" data-1p-ignore="true">
                <p class="text-xs editor-text-muted mt-1">Supports tracks, albums, playlists, and artists</p>
            </div>
            <div class="form-group">
                <label class="form-label">Title (Optional)</label>
                <input type="text" x-model="editingBlock.title" class="form-input" placeholder="My Music" autocomplete="off" data-lpignore="true" data-1p-ignore="true">
            </div>
        </div>
    </template>
    
    <!-- SoundCloud Block Fields -->
    <template x-if="editingBlock.type === 'soundcloud'">
        <div class="space-y-4">
            <div class="form-group">
                <label class="form-label">SoundCloud URL</label>
                <input type="url" x-model="editingBlock.embed_url" class="form-input" placeholder="https://soundcloud.com/..." autocomplete="off" data-lpignore="true" data-1p-ignore="true">
            </div>
            <div class="form-group">
                <label class="form-label">Title (Optional)</label>
                <input type="text" x-model="editingBlock.title" class="form-input" placeholder="My Track" autocomplete="off" data-lpignore="true" data-1p-ignore="true">
            </div>
        </div>
    </template>
    
    <!-- Email Signup Block Fields -->
    <template x-if="editingBlock.type === 'email'">
        <div class="space-y-4">
            <div class="form-group">
                <label class="form-label">Email Service URL</label>
                <input type="url" x-model="editingBlock.embed_url" class="form-input" placeholder="https://your-email-service.com/subscribe" autocomplete="off" data-lpignore="true" data-1p-ignore="true">
                <p class="text-xs editor-text-muted mt-1">Enter your email service signup form URL (Mailchimp, ConvertKit, etc.)</p>
            </div>
            <div class="form-group">
                <label class="form-label">Button Text</label>
                <input type="text" x-model="editingBlock.title" class="form-input" placeholder="Subscribe to Newsletter" autocomplete="off" data-lpignore="true" data-1p-ignore="true">
            </div>
            <div class="form-group">
                <label class="form-label">Description (Optional)</label>
                <textarea x-model="editingBlock.content" class="form-input form-textarea" rows="2" placeholder="Get the latest updates..."></textarea>
            </div>
        </div>
    </template>
    
    <!-- Map Block Fields -->
    <template x-if="editingBlock.type === 'map'">
        <div class="space-y-4">
            <div class="form-group">
                <label class="form-label">Location Title</label>
                <input type="text" x-model="editingBlock.title" class="form-input" placeholder="Our Office" autocomplete="off" data-lpignore="true" data-1p-ignore="true">
            </div>
            <div class="form-group">
                <label class="form-label">Address</label>
                <textarea x-model="editingBlock.map_address" class="form-input form-textarea" rows="2" placeholder="Jl. Example No. 123, Jakarta, Indonesia"></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Zoom Level</label>
                <input type="range" x-model="editingBlock.map_zoom" min="10" max="18" class="w-full">
                <p class="text-sm editor-text-muted text-center" x-text="'Zoom: ' + editingBlock.map_zoom"></p>
            </div>
        </div>
    </template>
    
    <!-- FAQ Block Fields -->
    <template x-if="editingBlock.type === 'faq'">
        <div class="space-y-4">
            <div class="form-group">
                <label class="form-label">FAQ Title</label>
                <input type="text" x-model="editingBlock.title" class="form-input" placeholder="Frequently Asked Questions">
            </div>
            <div class="form-group">
                <label class="form-label mb-3 block">FAQ Items</label>
                <template x-for="(item, index) in (editingBlock.faq_items || [])" :key="index">
                    <div class="p-3 bg-slate-50 dark:bg-slate-900 rounded-lg mb-3">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-xs font-medium editor-text-muted">Item #<span x-text="index + 1"></span></span>
                            <button @click="editingBlock.faq_items.splice(index, 1)" class="text-red-500 hover:text-red-700 text-xs">Remove</button>
                        </div>
                        <input type="text" x-model="item.question" class="form-input mb-2 text-sm" placeholder="Question">
                        <textarea x-model="item.answer" class="form-input form-textarea text-sm" rows="2" placeholder="Answer"></textarea>
                    </div>
                </template>
                <button @click="if(!editingBlock.faq_items) editingBlock.faq_items = []; editingBlock.faq_items.push({question: '', answer: ''})" class="btn btn-secondary w-full text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    Add FAQ Item
                </button>
            </div>
        </div>
    </template>
    
    <!-- Image Block Fields -->
    <template x-if="editingBlock.type === 'image'">
        <div class="space-y-4">
            <div class="form-group">
                <label class="form-label">Image Title (Optional)</label>
                <input type="text" x-model="editingBlock.title" class="form-input" placeholder="Image caption">
            </div>
            <div class="form-group">
                <label class="form-label">Upload Image</label>
                <div class="flex flex-col items-center gap-4">
                    <template x-if="editingBlock.thumbnail_url">
                        <div class="relative">
                            <img :src="editingBlock.thumbnail_url" class="max-w-full max-h-48 rounded-lg object-contain border border-slate-200 dark:[border-color:var(--editor-border)]">
                            <button @click="editingBlock.thumbnail_url = null" class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-sm hover:bg-red-600">×</button>
                        </div>
                    </template>
                    <input type="file" @change="uploadBlockImage($event, editingBlock)" accept="image/*" class="text-sm editor-text-muted file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900/50 dark:file:text-blue-300">
                    <p class="text-xs editor-text-muted">Supported: JPG, PNG, GIF (Max 5MB)</p>
                </div>
            </div>
        </div>
    </template>
    
    <!-- Active Toggle -->
    <div class="flex items-center justify-between pt-4 border-t border-slate-200 dark:[border-color:var(--editor-border)]">
        <div>
            <p class="font-medium text-slate-900 dark:text-white">Active</p>
            <p class="text-sm editor-text-muted">Show this block on your page</p>
        </div>
        <label class="toggle-switch">
            <input type="checkbox" x-model="editingBlock.is_active">
            <span class="toggle-slider"></span>
        </label>
    </div>
    
    <!-- Actions -->
    <div class="flex justify-end gap-3 pt-4">
        <button @click="editingBlock = null" class="btn btn-secondary">Cancel</button>
        <button @click="saveEditingBlock()" class="btn btn-primary">Save Changes</button>
    </div>
</div>
