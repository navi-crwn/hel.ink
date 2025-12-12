<x-marketing-layout title="About - HEL.ink">
    <section class="mx-auto max-w-5xl px-6 py-16 lg:px-8">
        <p class="text-xs uppercase tracking-[0.4em] text-white/60">About</p>
        <h1 class="mt-4 text-4xl font-semibold text-white">Why we built HEL.ink</h1>
        <p class="mt-4 max-w-3xl text-lg text-white/70">
            HEL.ink is a modern, open-source URL shortener for people who want simple link management without the bloat. 
            Clean redirects, solid analytics, and features that actually make sense.
        </p>
        <p class="mt-6 max-w-3xl text-base text-white/70">
            Whether you're running campaigns, sharing resources with your team, or building a Link in Bio page for social media, 
            HEL.ink has you covered: folders and tags for organization, QR codes, password protection, expiration dates, 
            geographic analytics, REST API for automation, and more.
        </p>
        <p class="mt-4 max-w-3xl text-base text-white/70">
            Built with Laravel 12 and modern web tech. Sign in with Google or email, create bio pages at hel.ink/b/yourname, 
            organize your links, integrate with ShareX, and track everything. Dark mode included because we care about your eyes.
        </p>
        <div class="mt-12 max-w-3xl space-y-6">
            <h2 class="text-2xl font-semibold text-white">Our Mission</h2>
            <p class="text-white/70">
                Link shortening should be powerful but simple. No need to juggle multiple services for shortening, analytics, 
                QR codes, and organization. Everything in one place, with an interface that makes sense from day one.
            </p>
            <h2 class="mt-10 text-2xl font-semibold text-white">What Makes Us Different</h2>
            <p class="text-white/70">
                Enterprise features without enterprise pricing. Detailed analytics with geographic data, device detection, 
                proxy detection, and referrer tracking. Unlimited folders and tags. Password protection. Custom QR codes 
                in PNG, SVG, or JPG. Expiration dates. All included, no hidden costs.
            </p>
            <h2 class="mt-10 text-2xl font-semibold text-white">Built for Real Workflows</h2>
            <p class="text-white/70">
                Every feature exists because someone needed it. Google OAuth for quick sign-in. 2FA for security. 
                REST API for ShareX and automation. Bulk operations for efficiency. CSV export for reporting. 
                Admin tools for moderation. Dark mode because your eyes matter.
            </p>
            <h2 class="mt-10 text-2xl font-semibold text-white">Open Source</h2>
            <p class="text-white/70">
                HEL.ink is 100% open source on GitHub. Self-host on your own server or use our hosted version. 
                Fork it, customize it, contribute improvements. Built by 
                <a href="https://github.com/navi-crwn" class="text-blue-400 hover:text-blue-300" target="_blank">@navi-crwn</a>, 
                maintained with love.
            </p>
        </div>
        <div class="mt-16 grid gap-6 md:grid-cols-3">
            <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
                <p class="text-sm uppercase tracking-[0.3em] text-white/50">Analytics</p>
                <h3 class="mt-2 text-xl font-semibold text-white">Deep insights</h3>
                <p class="mt-2 text-white/70">
                    Track every click with location data, device types, browsers, referrers, and unique visitor counts. 
                    Export to CSV for reporting.
                </p>
            </div>
            <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
                <p class="text-sm uppercase tracking-[0.3em] text-white/50">Organization</p>
                <h3 class="mt-2 text-xl font-semibold text-white">Folders & Tags</h3>
                <p class="mt-2 text-white/70">
                    Unlimited folders and tags to organize your links. Bulk operations to manage hundreds at once. 
                    Search and filter to find what you need.
                </p>
            </div>
            <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
                <p class="text-sm uppercase tracking-[0.3em] text-white/50">Security</p>
                <h3 class="mt-2 text-xl font-semibold text-white">Protected & Safe</h3>
                <p class="mt-2 text-white/70">
                    Password protect links, set expiration dates, enable 2FA, and review login history. 
                    Admins get IP banning and abuse reporting.
                </p>
            </div>
            <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
                <p class="text-sm uppercase tracking-[0.3em] text-white/50">Link in Bio</p>
                <h3 class="mt-2 text-xl font-semibold text-white">Your personal hub</h3>
                <p class="mt-2 text-white/70">
                    Create bio pages at hel.ink/b/yourname with unlimited links, 20+ themes, animations, 
                    and full analytics. No monthly fees.
                </p>
            </div>
            <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
                <p class="text-sm uppercase tracking-[0.3em] text-white/50">Automation</p>
                <h3 class="mt-2 text-xl font-semibold text-white">API Ready</h3>
                <p class="mt-2 text-white/70">
                    REST API with Bearer auth for ShareX, CLI tools, and custom apps. 
                    100 requests/hour. Great for screenshot automation.
                </p>
            </div>
        </div>
        <div class="mt-16 rounded-3xl border border-white/10 bg-white/5 p-8">
            <h2 class="text-2xl font-semibold text-white">Ready to Get Started?</h2>
            <p class="mt-3 text-white/70">
                Create a free account and experience link management that works. 
                Sign up with Google or email, create your first short link in seconds.
            </p>
            <div class="mt-6 flex flex-wrap gap-4">
                <a href="{{ route('register') }}" class="inline-block rounded-full bg-white px-6 py-3 font-semibold text-slate-900 hover:bg-white/90">Create Free Account</a>
                <a href="https://github.com/navi-crwn/hel.ink" target="_blank" class="inline-block rounded-full border border-white/20 bg-white/5 px-6 py-3 font-semibold text-white hover:bg-white/10">View on GitHub</a>
                <a href="/b/hel" class="inline-block rounded-full border border-indigo-400/40 bg-indigo-500/10 px-6 py-3 font-semibold text-indigo-300 hover:bg-indigo-500/20">Official Bio Page</a>
            </div>
        </div>
    </section>
</x-marketing-layout>
