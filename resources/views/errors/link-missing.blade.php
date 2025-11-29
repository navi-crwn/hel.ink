<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950 text-white">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Link not available - Hop Easy Link</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css'])
    </head>
    <body class="min-h-full bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 text-white font-['Inter']">
        <div class="flex min-h-screen flex-col items-center justify-center px-6 text-center">
            <span class="rounded-full border border-white/20 px-4 py-2 text-xs uppercase tracking-[0.4em] text-white/60">Hop Easy Link</span>
            <h1 class="mt-6 text-4xl font-semibold">Shortlink unavailable</h1>
            <p class="mt-3 max-w-lg text-white/70">Slug <span class="text-white">{{ $slug ?? 'unknown' }}</span> wasn’t found or has been disabled.</p>
            <div class="mt-6 flex flex-wrap items-center gap-4">
                <a href="{{ url('/') }}" class="rounded-2xl bg-blue-500 px-6 py-3 font-semibold text-white shadow-lg shadow-blue-500/40">Back to homepage</a>
            </div>
        </div>
    </body>
</html>
