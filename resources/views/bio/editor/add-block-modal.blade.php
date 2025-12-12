<!-- Add Block Modal Content -->

<div class="space-y-4">
    <!-- Search Bar -->
    <div class="relative flex items-center">
        <svg class="absolute left-3 w-4 h-4 pointer-events-none" style="color: var(--editor-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <input type="text" x-model="blockSearch" placeholder="Search blocks or platforms..." class="form-input pl-10 pr-10 py-2 w-full" autocomplete="off" data-lpignore="true" data-1p-ignore="true">
        <button x-show="blockSearch" @click="blockSearch = ''" class="absolute right-3" style="color: var(--editor-text-muted);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    
    <!-- Basic Blocks -->
    <div>
        <h4 class="text-xs font-medium mb-2" style="color: var(--editor-text-muted);">Basic Blocks</h4>
        <div class="grid grid-cols-4 gap-2">
            <button @click="addBlock('link')" class="block-modal-btn p-2 rounded-lg hover:border-blue-500 hover:bg-blue-900/20 transition-all text-center group">
                <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center mx-auto mb-1">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                </div>
                <p class="text-xs font-medium" style="color: var(--editor-text);">Link</p>
            </button>
            
            <button @click="addQuickBlock('text')" class="block-modal-btn p-2 rounded-lg hover:border-green-500 hover:bg-green-900/20 transition-all text-center group">
                <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center mx-auto mb-1">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                    </svg>
                </div>
                <p class="text-xs font-medium" style="color: var(--editor-text);">Text</p>
            </button>
            
            <button @click="addQuickBlock('image')" class="block-modal-btn p-2 rounded-lg hover:border-rose-500 hover:bg-rose-900/20 transition-all text-center group">
                <div class="w-10 h-10 bg-rose-500 rounded-lg flex items-center justify-center mx-auto mb-1">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <p class="text-xs font-medium" style="color: var(--editor-text);">Image</p>
            </button>
            
            <button @click="addDividerInstant(); showAddBlockModal = false" class="block-modal-btn p-2 rounded-lg hover:border-slate-500 transition-all text-center group">
                <div class="w-10 h-10 bg-slate-600 rounded-lg flex items-center justify-center mx-auto mb-1">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                    </svg>
                </div>
                <p class="text-xs font-medium" style="color: var(--editor-text);">Divider</p>
            </button>
        </div>
    </div>
    
    <!-- Media & Interactive Blocks Combined -->
    <div>
        <h4 class="text-xs font-medium mb-2" style="color: var(--editor-text-muted);">Media & Interactive</h4>
        <div class="grid grid-cols-5 gap-2">
            <button @click="addBlock('youtube')" class="block-modal-btn p-2 rounded-lg hover:border-red-500 hover:bg-red-900/20 transition-all text-center group">
                <div class="w-8 h-8 bg-red-600 rounded-lg flex items-center justify-center mx-auto mb-1">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                    </svg>
                </div>
                <p class="text-[10px] font-medium" style="color: var(--editor-text);">YouTube</p>
            </button>
            
            <button @click="addBlock('spotify')" class="block-modal-btn p-2 rounded-lg hover:border-green-500 hover:bg-green-500/20 transition-all text-center group">
                <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center mx-auto mb-1">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 0C5.4 0 0 5.4 0 12s5.4 12 12 12 12-5.4 12-12S18.66 0 12 0zm5.521 17.34c-.24.359-.66.48-1.021.24-2.82-1.74-6.36-2.101-10.561-1.141-.418.122-.779-.179-.899-.539-.12-.421.18-.78.54-.9 4.56-1.021 8.52-.6 11.64 1.32.42.18.479.659.301 1.02zm1.44-3.3c-.301.42-.841.6-1.262.3-3.239-1.98-8.159-2.58-11.939-1.38-.479.12-1.02-.12-1.14-.6-.12-.48.12-1.021.6-1.141C9.6 9.9 15 10.561 18.72 12.84c.361.181.54.78.241 1.2zm.12-3.36C15.24 8.4 8.82 8.16 5.16 9.301c-.6.179-1.2-.181-1.38-.721-.18-.601.18-1.2.72-1.381 4.26-1.26 11.28-1.02 15.721 1.621.539.3.719 1.02.419 1.56-.299.421-1.02.599-1.559.3z"/>
                    </svg>
                </div>
                <p class="text-[10px] font-medium" style="color: var(--editor-text);">Spotify</p>
            </button>
            
            <button @click="addBlock('soundcloud')" class="block-modal-btn p-2 rounded-lg hover:border-orange-500 hover:bg-orange-900/20 transition-all text-center group">
                <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center mx-auto mb-1">
                    <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M1.175 12.225c-.051 0-.094.046-.101.1l-.233 2.154.233 2.105c.007.058.05.098.101.098.05 0 .09-.04.099-.098l.255-2.105-.27-2.154c-.01-.057-.048-.1-.084-.1zm-.899.828c-.06 0-.091.037-.104.094l-.21 1.236.209 1.206c.013.057.045.094.104.094.057 0 .09-.037.103-.094l.232-1.206-.232-1.236c-.013-.057-.045-.094-.103-.094zm1.83-1.229c-.06 0-.102.045-.111.105l-.22 2.53.22 2.46c.009.06.05.105.111.105.062 0 .102-.045.114-.105l.25-2.46-.25-2.53c-.012-.06-.052-.105-.114-.105zm.872-.417c-.073 0-.119.056-.129.117l-.21 2.946.21 2.852c.01.061.056.117.13.117.07 0 .117-.056.125-.117l.235-2.852-.235-2.946c-.008-.061-.055-.117-.126-.117zm.872-.468c-.074 0-.121.057-.132.122l-.2 3.414.2 3.283c.01.065.058.122.132.122.073 0 .12-.057.131-.122l.226-3.283-.226-3.414c-.011-.065-.058-.122-.131-.122zm.871-.379c-.084 0-.135.063-.145.131l-.19 3.793.19 3.661c.01.068.061.13.145.13.082 0 .133-.062.144-.13l.213-3.661-.213-3.793c-.01-.068-.062-.131-.144-.131zm.87-.318c-.094 0-.148.07-.158.143l-.18 4.111.18 3.949c.01.073.064.143.158.143.093 0 .148-.07.158-.143l.204-3.949-.204-4.111c-.01-.073-.065-.143-.158-.143zm.871-.258c-.105 0-.16.077-.171.156l-.17 4.37.17 4.187c.011.078.066.155.171.155.104 0 .16-.077.172-.155l.193-4.187-.193-4.37c-.012-.079-.068-.156-.172-.156zm2.612-.16c-.044-.013-.092-.02-.14-.02-.15 0-.29.066-.39.176-.06.066-.105.153-.105.262v.02l-.16 4.533.16 4.247c.003.078.023.147.063.2.057.073.148.122.252.122.09 0 .17-.04.227-.105.053-.06.09-.143.09-.237l.178-4.227-.178-4.553v-.02c0-.082-.024-.154-.057-.205-.07-.116-.186-.193-.34-.193zm1.09-.054c-.14 0-.27.07-.348.178-.05.068-.084.153-.084.25v.018l-.153 4.548.153 4.2c.003.092.038.178.097.248.08.092.196.15.335.15.125 0 .236-.048.32-.128.063-.06.105-.137.112-.233l.172-4.237-.172-4.566v-.018c0-.092-.03-.175-.082-.242-.08-.106-.21-.178-.35-.178zm1.119-.02c-.157 0-.295.08-.377.199-.053.076-.084.168-.087.27v.005l-.15 4.544.15 4.155c.003.1.035.19.092.265.083.11.213.182.372.182.143 0 .267-.06.355-.155.065-.07.105-.158.11-.26l.17-4.187-.17-4.544-.002-.01c-.004-.096-.037-.18-.088-.251-.085-.108-.214-.178-.375-.178zm6.232 1.092c-.317 0-.623.052-.91.148-.162-1.89-1.734-3.37-3.666-3.37-.472 0-.933.097-1.353.27-.166.068-.21.137-.21.276v6.618c0 .143.124.264.28.28h5.86c1.06 0 1.92-.862 1.92-1.923 0-1.062-.86-1.925-1.92-1.925l-.001.626z"/>
                    </svg>
                </div>
                <p class="text-[10px] font-medium" style="color: var(--editor-text);">SoundCloud</p>
            </button>
            
            <button @click="addBlock('countdown')" class="block-modal-btn p-2 rounded-lg hover:border-cyan-500 hover:bg-cyan-900/20 transition-all text-center group">
                <div class="w-8 h-8 bg-cyan-500 rounded-lg flex items-center justify-center mx-auto mb-1">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-[10px] font-medium" style="color: var(--editor-text);">Countdown</p>
            </button>
            
            <button @click="addBlock('map')" class="block-modal-btn p-2 rounded-lg hover:border-teal-500 hover:bg-teal-900/20 transition-all text-center group">
                <div class="w-8 h-8 bg-teal-500 rounded-lg flex items-center justify-center mx-auto mb-1">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <p class="text-[10px] font-medium" style="color: var(--editor-text);">Location</p>
            </button>
        </div>
    </div>
    
    <!-- Brand Link Templates -->
    <div>
        <h4 class="text-sm font-medium mb-3" style="color: var(--editor-text-muted);">Platform Links</h4>
        
        <!-- Category Filter -->
        <div class="flex flex-wrap gap-2 mb-3">
            <button @click="blockCategoryFilter = 'all'" 
                :class="blockCategoryFilter === 'all' ? 'bg-blue-600 text-white' : 'filter-inactive'"
                class="px-3 py-1 rounded-full text-xs font-medium transition-all">All</button>
            <button @click="blockCategoryFilter = 'social'" 
                :class="blockCategoryFilter === 'social' ? 'bg-blue-600 text-white' : 'filter-inactive'"
                class="px-3 py-1 rounded-full text-xs font-medium transition-all">Social</button>
            <button @click="blockCategoryFilter = 'music'" 
                :class="blockCategoryFilter === 'music' ? 'bg-blue-600 text-white' : 'filter-inactive'"
                class="px-3 py-1 rounded-full text-xs font-medium transition-all">Music</button>
            <button @click="blockCategoryFilter = 'gaming'" 
                :class="blockCategoryFilter === 'gaming' ? 'bg-blue-600 text-white' : 'filter-inactive'"
                class="px-3 py-1 rounded-full text-xs font-medium transition-all">Gaming</button>
            <button @click="blockCategoryFilter = 'developer'" 
                :class="blockCategoryFilter === 'developer' ? 'bg-blue-600 text-white' : 'filter-inactive'"
                class="px-3 py-1 rounded-full text-xs font-medium transition-all">Dev</button>
            <button @click="blockCategoryFilter = 'messaging'" 
                :class="blockCategoryFilter === 'messaging' ? 'bg-blue-600 text-white' : 'filter-inactive'"
                class="px-3 py-1 rounded-full text-xs font-medium transition-all">Messaging</button>
        </div>
        
        <div class="grid grid-cols-5 gap-2 max-h-64 overflow-y-auto pr-2">
            @foreach(config('brands.platforms', []) as $brandId => $brand)
            <button 
                @click="addBrandBlock('{{ $brandId }}')"
                x-show="(blockCategoryFilter === 'all' || '{{ $brand['category'] ?? 'generic' }}' === blockCategoryFilter) && (blockSearch === '' || '{{ strtolower($brand['name'] ?? $brandId) }}'.includes(blockSearch.toLowerCase()) || '{{ $brandId }}'.includes(blockSearch.toLowerCase()))"
                class="block-modal-btn p-3 rounded-lg hover:border-blue-500 hover:shadow-md transition-all group"
            >
                <div class="w-10 h-10 rounded-lg flex items-center justify-center mx-auto mb-1" style="background-color: {{ $brand['color'] ?? '#6b7280' }}">
                    <img src="/images/brands/{{ $brand['icon'] ?? $brandId . '.svg' }}" class="w-6 h-6" style="filter: brightness(0) invert(1);" alt="{{ $brand['name'] ?? $brandId }}" onerror="this.style.display='none'">
                </div>
                <p class="text-xs text-center font-medium truncate" style="color: var(--editor-text-muted);">{{ $brand['name'] ?? ucfirst($brandId) }}</p>
            </button>
            @endforeach
        </div>
    </div>
</div>

<style>
    .block-modal-btn {
        border: 1px solid var(--editor-border);
    }
    .block-modal-btn:hover {
        background: var(--editor-hover-bg);
    }
    .filter-inactive {
        background: var(--editor-card-bg);
        color: var(--editor-text);
        border: 1px solid var(--editor-border);
    }
</style>
