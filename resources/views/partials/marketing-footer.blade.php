@php
    $footerBlocks = [
        [
            'title' => 'Company',
            'items' => [
                ['label' => 'About', 'route' => route('about')],
                ['label' => 'Contact', 'route' => route('contact')],
                ['label' => 'FAQ', 'route' => route('faq')],
                ['label' => 'Help Center', 'route' => route('help')],
            ],
        ],
        [
            'title' => 'Policies',
            'items' => [
                ['label' => 'Privacy policy', 'route' => route('privacy')],
                ['label' => 'Terms of service', 'route' => route('terms')],
                ['label' => 'Usage tiers', 'route' => route('plans')],
                ['label' => 'Request a feature', 'route' => route('feature.requests')],
            ],
        ],
        [
            'title' => 'Explore',
            'items' => [
                ['label' => 'Products', 'route' => route('products')],
                ['label' => 'Report abuse', 'route' => route('report.create')],
                ['label' => 'Features', 'route' => route('home').'#features'],
                ['label' => 'Analytics', 'route' => route('products').'#analytics-feature'],
            ],
        ],
        [
            'title' => 'HEL.ink Family',
            'items' => [
                ['label' => 'PixelHop - Image Hosting', 'route' => 'https://p.hel.ink', 'external' => true],
                ['label' => 'Image Tools', 'route' => 'https://p.hel.ink/features.php', 'external' => true],
            ],
        ],
    ];
@endphp
<footer class="mt-20 border-t border-white/10 bg-slate-950/90 py-12 text-sm text-white/70">
    <div class="mx-auto flex max-w-6xl flex-wrap gap-4 px-6 lg:px-8">
        <div class="flex-1 min-w-[240px] rounded-3xl border border-white/10 bg-white/5 p-6">
            <div class="flex items-center gap-3">
                <img src="{{ route('brand.logo') }}" alt="HEL.ink" class="h-8 w-8" />
                <p class="text-lg font-semibold text-white">HEL.ink</p>
            </div>
            <p class="mt-3 text-white/70">Modern URL shortener & Link in Bio platform. Folders, passwords, QR codes, and analytics included.</p>
            <p class="mt-4 text-xs text-white/50">© {{ date('Y') }} HEL.ink</p>
        </div>
        @foreach ($footerBlocks as $block)
            <div class="flex-1 min-w-[220px] rounded-3xl border border-white/10 bg-white/5 p-6">
                <p class="text-xs uppercase tracking-[0.3em] text-white/50">{{ $block['title'] }}</p>
                <ul class="mt-3 space-y-2">
                    @foreach ($block['items'] as $item)
                        <li><a href="{{ $item['route'] }}" @if(!empty($item['external'])) target="_blank" rel="noopener" @endif class="hover:text-white">{{ $item['label'] }}</a></li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>
</footer>
