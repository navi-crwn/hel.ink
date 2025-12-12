<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found - HEL.ink</title>
    <meta name="robots" content="noindex, nofollow">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-900 via-indigo-900 to-slate-900 flex items-center justify-center p-4">
    <div class="w-full max-w-lg text-center">
        <div class="mb-8 relative">
            <div class="w-32 h-32 mx-auto bg-indigo-500/20 rounded-full flex items-center justify-center relative">
                <svg class="w-16 h-16 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <svg class="w-32 h-32 text-red-500/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
            </div>
            <div class="absolute top-0 left-1/4 w-2 h-2 bg-indigo-400 rounded-full animate-bounce" style="animation-delay: 0.2s;"></div>
            <div class="absolute top-4 right-1/4 w-3 h-3 bg-purple-400 rounded-full animate-bounce" style="animation-delay: 0.4s;"></div>
            <div class="absolute bottom-0 left-1/3 w-2 h-2 bg-pink-400 rounded-full animate-bounce" style="animation-delay: 0.6s;"></div>
        </div>
        <h1 class="text-8xl font-black text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 mb-4">
            404
        </h1>
        <h2 class="text-2xl font-bold text-white mb-3">
            @if(isset($unpublished))
                Page Not Available
            @else
                Page Not Found
            @endif
        </h2>
        <p class="text-slate-400 mb-8 max-w-md mx-auto">
            @if(isset($unpublished))
                This bio page is currently not published. The owner may have temporarily disabled it.
            @else
                The bio page <span class="text-indigo-400 font-semibold">{{ $slug ?? 'you requested' }}</span> doesn't exist or may have been removed.
            @endif
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ url('/') }}" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition-colors inline-flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Go to Homepage
            </a>
            <a href="{{ url('/register') }}" class="px-6 py-3 bg-white/10 hover:bg-white/20 text-white font-semibold rounded-xl transition-colors border border-white/20 inline-flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Create Your Bio Page
            </a>
        </div>
        <div class="mt-12 p-6 bg-white/5 backdrop-blur-xl rounded-2xl border border-white/10">
            <p class="text-slate-300 mb-4">Looking for something specific?</p>
            <form action="{{ url('/') }}" method="GET" class="flex gap-2">
                <input type="text" name="search" placeholder="Search bio pages..." class="flex-1 px-4 py-2 bg-white/10 border border-white/10 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
            </form>
        </div>
        <div class="mt-8">
            <img src="{{ asset('images/logo-light.svg') }}" alt="HEL.ink" class="h-6 mx-auto opacity-50" onerror="this.style.display='none'">
            <p class="text-slate-500 text-sm mt-2">Powered by HEL.ink</p>
        </div>
    </div>
</body>
</html>
