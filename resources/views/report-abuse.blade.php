<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950 text-white">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Report abuse - Hop Easy Link</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
        @vite(['resources/css/app.css'])
    </head>
    <body class="min-h-full bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 font-['Inter']">
        <div class="mx-auto flex min-h-screen max-w-2xl flex-col justify-center px-6 py-12">
            <div class="rounded-3xl border border-white/10 bg-white/5 p-8 shadow-2xl backdrop-blur">
                <h1 class="text-3xl font-semibold text-white">Report a suspicious link</h1>
                <p class="mt-2 text-sm text-white/70">Flag spam, phishing, malware, or anything that violates policy. Moderators review every report—please avoid duplicate submissions for the same link.</p>
                @if (session('status'))
                    <div class="mt-4 rounded-2xl border border-emerald-400/40 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-100">
                        {{ session('status') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="mt-4 rounded-2xl border border-rose-400/40 bg-rose-500/10 px-4 py-3 text-sm text-rose-100">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('report.store') }}" class="mt-6 space-y-4">
                    @csrf
                    <input type="hidden" name="slug" value="{{ old('slug', $prefill['slug'] ?? '') }}">
                    <div>
                        <label class="text-sm text-white/80">URL</label>
                        <input type="url" name="url" value="{{ old('url', $prefill['url'] ?? '') }}" class="mt-1 w-full rounded-2xl border border-white/20 bg-white/10 px-4 py-3 text-white placeholder-white/40 focus:border-blue-500 focus:ring-blue-500" placeholder="https://hel.ink/..." required>
                    </div>
                    <div>
                        <label class="text-sm text-white/80">Email (required for contact)</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="mt-1 w-full rounded-2xl border border-white/20 bg-white/10 px-4 py-3 text-white placeholder-white/40 focus:border-blue-500 focus:ring-blue-500" placeholder="your@email.com" required>
                    </div>
                    <div>
                        <label class="text-sm text-white/80">Reason</label>
                        <textarea name="reason" rows="4" class="mt-1 w-full rounded-2xl border border-white/20 bg-white/10 px-4 py-3 text-white placeholder-white/40 focus:border-blue-500 focus:ring-blue-500" placeholder="Describe why this link is harmful." required>{{ old('reason') }}</textarea>
                    </div>
                    <div class="cf-turnstile" data-sitekey="{{ config('services.turnstile.site_key') }}"></div>
                    <div class="flex items-center justify-between text-xs text-white/60">
                        <span>Limit: 5 reports per 10 minutes per IP to avoid spam.</span>
                        <a href="{{ url('/') }}" class="text-blue-300 hover:underline">Back home</a>
                    </div>
                    <button type="submit" class="w-full rounded-2xl bg-blue-500 py-3 text-lg font-semibold text-white shadow-lg shadow-blue-500/40 hover:bg-blue-400">Submit report</button>
                </form>
            </div>
        </div>
    </body>
</html>
