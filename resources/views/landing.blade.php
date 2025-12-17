<x-marketing-layout title="HEL.ink - Modern URL Shortener">
    <section class="mx-auto grid max-w-6xl gap-12 px-6 py-16 lg:grid-cols-2 lg:items-center lg:px-8">
        <div class="space-y-6">
            <p class="text-xs uppercase tracking-[0.4em] text-white/60">Our next destination is just a hop away</p>
            <h1 class="text-4xl font-semibold leading-tight text-white sm:text-5xl">
                HEL.ink keeps shortlinks friendly for guests and teammates.
            </h1>
            <p class="text-lg text-white/70">
                HEL.ink is the refreshed shortlink experience: create public links without an account, or sign in to unlock folders, QR downloads, analytics, notes, and moderation. Everything stays minimal, modern, and completely free.
            </p>
            <div class="grid gap-4 text-sm text-white/80 sm:grid-cols-2">
                <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
                    <p class="font-semibold text-white">Guests</p>
                    <p class="mt-1 text-white/70">Tap “Hop It” to mint a random slug instantly. Perfect for quick drops that do not need an account.</p>
                </div>
                <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
                    <p class="font-semibold text-white">Registered users</p>
                    <p class="mt-1 text-white/70">Open a slide-over workspace to edit destinations, folders, tags, password protection, expiration, and QR previews without leaving the page.</p>
                </div>
            </div>
        </div>
        <div class="rounded-3xl border border-white/10 bg-white/5 p-8 shadow-2xl backdrop-blur">
            @if (session('link_error'))
                <div class="mb-4 rounded-2xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-100">
                    {{ session('link_error') }}
                </div>
            @endif
            <form
                    x-data="window.turnstileForm ? window.turnstileForm('{{ config('services.turnstile.site_key') }}', {{ $errors->has('turnstile') ? 'true' : 'false' }}) : {}"
                    x-init="
                        if (!window.turnstileForm) {
                            let interval = setInterval(() => {
                                if (window.turnstileForm) {
                                    Object.assign($data, window.turnstileForm('{{ config('services.turnstile.site_key') }}', {{ $errors->has('turnstile') ? 'true' : 'false' }}));
                                    clearInterval(interval);
                                }
                            }, 50);
                        }
                    "
                    x-on:submit="handleSubmit($event)"
                    method="POST"
                    action="{{ route('shorten.store') }}"
                    class="space-y-4"
            >
                @csrf
                <div>
                    <label for="target_url" class="text-sm font-medium text-white/80">Long URL</label>
                    <input
                        id="target_url"
                        type="url"
                        name="target_url"
                        value="{{ old('target_url') }}"
                        placeholder="https://example.com/campaign"
                        required
                        class="mt-1 w-full rounded-2xl border border-white/20 bg-slate-900/60 px-4 py-3 text-white placeholder-white/30 focus:border-blue-400 focus:outline-none"
                    >
                </div>
                <div>
                    <label for="slug" class="text-sm font-medium text-white/80">Custom slug (optional)</label>
                    <input
                        id="slug"
                        type="text"
                        name="slug"
                        value="{{ old('slug') }}"
                        placeholder="camp-launch"
                        pattern="[A-Za-z0-9-]+"
                        class="mt-1 w-full rounded-2xl border border-white/20 bg-slate-900/60 px-4 py-3 text-white placeholder-white/30 focus:border-blue-400 focus:outline-none"
                    >
                    <p class="mt-1 text-xs text-white/50">Leave blank to auto-generate a random slug.</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-xs text-white/70">
                    Add more once you sign in: passwords, expiration, redirect types, folders, tags, analytics, comments, and QR exports.
                </div>
                @if (config('services.turnstile.site_key'))
                    <div x-cloak x-show="showCaptcha" class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                        <div x-ref="captcha"></div>
                        <p class="mt-2 text-xs text-white/60">Verify once, then hop away instantly.</p>
                    </div>
                    <input type="hidden" name="cf-turnstile-response" :value="token">
                @endif
                <button
                    type="submit"
                    class="w-full rounded-2xl bg-white py-3 text-center font-semibold text-slate-900"
                    x-bind:disabled="showCaptcha && !token"
                >
                    <span class="sr-only">Submit</span>
                    <span style="color: #0f172a;">Shorten URL</span>
                </button>
                @if ($errors->any())
                    <div class="rounded-2xl border border-rose-500/30 bg-rose-500/10 px-4 py-3 text-sm text-rose-100">
                        <ul class="list-disc pl-4">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </form>
            @if ($shortened = session('shortened_link'))
                <div class="mt-6 rounded-3xl border border-emerald-400/40 bg-emerald-500/10 p-4 text-sm text-white" x-data="{ copied: false }">
                    <p class="text-xs uppercase tracking-[0.3em] text-emerald-200">Shortlink ready</p>
                    <div class="mt-2 flex flex-wrap items-center gap-3">
                        <span class="text-lg font-semibold">{{ $shortened->short_url }}</span>
                        <button
                            type="button"
                            class="rounded-full bg-white/20 px-4 py-1 text-xs font-semibold text-white hover:bg-white/30"
                            x-on:click="navigator.clipboard.writeText('{{ $shortened->short_url }}'); copied = true; setTimeout(() => copied = false, 2500);"
                        >
                            Copy
                        </button>
                        <span x-cloak x-show="copied" class="text-xs text-emerald-200">Link copied!</span>
                    </div>
                    <p class="mt-2 text-white/70 break-words">Redirects to {{ $shortened->target_url }}</p>
                </div>
            @endif
        </div>
    </section>
    <section id="features" class="mx-auto max-w-6xl px-6 py-10 lg:px-8">
        <div class="rounded-3xl border border-white/10 bg-white/5 p-8">
            <p class="text-sm uppercase tracking-[0.4em] text-white/60">More than a link shortener</p>
            <h2 class="mt-4 text-3xl font-semibold text-white">Track, analyze, and share every link in one HEL.ink workspace.</h2>
            <div class="mt-6 grid gap-6 md:grid-cols-2">
                @php
                    $features = [
                        ['title' => 'Passwords & Expiration', 'body' => 'Lock sensitive links with a password prompt and set an expiration date or redirects type (301, 302, 307).'],
                        ['title' => 'Analytics timeline', 'body' => 'See total clicks, per-day breakdowns, referrers, user agents, devices, and country heatmaps.'],
                        ['title' => 'QR codes & folders', 'body' => 'Generate QR in PNG/SVG, drop links into folders, and tag them for fast filtering.'],
                        ['title' => 'API & Integrations', 'body' => 'REST API for ShareX, CLI tools, and custom apps. Auto-shorten screenshots and automate link creation with 100 req/hour.'],
                    ];
                @endphp
                @foreach ($features as $feature)
                    <article class="rounded-3xl border border-white/10 bg-slate-950/40 p-6">
                        <h3 class="text-xl font-semibold text-white">{{ $feature['title'] }}</h3>
                        <p class="mt-2 text-white/70">{{ $feature['body'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
    <section id="stories" class="mx-auto max-w-6xl px-6 py-16 lg:px-8">
        <div class="rounded-3xl border border-white/10 bg-white/5 p-8">
            <p class="text-sm uppercase tracking-[0.4em] text-white/60">Open Source & Community Driven</p>
            <h2 class="mt-4 text-3xl font-semibold text-white">Built with modern tech, designed for real workflows</h2>
            <p class="mt-4 text-white/70">Hel.ink is completely open source on GitHub. Built with Laravel 12, Tailwind CSS, and Alpine.js. Self-host on your infrastructure or use our hosted version.</p>
            <div class="mt-8 grid gap-6 md:grid-cols-4 text-sm text-white/80">
                <div class="rounded-3xl border border-white/10 bg-slate-950/40 p-4">
                    <p class="text-2xl font-semibold text-white">✨ Open Source</p>
                    <p class="mt-2 text-white/70">100% open source on GitHub. Fork, customize, or contribute improvements.</p>
                </div>
                <div class="rounded-3xl border border-white/10 bg-slate-950/40 p-4">
                    <p class="text-2xl font-semibold text-white">🚀 Modern Stack</p>
                    <p class="mt-2 text-white/70">Laravel 12, Tailwind CSS, Alpine.js. Fast, secure, and maintainable.</p>
                </div>
                <div class="rounded-3xl border border-white/10 bg-slate-950/40 p-4">
                    <p class="text-2xl font-semibold text-white">👥 Community</p>
                    <p class="mt-2 text-white/70">Feature requests, bug reports, and contributions welcome on GitHub.</p>
                </div>
                <div class="rounded-3xl border border-white/10 bg-slate-950/40 p-4">
                    <p class="text-2xl font-semibold text-white">🔗 Link in Bio</p>
                    <p class="mt-2 text-white/70">Create beautiful bio pages. <a href="/b/hel" class="text-blue-400 hover:text-blue-300">See example →</a></p>
                </div>
            </div>
        </div>
    </section>
    <section class="mx-auto max-w-6xl px-6 pb-20 lg:px-8">
        <div class="rounded-3xl border border-white/10 bg-gradient-to-r from-blue-600/40 to-violet-600/40 p-8 text-center">
            <h2 class="text-3xl font-semibold text-white">Ready to upgrade your link game?</h2>
            <p class="mt-4 text-white/80">Join HEL.ink for powerful analytics, folders, QR codes, and security features. All free, no premium tiers.</p>
            <div class="mt-6 flex flex-wrap items-center justify-center gap-4">
                <a href="{{ route('register') }}" class="rounded-full bg-white px-6 py-3 font-semibold text-slate-900 hover:bg-white/90">Create Free Account</a>
                <a href="https://github.com/navi-crwn/hel.ink" target="_blank" class="rounded-full border border-white/40 px-6 py-3 text-white/80 hover:text-white">View on GitHub</a>
            </div>
        </div>
    </section>
    <section class="mx-auto max-w-6xl px-6 pb-20 lg:px-8">
        <div class="rounded-3xl border border-cyan-400/30 bg-gradient-to-r from-cyan-500/10 to-purple-500/10 p-8">
            <div class="flex flex-col md:flex-row items-center gap-6">
                <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-cyan-400 to-purple-500 flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="flex-1 text-center md:text-left">
                    <p class="text-xs uppercase tracking-[0.3em] text-cyan-300/70 mb-2">Part of HEL.ink Family</p>
                    <h2 class="text-2xl font-semibold text-white">PixelHop - Image Hosting & Tools</h2>
                    <p class="mt-3 text-white/70">
                        Need image hosting? Try <strong class="text-cyan-300">PixelHop</strong> (p.hel.ink) - free premium image hosting with compress, resize, convert, OCR, and AI background removal.
                    </p>
                </div>
                <a href="https://p.hel.ink" target="_blank" rel="noopener" class="rounded-full bg-gradient-to-r from-cyan-500 to-purple-500 px-6 py-3 font-semibold text-white hover:opacity-90 transition-opacity flex items-center gap-2 flex-shrink-0">
                    Visit PixelHop
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                </a>
            </div>
        </div>
    </section>
</x-marketing-layout>
