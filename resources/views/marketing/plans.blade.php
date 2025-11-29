<x-marketing-layout title="Usage tiers - HEL.ink">
    <section class="mx-auto max-w-5xl px-6 py-16 lg:px-8">
        <p class="text-xs uppercase tracking-[0.4em] text-white/60">Usage tiers</p>
        <h1 class="mt-4 text-4xl font-semibold text-white">Free forever, with sensible limits</h1>
        <p class="mt-4 text-white/70">Every plan is free. We simply separate limits for guests and signed-in users to keep HEL.ink stable.</p>

        <div class="mt-10 grid gap-6 md:grid-cols-2">
            <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
                <p class="text-sm uppercase tracking-[0.3em] text-white/50">Guest</p>
                <h3 class="mt-2 text-3xl font-semibold text-white">Public links</h3>
                <ul class="mt-4 space-y-2 text-sm text-white/70">
                    <li>Up to {{ config('limits.guest.max_links_per_day') }} shortlinks per day</li>
                    <li>Total cap {{ config('limits.guest.max_total_links') }} active guest links</li>
                    <li>Random + custom slug options</li>
                    <li>Basic analytics snapshot in the success card</li>
                </ul>
            </div>
            <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
                <p class="text-sm uppercase tracking-[0.3em] text-white/50">Account</p>
                <h3 class="mt-2 text-3xl font-semibold text-white">Personal workspace</h3>
                <ul class="mt-4 space-y-2 text-sm text-white/70">
                    <li>{{ config('limits.user.max_links_per_day') }} creations per day</li>
                    <li>{{ config('limits.user.max_active_links') }} active links stored</li>
                    <li>Folders, tags, comments, QR export, passwords, expiration</li>
                    <li>Full analytics dashboard + CSV export (soon)</li>
                </ul>
            </div>
        </div>
    </section>
</x-marketing-layout>
