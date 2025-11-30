<x-marketing-layout title="FAQ - Hel.ink">
    <section class="mx-auto max-w-4xl px-6 py-16 lg:px-8">
        <p class="text-xs uppercase tracking-[0.4em] text-white/60">FAQ</p>
        <h1 class="mt-4 text-4xl font-semibold text-white">Frequently asked questions</h1>
        <p class="mt-4 text-white/70">Everything you need to know about Hel.ink. Can't find what you're looking for? Contact us directly.</p>
        
        <dl class="mt-10 space-y-6">
            @php
                $faqs = [
                    [
                        'q' => 'Is Hel.ink really free?',
                        'a' => 'Yes! Hel.ink is completely free to use with all features included. No hidden costs, no premium tiers, no credit card required. Create unlimited links with full analytics, folders, tags, QR codes, and more.'
                    ],
                    [
                        'q' => 'What analytics do I get with each link?',
                        'a' => 'Every link includes comprehensive analytics: total clicks, unique visitors, geographic data (city, country with flag icons), device types, browsers, operating systems, ISP information, proxy/VPN detection, referrer sources, and click timestamps. Export everything to CSV for deeper analysis.'
                    ],
                    [
                        'q' => 'Can I organize my links?',
                        'a' => 'Absolutely! Use folders to categorize links by project or campaign, add multiple tags for flexible filtering, and perform bulk operations to manage hundreds of links at once. Search and filter to find exactly what you need instantly.'
                    ],
                    [
                        'q' => 'How do QR codes work?',
                        'a' => 'Every short link automatically generates a QR code that you can download in PNG, SVG, or JPG format. Customize colors to match your brand. Perfect for print materials, presentations, or anywhere you need offline access to your links.'
                    ],
                    [
                        'q' => 'Can I password protect my links?',
                        'a' => 'Yes! Add password protection to any link for an extra layer of security. Visitors will need to enter the password before being redirected. You can also set expiration dates to automatically disable links after a certain time.'
                    ],
                    [
                        'q' => 'What authentication options are available?',
                        'a' => 'Sign in with Google OAuth for instant access, or use traditional email/password registration. Enable two-factor authentication (2FA) with TOTP apps like Google Authenticator or Authy for enhanced security.'
                    ],
                    [
                        'q' => 'How does the abuse reporting system work?',
                        'a' => 'If you encounter a malicious or suspicious link, use our abuse reporting system to flag it. Admins can review reports, ban IPs, blacklist domains, and take action to keep the platform safe for everyone.'
                    ],
                    [
                        'q' => 'Can I self-host Hel.ink?',
                        'a' => 'Yes! Hel.ink is completely open source on GitHub. Self-host on your own infrastructure for complete control over your data and privacy. The codebase is built with Laravel 11 and includes comprehensive documentation for setup.'
                    ],
                    [
                        'q' => 'What redirect types are supported?',
                        'a' => 'Choose from 301 (permanent redirect), 302 (temporary redirect), or 307 (temporary redirect with method preservation). Different redirect types serve different SEO and caching purposes.'
                    ],
                    [
                        'q' => 'Is there a link preview feature?',
                        'a' => 'Yes! Enable preview mode on any link to show visitors a preview page before redirecting. This adds transparency and lets users know where they\'re being sent before clicking through.'
                    ],
                    [
                        'q' => 'How is my data protected?',
                        'a' => 'All passwords use secure hashing with bcrypt. You get a unique catchphrase for account recovery. Optional 2FA adds extra protection. We track login history with geographic data so you can monitor account activity. Admin features include IP banning and watchlists.'
                    ],
                    [
                        'q' => 'Can I export my data?',
                        'a' => 'Yes! Export all your links and analytics data to CSV format with comprehensive filtering options (date range, folder, tag, minimum clicks, sort order). Perfect for reporting, backups, or migrating data.'
                    ],
                ];
            @endphp
            @foreach ($faqs as $item)
                <div class="rounded-3xl border border-white/10 bg-white/5 p-6 hover:border-white/20 transition-colors">
                    <dt class="text-lg font-semibold text-white">{{ $item['q'] }}</dt>
                    <dd class="mt-3 text-white/70">{{ $item['a'] }}</dd>
                </div>
            @endforeach
        </dl>

        <div class="mt-12 rounded-3xl border border-blue-500/30 bg-blue-500/10 p-8">
            <h2 class="text-2xl font-semibold text-white">Still have questions?</h2>
            <p class="mt-3 text-white/70">Can't find the answer you're looking for? We're here to help.</p>
            <div class="mt-6 flex flex-wrap gap-4">
                <a href="{{ route('marketing.contact') }}" class="inline-block rounded-full bg-white px-6 py-3 font-semibold text-slate-900 hover:bg-white/90">Contact Us</a>
                <a href="https://github.com/navi-crwn/hel.ink" target="_blank" class="inline-block rounded-full border border-white/20 bg-white/5 px-6 py-3 font-semibold text-white hover:bg-white/10">View Documentation</a>
            </div>
        </div>
    </section>
</x-marketing-layout>
