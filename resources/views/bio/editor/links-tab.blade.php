<!-- Build Tab Content (formerly Links) -->
<div class="space-y-4">
    <!-- Two Column Layout: Your Blocks + Quick Add -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Your Blocks (2/3 width) -->
        <div class="lg:col-span-2 editor-card">
            <div class="editor-card-header">
                <h3 class="editor-card-title">Your Blocks</h3>
                <span class="text-xs editor-text-muted" x-text="blocks.length + ' blocks'"></span>
            </div>
            <div class="p-4">
                <!-- Blocks List (Sortable) -->
                <div id="blocks-list" class="space-y-2 mb-4" x-show="blocks.length > 0">
                    <template x-for="(item, idx) in blocks" :key="item.id">
                        <div class="block-item" :data-id="item.id" :class="{ 'opacity-50': item && !item.is_active }">
                            <!-- Drag Handle -->
                            <div class="block-drag-handle">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                                </svg>
                            </div>
                            <!-- Block Icon -->
                            <div class="block-icon" :style="{ backgroundColor: item.brand ? getPlatformColor(item.brand) : getBlockTypeColor(item.type) }">
                                <template x-if="item.custom_icon">
                                    <img :src="item.custom_icon" alt="" class="w-5 h-5 object-contain">
                                </template>
                                <template x-if="!item.custom_icon && item.brand">
                                    <img :src="getPlatformIcon(item.brand)" alt="" class="w-5 h-5" style="filter: brightness(0) invert(1);" onerror="this.style.display='none'">
                                </template>
                                <template x-if="!item.custom_icon && !item.brand">
                                    <div x-html="getBlockTypeIcon(item.type)"></div>
                                </template>
                            </div>
                            <!-- Block Content -->
                            <div class="block-content">
                                <div class="text-sm font-medium editor-text truncate" x-text="getBlockDisplayTitle(item)"></div>
                                <div class="text-xs editor-text-muted truncate" x-text="item.url || item.type"></div>
                            </div>
                            <!-- Block Actions -->
                            <div class="block-actions">
                                <!-- Visibility Toggle -->
                                <label class="toggle-switch">
                                    <input type="checkbox" x-model="item.is_active">
                                    <span class="toggle-slider"></span>
                                </label>
                                <!-- Edit Button -->
                                <button @click="openBlockEditor(item)" class="block-action-btn">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <!-- Delete Button -->
                                <button @click="removeBlock(idx)" class="block-action-btn delete">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
                <!-- Empty State -->
                <div x-show="blocks.length === 0" class="text-center py-8">
                    <svg class="w-12 h-12 mx-auto editor-text-muted mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <p class="editor-text-muted">No blocks yet</p>
                    <p class="text-sm editor-text-muted">Add your first block →</p>
                </div>
                <!-- Add Block Button -->
                <button @click="showAddBlockModal = true" class="w-full py-3 border-2 border-dashed [border-color:var(--editor-border)] rounded-xl editor-text-muted hover:editor-text hover:border-blue-500 transition-colors flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Add Block
                </button>
            </div>
        </div>
        <!-- Quick Add (1/3 width) -->
        <div class="lg:col-span-1 editor-card h-fit sticky top-4">
            <div class="editor-card-header">
                <h3 class="editor-card-title">Quick Add</h3>
            </div>
            <div class="p-3">
                <div class="grid grid-cols-2 gap-2">
                    <button @click="addQuickBlock('link')" class="quick-add-btn-mini group">
                        <div class="quick-add-icon-mini bg-blue-500">
                            <img src="/images/quick-add/link.svg" alt="Link" class="w-5 h-5" style="filter: brightness(0) invert(1);">
                        </div>
                        <span>Link</span>
                    </button>
                    <button @click="addQuickBlock('text')" class="quick-add-btn-mini group">
                        <div class="quick-add-icon-mini bg-green-500">
                            <img src="/images/quick-add/text.svg" alt="Text" class="w-5 h-5" style="filter: brightness(0) invert(1);">
                        </div>
                        <span>Text</span>
                    </button>
                    <button @click="addQuickBlock('image')" class="quick-add-btn-mini group">
                        <div class="quick-add-icon-mini" style="background-color: #f43f5e;">
                            <img src="/images/quick-add/image.svg" alt="Image" class="w-5 h-5" style="filter: brightness(0) invert(1);">
                        </div>
                        <span>Image</span>
                    </button>
                    <button @click="addDividerInstant()" class="quick-add-btn-mini group">
                        <div class="quick-add-icon-mini" style="background-color: #64748b;">
                            <img src="/images/quick-add/divider.svg" alt="Divider" class="w-5 h-5" style="filter: brightness(0) invert(1);">
                        </div>
                        <span>Divider</span>
                    </button>
                </div>
                <!-- Popular Brands Section -->
                <div class="mt-4 pt-3" style="border-top: 1px solid var(--editor-border);">
                    <p class="text-xs mb-2" style="color: var(--editor-text-muted);">Popular</p>
                    <div class="grid grid-cols-3 gap-2">
                        <template x-for="brandId in ['instagram', 'tiktok', 'youtube', 'twitter', 'spotify', 'whatsapp', 'telegram', 'email', 'facebook']" :key="brandId">
                            <button @click="addBrandBlock(brandId)" 
                                    class="p-2 rounded-lg transition-colors flex flex-col items-center gap-1" 
                                    style="border: 1px solid var(--editor-border);"
                                    onmouseover="this.style.background='var(--editor-hover-bg)'"
                                    onmouseout="this.style.background=''"
                                    :title="brands[brandId]?.name">
                                <div class="w-8 h-8 rounded-md flex items-center justify-center" 
                                     :style="{ backgroundColor: brands[brandId]?.color || '#6b7280' }">
                                    <img :src="'/images/brands/' + brandId + '.svg'" class="w-5 h-5" style="filter: brightness(0) invert(1);" onerror="this.style.display='none'">
                                </div>
                                <span class="text-[10px] capitalize truncate w-full text-center" style="color: var(--editor-text-muted);" x-text="brandId === 'twitter' ? 'X' : brandId"></span>
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .quick-add-btn-mini {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.375rem;
        padding: 0.625rem;
        background: var(--editor-card-bg);
        border: 1px solid var(--editor-border);
        border-radius: 0.75rem;
        transition: all 0.2s;
        color: var(--editor-text);
        font-size: 0.75rem;
        font-weight: 500;
        min-height: 85px;
    }
    .quick-add-btn-mini:hover {
        background: var(--editor-hover-bg);
        border-color: var(--editor-text-muted);
    }
    .quick-add-btn-mini:hover .quick-add-icon-mini {
        transform: scale(1.05);
    }
    .quick-add-icon-mini {
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        transition: transform 0.2s;
        flex-shrink: 0;
        width: 36px;
        height: 36px;
        border-radius: 10px;
    }
    .quick-add-icon-mini img {
        width: 18px;
        height: 18px;
    }
</style>
