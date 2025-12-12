<!-- Profile Tab Content -->
<div class="space-y-4">
    <!-- Profile & QR Code Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Profile Card -->
        <div class="editor-card">
            <div class="editor-card-header">
                <h3 class="editor-card-title">Profile</h3>
            </div>
        
        <div class="p-4">
            <!-- Avatar Row with responsive layout -->
            <div class="flex flex-col sm:flex-row gap-4 mb-4">
                <!-- Avatar with shape selector below -->
                <div class="flex flex-col items-center gap-3 flex-shrink-0">
                    <div class="relative">
                        <div class="w-24 h-24 rounded-full bg-slate-200 dark:bg-slate-700 overflow-hidden"
                             :class="{ 'rounded-xl': bioPage.avatar_shape === 'rounded', 'rounded-none': bioPage.avatar_shape === 'square' }">
                            <template x-if="bioPage.avatar_url">
                                <img :src="bioPage.avatar_url.startsWith('/storage/') || bioPage.avatar_url.startsWith('http') ? bioPage.avatar_url : '/storage/' + bioPage.avatar_url" alt="Avatar" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!bioPage.avatar_url">
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-500 to-indigo-600 text-white text-2xl font-bold" x-text="(bioPage.title || 'U').charAt(0).toUpperCase()"></div>
                            </template>
                        </div>
                        <!-- Camera button overlay -->
                        <input type="file" accept="image/*" class="hidden" x-ref="avatarInput" @change="uploadAvatar($event)">
                        <button @click="$refs.avatarInput.click()" 
                                class="absolute bottom-0 left-1/2 -translate-x-1/2 translate-y-1/3 w-8 h-8 bg-blue-600 hover:bg-blue-700 rounded-full flex items-center justify-center shadow-lg border-2 border-slate-900 transition-colors z-10" style="aspect-ratio: 1/1;">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Avatar Shape buttons - horizontal below avatar -->
                    <div class="flex flex-col items-center gap-1 mt-1">
                        <span class="text-xs editor-text-muted">Shape</span>
                        <div class="flex items-center gap-2">
                            <button @click="bioPage.avatar_shape = 'circle'" 
                                :class="bioPage.avatar_shape === 'circle' || !bioPage.avatar_shape ? 'ring-2 ring-blue-500 active' : ''"
                                class="option-btn w-8 h-8 rounded-lg transition-all flex items-center justify-center" title="Circle">
                                <svg class="w-4 h-4 editor-icon" viewBox="0 0 24 24" fill="currentColor">
                                    <circle cx="12" cy="12" r="10"/>
                                </svg>
                            </button>
                            <button @click="bioPage.avatar_shape = 'rounded'" 
                                    :class="bioPage.avatar_shape === 'rounded' ? 'ring-2 ring-blue-500 active' : ''"
                                    class="option-btn w-8 h-8 rounded-lg transition-all flex items-center justify-center" title="Rounded">
                                <svg class="w-4 h-4 editor-icon" viewBox="0 0 24 24" fill="currentColor">
                                    <rect x="2" y="2" width="20" height="20" rx="6"/>
                                </svg>
                            </button>
                            <button @click="bioPage.avatar_shape = 'square'" 
                                    :class="bioPage.avatar_shape === 'square' ? 'ring-2 ring-blue-500 active' : ''"
                                    class="option-btn w-8 h-8 rounded-lg transition-all flex items-center justify-center" title="Square">
                                <svg class="w-4 h-4 editor-icon" viewBox="0 0 24 24" fill="currentColor">
                                    <rect x="2" y="2" width="20" height="20"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Form fields -->
                <div class="flex-1 min-w-0 space-y-3">
                    <div class="form-group mb-0">
                        <label class="form-label">Display Name <span class="text-xs editor-text-muted" x-text="'(' + (bioPage.title?.length || 0) + '/25)'"></span></label>
                        <input type="text" x-model="bioPage.title" @input="bioPage.title = $event.target.value.slice(0, 25)" class="form-input" placeholder="Your name" maxlength="25" autocomplete="off" data-lpignore="true" data-1p-ignore="true" data-form-type="other">
                    </div>
                    
                    <div class="form-group mb-0">
                        <label class="form-label">Bio <span class="text-xs editor-text-muted" x-text="'(' + (bioPage.bio?.length || 0) + '/80)'"></span></label>
                        <textarea x-model="bioPage.bio" @input="bioPage.bio = $event.target.value.slice(0, 80)" class="form-input form-textarea" placeholder="Tell visitors about yourself..." rows="2" maxlength="80"></textarea>
                    </div>
                    
                    <!-- Badge Feature -->
                    <div class="form-group mb-0">
                        <label class="form-label">Badge</label>
                        <div class="flex items-center gap-3 flex-wrap">
                            <!-- Badge Type Selection -->
                            <div class="flex items-center gap-2">
                                <button @click="bioPage.badge = null" 
                                        :class="!bioPage.badge ? 'ring-2 ring-blue-500 active' : ''"
                                        class="option-btn w-8 h-8 rounded-lg transition-all flex items-center justify-center" title="No Badge">
                                    <svg class="w-4 h-4 editor-text-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                    </svg>
                                </button>
                                <button @click="bioPage.badge = 'verified'" 
                                        :class="bioPage.badge === 'verified' ? 'ring-2 ring-blue-500 active' : ''"
                                        class="option-btn w-8 h-8 rounded-lg transition-all flex items-center justify-center" title="Verified">
                                    <svg class="w-4 h-4" :style="{ color: bioPage.badge_color || '#3b82f6' }" viewBox="0 0 24 24" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.603 3.799A4.49 4.49 0 0112 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 013.498 1.307 4.491 4.491 0 011.307 3.497A4.49 4.49 0 0121.75 12a4.49 4.49 0 01-1.549 3.397 4.491 4.491 0 01-1.307 3.497 4.491 4.491 0 01-3.497 1.307A4.49 4.49 0 0112 21.75a4.49 4.49 0 01-3.397-1.549 4.49 4.49 0 01-3.498-1.306 4.491 4.491 0 01-1.307-3.498A4.49 4.49 0 012.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 011.307-3.497 4.49 4.49 0 013.497-1.307zm7.007 6.387a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                                <button @click="bioPage.badge = 'star'" 
                                        :class="bioPage.badge === 'star' ? 'ring-2 ring-blue-500 active' : ''"
                                        class="option-btn w-8 h-8 rounded-lg transition-all flex items-center justify-center" title="Star">
                                    <svg class="w-4 h-4" :style="{ color: bioPage.badge_color || '#eab308' }" viewBox="0 0 24 24" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                                <button @click="bioPage.badge = 'crown'" 
                                        :class="bioPage.badge === 'crown' ? 'ring-2 ring-blue-500 active' : ''"
                                        class="option-btn w-8 h-8 rounded-lg transition-all flex items-center justify-center" title="Crown">
                                    <svg class="w-4 h-4" :style="{ color: bioPage.badge_color || '#f59e0b' }" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M2 17l2-11 4 5 4-7 4 7 4-5 2 11H2z"/>
                                        <rect x="3" y="18" width="18" height="3" rx="1"/>
                                    </svg>
                                </button>
                                <button @click="bioPage.badge = 'fire'" 
                                        :class="bioPage.badge === 'fire' ? 'ring-2 ring-blue-500 active' : ''"
                                        class="option-btn w-8 h-8 rounded-lg transition-all flex items-center justify-center" title="Fire">
                                    <svg class="w-4 h-4" :style="{ color: bioPage.badge_color || '#ef4444' }" viewBox="0 0 24 24" fill="currentColor">
                                        <path fill-rule="evenodd" d="M12.963 2.286a.75.75 0 00-1.071-.136 9.742 9.742 0 00-3.539 6.177A7.547 7.547 0 016.648 6.61a.75.75 0 00-1.152.082A9 9 0 1015.68 4.534a7.46 7.46 0 01-2.717-2.248zM15.75 14.25a3.75 3.75 0 11-7.313-1.172c.628.465 1.35.81 2.133 1a5.99 5.99 0 011.925-3.545 3.75 3.75 0 013.255 3.717z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </div>
                            
                            <!-- Color Selection (only visible when badge is selected) -->
                            <template x-if="bioPage.badge">
                                <div class="flex items-center gap-1 ml-2">
                                    <span class="text-xs editor-text-muted mr-1">Color:</span>
                                    <!-- Rainbow order: Green, Blue, Black, Red, White, Yellow -->
                                    <button @click="bioPage.badge_color = '#22c55e'" 
                                            :class="bioPage.badge_color === '#22c55e' ? 'ring-2 ring-offset-1 ring-offset-slate-900 ring-white' : ''"
                                            class="w-5 h-5 rounded-full transition-all border border-slate-500/50" style="background-color: #22c55e" title="Green"></button>
                                    <button @click="bioPage.badge_color = '#3b82f6'" 
                                            :class="bioPage.badge_color === '#3b82f6' || !bioPage.badge_color ? 'ring-2 ring-offset-1 ring-offset-slate-900 ring-white' : ''"
                                            class="w-5 h-5 rounded-full transition-all border border-slate-500/50" style="background-color: #3b82f6" title="Blue"></button>
                                    <button @click="bioPage.badge_color = '#000000'" 
                                            :class="bioPage.badge_color === '#000000' ? 'ring-2 ring-offset-1 ring-offset-slate-900 ring-white' : ''"
                                            class="w-5 h-5 rounded-full transition-all border border-slate-500/50" style="background-color: #000000" title="Black"></button>
                                    <button @click="bioPage.badge_color = '#ef4444'" 
                                            :class="bioPage.badge_color === '#ef4444' ? 'ring-2 ring-offset-1 ring-offset-slate-900 ring-white' : ''"
                                            class="w-5 h-5 rounded-full transition-all border border-slate-500/50" style="background-color: #ef4444" title="Red"></button>
                                    <button @click="bioPage.badge_color = '#ffffff'" 
                                            :class="bioPage.badge_color === '#ffffff' ? 'ring-2 ring-offset-1 ring-offset-slate-900 ring-blue-500' : ''"
                                            class="w-5 h-5 rounded-full bg-white transition-all border border-slate-400" title="White"></button>
                                    <button @click="bioPage.badge_color = '#eab308'" 
                                            :class="bioPage.badge_color === '#eab308' ? 'ring-2 ring-offset-1 ring-offset-slate-900 ring-white' : ''"
                                            class="w-5 h-5 rounded-full transition-all border border-slate-500/50" style="background-color: #eab308" title="Yellow"></button>
                                    <!-- Color Picker -->
                                    <div class="relative">
                                        <input type="color" x-model="bioPage.badge_color" 
                                               class="w-5 h-5 rounded-full cursor-pointer opacity-0 absolute inset-0 z-10">
                                        <div class="w-5 h-5 rounded-full flex items-center justify-center cursor-pointer" 
                                             style="background: linear-gradient(135deg, #ef4444, #22c55e, #3b82f6); border: 1px solid rgba(255,255,255,0.3);" 
                                             title="Custom Color">
                                            <span style="font-size: 10px; color: white; font-weight: bold; text-shadow: 0 1px 2px rgba(0,0,0,0.5);">+</span>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
        <!-- QR Code Card -->
        <div class="editor-card" x-data="{ showQR: true }">
            <div class="editor-card-header cursor-pointer" @click="showQR = !showQR">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 editor-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h2M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                    <h3 class="editor-card-title">QR Code</h3>
                </div>
                <svg class="w-5 h-5 editor-text-muted transition-transform" :class="{ 'rotate-180': showQR }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
            
            <div x-show="showQR" x-collapse class="p-4">
                <div x-data="window.qrGenerator()" x-init="init()">
                    <!-- QR Preview -->
                    <div class="flex justify-center mb-4">
                        <div class="p-3 bg-white rounded-xl overflow-hidden">
                            <div x-ref="qrContainer" class="w-40 h-40 flex items-center justify-center">
                                <div class="editor-text-muted text-xs">Loading...</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- QR Customization -->
                    <div class="space-y-3">
                        <!-- Dot Style & Corner Styles -->
                        <div class="grid grid-cols-3 gap-2">
                            <!-- Dot Style Dropdown -->
                            <div class="form-group">
                                <label class="form-label text-xs">Dot Style</label>
                                <div class="relative">
                                    <select x-model="dotStyle" @change="updateQR(); saveSettings()" 
                                            class="w-full form-input text-xs appearance-none cursor-pointer focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="dots">◉ Dots</option>
                                        <option value="rounded">● Rounded</option>
                                        <option value="classy">◆ Classy</option>
                                        <option value="square">■ Square</option>
                                        <option value="extra-rounded">⬤ Extra</option>
                                    </select>
                                    <svg class="absolute right-2 top-1/2 -translate-y-1/2 w-3 h-3 editor-text-muted pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                            
                            <!-- Corner Square Dropdown -->
                            <div class="form-group">
                                <label class="form-label text-xs">Corner Sqr</label>
                                <div class="relative">
                                    <select x-model="cornerSquareStyle" @change="updateQR(); saveSettings()" 
                                            class="w-full form-input text-xs appearance-none cursor-pointer focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="dot">● Dot</option>
                                        <option value="extra-rounded">⬤ Rnd</option>
                                        <option value="square">▢ Sqr</option>
                                    </select>
                                    <svg class="absolute right-2 top-1/2 -translate-y-1/2 w-3 h-3 editor-text-muted pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>

                            <!-- Corner Dot Dropdown -->
                            <div class="form-group">
                                <label class="form-label text-xs">Corner Dot</label>
                                <div class="relative">
                                    <select x-model="cornerDotStyle" @change="updateQR(); saveSettings()" 
                                            class="w-full form-input text-xs appearance-none cursor-pointer focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="dot">● Dot</option>
                                        <option value="square">▢ Sqr</option>
                                    </select>
                                    <svg class="absolute right-2 top-1/2 -translate-y-1/2 w-3 h-3 editor-text-muted pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Logo Upload -->
                        <div class="form-group">
                            <label class="form-label text-xs">Logo (optional)</label>
                            <div class="flex items-center gap-2">
                                <input type="file" accept="image/*" @change="uploadLogo($event)" class="flex-1 text-xs editor-text-muted file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-blue-600 file:text-white file:font-medium hover:file:bg-blue-700">
                                <button x-show="logoUrl" @click="removeLogo()" class="px-2 py-1 text-xs bg-red-600 hover:bg-red-700 text-white rounded-lg">×</button>
                            </div>
                            <template x-if="logoUrl">
                                <div class="mt-2 flex items-center gap-2 p-2 rounded-lg" style="background: var(--editor-input-bg);">
                                    <img :src="logoUrl" class="w-8 h-8 rounded object-cover" alt="Logo">
                                    <span class="text-xs text-green-400 flex-1">✓ Logo saved</span>
                                </div>
                            </template>
                        </div>
                        
                        <!-- Colors -->
                        <div class="grid grid-cols-2 gap-2">
                            <div class="form-group">
                                <label class="form-label text-xs">QR Color</label>
                                <div class="flex gap-1 flex-wrap items-center">
                                    <template x-for="color in ['#000000', '#1E40AF', '#EF4444', '#10B981', '#8B5CF6']" :key="color">
                                        <button @click="setColor(color)" 
                                                :class="{ 'ring-2 ring-blue-500': qrColor === color }"
                                                :style="{ backgroundColor: color }"
                                                class="w-5 h-5 rounded "></button>
                                    </template>
                                    <div class="relative">
                                        <input type="color" x-model="qrColor" @input="debouncedUpdateQR()" 
                                               class="w-5 h-5 rounded cursor-pointer opacity-0 absolute inset-0 z-10">
                                        <div class="w-5 h-5 rounded flex items-center justify-center cursor-pointer" style="background: linear-gradient(135deg, #ef4444, #22c55e, #3b82f6); border: 1px solid rgba(255,255,255,0.3);">
                                            <span style="font-size: 12px; color: white; font-weight: bold; text-shadow: 0 1px 2px rgba(0,0,0,0.5);">+</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label text-xs">Background</label>
                                <div class="flex gap-1 flex-wrap items-center">
                                    <template x-for="color in ['#ffffff', '#F3F4F6', '#FEF3C7', '#DBEAFE', '#FEE2E2']" :key="color">
                                        <button @click="setBgColor(color)" 
                                                :class="{ 'ring-2 ring-blue-500': bgColor === color }"
                                                :style="{ backgroundColor: color }"
                                                class="w-5 h-5 rounded "></button>
                                    </template>
                                    <div class="relative">
                                        <input type="color" x-model="bgColor" @input="debouncedUpdateQR()" 
                                               class="w-5 h-5 rounded cursor-pointer opacity-0 absolute inset-0 z-10">
                                        <div class="w-5 h-5 rounded flex items-center justify-center cursor-pointer" style="background: linear-gradient(135deg, #eab308, #ec4899, #06b6d4); border: 1px solid rgba(255,255,255,0.3);">
                                            <span style="font-size: 12px; color: white; font-weight: bold; text-shadow: 0 1px 2px rgba(0,0,0,0.5);">+</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Download Button -->
                        <div class="flex gap-2">
                            <button @click="downloadQR('png')" class="flex-1 btn btn-secondary text-xs py-2">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                PNG
                            </button>
                            <button @click="downloadQR('svg')" class="flex-1 btn btn-secondary text-xs py-2">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                SVG
                            </button>
                        </div>
                        
                        <!-- Save QR Design Button -->
                        <button @click="saveQrDesign()" class="btn btn-primary text-xs py-1.5 px-3 mt-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<script>
