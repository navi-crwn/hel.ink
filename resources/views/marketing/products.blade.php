@php use Illuminate\Support\Str; @endphp
<x-marketing-layout title="Products - HEL.ink">
    <section class="mx-auto max-w-5xl px-6 py-16 lg:px-8">
        <p class="text-xs uppercase tracking-[0.4em] text-white/60">Products</p>
        <h1 class="mt-4 text-4xl font-semibold text-white">Tools included with HEL.ink</h1>
        <p class="mt-4 text-lg text-white/70 max-w-3xl">Everything you need to create, manage, track, and optimize your short links. All features are included for free with every account—no upsells, no hidden costs.</p>
        
        <div class="mt-10 grid gap-6 md:grid-cols-2">
            <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
                <h2 class="text-2xl font-semibold text-white">URL Shortener</h2>
                <p class="mt-3 text-white/70">Create random or custom slugs, set passwords, expiration, redirect status, and branded previews. Our shortener supports multiple redirect types (301, 302, 307) for SEO flexibility and campaign control.</p>
                <p class="mt-3 text-white/70">Custom slugs let you create memorable, branded links like hel.ink/launch-2025. Random slugs provide quick, anonymous shortening without registration. Both options include full analytics and customization options.</p>
            </div>
            
            <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
                <h2 class="text-2xl font-semibold text-white">QR Code Generator</h2>
                <p class="mt-3 text-white/70">Each shortlink has a ready-to-download SVG/PNG QR code for print or packaging. High-resolution exports ensure perfect clarity on business cards, posters, product labels, and event materials.</p>
                <p class="mt-3 text-white/70">QR codes are generated instantly and include error correction for reliable scanning even if partially damaged. Download once and use forever—no expiration, no additional fees.</p>
            </div>
            
            <div id="analytics-feature" class="rounded-3xl border border-white/10 bg-white/5 p-6">
                <h2 class="text-2xl font-semibold text-white">Analytics</h2>
                <p class="mt-3 text-white/70">Per-link and global charts for clicks, referrers, devices, and geography. Track campaign performance with detailed insights into user behavior, traffic sources, and engagement patterns.</p>
                <p class="mt-3 text-white/70">Our analytics are privacy-first: we retain only the minimum metadata required for reporting, provide a configurable retention window, and allow export for compliance. Use the per-link charts to monitor campaign performance or export raw click logs for deeper analysis.</p>
                <p class="mt-3 text-white/70">View real-time click tracking, hourly/daily trends, top referrers, device breakdowns (mobile, desktop, tablet), geographic heatmaps, and browser/OS statistics. Filter by date range, country, or device type for precise reporting.</p>
            </div>
            
            <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
                <h2 class="text-2xl font-semibold text-white">Folders & Tags</h2>
                <p class="mt-3 text-white/70">Group links by campaign, apply tags, filter, and comment collaboratively. Create unlimited folders for projects, clients, or marketing channels. Tag links for cross-folder organization and powerful search capabilities.</p>
                <p class="mt-3 text-white/70">Collaborate with team members by leaving comments on links. Perfect for agencies managing multiple clients or marketing teams coordinating complex campaigns across platforms.</p>
            </div>
            
            <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
                <h2 class="text-2xl font-semibold text-white">Link Management</h2>
                <p class="mt-3 text-white/70">Comprehensive link library with search, filtering, and bulk operations. Edit links after creation, update destination URLs, change passwords, modify expiration dates, or archive old campaigns—all without losing analytics history.</p>
                <p class="mt-3 text-white/70">Bulk actions let you organize, tag, or delete multiple links at once. Export your link library to CSV for backup or reporting. Import links from other platforms with our migration tools.</p>
            </div>
            
            <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
                <h2 class="text-2xl font-semibold text-white">Security Features</h2>
                <p class="mt-3 text-white/70">Password protection, link expiration, IP banning, and abuse reporting keep your links secure. Protect sensitive content with password gates. Set expiration dates for time-limited offers. Report spam or malicious links for community safety.</p>
                <p class="mt-3 text-white/70">Admin controls include IP bans, link status management, and comprehensive abuse logs. Self-hosted instances give you complete control over security policies and data retention.</p>
            </div>
            
            <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
                <h2 class="text-2xl font-semibold text-white">Custom Branding</h2>
                <p class="mt-3 text-white/70">Customize link previews with your own title, description, and image for social media. When your links are shared on Facebook, Twitter, LinkedIn, or messaging apps, they'll display your branded preview card instead of generic metadata.</p>
                <p class="mt-3 text-white/70">Perfect for marketing campaigns, product launches, and professional presentations. Stand out in crowded social feeds with eye-catching, on-brand link previews.</p>
            </div>
            
            <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
                <h2 class="text-2xl font-semibold text-white">API Access</h2>
                <p class="mt-3 text-white/70">Integrate HEL.ink into your workflow with our RESTful API. Create, edit, delete, and analyze links programmatically. Perfect for automation, CMS integration, and custom dashboard development.</p>
                <p class="mt-3 text-white/70">Full API documentation available with examples in multiple languages. Rate limits are generous for legitimate use cases. Self-hosted instances can customize API limits to match their infrastructure.</p>
            </div>
        </div>
        
        <div class="mt-16 rounded-3xl border border-white/10 bg-white/5 p-8">
            <h2 class="text-2xl font-semibold text-white">Ready to Experience All Features?</h2>
            <p class="mt-3 text-white/70 max-w-2xl">Create a free account and get instant access to every feature listed above. No credit card required, no trial period, no feature locks. Everything is included, forever.</p>
            <div class="mt-6">
                <a href="{{ route('register') }}" class="inline-block rounded-full bg-white px-6 py-3 font-semibold text-slate-900 hover:bg-white/90">Start Using HEL.ink Free</a>
            </div>
        </div>
    </section>
</x-marketing-layout>
