<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @php
            $metaTitle = $seoSettings->site_title ?? ($title ?? config('app.name'));
            $metaDescription = $seoSettings->meta_description ?? 'Hop Easy Link keeps hel.ink lightweight for guests and teams.';
            $metaKeywords = $seoSettings->meta_keywords ?? 'shortlink, helink, qr, analytics';
            $ogTitle = $seoSettings->og_title ?? $metaTitle;
            $ogDescription = $seoSettings->og_description ?? $metaDescription;
            $ogImage = $seoSettings->og_image ?? route('brand.logo.dark');
            $favicon = $seoSettings->favicon ?? route('brand.favicon');
        @endphp
        <title>{{ $metaTitle }}</title>
        <meta name="description" content="{{ $metaDescription }}">
        <meta name="keywords" content="{{ $metaKeywords }}">
        <meta property="og:title" content="{{ $ogTitle }}">
        <meta property="og:description" content="{{ $ogDescription }}">
        <meta property="og:image" content="{{ $ogImage }}">
        <link rel="icon" href="{{ $favicon }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>[x-cloak]{display:none !important;}</style>
    </head>
    <body class="min-h-full bg-slate-950 text-white font-['Inter'] antialiased">
        <div class="relative isolate overflow-hidden">
            <div class="absolute inset-x-0 top-[-10rem] -z-10 transform-gpu overflow-hidden blur-3xl">
                <div class="relative left-1/2 aspect-[1155/678] w-[70rem] -translate-x-1/2 bg-gradient-to-r from-blue-500 to-indigo-600 opacity-30"></div>
            </div>
            @include('partials.marketing-nav')
            <main>
                {{ $slot }}
            </main>
            @include('partials.marketing-footer')
        </div>
        @stack('scripts')
    </body>
</html>
