<x-marketing-layout title="Help center - Hop Easy Link">
    <section class="mx-auto max-w-5xl px-6 py-16 lg:px-8">
        <p class="text-xs uppercase tracking-[0.4em] text-white/60">Help center</p>
        <h1 class="mt-4 text-4xl font-semibold text-white">Guides and troubleshooting</h1>
        <p class="mt-4 text-lg text-white/70 max-w-3xl">Welcome to the HEL.ink help center. Find answers to common questions, learn how to use advanced features, and troubleshoot any issues you encounter.</p>
        
        <div class="mt-10 grid gap-6 md:grid-cols-2">
            <article id="usage" class="rounded-3xl border border-white/10 bg-white/5 p-6">
                <h2 class="text-2xl font-semibold text-white">Create a link</h2>
                <ol class="mt-3 list-decimal space-y-2 pl-6 text-sm text-white/70">
                    <li>Paste your long URL on the homepage or dashboard.</li>
                    <li>Pick a custom slug, folder, tags, password, or expiration if needed.</li>
                    <li>Hit Create. Copy the shortlink from the overlay or toast.</li>
                    <li>Share your shortlink on social media, emails, or print materials.</li>
                </ol>
                <p class="mt-3 text-sm text-white/60">Pro tip: Use custom slugs for branded, memorable links that are easy to type and share.</p>
            </article>
            
            <article class="rounded-3xl border border-white/10 bg-white/5 p-6">
                <h2 class="text-2xl font-semibold text-white">Organize with Folders & Tags</h2>
                <p class="mt-3 text-sm text-white/70">Create folders to group links by campaign, project, or client. Add tags for cross-folder categorization and easier search. Access the Folders and Tags pages from your dashboard sidebar to manage your organization system.</p>
                <p class="mt-3 text-sm text-white/60">Example: Create a "Social Media" folder and tag links with "Facebook", "Twitter", or "LinkedIn" for better tracking.</p>
            </article>
            
            <article class="rounded-3xl border border-white/10 bg-white/5 p-6">
                <h2 class="text-2xl font-semibold text-white">Download QR Codes</h2>
                <p class="mt-3 text-sm text-white/70">Every shortlink includes a QR code. Navigate to your link's analytics page and click the QR code download button. Choose SVG for high-resolution prints or PNG for quick sharing. QR codes work great for business cards, posters, product packaging, and event materials.</p>
            </article>
            
            <article class="rounded-3xl border border-white/10 bg-white/5 p-6">
                <h2 class="text-2xl font-semibold text-white">Understanding Analytics</h2>
                <p class="mt-3 text-sm text-white/70">View detailed analytics for each link including click count, geographic distribution (powered by multiple IP geolocation APIs), device types, browsers, operating systems, referral sources, and time-based trends. Analytics are updated in real-time and retained for 30 days. Export data to CSV for deeper analysis or compliance reporting.</p>
            </article>
            
            <article class="rounded-3xl border border-white/10 bg-white/5 p-6">
                <h2 class="text-2xl font-semibold text-white">Link is blocked?</h2>
                <p class="mt-3 text-sm text-white/70">If a link gets disabled due to abuse filters, request a review through the report form or contact support with proof of ownership. We typically respond within 24 hours. Blocked links are usually the result of automated filters detecting potential spam or malicious content.</p>
            </article>
            
            <article class="rounded-3xl border border-white/10 bg-white/5 p-6">
                <h2 class="text-2xl font-semibold text-white">Password Protection</h2>
                <p class="mt-3 text-sm text-white/70">Secure your links with password protection. When creating or editing a link, add a password in the settings. Anyone clicking the link will need to enter the correct password before being redirected. Perfect for private events, beta releases, or confidential resources.</p>
            </article>
            
            <article class="rounded-3xl border border-white/10 bg-white/5 p-6">
                <h2 class="text-2xl font-semibold text-white">Link Expiration</h2>
                <p class="mt-3 text-sm text-white/70">Set an expiration date and time for temporary links. Once expired, the link will display a custom message instead of redirecting. Ideal for limited-time offers, event registrations, or time-sensitive campaigns.</p>
            </article>
            
            <article class="rounded-3xl border border-white/10 bg-white/5 p-6">
                <h2 class="text-2xl font-semibold text-white">Queue & automation</h2>
                <p class="mt-3 text-sm text-white/70">Hop Easy Link uses Redis + worker queues for background processing. Visit <code class="rounded bg-white/10 px-2 py-1">/health/queue</code> to verify the worker is alive. If you're self-hosting, ensure your queue worker is running with <code class="rounded bg-white/10 px-2 py-1">php artisan queue:work</code>.</p>
            </article>
            
            <article class="rounded-3xl border border-white/10 bg-white/5 p-6">
                <h2 class="text-2xl font-semibold text-white">Account & Privacy</h2>
                <p class="mt-3 text-sm text-white/70">Manage your account settings, update your profile, change your password, or delete your account from the Account page. We respect your privacy and never share your data with third parties. Read our Privacy Policy for complete details.</p>
            </article>
            
            <article class="rounded-3xl border border-white/10 bg-white/5 p-6">
                <h2 class="text-2xl font-semibold text-white">Link in Bio Pages</h2>
                <p class="mt-3 text-sm text-white/70">Create beautiful bio pages at hel.ink/b/yourname with unlimited links, custom themes, and full analytics. Perfect for Instagram, TikTok, and other social profiles. Choose from multiple themes, add social icons, upload an avatar, and embed videos or music. Drag and drop to reorder links, and track every view and click.</p>
                <p class="mt-3 text-sm text-white/60">Check out our official page at <a href="/b/hel" class="text-blue-400 hover:text-blue-300">hel.ink/b/hel</a> for an example!</p>
            </article>
            
            <article class="rounded-3xl border border-white/10 bg-white/5 p-6">
                <h2 class="text-2xl font-semibold text-white">Need more help?</h2>
                <p class="mt-3 text-sm text-white/70">Email support@hel.ink or open the feature request form. We try to respond within 24 hours. For urgent issues, include your account email and a detailed description of the problem. Check our FAQ page for answers to common questions.</p>
            </article>
        </div>
    </section>
</x-marketing-layout>
