<x-marketing-layout title="FAQ - Hop Easy Link">
    <section class="mx-auto max-w-4xl px-6 py-16 lg:px-8">
        <p class="text-xs uppercase tracking-[0.4em] text-white/60">FAQ</p>
        <h1 class="mt-4 text-4xl font-semibold text-white">Frequently asked questions</h1>
        <dl class="mt-10 space-y-6">
            @php
                $faqs = [
                    ['q' => 'Is Hop Easy Link really free?', 'a' => 'Yes. We only limit throughput to keep shared infrastructure healthy.'],
                    ['q' => 'Can guests delete their links?', 'a' => 'Yes, use the deletion token shown after creation or contact support with the short URL.'],
                    ['q' => 'Do you profile visitors?', 'a' => 'No. We capture minimal analytics (country, referrer, device family) to help you understand traffic.'],
                    ['q' => 'Does Hop Easy Link support QR codes?', 'a' => 'Every link automatically generates a QR image stored in your dashboard for download.'],
                    ['q' => 'How do I report abuse?', 'a' => 'Use the Report Abuse form linked in the footer or send an email to abuse@hel.ink.'],
                ];
            @endphp
            @foreach ($faqs as $item)
                <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
                    <dt class="text-lg font-semibold text-white">{{ $item['q'] }}</dt>
                    <dd class="mt-3 text-white/70">{{ $item['a'] }}</dd>
                </div>
            @endforeach
        </dl>
    </section>
</x-marketing-layout>
