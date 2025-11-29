<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Protected Link - {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600" rel="stylesheet" />
    @if($link->custom_title)
        <meta property="og:title" content="{{ $link->custom_title }}" />
    @endif
    @if($link->custom_description)
        <meta property="og:description" content="{{ $link->custom_description }}" />
    @endif
    @if($link->custom_image_url)
        <meta property="og:image" content="{{ $link->custom_image_url }}" />
    @endif
    <style>
        body {font-family: 'Inter', sans-serif; background:#020617; color:#f8fafc; display:flex;align-items:center;justify-content:center;min-height:100vh;}
        .card {background:rgba(15,23,42,0.85); padding:2rem; border-radius:1.5rem; width:100%; max-width:420px; box-shadow:0 25px 50px -12px rgba(15,23,42,0.6);}
        input[type="password"] {width:100%; padding:0.9rem 1rem; border-radius:0.75rem; border:1px solid rgba(148,163,184,0.4); background:rgba(15,23,42,0.6); color:#f8fafc;}
        button {margin-top:1rem; width:100%; padding:0.9rem; border-radius:0.75rem; background:#2563eb; color:#fff; font-weight:600; border:none;}
        .error {color:#fda4af; margin-top:0.75rem; font-size:0.875rem;}
    </style>
</head>
<body>
    <form method="POST" action="{{ url($link->slug) }}" class="card">
        @csrf
        <h1 class="text-xl font-semibold">This shortlink is password protected</h1>
        <p class="text-sm text-slate-400 mt-2">Enter the password to continue to <span class="text-slate-200">{{ $link->short_url }}</span>.</p>
        <input type="password" name="password" placeholder="Password" required autofocus>
        @error('password')
            <div class="error">{{ $message }}</div>
        @enderror
        <button type="submit">Unlock link</button>
    </form>
</body>
</html>
