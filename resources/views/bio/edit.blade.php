<x-app-layout>
    <x-slot name="pageTitle">Edit {{ $bioPage->title }}</x-slot>
    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
    <div class="min-h-screen bg-slate-50 dark:bg-slate-900" x-data="bioEditor()" x-init="init()" @save-blocks.window="saveBlocks()">
        <template x-teleport="body">
            <div x-show="confirmModal.show" x-cloak class="fixed inset-0 z-[99999] flex items-center justify-center" @keydown.escape.window="confirmModal.show = false" style="isolation: isolate;">
                <div class="absolute inset-0 bg-black/50" @click="confirmModal.show = false"></div>
                <div class="relative rounded-xl bg-white dark:bg-slate-800 shadow-2xl p-6 w-full max-w-sm mx-4 transform transition-all" @click.stop>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center" :class="confirmModal.type === 'danger' ? 'bg-red-100 dark:bg-red-900/30' : 'bg-blue-100 dark:bg-blue-900/30'">
                            <svg x-show="confirmModal.type === 'danger'" class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <svg x-show="confirmModal.type !== 'danger'" class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white" x-text="confirmModal.title"></h3>
                    </div>
                    <p class="mb-6 text-sm text-slate-600 dark:text-slate-400" x-text="confirmModal.message"></p>
                    <div class="flex justify-end gap-3">
                        <button @click="confirmModal.show = false; if(confirmModal.onCancel) confirmModal.onCancel()" class="rounded-lg bg-slate-100 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-200 dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-600 transition-colors" x-text="confirmModal.cancelText || 'Cancel'"></button>
                        <button @click="confirmModal.show = false; if(confirmModal.onConfirm) confirmModal.onConfirm()" class="rounded-lg px-4 py-2.5 text-sm font-medium text-white transition-colors" :class="confirmModal.type === 'danger' ? 'bg-red-600 hover:bg-red-700' : 'bg-blue-600 hover:bg-blue-700'" x-text="confirmModal.confirmText || 'OK'"></button>
                    </div>
                </div>
            </div>
        </template>
        <template x-teleport="body">
            <div x-show="showUnsavedModal" x-cloak class="fixed inset-0 z-[99999] flex items-center justify-center">
                <div class="absolute inset-0 bg-black/40" @click="showUnsavedModal = false"></div>
                <div class="relative rounded-lg bg-white dark:bg-slate-800 shadow-xl p-6 w-full max-w-sm" @click.stop>
                    <h3 class="text-lg font-semibold mb-2 text-slate-900 dark:text-white">Unsaved Changes</h3>
                    <p class="mb-4 text-sm text-slate-600 dark:text-slate-400">You have unsaved changes. Are you sure you want to discard them?</p>
                    <div class="flex justify-end gap-2">
                        <button @click="showUnsavedModal = false" class="rounded bg-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-300">Cancel</button>
                        <button @click="confirmLeave()" class="rounded bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">Leave</button>
                    </div>
                </div>
            </div>
        </template>
        <div id="editorHeader" class="sticky top-0 z-40 border-b border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-800">
            <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('bio.index') }}" class="text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </a>
                        <div>
                            <h1 class="text-xl font-bold text-slate-900 dark:text-white">{{ $bioPage->title }}</h1>
                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                hel.ink/b/{{ $bioPage->slug }}
                                @if(auth()->user() && auth()->user()->is_admin)
                                    <span class="ml-2 rounded bg-yellow-100 px-2 py-0.5 text-xs font-semibold text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">ADMIN TESTING</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <div x-show="saving" x-transition class="flex items-center gap-2 rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                            <svg class="h-3 w-3 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Saving...</span>
                        </div>
                        <a href="{{ route('bio.public.show', $bioPage->slug) }}" target="_blank" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition-all duration-200 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-700">
                            <svg class="inline h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            View Live
                        </a>
                        <button @click="saveAll()" :disabled="saving" class="rounded-lg bg-blue-600 px-6 py-2 text-sm font-medium text-white transition-all duration-200 hover:bg-blue-700 hover:shadow-lg active:scale-95 disabled:bg-slate-400 disabled:cursor-not-allowed">
                            <span x-show="!saving">Save Changes</span>
                            <span x-show="saving">Saving...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 overflow-visible">
            <div class="grid gap-4 lg:grid-cols-4 lg:items-start overflow-visible">
                <div class="space-y-4 lg:col-span-3">
                    <div class="flex gap-2 rounded-lg bg-white p-1.5 shadow-sm dark:bg-slate-800">
                        <button @click="changeTab('build')" :class="activeTab === 'build' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700'" class="flex-1 rounded-md px-3 py-1.5 text-sm font-medium transition-all duration-200">Build</button>
                        <button @click="changeTab('design')" :class="activeTab === 'design' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700'" class="flex-1 rounded-md px-3 py-1.5 text-sm font-medium transition-all duration-200">Design</button>
                        <button @click="changeTab('social')" :class="activeTab === 'social' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700'" class="flex-1 rounded-md px-3 py-1.5 text-sm font-medium transition-all duration-200">Social</button>
                        <button @click="changeTab('seo')" :class="activeTab === 'seo' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700'" class="flex-1 rounded-md px-3 py-1.5 text-sm font-medium transition-all duration-200">SEO</button>
                        <button @click="changeTab('track')" :class="activeTab === 'track' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700'" class="flex-1 rounded-md px-3 py-1.5 text-sm font-medium transition-all duration-200">Track</button>
                    </div>
                    <div x-show="activeTab === 'build'" x-cloak>
                        @include('bio.partials.build-tab')
                    </div>
                    <div x-show="activeTab === 'design'" x-cloak>
                        @include('bio.partials.design-tab')
                    </div>
                    <div x-show="activeTab === 'track'" x-cloak>
                        @include('bio.partials.track-tab')
                    </div>
                    <div x-show="activeTab === 'seo'" x-cloak>
                        @include('bio.partials.seo-tab')
                    </div>
                    <div x-show="activeTab === 'social'" x-cloak>
                        <div class="max-w-2xl mx-auto">
                            @include('bio.partials.social-tab')
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-1">
                    <div x-data="stickyPreview()" x-init="init()" x-ref="wrap" class="relative flex justify-center pl-6">
                        <div x-ref="inner" :style="style" class="rounded-lg bg-white p-3 shadow-sm dark:bg-slate-800">
                            <div class="mb-3 flex items-center justify-between">
                                <h3 class="text-xs font-semibold text-slate-900 dark:text-white">Live Preview</h3>
                                <div class="flex gap-1 rounded-lg bg-slate-100 p-1 dark:bg-slate-700">
                                    <button @click="previewMode = 'mobile'" 
                                            :class="previewMode === 'mobile' ? 'bg-white text-blue-600 shadow-sm dark:bg-slate-600 dark:text-blue-400' : 'text-slate-500 dark:text-slate-400'"
                                            class="rounded-md px-2 py-1 text-xs font-medium transition-all">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                    </button>
                                    <button @click="previewMode = 'desktop'" 
                                            :class="previewMode === 'desktop' ? 'bg-white text-blue-600 shadow-sm dark:bg-slate-600 dark:text-blue-400' : 'text-slate-500 dark:text-slate-400'"
                                            class="rounded-md px-2 py-1 text-xs font-medium transition-all">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div x-show="previewMode === 'mobile'" x-transition class="relative mx-auto h-[580px] w-[280px] rounded-[2.5rem] border-[10px] border-black bg-black shadow-xl dark:border-slate-700 dark:bg-slate-800">
                                <div class="absolute left-1/2 top-0 z-10 h-5 w-24 -translate-x-1/2 rounded-b-xl bg-black dark:bg-slate-700">
                                    <div class="absolute left-1/2 top-1 h-2 w-10 -translate-x-1/2 rounded-full bg-slate-700 dark:bg-slate-600"></div>
                                </div>
                                <div class="h-full w-full overflow-hidden rounded-[2rem]">
                                    <div class="h-full w-full overflow-y-auto scrollbar-hide" style="scrollbar-width: none; -ms-overflow-style: none;" x-html="getPreviewHtml('mobile')"></div>
                                </div>
                            </div>
                            <div x-show="previewMode === 'desktop'" x-transition x-cloak class="relative mx-auto">
                                <div class="w-[320px] rounded-t-lg border-4 border-b-0 border-slate-700 bg-slate-700 px-2 pt-2 dark:border-slate-600">
                                    <div class="flex items-center gap-1.5 rounded-t bg-slate-600 px-2 py-1">
                                        <div class="flex gap-1">
                                            <div class="h-2 w-2 rounded-full bg-red-400"></div>
                                            <div class="h-2 w-2 rounded-full bg-yellow-400"></div>
                                            <div class="h-2 w-2 rounded-full bg-green-400"></div>
                                        </div>
                                        <div class="ml-2 flex-1 rounded bg-slate-500 px-2 py-0.5 text-center">
                                            <span class="text-[8px] text-slate-300">hel.ink/b/{{ $bioPage->slug }}</span>
                                        </div>
                                    </div>
                                    <div class="h-[400px] overflow-y-auto bg-white">
                                        <div x-html="getPreviewHtml('desktop')"></div>
                                    </div>
                                </div>
                                <div class="mx-auto h-4 w-20 bg-slate-700 dark:bg-slate-600"></div>
                                <div class="mx-auto h-2 w-32 rounded-b-lg bg-slate-600 dark:bg-slate-500"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode-styling@1.5.0/lib/qrcode.min.js"></script>
    <script>
        function stickyPreview() {
            return {
                style: '',
                _onScroll: null,
                baseLeft: null,
                baseWidth: null,
                init() {
                    this.header = document.getElementById('editorHeader');
                    this._onScroll = this.update.bind(this);
                    window.addEventListener('scroll', this._onScroll, { passive: true });
                    window.addEventListener('resize', this._onScroll);
                    this.update();
                    requestAnimationFrame(() => this.update());
                    window.addEventListener('load', this._onScroll);
                },
                topOffset() {
                    const headerH = this.header ? this.header.offsetHeight : 64;
                    return headerH + 24;
                },
                update() {
                    const wrap = this.$refs.wrap;
                    const inner = this.$refs.inner;
                    if (!wrap || !inner) return;
                    const rect = wrap.getBoundingClientRect();
                    const innerRect = inner.getBoundingClientRect();
                    const startY = window.scrollY + rect.top;
                    const trigger = window.scrollY + this.topOffset();
                    if (trigger < startY) {
                        this.baseLeft = innerRect.left + window.scrollX;
                        this.baseWidth = innerRect.width;
                        this.style = '';
                        wrap.style.minHeight = '';
                        wrap.style.minWidth = '';
                        return;
                    }
                    if (trigger >= startY) {
                        wrap.style.minHeight = `${innerRect.height}px`;
                        wrap.style.minWidth = `${this.baseWidth ?? innerRect.width}px`;
                        const left = (this.baseLeft ?? (innerRect.left + window.scrollX));
                        this.style = `position: fixed; top: ${this.topOffset()}px; left: ${left}px; z-index: 30;`;
                    } else {
                        this.style = '';
                        wrap.style.minHeight = '';
                        wrap.style.minWidth = '';
                    }
                }
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            if (!window.Alpine) {
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 right-4 z-50 rounded-lg bg-red-600 p-4 text-white shadow-2xl';
                notification.innerHTML = '<p class="font-medium">✕ Alpine.js not loaded! Socials block will not work.</p>';
                document.body.appendChild(notification);
            }
        });
        function qrGenerator() {
            return {
                currentSlug: '{{ $bioPage->slug }}',
                generating: false,
                qrColor: '{{ $bioPage->settings['qr_color'] ?? '#000000' }}',
                customColor: '{{ $bioPage->settings['qr_color'] ?? '#000000' }}',
                logoUrl: '{{ $bioPage->settings['qr_logo'] ?? '' }}',
                qrCodeInstance: null,
                dotsType: '{{ $bioPage->settings['qr_dots_type'] ?? 'rounded' }}',
                bgColor: '{{ $bioPage->settings['qr_bg_color'] ?? '#ffffff' }}',
                cornerSquareType: '{{ $bioPage->settings['qr_corner_square_type'] ?? 'extra-rounded' }}',
                cornerSquareColor: '{{ $bioPage->settings['qr_corner_square_color'] ?? ($bioPage->settings['qr_color'] ?? '#000000') }}',
                cornerDotType: '{{ $bioPage->settings['qr_corner_dot_type'] ?? 'dot' }}',
                cornerDotColor: '{{ $bioPage->settings['qr_corner_dot_color'] ?? ($bioPage->settings['qr_color'] ?? '#000000') }}',
                init() {
                    this.$nextTick(() => {
                        this.generateQR();
                    });
                    this.$watch('qrColor', () => this.updateQR());
                    this.$watch('bgColor', () => this.updateQR());
                    this.$watch('logoUrl', () => this.updateQR());
                    this.$watch('dotsType', () => this.updateQR());
                    this.$watch('cornerSquareType', () => this.updateQR());
                    this.$watch('cornerSquareColor', () => this.updateQR());
                    this.$watch('cornerDotType', () => this.updateQR());
                    this.$watch('cornerDotColor', () => this.updateQR());
                },
                setColor(color) {
                    this.qrColor = color;
                    this.customColor = color;
                    if (!this.cornerSquareColor || this.cornerSquareColor === '#000000') this.cornerSquareColor = color;
                    if (!this.cornerDotColor || this.cornerDotColor === '#000000') this.cornerDotColor = color;
                    this.updateQR();
                },
                uploadLogo(event) {
                    const file = event.target.files[0];
                    if (!file) return;
                    if (file.size > 1024 * 1024) {
                        const bioEditor = document.querySelector('[x-data*="bioEditor"]');
                        if (bioEditor && Alpine.$data(bioEditor)) {
                            Alpine.$data(bioEditor).showNotification('File size must be less than 1MB', 'error');
                        }
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.logoUrl = e.target.result;
                        this.updateQR();
                    };
                    reader.readAsDataURL(file);
                },
                removeLogo() {
                    this.logoUrl = '';
                    this.updateQR();
                },
                updateDotsType(type) {
                    this.dotsType = type;
                    this.updateQR();
                },
                updateBgColor(color) {
                    this.bgColor = color;
                    this.updateQR();
                },
                updateCornerSquare(type) {
                    this.cornerSquareType = type;
                    this.updateQR();
                },
                updateCornerSquareColor(color) {
                    this.cornerSquareColor = color;
                    this.updateQR();
                },
                updateCornerDot(type) {
                    this.cornerDotType = type;
                    this.updateQR();
                },
                updateCornerDotColor(color) {
                    this.cornerDotColor = color;
                    this.updateQR();
                },
                updateQR() {
                    if (!this.qrCodeInstance) {
                        this.generateQR();
                        return;
                    }
                    this.qrCodeInstance.update({
                        dotsOptions: {
                            color: this.qrColor,
                            type: this.dotsType
                        },
                        backgroundOptions: {
                            color: this.bgColor,
                        },
                        cornersSquareOptions: {
                            color: this.cornerSquareColor,
                            type: this.cornerSquareType
                        },
                        cornersDotOptions: {
                            color: this.cornerDotColor,
                            type: this.cornerDotType
                        },
                        image: this.logoUrl || undefined,
                    });
                },
                generateQR() {
                    this.generating = true;
                    const qrContainer = this.$refs.qrContainer;
                    if (!qrContainer) {
                        console.error("QR Container element not found.");
                        this.generating = false;
                        return;
                    }
                    qrContainer.innerHTML = '<div class="flex items-center justify-center h-full"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div></div>';
                    const url = `{{ config('app.url') }}/b/${this.currentSlug}`;
                    setTimeout(() => {
                        qrContainer.innerHTML = '';
                        const qrCode = new QRCodeStyling({
                            width: 240,
                            height: 240,
                            type: "canvas",
                            data: url,
                            image: this.logoUrl || undefined,
                            dotsOptions: {
                                color: this.qrColor,
                                type: this.dotsType
                            },
                            backgroundOptions: {
                                color: this.bgColor,
                            },
                            margin: 5,
                            imageOptions: {
                                crossOrigin: "anonymous",
                                margin: 8,
                                imageSize: 0.3,
                                hideBackgroundDots: true
                            },
                            cornersSquareOptions: {
                                color: this.cornerSquareColor,
                                type: this.cornerSquareType
                            },
                            cornersDotOptions: {
                                color: this.cornerDotColor,
                                type: this.cornerDotType
                            },
                            qrOptions: {
                                errorCorrectionLevel: "H"
                            }
                        });
                        this.qrCodeInstance = qrCode;
                        qrCode.append(qrContainer);
                        this.generating = false;
                    }, 100);
                }
            };
        }
        function bioEditor() {
            return {
                activeTab: 'build',
                previewMode: 'mobile',
                saving: false,
                bioPage: @json($bioPage),
                blocks: @json($bioPage->links),
                unsavedChanges: false,
                showUnsavedModal: false,
                nextTab: null,
                confirmModal: {
                    show: false,
                    title: '',
                    message: '',
                    type: 'info',
                    confirmText: 'OK',
                    cancelText: 'Cancel',
                    onConfirm: null,
                    onCancel: null
                },
                showConfirm(options) {
                    this.confirmModal = {
                        show: true,
                        title: options.title || 'Confirm',
                        message: options.message || 'Are you sure?',
                        type: options.type || 'info',
                        confirmText: options.confirmText || 'OK',
                        cancelText: options.cancelText || 'Cancel',
                        onConfirm: options.onConfirm || null,
                        onCancel: options.onCancel || null
                    };
                },
                changeTab(tab) {
                    if (tab === 'track') {
                        this.activeTab = tab;
                        return;
                    }
                    if (this.unsavedChanges) {
                        this.nextTab = tab;
                        this.showUnsavedModal = true;
                    } else {
                        this.activeTab = tab;
                    }
                },
                confirmLeave() {
                    this.showUnsavedModal = false;
                    if (this.nextTab) {
                        this.activeTab = this.nextTab;
                        this.nextTab = null;
                    } else {
                        window.location.reload();
                    }
                    this.unsavedChanges = false;
                },
                markUnsaved() {
                    if (this.activeTab !== 'track') {
                        this.unsavedChanges = true;
                    }
                },
                init() {
                    if (!this.bioPage.theme) {
                        this.bioPage.theme = 'none';
                    }
                    if (!this.bioPage.social_icons_position) {
                        this.bioPage.social_icons_position = 'below_bio';
                    }
                    window.addEventListener('social-links-updated', (event) => {
                        this.bioPage.social_links = event.detail.social_links;
                        this.markUnsaved();
                    });
                    window.addEventListener('social-position-updated', (event) => {
                        this.bioPage.social_icons_position = event.detail.position;
                        this.markUnsaved();
                    });
                    document.addEventListener('input', (e) => {
                        if (this.activeTab !== 'track') {
                            this.markUnsaved();
                        }
                    }, true);
                    this.$watch('bioPage.social_icons_position', (newVal, oldVal) => {
                        this.markUnsaved();
                    });
                    this.$watch('bioPage.social_links', () => {
                        this.markUnsaved();
                    }, { deep: true });
                },
                async uploadAvatar(event) {
                    const file = event.target.files[0];
                    if (!file) return;
                    if (file.size > 2 * 1024 * 1024) {
                        this.showNotification('Avatar too large. Max 2MB', 'error');
                        return;
                    }
                    const formData = new FormData();
                    formData.append('avatar', file);
                    formData.append('title', this.bioPage.title);
                    formData.append('bio', this.bioPage.bio || '');
                    formData.append('slug', this.bioPage.slug);
                    formData.append('_method', 'PUT');
                    try {
                        this.saving = true;
                        const response = await fetch('{{ route('bio.update', $bioPage) }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: formData
                        });
                        if (!response.ok) {
                            const errorData = await response.json().catch(() => ({}));
                            throw new Error(errorData.message || 'Failed to upload avatar');
                        }
                        const data = await response.json();
                        const blobUrl = URL.createObjectURL(file);
                        const avatarPreview = document.querySelector('[x-ref="avatarPreview"]');
                        if (avatarPreview) {
                            if (avatarPreview.tagName === 'IMG') {
                                avatarPreview.src = blobUrl;
                            } else {
                                const img = document.createElement('img');
                                img.src = blobUrl;
                                img.className = 'h-24 w-24 flex-shrink-0 rounded-full object-cover';
                                img.alt = 'Avatar';
                                img.setAttribute('x-ref', 'avatarPreview');
                                avatarPreview.replaceWith(img);
                            }
                        }
                        if (data.avatar_url || data.bioPage?.avatar_url) {
                            this.bioPage.avatar_url = data.avatar_url || data.bioPage.avatar_url;
                        }
                        this.showNotification('Avatar uploaded successfully!', 'success');
                    } catch (error) {
                        console.error('Upload error:', error);
                        this.showNotification(error.message || 'Failed to upload avatar', 'error');
                    } finally {
                        this.saving = false;
                    }
                },
                addBlock(type) {
                    const newBlock = {
                        id: Date.now(),
                        bio_page_id: this.bioPage.id,
                        type: type,
                        title: '',
                        url: '',
                        mode: 'new',
                        content: '',
                        is_active: true,
                        order: this.blocks.length
                    };
                    this.blocks.push(newBlock);
                },
                toggleBlock(index) {
                    this.blocks[index].is_active = !this.blocks[index].is_active;
                    this.saveBlocks();
                },
                deleteBlock(index) {
                    this.showConfirm({
                        title: 'Delete Block',
                        message: 'Are you sure you want to delete this block? This action cannot be undone.',
                        type: 'danger',
                        confirmText: 'Delete',
                        cancelText: 'Cancel',
                        onConfirm: () => {
                            const block = this.blocks[index];
                            this.blocks.splice(index, 1);
                            if (block.id < 1000000000) {
                                this.deleteBlockFromServer(block.id);
                            }
                            this.showNotification('Block deleted successfully', 'success');
                        }
                    });
                },
                async uploadBlockImage(event, block) {
                    const file = event.target.files[0];
                    if (!file) return;
                    if (file.size > 2 * 1024 * 1024) {
                        this.showNotification('Image too large. Max 2MB', 'error');
                        return;
                    }
                    const formData = new FormData();
                    formData.append('image', file);
                    try {
                        const response = await fetch('/api/upload-image', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: formData
                        });
                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            this.showNotification('Server error: Invalid response. Please check server logs.', 'error');
                            const reader = new FileReader();
                            reader.onload = (e) => { block.thumbnail_url = e.target.result; };
                            reader.readAsDataURL(file);
                            return;
                        }
                        if (!response.ok) {
                            const errorData = await response.json().catch(() => ({}));
                            throw new Error(errorData.message || 'Failed to upload image');
                        }
                        const data = await response.json();
                        block.thumbnail_url = data.path;
                        if (block.id > 1000000000) {
                            await this.createBlock(block);
                        } else {
                            await this.updateBlock(block);
                        }
                        this.showNotification('Image uploaded successfully!', 'success');
                    } catch (error) {
                        console.error('Upload error:', error);
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            block.thumbnail_url = e.target.result;
                        };
                        reader.readAsDataURL(file);
                        this.showNotification(error.message || 'Upload failed. Showing preview only.', 'warning');
                    }
                },
                async deleteBlockFromServer(blockId) {
                    try {
                        const response = await fetch(`/bio/${this.bioPage.id}/links/${blockId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        });
                        if (!response.ok) throw new Error('Failed to delete block');
                    } catch (error) {
                        console.error('Delete error:', error);
                    }
                },
                async saveAll() {
                    this.saving = true;
                    try {
                        await this.saveBioPage();
                        await this.saveBlocks();
                        this.unsavedChanges = false;
                        this.showNotification('Changes saved successfully!', 'success');
                    } catch (error) {
                        console.error('Save error:', error);
                        this.showNotification('Error saving changes', 'error');
                    } finally {
                        this.saving = false;
                    }
                },
                async saveBioPage() {
                    const updateUrl = '{{ route('bio.update', $bioPage) }}';
                    const dataToSave = {
                        title: this.bioPage.title,
                        bio: this.bioPage.bio,
                        seo_title: this.bioPage.seo_title,
                        seo_description: this.bioPage.seo_description,
                        seo_noindex: this.bioPage.seo_noindex || false,
                        seo_nofollow: this.bioPage.seo_nofollow || false,
                        slug: this.bioPage.slug,
                        theme: this.bioPage.theme || 'none',
                        layout: this.bioPage.layout || 'centered',
                        background_type: this.bioPage.background_type || 'color',
                        background_value: this.bioPage.background_value || '#ffffff',
                        title_color: this.bioPage.title_color || '#1e293b',
                        bio_color: this.bioPage.bio_color || '#64748b',
                        link_bg_color: this.bioPage.link_bg_color || '#ffffff',
                        link_text_color: this.bioPage.link_text_color || '#1e293b',
                        header_bg_color: this.bioPage.header_bg_color || '',
                        font_family: this.bioPage.font_family || 'default',
                        block_shape: this.bioPage.block_shape || 'rounded',
                        block_shadow: this.bioPage.block_shadow || 'sm',
                        social_links: this.bioPage.social_links || [],
                        social_icons_position: this.bioPage.social_icons_position || 'below_bio',
                        _method: 'PUT'
                    };
                    try {
                        const response = await fetch(updateUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(dataToSave)
                        });
                        const responseText = await response.text();
                        if (!response.ok) {
                            let errorData;
                            try {
                                errorData = JSON.parse(responseText);
                            } catch (e) {
                                console.error('Server returned HTML instead of JSON:', responseText.substring(0, 200));
                                throw new Error('Server error: ' + response.status + ' - Check server logs');
                            }
                            console.error('Save error:', errorData);
                            throw new Error(errorData.message || 'Failed to save bio page');
                        }
                        return JSON.parse(responseText);
                    } catch (error) {
                        console.error('Fetch error:', error);
                        throw error;
                    }
                },
                async saveBlocks() {
                    for (let i = 0; i < this.blocks.length; i++) {
                        const block = this.blocks[i];
                        block.order = i;
                        if (block.type === 'link' && (!block.title || !block.url)) {
                            continue;
                        }
                        if (block.id > 1000000000) {
                            await this.createBlock(block);
                        } else {
                            await this.updateBlock(block);
                        }
                    }
                    this.showNotification('Blocks saved successfully!', 'success');
                },
                async saveBlocksOrder() {
                    const reorderUrl = '{{ route('bio.links.reorder', $bioPage) }}';
                    const linksData = this.blocks
                        .filter(b => b.id < 1000000000)
                        .map((b, idx) => ({ id: b.id, order: idx }));
                    if (linksData.length === 0) return;
                    try {
                        const response = await fetch(reorderUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ links: linksData })
                        });
                        if (response.ok) {
                            this.showNotification('Block order saved!', 'success');
                        }
                    } catch (e) {
                        console.error('Failed to save block order:', e);
                    }
                },
                async createShortlink(url, title) {
                    try {
                        const response = await fetch('/api/shorten-web', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                url: url,
                                title: title || 'Bio Link',
                                is_public: false
                            })
                        });
                        if (!response.ok) throw new Error('Failed to create shortlink');
                        const data = await response.json();
                        return data.short_url || data.url;
                    } catch (error) {
                        console.error('Shortlink creation error:', error);
                        return null;
                    }
                },
                normalizeUrlInput(raw) {
                    if (!raw) return '';
                    const trimmed = raw.trim();
                    if (/^https?:\/\//i.test(trimmed)) {
                        return trimmed;
                    }
                    return 'https://' + trimmed.replace(/^\/+/, '');
                },
                async searchUserLibrary(query) {
                    const q = (query || '').trim();
                    if (!q) return [];
                    try {
                        const res = await fetch(`/api/links?query=${encodeURIComponent(q)}`, { headers: { 'Accept': 'application/json' } });
                        if (!res.ok) return [];
                        const data = await res.json();
                        return Array.isArray(data) ? data : (data.items || []);
                    } catch(e) { return []; }
                },
                async createBlock(block) {
                    const createUrl = '{{ route('bio.links.store', $bioPage) }}';
                    if (!block.title) {
                        if (block.type === 'text') {
                            block.title = 'Text Block';
                        } else if (block.type === 'image') {
                            block.title = 'Image Block';
                        }
                    }
                    if (block.type === 'link' && (!block.title || !block.url)) {
                        this.showNotification('Please enter both title and URL for link blocks', 'error');
                        throw new Error('Validation failed');
                    }
                    const blockData = {
                        ...block,
                        title: String(block.title || ''),
                        content: block.content || null,
                        url: block.url || null
                    };
                    const response = await fetch(createUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(blockData)
                    });
                    if (!response.ok) {
                        const errorData = await response.json().catch(() => ({}));
                        console.error('Create block error:', errorData);
                        throw new Error(errorData.message || 'Failed to create block');
                    }
                    const data = await response.json();
                    block.id = data.link.id;
                },
                async updateBlock(block) {
                    const updateUrl = `/bio/${this.bioPage.id}/links/${block.id}`;
                    if (!block.title) {
                        if (block.type === 'text') {
                            block.title = 'Text Block';
                        } else if (block.type === 'image') {
                            block.title = 'Image Block';
                        }
                    }
                    if (block.type === 'link' && (!block.title || !block.url)) {
                        this.showNotification('Please enter both title and URL for link blocks', 'error');
                        throw new Error('Validation failed');
                    }
                    const blockData = {
                        ...block,
                        title: String(block.title || ''),
                        content: block.content || null,
                        url: block.url || null
                    };
                    const response = await fetch(updateUrl, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(blockData)
                    });
                    if (!response.ok) {
                        const errorData = await response.json().catch(() => ({}));
                        console.error('Update block error:', errorData);
                        throw new Error(errorData.message || 'Failed to update block');
                    }
                    return response.json();
                },
                showNotification(message, type) {
                    const notification = document.createElement('div');
                    notification.className = `fixed top-4 right-4 z-50 max-w-sm rounded-lg p-4 shadow-2xl transition-all duration-300 transform translate-x-0 ${
                        type === 'success' ? 'bg-green-600' : 
                        type === 'warning' ? 'bg-yellow-600' : 
                        'bg-red-600'
                    } text-white`;
                    const icon = type === 'success' ? '✓' : type === 'warning' ? '⚠️' : '✕';
                    notification.innerHTML = `
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">${icon}</span>
                            <div class="flex-1">
                                <p class="font-medium">${type === 'success' ? 'Success!' : type === 'warning' ? 'Warning' : 'Error'}</p>
                                <p class="text-sm opacity-90 mt-1">${message}</p>
                            </div>
                            <button onclick="this.parentElement.parentElement.remove()" class="text-white hover:text-gray-200">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    `;
                    document.body.appendChild(notification);
                    setTimeout(() => {
                        notification.style.transform = 'translateX(400px)';
                        setTimeout(() => notification.remove(), 300);
                    }, 5000);
                },
                getPreviewHtml(mode = 'mobile') {
                    const theme = this.bioPage.theme;
                    const name = this.bioPage.title || 'Your Name';
                    const bio = this.bioPage.bio || '';
                    const profilePic = this.bioPage.avatar_url ? '{{ Storage::url('') }}' + this.bioPage.avatar_url : '';
                    const allBlocks = (this.blocks || []).filter(b => b.is_active).sort((a, b) => (a.order ?? a.position ?? 0) - (b.order ?? b.position ?? 0));
                    const socials = (this.bioPage.social_links || []).filter(s => s.enabled && s.value).map(s => ({ platform: s.platform, value: s.value }));
                    const socialPosition = this.bioPage.social_icons_position || 'below_bio';
                    const layout = this.bioPage.layout || 'centered';
                    const bgType = this.bioPage.background_type || 'solid';
                    const bgValue = this.bioPage.background_value || '#f0f9ff';
                    const titleColor = this.bioPage.title_color || '#1e293b';
                    const bioColor = this.bioPage.bio_color || '#64748b';
                    const linkTextColor = this.bioPage.link_text_color || '#1e293b';
                    const linkBgColor = this.bioPage.link_bg_color || '#ffffff';
                    const headerBgColor = this.bioPage.header_bg_color || '';
                    const fontFamily = this.bioPage.font_family || 'Inter';
                    const blockShape = this.bioPage.block_shape || 'rounded';
                    const blockShadow = this.bioPage.block_shadow || 'sm';
                    const isDark = theme && theme.includes('dark');
                    const isModern = theme && theme.includes('modern');
                    const isClassic = theme && theme.includes('classic');
                    let bgStyle = '';
                    if (theme && theme !== 'none') {
                        bgStyle = isDark ? 'linear-gradient(135deg, #1e293b 0%, #0f172a 100%)' : 
                                  isModern ? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' :
                                  isClassic ? 'linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%)' :
                                  'linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%)';
                    } else {
                        bgStyle = bgType === 'gradient' ? bgValue : bgValue;
                    }
                    const textColor = theme && theme !== 'none' ? (isDark ? '#f1f5f9' : '#1e293b') : titleColor;
                    const descColor = theme && theme !== 'none' ? (isDark ? 'rgba(241,245,249,0.8)' : 'rgba(30,41,59,0.7)') : bioColor;
                    const blockBg = theme && theme !== 'none' ? (isDark ? 'rgba(51, 65, 85, 0.9)' : 'rgba(255, 255, 255, 0.9)') : linkBgColor;
                    const blockText = theme && theme !== 'none' ? textColor : linkTextColor;
                    const blockBorder = isDark ? 'rgba(71, 85, 105, 0.5)' : 'rgba(0, 0, 0, 0.1)';
                    const socialBg = isDark ? 'rgba(51, 65, 85, 0.8)' : 'rgba(241, 245, 249, 0.9)';
                    const cardBg = theme && theme !== 'none' ? (isDark ? 'rgba(30, 41, 59, 0.95)' : 'rgba(255, 255, 255, 0.95)') : 'transparent';
                    const borderRadius = blockShape === 'square' ? '4px' : blockShape === 'pill' ? '9999px' : '12px';
                    const shadowStyle = blockShadow === 'none' ? 'none' : 
                                       blockShadow === 'sm' ? '0 1px 2px rgba(0,0,0,0.05)' :
                                       blockShadow === 'md' ? '0 4px 6px -1px rgba(0,0,0,0.1)' :
                                       '0 10px 15px -3px rgba(0,0,0,0.1)';
                    const isDesktop = mode === 'desktop';
                    const avatarSize = isDesktop ? '80px' : '60px';
                    const titleSize = isDesktop ? '18px' : '14px';
                    const bioSize = isDesktop ? '13px' : '10px';
                    const linkPadding = isDesktop ? '14px 18px' : '12px 14px';
                    const linkFontSize = isDesktop ? '14px' : '12px';
                    const socialIconSize = isDesktop ? '36px' : '32px';
                    const socialImgSize = isDesktop ? '20px' : '18px';
                    const containerPadding = isDesktop ? '24px' : '12px';
                    const cardPadding = isDesktop ? '28px' : '20px';
                    let maxWidth = '100%';
                    if (isDesktop) {
                        maxWidth = layout === 'wide' ? '600px' : '500px';
                    }
                    const containerAlign = layout === 'left' ? 'flex-start' : 'center';
                    const textAlign = layout === 'left' ? 'left' : 'center';
                    const blocksHtml = allBlocks.map(b => {
                        if (b.type === 'link') {
                            const icon = b.thumbnail_url ? (b.thumbnail_url.startsWith('http') ? b.thumbnail_url : '/storage/' + b.thumbnail_url) : '';
                            const link = b.pending_short_url || b.url || '#';
                            const title = b.title || 'Link';
                            if (icon) {
                                return `
                                    <div style="margin-bottom:8px;">
                                        <a href="${link}" target="_blank" rel="noopener" style="display:flex;align-items:center;gap:12px;text-decoration:none;color:${blockText};background:${blockBg};border:1px solid ${blockBorder};padding:${linkPadding};border-radius:${borderRadius};font-size:${linkFontSize};transition:all 0.2s;box-shadow:${shadowStyle};">
                                            <img src="${icon}" style="width:${isDesktop ? '32px' : '28px'};height:${isDesktop ? '32px' : '28px'};border-radius:50%;object-fit:cover;flex-shrink:0;" alt="">
                                            <span style="flex:1;text-align:left;font-weight:500;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${title}</span>
                                        </a>
                                    </div>
                                `;
                            } else {
                                return `
                                    <div style="margin-bottom:8px;">
                                        <a href="${link}" target="_blank" rel="noopener" style="display:block;text-decoration:none;color:${blockText};background:${blockBg};border:1px solid ${blockBorder};padding:${linkPadding};border-radius:${borderRadius};font-size:${linkFontSize};transition:all 0.2s;box-shadow:${shadowStyle};text-align:center;">
                                            <span style="font-weight:500;">${title}</span>
                                        </a>
                                    </div>
                                `;
                            }
                        } else if (b.type === 'text') {
                            const content = b.content || '';
                            return `
                                <div style="margin-bottom:8px;">
                                    <div style="background:rgba(255,255,255,0.5);border:1px solid ${blockBorder};border-radius:12px;overflow:hidden;backdrop-filter:blur(4px);">
                                        <div style="padding:6px 10px;border-bottom:1px solid ${blockBorder};background:rgba(0,0,0,0.03);display:flex;align-items:center;gap:4px;">
                                            <span style="width:8px;height:8px;border-radius:50%;background:#f87171;"></span>
                                            <span style="width:8px;height:8px;border-radius:50%;background:#facc15;"></span>
                                            <span style="width:8px;height:8px;border-radius:50%;background:#4ade80;"></span>
                                        </div>
                                        <div style="padding:${linkPadding};font-size:${linkFontSize};color:${blockText};line-height:1.5;">${content}</div>
                                    </div>
                                </div>
                            `;
                        } else if (b.type === 'image' && b.thumbnail_url) {
                            const imgSrc = b.thumbnail_url.startsWith('http') ? b.thumbnail_url : '/storage/' + b.thumbnail_url;
                            const title = b.title || '';
                            return `
                                <div style="margin-bottom:8px;">
                                    <img src="${imgSrc}" style="width:100%;max-height:180px;object-fit:contain;border-radius:12px;" alt="${title}">
                                    ${title ? `<p style="margin-top:8px;text-align:center;font-size:${linkFontSize};color:${blockText};">${title}</p>` : ''}
                                </div>
                            `;
                        }
                        return '';
                    }).join('');
                    const socialsIconsHtml = socials.length ? socials.map(s => `
                        <a href="#" style="display:flex;align-items:center;justify-content:center;width:${socialIconSize};height:${socialIconSize};border-radius:50%;background:${socialBg};">
                            <img src="/images/brands/${s.platform}.svg" style="width:${socialImgSize};height:${socialImgSize};filter:${isDark ? 'brightness(0) invert(1)' : 'brightness(0)'};" alt="${s.platform}" onerror="this.style.display='none'">
                        </a>
                    `).join('') : '';
                    const socialsBelowBio = socialPosition === 'below_bio' && socials.length ? `
                        <div style="display:flex;gap:10px;justify-content:center;margin-top:12px;flex-wrap:wrap;">
                            ${socialsIconsHtml}
                        </div>
                    ` : '';
                    const socialsBottom = socialPosition === 'bottom_page' && socials.length ? `
                        <div style="display:flex;gap:10px;justify-content:center;margin-top:20px;flex-wrap:wrap;">
                            ${socialsIconsHtml}
                        </div>
                    ` : '';
                    const hasTheme = theme && theme !== 'none';
                    const avatarMargin = layout === 'left' ? '0' : '0 auto';
                    const showHeaderBg = layout === 'wide' && headerBgColor;
                    const headerBgStyle = showHeaderBg ? `background:${headerBgColor};margin:-${containerPadding};margin-bottom:16px;padding:${containerPadding};padding-top:28px;padding-bottom:20px;border-radius:0 0 20px 20px;` : '';
                    return `
                        <div style="min-height:100%;height:100%;background:${bgStyle};padding:${containerPadding};padding-top:${showHeaderBg ? '0' : '28px'};font-family:${fontFamily}, sans-serif;display:flex;align-items:${containerAlign};">
                            <div style="width:100%;max-width:${maxWidth};margin:0 auto;${hasTheme ? `background:${cardBg};border-radius:20px;padding:${cardPadding};backdrop-filter:blur(10px);box-shadow:0 25px 50px -12px rgba(0,0,0,0.25);` : ''}">
                                <div style="text-align:${textAlign};margin-bottom:12px;${headerBgStyle}">
                                    ${profilePic ? `<img src="${profilePic}" alt="Profile" style="width:${avatarSize};height:${avatarSize};border-radius:9999px;object-fit:cover;margin:${avatarMargin};display:block;border:3px solid rgba(255,255,255,0.5);box-shadow:0 4px 6px -1px rgba(0,0,0,0.1);"/>` : `<div style="width:${avatarSize};height:${avatarSize};border-radius:9999px;background:linear-gradient(135deg,#3b82f6,#8b5cf6);margin:${avatarMargin};display:flex;align-items:center;justify-content:center;color:white;font-weight:bold;font-size:${isDesktop ? '28px' : '22px'};border:3px solid rgba(255,255,255,0.5);box-shadow:0 4px 6px -1px rgba(0,0,0,0.1);">${name.charAt(0)}</div>`}
                                    <p style="margin-top:10px;font-weight:700;font-size:${titleSize};color:${showHeaderBg ? '#ffffff' : textColor};">${name}</p>
                                    ${bio ? `<p style="margin-top:4px;font-size:${bioSize};color:${showHeaderBg ? 'rgba(255,255,255,0.9)' : descColor};line-height:1.4;">${bio}</p>` : ''}
                                </div>
                                ${socialsBelowBio}
                                <div style="margin-top:16px;">
                                    ${blocksHtml || `<p style="text-align:center;font-size:${isDesktop ? '13px' : '11px'};opacity:0.6;padding:20px 0;">No links yet</p>`}
                                </div>
                                ${socialsBottom}
                                <div style="margin-top:20px;text-align:center;">
                                    <span style="font-size:${isDesktop ? '11px' : '10px'};color:${textColor};opacity:0.5;">Powered by HEL.ink</span>
                                </div>
                            </div>
                        </div>
                    `;
                },
                getAnkerPreviewHtml() {
                    const theme = this.bioPage.theme;
                    if (!theme || theme === 'none') return '';
                    const name = this.bioPage.title || 'Your Name';
                    const bio = this.bioPage.bio || '';
                    const profilePic = this.bioPage.avatar_url ? '{{ Storage::url('') }}' + this.bioPage.avatar_url : '';
                    const links = (this.blocks || []).filter(b => b.type === 'link' && b.is_active).slice(0, 10).map(b => ({ 
                        title: b.title || 'Link', 
                        link: b.pending_short_url || b.url || '#',
                        icon: b.thumbnail_url ? (b.thumbnail_url.startsWith('http') ? b.thumbnail_url : '/storage/' + b.thumbnail_url) : ''
                    }));
                    const socials = (this.bioPage.social_links || []).filter(s => s.enabled && s.value).map(s => ({ platform: s.platform, value: s.value }));
                    const socialPosition = this.bioPage.social_icons_position || 'below_bio';
                    const isDark = theme.includes('dark');
                    const isModern = theme.includes('modern');
                    const isClassic = theme.includes('classic');
                    let bgColor = isDark ? 'linear-gradient(135deg, #1e293b 0%, #0f172a 100%)' : 
                                  isModern ? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' :
                                  isClassic ? 'linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%)' :
                                  'linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%)';
                    let cardBg = isDark ? 'rgba(30, 41, 59, 0.95)' : 'rgba(255, 255, 255, 0.95)';
                    let textColor = isDark ? '#f1f5f9' : '#1e293b';
                    let blockBg = isDark ? 'rgba(51, 65, 85, 0.9)' : 'rgba(255, 255, 255, 0.9)';
                    let blockBorder = isDark ? 'rgba(71, 85, 105, 0.5)' : 'rgba(0, 0, 0, 0.1)';
                    let socialBg = isDark ? 'rgba(51, 65, 85, 0.8)' : 'rgba(241, 245, 249, 0.9)';
                    const linksHtml = links.map(l => `
                        <div style="margin-bottom:8px;">
                            <a href="${l.link}" style="display:flex;align-items:center;gap:8px;text-decoration:none;color:${textColor};background:${blockBg};border:1px solid ${blockBorder};padding:12px 14px;border-radius:12px;font-size:12px;transition:all 0.2s;">
                                ${l.icon ? `<img src="${l.icon}" style="width:24px;height:24px;border-radius:50%;object-fit:cover;flex-shrink:0;" alt="">` : ''}
                                <span style="flex:1;text-align:center;font-weight:500;">${l.title}</span>
                            </a>
                        </div>
                    `).join('');
                    const socialsIconsHtml = socials.length ? socials.map(s => `
                        <a href="#" style="display:flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:50%;background:${socialBg};">
                            <img src="/images/brands/${s.platform}.svg" style="width:18px;height:18px;filter:${isDark ? 'brightness(0) invert(1)' : 'brightness(0)'};" alt="${s.platform}" onerror="this.style.display='none'">
                        </a>
                    `).join('') : '';
                    const socialsBelowBio = socialPosition === 'below_bio' && socials.length ? `
                        <div style="display:flex;gap:10px;justify-content:center;margin-top:12px;flex-wrap:wrap;">
                            ${socialsIconsHtml}
                        </div>
                    ` : '';
                    const socialsBottom = socialPosition === 'bottom_page' && socials.length ? `
                        <div style="display:flex;gap:10px;justify-content:center;margin-top:20px;flex-wrap:wrap;">
                            ${socialsIconsHtml}
                        </div>
                    ` : '';
                    return `
                        <div style="min-height:100%;background:${bgColor};padding:12px;">
                            <div style="background:${cardBg};border-radius:20px;padding:20px;backdrop-filter:blur(10px);box-shadow:0 25px 50px -12px rgba(0,0,0,0.25);">
                                <div style="text-align:center;margin-bottom:12px;">
                                    ${profilePic ? `<img src="${profilePic}" alt="Profile" style="width:60px;height:60px;border-radius:9999px;object-fit:cover;margin:0 auto;display:block;border:3px solid rgba(255,255,255,0.5);box-shadow:0 4px 6px -1px rgba(0,0,0,0.1);"/>` : `<div style="width:60px;height:60px;border-radius:9999px;background:linear-gradient(135deg,#3b82f6,#8b5cf6);margin:0 auto;display:flex;align-items:center;justify-content:center;color:white;font-weight:bold;font-size:22px;border:3px solid rgba(255,255,255,0.5);box-shadow:0 4px 6px -1px rgba(0,0,0,0.1);">${name.charAt(0)}</div>`}
                                    <p style="margin-top:10px;font-weight:700;font-size:14px;color:${textColor};">${name}</p>
                                    ${bio ? `<p style="margin-top:4px;font-size:10px;color:${textColor};opacity:0.8;line-height:1.4;">${bio}</p>` : ''}
                                </div>
                                ${socialsBelowBio}
                                <div style="margin-top:16px;">
                                    ${linksHtml || '<p style="text-align:center;font-size:11px;opacity:0.6;padding:20px 0;">No links yet</p>'}
                                </div>
                                ${socialsBottom}
                                <div style="margin-top:20px;text-align:center;">
                                    <span style="font-size:10px;color:${textColor};opacity:0.5;">Powered by HEL.ink</span>
                                </div>
                            </div>
                        </div>
                    `;
                },
                getSocialIconPreview(platform) {
                    // Use brands icons with proper path - return dynamic img tag
                    return `<img src="/images/brands/${platform}.svg" class="h-4 w-4" style="filter:brightness(0);" alt="${platform}" onerror="this.outerHTML='<svg class=\'h-4 w-4\' viewBox=\'0 0 24 24\' fill=\'currentColor\'><circle cx=\'12\' cy=\'12\' r=\'10\'/></svg>'">`;
                }
            };
        }
    </script>
    @endpush
</x-app-layout>
