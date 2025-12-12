<div class="space-y-6" x-data="socialLinksManager()" x-init="if (!activeSocials) { const notification = document.createElement('div'); notification.className = 'fixed top-4 right-4 z-50 rounded-lg bg-red-600 p-4 text-white shadow-2xl'; notification.innerHTML = '<p class=\'font-medium\'>✕ socialLinksManager() failed to initialize!</p>'; document.body.appendChild(notification); }">
    <!-- Sortable.js for drag and drop -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <style>
        .social-list-scroll::-webkit-scrollbar {
            width: 6px;
        }
        .social-list-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .social-list-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        .social-list-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        .dark .social-list-scroll::-webkit-scrollbar-thumb {
            background: #475569;
        }
        .dark .social-list-scroll::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }
    </style>
    <!-- Header -->
    <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-slate-800 border-t-4 border-purple-600 dark:border-purple-400">
        <h3 class="mb-2 text-2xl font-semibold text-slate-900 dark:text-white">Social icons</h3>
        <p class="text-sm text-slate-600 dark:text-slate-400">Show visitors where to find you</p>
        <p class="text-sm text-slate-600 dark:text-slate-400">Add your social profiles, email and more as linked icons on your bio page.</p>
    </div>
    <!-- Social Icons Position -->
    <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-slate-800">
        <h4 class="mb-3 text-lg font-semibold text-slate-900 dark:text-white">Social Icons Position</h4>
        <p class="mb-4 text-sm text-slate-600 dark:text-slate-400">Choose where to display social icons on your bio page</p>
        <div class="grid grid-cols-2 gap-4">
            <!-- Below Bio Option -->
            <button type="button" @click.prevent="updatePosition('below_bio')" 
                    class="flex flex-col items-center gap-2 rounded-lg border-2 p-4 transition-all"
                    :class="socialPosition === 'below_bio' ? 'border-purple-600 bg-purple-50 dark:bg-purple-900/20' : 'border-slate-200 hover:border-slate-300 dark:border-slate-700'">
                <svg class="h-8 w-8" :class="socialPosition === 'below_bio' ? 'text-purple-600' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span class="text-sm font-medium" :class="socialPosition === 'below_bio' ? 'text-purple-600 dark:text-purple-400' : 'text-slate-700 dark:text-slate-300'">Below Bio</span>
                <span class="text-xs text-slate-500 dark:text-slate-400">Under description</span>
            </button>
            <!-- Bottom of Page Option -->
            <button type="button" @click.prevent="updatePosition('bottom_page')" 
                    class="flex flex-col items-center gap-2 rounded-lg border-2 p-4 transition-all"
                    :class="socialPosition === 'bottom_page' ? 'border-purple-600 bg-purple-50 dark:bg-purple-900/20' : 'border-slate-200 hover:border-slate-300 dark:border-slate-700'">
                <svg class="h-8 w-8" :class="socialPosition === 'bottom_page' ? 'text-purple-600' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                </svg>
                <span class="text-sm font-medium" :class="socialPosition === 'bottom_page' ? 'text-purple-600 dark:text-purple-400' : 'text-slate-700 dark:text-slate-300'">Bottom of Page</span>
                <span class="text-xs text-slate-500 dark:text-slate-400">After all links</span>
            </button>
        </div>
    </div>
    <!-- Active Social Links (max 5) -->
    <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-slate-800">
        <div id="social-links-list" x-ref="socialList" class="space-y-3" x-init="$nextTick(() => initSocialSortable())">
            <template x-for="(link, index) in activeSocials" :key="link.platform + '-' + index">
                <div class="social-sortable-item flex items-center gap-4 rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-900" :data-index="index">
                    <!-- Drag Handle -->
                    <div class="social-drag-handle cursor-move text-slate-400 hover:text-slate-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                        </svg>
                    </div>
                    <!-- Icon -->
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white dark:bg-slate-800" x-html="getSocialIcon(link.platform)"></div>
                    <!-- Platform Name & Input -->
                    <div class="flex-1">
                        <p class="mb-1 text-sm font-medium text-slate-900 dark:text-white" x-text="link.label"></p>
                        <input type="text" 
                               x-model="link.value"
                               :placeholder="getSocialPlaceholder(link.platform)"
                               @input="validateSocialLink(link)"
                               class="w-full rounded border px-3 py-1.5 text-sm"
                               :class="link.error ? 'border-red-500' : 'border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-white'">
                        <p x-show="link.error" x-text="link.error" class="mt-1 text-xs text-red-600" x-cloak></p>
                    </div>
                    <!-- Delete Button -->
                    <button @click="removeSocial(index)" class="text-red-500 hover:text-red-700 ml-2" title="Delete">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    <!-- Edit/Toggle -->
                    <button @click="link.enabled = !link.enabled" class="text-slate-400 hover:text-slate-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                    </button>
                    <!-- Toggle Switch -->
                    <label class="relative inline-flex cursor-pointer items-center">
                        <input type="checkbox" x-model="link.enabled" class="peer sr-only">
                        <div class="peer h-6 w-11 rounded-full bg-slate-300 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-slate-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-green-600 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 dark:border-slate-600 dark:bg-slate-700 dark:peer-focus:ring-green-800"></div>
                    </label>
                </div>
            </template>
            <!-- Empty State -->
            <div x-show="activeSocials.length === 0" class="py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">No social icons added yet</p>
            </div>
        </div>
        <!-- Add Social Button -->
        <button @click="showModal = true" 
                :disabled="activeSocials.length >= 5"
                class="mt-4 w-full rounded-lg bg-purple-600 py-3 font-medium text-white transition-all hover:bg-purple-700 disabled:opacity-50 disabled:cursor-not-allowed">
            <span x-show="activeSocials.length < 5">+ Add social icon</span>
            <span x-show="activeSocials.length >= 5" x-cloak>Maximum 5 social icons reached</span>
        </button>
        <p class="mt-2 text-center text-xs text-slate-500 dark:text-slate-400" x-text="activeSocials.length + '/5 social icons'"></p>
    </div>
    <!-- Save Button -->
    <div class="flex justify-end">
        <button @click="saveSocialLinks" 
                :disabled="saving"
                class="rounded-lg bg-blue-600 px-6 py-3 font-medium text-white transition-all hover:bg-blue-700 disabled:opacity-50">
            <span x-show="!saving">💾 Save Social Links</span>
            <span x-show="saving" x-cloak>Saving...</span>
        </button>
    </div>
    <!-- Add Social Modal -->
    <div x-show="showModal" 
         x-cloak
         @click.self="showModal = false"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div @click.stop class="w-full max-w-md rounded-lg bg-white shadow-xl dark:bg-slate-800">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-slate-200 p-4 dark:border-slate-700">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Add social icon</h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <!-- Search -->
            <div class="p-4 border-b border-slate-200 dark:border-slate-700">
                <div class="relative">
                    <input type="text" 
                           x-model="searchQuery"
                           placeholder="Search"
                           class="w-full rounded-lg border border-slate-300 py-2.5 pl-4 pr-10 text-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500/20 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-purple-400">
                    <svg class="absolute right-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>
            <!-- Social Platforms List (Exactly 5 visible items, rest scroll) -->
            <div class="social-list-scroll overflow-y-auto" style="max-height: 280px;">
                <template x-for="platform in filteredPlatforms" :key="platform.id">
                    <button @click="addSocial(platform)" 
                            class="flex w-full items-center gap-3 border-b border-slate-100 px-4 py-2.5 text-left transition-colors hover:bg-slate-50 dark:border-slate-700 dark:hover:bg-slate-700">
                        <div class="flex h-8 w-8 items-center justify-center" x-html="platform.icon"></div>
                        <span class="text-sm font-medium text-slate-900 dark:text-white" x-text="platform.label"></span>
                        <svg class="ml-auto h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </template>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
