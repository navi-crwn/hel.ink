@php
    $marketingLinks = [
        ['label' => 'Features', 'href' => route('home') . '#features'],
        ['label' => 'Stories', 'href' => route('home') . '#stories'],
        ['label' => 'Products', 'href' => route('products')],
        ['label' => 'Help Center', 'href' => route('help')],
        ['label' => 'FAQ', 'href' => route('faq')],
    ];
@endphp
<nav class="mx-auto flex max-w-6xl items-center justify-between px-6 py-6 lg:px-8">
    <a href="{{ route('home') }}" class="flex items-center gap-3 text-white">
        <img src="{{ route('brand.logo') }}" alt="HEL.ink logo" class="h-10 w-10" />
        <span class="text-2xl font-semibold tracking-tight">HEL.ink</span>
    </a>
    <div class="hidden items-center gap-6 text-sm font-medium text-white/70 md:flex">
        @foreach ($marketingLinks as $item)
            <a href="{{ $item['href'] }}" class="hover:text-white">{{ $item['label'] }}</a>
        @endforeach
    </div>
    <div class="flex items-center gap-3 text-sm">
        @auth
            <a href="{{ route('dashboard') }}" class="rounded-full border border-white/30 px-4 py-2 text-white hover:bg-white/10 transition">Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="text-white/80 hover:text-white">Log in</a>
            <a href="{{ route('register') }}" class="rounded-full bg-white px-4 py-2 font-semibold text-slate-900">Join now</a>
        @endauth
    </div>
</nav>
