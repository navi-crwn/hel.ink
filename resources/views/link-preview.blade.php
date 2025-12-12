<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ $link->short_url }}" />
    <meta property="og:title" content="{{ $link->custom_title ?? config('app.name') }}" />
    <meta property="og:description" content="{{ $link->custom_description ?? 'Shortlink by '.config('app.name') }}" />
    @if($link->custom_image_url)
        <meta property="og:image" content="{{ $link->custom_image_url }}" />
        <meta property="og:image:width" content="1200" />
        <meta property="og:image:height" content="630" />
    @endif
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:url" content="{{ $link->short_url }}" />
    <meta name="twitter:title" content="{{ $link->custom_title ?? config('app.name') }}" />
    <meta name="twitter:description" content="{{ $link->custom_description ?? 'Shortlink by '.config('app.name') }}" />
    @if($link->custom_image_url)
        <meta name="twitter:image" content="{{ $link->custom_image_url }}" />
    @endif
    <meta http-equiv="refresh" content="0;url={{ $link->target_url }}">
    <title>{{ $link->custom_title ?? config('app.name') }}</title>
    <style>
        body {font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background:#020617; color:#f8fafc; display:flex; align-items:center; justify-content:center; min-height:100vh; text-align:center;}
        .box {background:rgba(15,23,42,0.85); padding:2rem 3rem; border-radius:1.5rem; box-shadow:0 25px 50px -12px rgba(15,23,42,0.6); max-width:500px;}
        a {color:#93c5fd;}
    </style>
</head>
<body>
    <div class="box">
        <h1>{{ $link->custom_title ?? 'Redirecting...' }}</h1>
        <p class="text-slate-400 mt-2">Redirecting you to <a href="{{ $link->target_url }}">{{ parse_url($link->target_url, PHP_URL_HOST) }}</a></p>
    </div>
</body>
</html>
