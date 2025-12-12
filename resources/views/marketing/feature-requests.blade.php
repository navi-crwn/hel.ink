<x-marketing-layout title="Feature requests - HEL.ink">
    <section class="mx-auto max-w-4xl px-6 py-16 lg:px-8">
        <p class="text-xs uppercase tracking-[0.4em] text-white/60">Feature board</p>
        <h1 class="mt-4 text-4xl font-semibold text-white">Request or vote on improvements</h1>
        <p class="mt-4 text-white/70">Tell us what to build next. We track all submissions publicly and prioritize changes that help most people.</p>
        <form method="POST" action="https://formspree.io/f/mayvdlda" class="mt-10 rounded-3xl border border-white/10 bg-white/5 p-6 space-y-4" x-data="{ submitted: false }" x-on:submit="submitted = true">
            <div>
                <label class="text-sm font-medium text-white/80">Name</label>
                <input type="text" name="name" required class="mt-1 w-full rounded-2xl border border-white/20 bg-slate-950/60 px-4 py-3 text-white placeholder-white/40">
            </div>
            <div>
                <label class="text-sm font-medium text-white/80">Email</label>
                <input type="email" name="email" required class="mt-1 w-full rounded-2xl border border-white/20 bg-slate-950/60 px-4 py-3 text-white placeholder-white/40">
            </div>
            <div>
                <label class="text-sm font-medium text-white/80">Requested feature</label>
                <textarea name="message" rows="4" required class="mt-1 w-full rounded-2xl border border-white/20 bg-slate-950/60 px-4 py-3 text-white placeholder-white/40" placeholder="Describe the use case, why it matters, and any screenshots."></textarea>
            </div>
            <button type="submit" class="w-full rounded-2xl bg-white px-6 py-3 text-center text-slate-900 font-semibold" x-bind:disabled="submitted">
                <span x-show="!submitted">Submit request</span>
                <span x-cloak x-show="submitted">Sending...</span>
            </button>
        </form>
    </section>
</x-marketing-layout>
