<x-marketing-layout title="Frequently Asked Questions - HEL.ink">
    <section class="mx-auto max-w-5xl px-6 py-16 lg:px-8">
        <p class="text-xs uppercase tracking-[0.4em] text-white/60">FAQ</p>
        <h1 class="mt-4 text-4xl font-semibold text-white">Frequently Asked Questions</h1>
        <p class="mt-4 text-lg text-white/70 max-w-3xl">Find quick answers to common questions about HEL.ink. If you can't find what you're looking for, feel free to reach out.</p>
        <div class="mt-12 space-y-8">
            <div>
                <h2 class="text-xl font-semibold text-white">Is HEL.ink free to use?</h2>
                <p class="mt-2 text-white/70">Yes, HEL.ink is completely free. All features, including custom slugs, analytics, QR codes, and password protection, are available to all users without any cost.</p>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-white">Do I need an account to create a short link?</h2>
                <p class="mt-2 text-white/70">You can create random short links without an account directly from the homepage. However, to use custom slugs, folders, tags, and access analytics, you will need to create a free account.</p>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-white">How long are my links stored?</h2>
                <p class="mt-2 text-white/70">Links created with an account are stored indefinitely unless you delete them. Links created anonymously may be removed after a period of inactivity. Analytics data for all links is retained for 30 days.</p>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-white">Can I edit the destination of a short link?</h2>
                <p class="mt-2 text-white/70">Yes. If you created the link with an account, you can edit the destination URL at any time from your dashboard without changing the short link itself.</p>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-white">What kind of analytics do you provide?</h2>
                <p class="mt-2 text-white/70">We provide detailed, real-time analytics for each link, including click counts, geographic location (city and country via IP geolocation APIs), device types, operating systems, browsers, ISP information, and referral sources. All analytics data is retained for 30 days.</p>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-white">Is my data private?</h2>
                <p class="mt-2 text-white/70">Absolutely. We prioritize user privacy. We do not sell or share your personal data or link information with third parties. For more details, please read our <a href="{{ route('privacy') }}" class="text-blue-400 hover:underline">Privacy Policy</a>.</p>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-white">Can I use my own domain?</h2>
                <p class="mt-2 text-white/70">Custom domain support is a planned feature for future releases. Currently, all short links use the hel.ink domain. If you self-host the application, you can use any domain you own.</p>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-white">What is Link in Bio and how does it work?</h2>
                <p class="mt-2 text-white/70">Link in Bio is a free feature that creates a personal landing page at hel.ink/b/yourname where you can share unlimited links. Perfect for Instagram, TikTok, Twitter, and LinkedIn bios. Choose from 5 custom themes, add social media icons, upload avatars, and track views and clicks with detailed analytics. No monthly fees or link limits, unlike Linktree or Heylink.</p>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-white">Can I customize my bio page design?</h2>
                <p class="mt-2 text-white/70">Yes! Choose from 5 professional themes (Default, Minimal, Gradient, Dark, Vibrant), upload a custom avatar image, add social media links with icons, and even inject your own custom CSS for advanced styling. Drag and drop to reorder links, toggle link visibility, and enable/disable your bio page at any time.</p>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-white">How many links can I add to my bio page?</h2>
                <p class="mt-2 text-white/70">Unlimited! Unlike paid services that charge for more than 5-10 links, HEL.ink lets you add as many links as you need, completely free. Edit, reorder, or delete links anytime without losing analytics data.</p>
            </div>
        </div>
        <div class="mt-16 rounded-3xl border border-white/10 bg-white/5 p-8 text-center">
            <h2 class="text-2xl font-semibold text-white">Still have questions?</h2>
            <p class="mt-3 text-white/70">If you couldn't find the answer you were looking for, please don't hesitate to get in touch.</p>
            <div class="mt-6">
                <a href="{{ route('contact') }}" class="inline-block rounded-full bg-white px-6 py-3 font-semibold text-slate-900 hover:bg-white/90">Contact Us</a>
            </div>
        </div>
    </section>
</x-marketing-layout>
```
Dengan menerapkan perubahan-perubahan di atas, halaman `/about` dan `/products` Anda akan bebas dari *em dash*, dan halaman `/faq` akan berfungsi dengan benar tanpa error.
