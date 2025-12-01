<x-marketing-layout title="About - HEL.ink">
    <section class="mx-auto max-w-5xl px-6 py-16 lg:px-8">
        <p class="text-xs uppercase tracking-[0.4em] text-white/60">About</p>
        <h1 class="mt-4 text-4xl font-semibold text-white">Why Hel.ink exists</h1>
        <p class="mt-4 max-w-3xl text-lg text-white/70">
            Hel.ink is a modern, open-source URL shortener built for individuals and teams who want powerful link management without the complexity. 
            We focus on clean redirects, comprehensive analytics, and features that actually matter, all while respecting your privacy.
        </p>
        <p class="mt-6 max-w-3xl text-base text-white/70">Whether you're managing social media campaigns, organizing team resources, or tracking marketing performance, Hel.ink provides the tools you need: folders and tags for organization, QR code generation, password protection, link expiration, comprehensive analytics with geographic tracking and device detection, REST API for ShareX and automation tools, and even proxy/VPN detection for security.</p>
        <p class="mt-4 max-w-3xl text-base text-white/70">Built with Laravel 12 and modern web technologies, Hel.ink is designed for both developers and everyday users. Sign in with Google OAuth or email, organize your links with folders and tags, integrate with ShareX and CLI tools via REST API, track every click with detailed analytics powered by multiple geolocation providers, and download QR codes in multiple formats. Everything works seamlessly across devices with our dark mode interface.</p>
        
        <div class="mt-12 max-w-3xl space-y-6">
            <h2 class="text-2xl font-semibold text-white">Our Mission</h2>
            <p class="text-white/70">URL shortening should be powerful yet simple, feature-rich without being overwhelming. Hel.ink was built to eliminate the frustration of juggling multiple tools: no more switching between services for shortening, analytics, QR codes, and organization. Everything you need is in one place, with an interface that makes sense from day one.</p>
            
            <h2 class="mt-10 text-2xl font-semibold text-white">What Makes Us Different</h2>
            <p class="text-white/70">Hel.ink provides enterprise-grade features without the enterprise price tag. Get detailed analytics including geographic data (city, country), ISP tracking, device and browser detection, proxy/VPN detection, and referrer tracking. Organize with unlimited folders and tags. Secure links with password protection. Generate custom QR codes in PNG, SVG, or JPG. Set expiration dates. Track unique visitors. All included, no hidden costs or premium tiers.</p>
            
            <h2 class="mt-10 text-2xl font-semibold text-white">Built for Real Workflows</h2>
            <p class="text-white/70">Every feature exists because users needed it. Google OAuth for quick sign-in. Two-factor authentication for security. REST API for ShareX integration and automation. Bulk operations for efficiency. Export to CSV for reporting. Admin panel with domain blacklist and abuse reporting. Dark mode because your eyes matter. Queue-based click logging for performance. Comprehensive login history with location tracking. These aren't "nice-to-haves"; they're essentials for modern link management.</p>
            
            <h2 class="mt-10 text-2xl font-semibold text-white">Open Source & Self-Hostable</h2>
            <p class="text-white/70">Hel.ink is completely open source on GitHub. Self-host on your own infrastructure for complete control, or use our hosted version for convenience. Full transparency means you can audit the code, contribute improvements, or customize it for your needs. Built by <a href="https://github.com/navi-crwn" class="text-blue-400 hover:text-blue-300" target="_blank">@navi-crwn</a>, maintained with love, and improved by the community.</p>
        </div>

        <div class="mt-16 grid gap-6 md:grid-cols-3">
            <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
                <p class="text-sm uppercase tracking-[0.3em] text-white/50">Analytics</p>
                <h3 class="mt-2 text-xl font-semibold text-white">Deep insights</h3>
                <p class="mt-2 text-white/70">Track every click with geographic data (city, country via multi-provider IP geolocation), device types, browsers, operating systems, ISP information, proxy/VPN detection, referrer sources, and unique visitor counts. Export everything to CSV for reporting and analysis.</p>
            </div>
            <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
                <p class="text-sm uppercase tracking-[0.3em] text-white/50">Organization</p>
                <h3 class="mt-2 text-xl font-semibold text-white">Folders & Tags</h3>
                <p class="mt-2 text-white/70">Create unlimited folders and tags to organize your links. Bulk operations let you manage hundreds of links at once. Search and filter to find exactly what you need in seconds.</p>
            </div>
            <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
                <p class="text-sm uppercase tracking-[0.3em] text-white/50">Security</p>
                <h3 class="mt-2 text-xl font-semibold text-white">Protected & Safe</h3>
                <p class="mt-2 text-white/70">Password protect sensitive links, set expiration dates, enable 2FA on your account, and review login history. Admins get IP banning, domain blacklist, and abuse reporting tools.</p>
            </div>
            <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
                <p class="text-sm uppercase tracking-[0.3em] text-white/50">Automation</p>
                <h3 class="mt-2 text-xl font-semibold text-white">API Ready</h3>
                <p class="mt-2 text-white/70">REST API with Bearer authentication for ShareX, CLI tools, and custom applications. Rate limited at 100 requests per hour. Perfect for screenshot automation and bulk operations.</p>
            </div>
        </div>
        
        <div class="mt-16 rounded-3xl border border-white/10 bg-white/5 p-8">
            <h2 class="text-2xl font-semibold text-white">Ready to Get Started?</h2>
            <p class="mt-3 text-white/70">Join Hel.ink and experience link management that actually works. Sign up with Google or email, create your first short link in seconds, and discover why simplicity and power can coexist.</p>
            <div class="mt-6 flex flex-wrap gap-4">
                <a href="{{ route('register') }}" class="inline-block rounded-full bg-white px-6 py-3 font-semibold text-slate-900 hover:bg-white/90">Create Free Account</a>
                <a href="https://github.com/navi-crwn/hel.ink" target="_blank" class="inline-block rounded-full border border-white/20 bg-white/5 px-6 py-3 font-semibold text-white hover:bg-white/10">View on GitHub</a>
            </div>
        </div>
    </section>
</x-marketing-layout>