window.qrGenerator = function() {
    return {
        qrColor: '#000000',
        bgColor: '#ffffff',
        dotStyle: 'rounded',
        cornerSquareStyle: 'extra-rounded',
        cornerDotStyle: 'dot',
        logoUrl: null,
        qrCodeInstance: null,
        qrLoaded: false,
        updateTimer: null,
        
        getBioPage() {
            // Try multiple ways to access bioPage
            if (window.bioEditorData?.bioPage) {
                return window.bioEditorData.bioPage;
            }
            // Try parent scope
            let el = this.$el;
            while (el) {
                if (el._x_dataStack) {
                    for (const data of el._x_dataStack) {
                        if (data.bioPage) return data.bioPage;
                    }
                }
                el = el.parentElement;
            }
            return {};
        },
        
        init() {
            // Load QR styling library first
            if (typeof QRCodeStyling === 'undefined') {
                const script = document.createElement('script');
                script.src = 'https://unpkg.com/qr-code-styling@1.5.0/lib/qr-code-styling.js';
                script.onload = () => {
                    this.qrLoaded = true;
                    this.loadSettings();
                    this.createQR();
                };
                document.head.appendChild(script);
            } else {
                this.qrLoaded = true;
                this.loadSettings();
                this.createQR();
            }
        },
        
        loadSettings() {
            const bioPage = this.getBioPage();
            const qrSettings = bioPage?.qr_settings || {};
            
            this.qrColor = qrSettings.dotsOptions?.color || qrSettings.color || '#000000';
            this.bgColor = qrSettings.backgroundOptions?.color || qrSettings.bg_color || '#ffffff';
            this.dotStyle = qrSettings.dotsOptions?.type || qrSettings.dot_style || 'rounded';
            this.cornerSquareStyle = qrSettings.cornersSquareOptions?.type || qrSettings.corner_style || 'extra-rounded';
            this.cornerDotStyle = qrSettings.cornersDotOptions?.type || 'dot';
            this.logoUrl = qrSettings.image || qrSettings.logo_url || null;
        },
        
        saveSettings() {
            const bioPage = this.getBioPage();
            if (bioPage) {
                bioPage.qr_settings = {
                    dotsOptions: { color: this.qrColor, type: this.dotStyle },
                    backgroundOptions: { color: this.bgColor },
                    cornersSquareOptions: { color: this.qrColor, type: this.cornerSquareStyle },
                    cornersDotOptions: { color: this.qrColor, type: this.cornerDotStyle },
                    image: this.logoUrl
                };
            }
        },
        
        saveQrDesign() {
            // Update local settings
            this.saveSettings();
            
            // Find parent Alpine scope and trigger saveBioPage
            let el = this.$el;
            while (el) {
                if (el._x_dataStack) {
                    for (const data of el._x_dataStack) {
                        if (typeof data.saveBioPage === 'function') {
                            data.saveBioPage();
                            this.showQrNotification('QR design saved successfully!', 'success');
                            return;
                        }
                    }
                }
                el = el.parentElement;
            }
            
            // Fallback notification
            this.showQrNotification('QR design updated locally. Click main "Save" button to persist.', 'info');
        },
        
        generateQR() {
            if (!this.qrLoaded) return;
            this.createQR();
        },
        
        createQR() {
            if (typeof QRCodeStyling === 'undefined') return;
            
            const bioPage = this.getBioPage();
            const url = window.location.origin + '/b/' + (bioPage?.slug || 'preview');
            
            this.qrCodeInstance = new QRCodeStyling({
                width: 160,
                height: 160,
                type: 'svg',
                data: url,
                dotsOptions: {
                    color: this.qrColor,
                    type: this.dotStyle
                },
                backgroundOptions: {
                    color: this.bgColor
                },
                cornersSquareOptions: {
                    color: this.qrColor,
                    type: this.cornerSquareStyle
                },
                cornersDotOptions: {
                    color: this.qrColor,
                    type: this.cornerDotStyle
                },
                imageOptions: {
                    crossOrigin: 'anonymous',
                    margin: 5,
                    imageSize: 0.4
                },
                image: this.logoUrl || undefined
            });
            
            if (this.$refs.qrContainer) {
                this.$refs.qrContainer.innerHTML = '';
                this.qrCodeInstance.append(this.$refs.qrContainer);
            }
        },
        
        setColor(color) {
            this.qrColor = color;
            this.updateQR();
            this.saveSettings();
        },
        
        setBgColor(color) {
            this.bgColor = color;
            this.updateQR();
            this.saveSettings();
        },
        
        updateQR() {
            if (this.qrCodeInstance) {
                this.qrCodeInstance.update({
                    dotsOptions: {
                        color: this.qrColor,
                        type: this.dotStyle
                    },
                    backgroundOptions: {
                        color: this.bgColor
                    },
                    cornersSquareOptions: {
                        type: this.cornerSquareStyle
                    },
                    cornersDotOptions: {
                        type: this.cornerDotStyle
                    },
                    image: this.logoUrl || undefined
                });
            }
        },
        
        // Debounced version untuk color picker
        debouncedUpdateQR() {
            if (this.updateTimer) clearTimeout(this.updateTimer);
            this.updateTimer = setTimeout(() => {
                this.updateQR();
                this.saveSettings();
            }, 100);
        },
        
        async uploadLogo(event) {
            const file = event.target.files[0];
            if (!file) return;
            
            const bioPage = this.getBioPage();
            if (!bioPage?.id) {
                this.showQrNotification('Unable to upload: Bio page not found', 'error');
                return;
            }
            
            // Validate file size (max 1MB)
            if (file.size > 1024 * 1024) {
                this.showQrNotification('Logo file must be less than 1MB', 'error');
                event.target.value = '';
                return;
            }
            
            // Validate file type
            if (!file.type.startsWith('image/')) {
                this.showQrNotification('Please select an image file', 'error');
                event.target.value = '';
                return;
            }
            
            const formData = new FormData();
            formData.append('logo', file);
            
            try {
                const response = await fetch(`/bio/${bioPage.id}/upload-qr-logo`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    this.logoUrl = result.logo_url;
                    if (this.qrCodeInstance) {
                        this.qrCodeInstance.update({ image: this.logoUrl });
                    }
                    this.saveSettings();
                    this.showQrNotification('Logo uploaded successfully', 'success');
                } else {
                    this.showQrNotification('Failed to upload: ' + (result.message || 'Unknown error'), 'error');
                }
            } catch (error) {
                console.error('Upload error:', error);
                this.showQrNotification('Failed to upload logo. Please try again.', 'error');
            }
            
            event.target.value = '';
        },
        
        showQrNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed bottom-4 right-4 z-[200] px-4 py-2 rounded-lg shadow-lg text-white text-sm ${type === 'success' ? 'bg-green-600' : type === 'error' ? 'bg-red-600' : 'bg-blue-600'}`;
            notification.textContent = message;
            document.body.appendChild(notification);
            setTimeout(() => notification.remove(), 3000);
        },
        
        removeLogo() {
            this.logoUrl = null;
            if (this.qrCodeInstance) {
                this.qrCodeInstance.update({ image: undefined });
            }
            this.saveSettings();
        },
        
        async downloadQR(extension) {
            const bioPage = this.getBioPage();
            if (!this.qrCodeInstance) return;
            
            const url = window.location.origin + '/b/' + (bioPage?.slug || 'bio');
            const qrSize = 400;
            const padding = 40;
            const totalSize = qrSize + (padding * 2);
            
            // Buat temporary QR untuk download dengan ukuran besar
            const downloadQR = new QRCodeStyling({
                width: qrSize,
                height: qrSize,
                type: 'canvas',
                data: url,
                dotsOptions: {
                    color: this.qrColor,
                    type: this.dotStyle
                },
                backgroundOptions: {
                    color: 'transparent'
                },
                cornersSquareOptions: {
                    type: this.cornerSquareStyle
                },
                cornersDotOptions: {
                    type: this.cornerDotStyle
                },
                imageOptions: {
                    crossOrigin: 'anonymous',
                    margin: 10,
                    imageSize: 0.4
                },
                image: this.logoUrl || undefined
            });
            
            try {
                if (extension === 'svg') {
                    await downloadQR.download({ 
                        name: 'qr-' + (bioPage?.slug || 'bio'), 
                        extension: 'svg' 
                    });
                } else {
                    // Get raw canvas blob dari QRCodeStyling
                    const blob = await downloadQR.getRawData('png');
                    
                    if (!blob) {
                        throw new Error('Failed to generate QR code');
                    }
                    
                    // Create image dari blob
                    const img = new Image();
                    const blobUrl = URL.createObjectURL(blob);
                    
                    await new Promise((resolve, reject) => {
                        img.onload = resolve;
                        img.onerror = reject;
                        img.src = blobUrl;
                    });
                    
                    // Buat canvas dengan padding dan background
                    const paddedCanvas = document.createElement('canvas');
                    paddedCanvas.width = totalSize;
                    paddedCanvas.height = totalSize;
                    const ctx = paddedCanvas.getContext('2d');
                    
                    // Fill background
                    ctx.fillStyle = this.bgColor;
                    ctx.fillRect(0, 0, totalSize, totalSize);
                    
                    // Draw QR di tengah dengan padding
                    ctx.drawImage(img, padding, padding, qrSize, qrSize);
                    
                    // Cleanup blob URL
                    URL.revokeObjectURL(blobUrl);
                    
                    // Download
                    const link = document.createElement('a');
                    link.download = 'qr-' + (bioPage?.slug || 'bio') + '.' + extension;
                    link.href = paddedCanvas.toDataURL('image/' + (extension === 'png' ? 'png' : 'jpeg'), 1.0);
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }
                
                this.showQrNotification('QR Code downloaded successfully', 'success');
            } catch (error) {
                console.error('Download QR error:', error);
                this.showQrNotification('Failed to download QR code', 'error');
            }
        }
    };
}
</script>
@endpush