function socialLinksManager() {
    return {
        activeSocials: @json($bioPage->social_links ?? []),
        showModal: false,
        searchQuery: '',
        saving: false,
        socialPosition: '{{ $bioPage->social_icons_position ?? "below_bio" }}',
        socialSortable: null,
        init() {
            // Initialize Sortable after DOM is ready
            this.$nextTick(() => {
                this.initSocialSortable();
            });
        },
        initSocialSortable() {
            const container = document.getElementById('social-links-list');
            if (!container || this.socialSortable) return;
            const self = this;
            this.socialSortable = Sortable.create(container, {
                animation: 120,
                easing: 'ease-out',
                handle: '.social-drag-handle',
                draggable: '.social-sortable-item',
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                dragClass: 'sortable-drag',
                onEnd: function(evt) {
                    if (evt.oldIndex === evt.newIndex) return;
                    const movedItem = self.activeSocials.splice(evt.oldIndex, 1)[0];
                    self.activeSocials.splice(evt.newIndex, 0, movedItem);
                    window.dispatchEvent(new CustomEvent('social-links-updated', { 
                        detail: { social_links: self.activeSocials } 
                    }));
                }
            });
        },
        updatePosition(position) {
            this.socialPosition = position;
            // Dispatch event to parent bioEditor
            window.dispatchEvent(new CustomEvent('social-position-updated', { 
                detail: { position: position } 
            }));
        },
        availablePlatforms: [
            // Social Media - Popular
            { id: 'instagram', label: 'Instagram', icon: '<img src="/images/brands/instagram.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Instagram">', placeholder: '@username' },
            { id: 'tiktok', label: 'TikTok', icon: '<img src="/images/brands/tiktok.svg" class="h-6 w-6" style="filter:brightness(0);" alt="TikTok">', placeholder: '@username' },
            { id: 'youtube', label: 'YouTube', icon: '<img src="/images/brands/youtube.svg" class="h-6 w-6" style="filter:brightness(0);" alt="YouTube">', placeholder: '@channel' },
            { id: 'x', label: 'X (Twitter)', icon: '<img src="/images/brands/x.svg" class="h-6 w-6" style="filter:brightness(0);" alt="X">', placeholder: '@username' },
            { id: 'facebook', label: 'Facebook', icon: '<img src="/images/brands/facebook.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Facebook">', placeholder: 'pagename atau URL' },
            { id: 'threads', label: 'Threads', icon: '<img src="/images/brands/threads.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Threads">', placeholder: '@username' },
            { id: 'linkedin', label: 'LinkedIn', icon: '<img src="/images/brands/linkedin.svg" class="h-6 w-6" style="filter:brightness(0);" alt="LinkedIn">', placeholder: 'in/yourname' },
            { id: 'pinterest', label: 'Pinterest', icon: '<img src="/images/brands/pinterest.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Pinterest">', placeholder: '@username' },
            { id: 'snapchat', label: 'Snapchat', icon: '<img src="/images/brands/snapchat.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Snapchat">', placeholder: 'username' },
            { id: 'reddit', label: 'Reddit', icon: '<img src="/images/brands/reddit.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Reddit">', placeholder: 'username atau r/subreddit' },
            { id: 'tumblr', label: 'Tumblr', icon: '<img src="/images/brands/tumblr.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Tumblr">', placeholder: 'blogname atau URL' },
            // Decentralized/New Platforms
            { id: 'bluesky', label: 'Bluesky', icon: '<img src="/images/brands/bluesky.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Bluesky">', placeholder: 'handle.bsky.social' },
            { id: 'mastodon', label: 'Mastodon', icon: '<img src="/images/brands/mastodon.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Mastodon">', placeholder: '@user@instance.social' },
            { id: 'bereal', label: 'BeReal', icon: '<img src="/images/brands/bereal.svg" class="h-6 w-6" style="filter:brightness(0);" alt="BeReal">', placeholder: 'username' },
            { id: 'lemmy', label: 'Lemmy', icon: '<img src="/images/brands/lemmy.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Lemmy">', placeholder: '@user@instance' },
            { id: 'pixelfed', label: 'Pixelfed', icon: '<img src="/images/brands/pixelfed.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Pixelfed">', placeholder: '@user@instance atau URL' },
            { id: 'nostr', label: 'Nostr', icon: '<img src="/images/brands/nostr.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Nostr">', placeholder: 'npub1...' },
            // Messaging
            { id: 'whatsapp', label: 'WhatsApp', icon: '<img src="/images/brands/whatsapp.svg" class="h-6 w-6" style="filter:brightness(0);" alt="WhatsApp">', placeholder: '+62812345678' },
            { id: 'telegram', label: 'Telegram', icon: '<img src="/images/brands/telegram.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Telegram">', placeholder: '@username' },
            { id: 'discord', label: 'Discord', icon: '<img src="/images/brands/discord.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Discord">', placeholder: 'invite_code' },
            { id: 'messenger', label: 'Messenger', icon: '<img src="/images/brands/messenger.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Messenger">', placeholder: 'username' },
            { id: 'signal', label: 'Signal', icon: '<img src="/images/brands/signal.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Signal">', placeholder: '+62812345678' },
            { id: 'line', label: 'LINE', icon: '<img src="/images/brands/line.svg" class="h-6 w-6" style="filter:brightness(0);" alt="LINE">', placeholder: 'username' },
            { id: 'matrix', label: 'Matrix', icon: '<img src="/images/brands/matrix.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Matrix">', placeholder: '@user:matrix.org' },
            // Music & Audio
            { id: 'spotify', label: 'Spotify', icon: '<img src="/images/brands/spotify.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Spotify">', placeholder: 'username atau URL' },
            { id: 'apple-music', label: 'Apple Music', icon: '<img src="/images/brands/apple-music.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Apple Music">', placeholder: 'profile atau URL' },
            { id: 'soundcloud', label: 'SoundCloud', icon: '<img src="/images/brands/soundcloud.svg" class="h-6 w-6" style="filter:brightness(0);" alt="SoundCloud">', placeholder: 'username' },
            { id: 'bandcamp', label: 'Bandcamp', icon: '<img src="/images/brands/bandcamp.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Bandcamp">', placeholder: 'artist' },
            { id: 'deezer', label: 'Deezer', icon: '<img src="/images/brands/deezer.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Deezer">', placeholder: 'profile_id' },
            { id: 'youtube-music', label: 'YouTube Music', icon: '<img src="/images/brands/youtube-music.svg" class="h-6 w-6" style="filter:brightness(0);" alt="YouTube Music">', placeholder: 'channel_id' },
            // Video & Streaming
            { id: 'twitch', label: 'Twitch', icon: '<img src="/images/brands/twitch.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Twitch">', placeholder: 'username' },
            { id: 'kick', label: 'Kick', icon: '<img src="/images/brands/kick.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Kick">', placeholder: 'username' },
            { id: 'vimeo', label: 'Vimeo', icon: '<img src="/images/brands/vimeo.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Vimeo">', placeholder: 'username' },
            { id: 'bilibili', label: 'Bilibili', icon: '<img src="/images/brands/bilibili.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Bilibili">', placeholder: 'user_id' },
            // Developer
            { id: 'github', label: 'GitHub', icon: '<img src="/images/brands/github.svg" class="h-6 w-6" style="filter:brightness(0);" alt="GitHub">', placeholder: 'username' },
            { id: 'gitlab', label: 'GitLab', icon: '<img src="/images/brands/gitlab.svg" class="h-6 w-6" style="filter:brightness(0);" alt="GitLab">', placeholder: 'username' },
            { id: 'codepen', label: 'CodePen', icon: '<img src="/images/brands/codepen.svg" class="h-6 w-6" style="filter:brightness(0);" alt="CodePen">', placeholder: 'username' },
            { id: 'dev-to', label: 'Dev.to', icon: '<img src="/images/brands/dev-to.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Dev.to">', placeholder: 'username' },
            { id: 'stack-overflow', label: 'Stack Overflow', icon: '<img src="/images/brands/stack-overflow.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Stack Overflow">', placeholder: 'user_id' },
            // Design & Art
            { id: 'dribbble', label: 'Dribbble', icon: '<img src="/images/brands/dribbble.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Dribbble">', placeholder: 'username' },
            { id: 'behance', label: 'Behance', icon: '<img src="/images/brands/behance.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Behance">', placeholder: 'username' },
            { id: 'figma', label: 'Figma', icon: '<img src="/images/brands/figma.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Figma">', placeholder: '@username' },
            { id: 'artstation', label: 'ArtStation', icon: '<img src="/images/brands/artstation.svg" class="h-6 w-6" style="filter:brightness(0);" alt="ArtStation">', placeholder: 'username' },
            { id: 'unsplash', label: 'Unsplash', icon: '<img src="/images/brands/unsplash.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Unsplash">', placeholder: '@username' },
            { id: 'deviantart', label: 'DeviantArt', icon: '<img src="/images/brands/deviantart.svg" class="h-6 w-6" style="filter:brightness(0);" alt="DeviantArt">', placeholder: 'username' },
            // Gaming
            { id: 'steam', label: 'Steam', icon: '<img src="/images/brands/steam.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Steam">', placeholder: 'id/username' },
            { id: 'xbox', label: 'Xbox', icon: '<img src="/images/brands/xbox.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Xbox">', placeholder: 'gamertag' },
            { id: 'playstation', label: 'PlayStation', icon: '<img src="/images/brands/playstation.svg" class="h-6 w-6" style="filter:brightness(0);" alt="PlayStation">', placeholder: 'psn_id' },
            { id: 'itch-io', label: 'Itch.io', icon: '<img src="/images/brands/itch-io.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Itch.io">', placeholder: 'username' },
            { id: 'roblox', label: 'Roblox', icon: '<img src="/images/brands/roblox.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Roblox">', placeholder: 'user_id' },
            // Entertainment
            { id: 'myanimelist', label: 'MyAnimeList', icon: '<img src="/images/brands/myanimelist.svg" class="h-6 w-6" style="filter:brightness(0);" alt="MyAnimeList">', placeholder: 'username' },
            { id: 'anilist', label: 'AniList', icon: '<img src="/images/brands/anilist.svg" class="h-6 w-6" style="filter:brightness(0);" alt="AniList">', placeholder: 'username' },
            { id: 'letterboxd', label: 'Letterboxd', icon: '<img src="/images/brands/letterboxd.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Letterboxd">', placeholder: 'username' },
            { id: 'goodreads', label: 'Goodreads', icon: '<img src="/images/brands/goodreads.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Goodreads">', placeholder: 'user_id' },
            // Payment/Donation
            { id: 'paypal', label: 'PayPal', icon: '<img src="/images/brands/paypal.svg" class="h-6 w-6" style="filter:brightness(0);" alt="PayPal">', placeholder: 'paypal.me/username' },
            { id: 'venmo', label: 'Venmo', icon: '<img src="/images/brands/venmo.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Venmo">', placeholder: '@username' },
            { id: 'cash-app', label: 'Cash App', icon: '<img src="/images/brands/cash-app.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Cash App">', placeholder: '$cashtag' },
            { id: 'patreon', label: 'Patreon', icon: '<img src="/images/brands/patreon.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Patreon">', placeholder: 'username' },
            { id: 'ko-fi', label: 'Ko-fi', icon: '<img src="/images/brands/ko-fi.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Ko-fi">', placeholder: 'username' },
            { id: 'buy-me-a-coffee', label: 'Buy Me a Coffee', icon: '<img src="/images/brands/buy-me-a-coffee.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Buy Me a Coffee">', placeholder: 'username' },
            { id: 'gumroad', label: 'Gumroad', icon: '<img src="/images/brands/gumroad.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Gumroad">', placeholder: 'username' },
            // E-commerce
            { id: 'etsy', label: 'Etsy', icon: '<img src="/images/brands/etsy.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Etsy">', placeholder: 'shop/username' },
            { id: 'amazon', label: 'Amazon', icon: '<img src="/images/brands/amazon.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Amazon">', placeholder: 'shop/username' },
            { id: 'fiverr', label: 'Fiverr', icon: '<img src="/images/brands/fiverr.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Fiverr">', placeholder: 'username' },
            { id: 'upwork', label: 'Upwork', icon: '<img src="/images/brands/upwork.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Upwork">', placeholder: 'profile_url' },
            // Productivity & Blog
            { id: 'medium', label: 'Medium', icon: '<img src="/images/brands/medium.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Medium">', placeholder: '@username' },
            { id: 'substack', label: 'Substack', icon: '<img src="/images/brands/substack.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Substack">', placeholder: 'username' },
            { id: 'notion', label: 'Notion', icon: '<img src="/images/brands/notion.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Notion">', placeholder: 'page_id' },
            { id: 'wordpress', label: 'WordPress', icon: '<img src="/images/brands/wordpress.svg" class="h-6 w-6" style="filter:brightness(0);" alt="WordPress">', placeholder: 'site.wordpress.com' },
            { id: 'calendly', label: 'Calendly', icon: '<img src="/images/brands/calendly.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Calendly">', placeholder: 'username' },
            { id: 'zoom', label: 'Zoom', icon: '<img src="/images/brands/zoom.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Zoom">', placeholder: 'meeting_id' },
            // Academic
            { id: 'orcid', label: 'ORCID', icon: '<img src="/images/brands/orcid.svg" class="h-6 w-6" style="filter:brightness(0);" alt="ORCID">', placeholder: '0000-0000-0000-0000' },
            { id: 'researchgate', label: 'ResearchGate', icon: '<img src="/images/brands/researchgate.svg" class="h-6 w-6" style="filter:brightness(0);" alt="ResearchGate">', placeholder: 'profile_name' },
            { id: 'google-scholar', label: 'Google Scholar', icon: '<img src="/images/brands/google-scholar.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Google Scholar">', placeholder: 'user_id' },
            // Contact
            { id: 'email', label: 'Email', icon: '<img src="/images/brands/email.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Email">', placeholder: 'your@email.com' },
            { id: 'phone', label: 'Phone', icon: '<img src="/images/brands/phone.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Phone">', placeholder: '+62812345678' },
            { id: 'sms', label: 'SMS', icon: '<img src="/images/brands/sms.svg" class="h-6 w-6" style="filter:brightness(0);" alt="SMS">', placeholder: '+62812345678' },
            { id: 'website', label: 'Website', icon: '<img src="/images/brands/website.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Website">', placeholder: 'https://yoursite.com' },
            { id: 'blog', label: 'Blog', icon: '<img src="/images/brands/blog.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Blog">', placeholder: 'https://blog.com' },
            { id: 'link', label: 'Link', icon: '<img src="/images/brands/link.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Link">', placeholder: 'https://...' },
            // Other
            { id: 'onlyfans', label: 'OnlyFans', icon: '<img src="/images/brands/onlyfans.svg" class="h-6 w-6" style="filter:brightness(0);" alt="OnlyFans">', placeholder: 'username' },
            { id: 'strava', label: 'Strava', icon: '<img src="/images/brands/strava.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Strava">', placeholder: 'athlete_id' },
            { id: 'opensea', label: 'OpenSea', icon: '<img src="/images/brands/opensea.svg" class="h-6 w-6" style="filter:brightness(0);" alt="OpenSea">', placeholder: 'username' },
            { id: 'xing', label: 'Xing', icon: '<img src="/images/brands/xing.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Xing">', placeholder: 'profile_name' },
            { id: 'clubhouse', label: 'Clubhouse', icon: '<img src="/images/brands/clubhouse.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Clubhouse">', placeholder: '@username' },
            { id: 'spacehey', label: 'SpaceHey', icon: '<img src="/images/brands/spacehey.svg" class="h-6 w-6" style="filter:brightness(0);" alt="SpaceHey">', placeholder: 'profile_id' },
            { id: 'vero', label: 'Vero', icon: '<img src="/images/brands/vero.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Vero">', placeholder: 'username' },
            { id: 'ngl', label: 'NGL', icon: '<img src="/images/brands/ngl.svg" class="h-6 w-6" style="filter:brightness(0);" alt="NGL">', placeholder: 'username' },
            { id: 'cameo', label: 'Cameo', icon: '<img src="/images/brands/cameo.svg" class="h-6 w-6" style="filter:brightness(0);" alt="Cameo">', placeholder: 'username' },
        ],
        get filteredPlatforms() {
            if (!this.searchQuery) return this.availablePlatforms;
            const query = this.searchQuery.toLowerCase();
            return this.availablePlatforms.filter(p => 
                p.label.toLowerCase().includes(query)
            );
        },
        init() {
            // Initialize Sortable for drag and drop
            this.$nextTick(() => {
                const container = this.$el.querySelector('.space-y-3');
                if (container) {
                    Sortable.create(container, {
                        animation: 150,
                        handle: '.cursor-move',
                        onEnd: (evt) => {
                            const movedItem = this.activeSocials.splice(evt.oldIndex, 1)[0];
                            this.activeSocials.splice(evt.newIndex, 0, movedItem);
                            window.dispatchEvent(new CustomEvent('social-links-updated', { detail: { social_links: this.activeSocials } }));
                        }
                    });
                }
            });
        },
        getSocialIcon(platform) {
            const found = this.availablePlatforms.find(p => p.id === platform);
            return found ? found.icon : '';
        },
        getSocialPlaceholder(platform) {
            const found = this.availablePlatforms.find(p => p.id === platform);
            return found ? found.placeholder : '';
        },
        removeSocial(index) {
            this.activeSocials.splice(index, 1);
            window.dispatchEvent(new CustomEvent('social-links-updated', { detail: { social_links: this.activeSocials } }));
        },
        addSocial(platform) {
            if (this.activeSocials.length >= 5) {
                // Show notification instead of alert
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 right-4 z-50 rounded-lg bg-amber-500 p-4 text-white shadow-2xl';
                notification.innerHTML = '<p class="font-medium">⚠ Maximum 5 social icons allowed</p>';
                document.body.appendChild(notification);
                setTimeout(() => notification.remove(), 3000);
                return;
            }
            this.activeSocials.push({
                platform: platform.id,
                label: platform.label,
                value: '',
                enabled: true,
                error: null
            });
            window.dispatchEvent(new CustomEvent('social-links-updated', { detail: { social_links: this.activeSocials } }));
            this.showModal = false;
            this.searchQuery = '';
        },
        validateSocialLink(link) {
            // Platform-specific validation
            const patterns = {
                facebook: /^https:\/\/(www\.)?facebook\.com\//,
                youtube: /^https:\/\/(www\.)?youtube\.com\//,
                linkedin: /^https:\/\/(www\.)?linkedin\.com\/in\//,
                github: /^https:\/\/(www\.)?github\.com\//,
                twitch: /^https:\/\/(www\.)?twitch\.tv\//,
                spotify: /^https:\/open\.spotify\.com\//,
                whatsapp: /^(\d{10,15}|https:\/\/wa\.me\/\d{10,15})$/,
                email: /^[^\s@]+@[^\s@]+\.[^\s@]+$/
            };
            if (!link.value) {
                link.error = null;
                return;
            }
            if (patterns[link.platform]) {
                if (!patterns[link.platform].test(link.value)) {
                    link.error = 'Format salah. Periksa placeholder.';
                    return;
                }
            }
            link.error = null;
        },
        async saveSocialLinks() {
            // Check for errors
            const hasErrors = this.activeSocials.some(link => link.error);
            if (hasErrors) {
                // Show notification instead of alert
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 right-4 z-50 rounded-lg bg-red-600 p-4 text-white shadow-2xl';
                notification.innerHTML = '<p class="font-medium">⚠ Please fix validation errors before saving</p>';
                document.body.appendChild(notification);
                setTimeout(() => notification.remove(), 3000);
                return;
            }
            this.saving = true;
            try {
                // Update parent bioEditor social_links first
                const editor = bioEditor();
                editor.bioPage.social_links = this.activeSocials;
                // Use parent's saveBioPage method
                await editor.saveBioPage();
                // Clear unsaved changes flag after successful save
                editor.unsavedChanges = false;
                // Show success notification
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 right-4 z-50 rounded-lg bg-green-600 p-4 text-white shadow-2xl';
                notification.innerHTML = '<p class="font-medium">✓ Social links saved successfully!</p>';
                document.body.appendChild(notification);
                setTimeout(() => notification.remove(), 3000);
            } catch (error) {
                console.error(error);
                // Show custom error popup
                const errorPopup = document.createElement('div');
                errorPopup.className = 'fixed inset-0 z-50 flex items-center justify-center bg-black/40';
                errorPopup.innerHTML = `
                    <div class="rounded-lg bg-white dark:bg-slate-800 shadow-xl p-6 w-full max-w-sm">
                        <h3 class="text-lg font-semibold mb-2 text-slate-900 dark:text-white">hel.ink says</h3>
                        <p class="mb-4 text-sm text-slate-600 dark:text-slate-400">${error.message || 'Failed to save social links'}</p>
                        <div class="flex justify-end">
                            <button onclick="this.closest('.fixed').remove()" class="rounded-full bg-yellow-600 hover:bg-yellow-700 px-6 py-2 text-sm font-medium text-white">OK</button>
                        </div>
                    </div>
                `;
                document.body.appendChild(errorPopup);
            } finally {
                this.saving = false;
            }
        }
    };
}
</script>
