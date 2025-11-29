<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950 text-white">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Page not found - Hop Easy Link</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />
        <style>
            body {font-family: 'Inter', sans-serif;}
        </style>
    </head>
    <body class="min-h-full bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 text-white">
        <div class="flex min-h-screen flex-col items-center justify-center px-6 text-center">
            <div class="flex flex-col items-center">
                <img src="{{ route('brand.logo') }}" alt="HEL.ink logo" class="h-16 w-16 rounded-full shadow-lg">
                <p class="mt-4 text-sm uppercase tracking-[0.4em] text-white/60">Hop Easy Link</p>
            </div>
            <h1 class="mt-4 text-4xl font-semibold">Link not found</h1>
            <p class="mt-2 max-w-md text-white/70">The shortlink you opened doesn’t exist anymore, is disabled, or expired. Double-check the slug or head back home.</p>
            <div class="mt-6 flex flex-wrap items-center gap-4">
                <a href="{{ url('/') }}" class="rounded-2xl bg-blue-500 px-6 py-3 font-semibold text-white shadow-lg shadow-blue-500/40">Back to homepage</a>
            </div>
        </div>
    </body>
</html>
