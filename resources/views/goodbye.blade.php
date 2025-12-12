<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches) }" x-bind:class="{ 'dark': darkMode }" x-init="$watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light'))">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Deleted - HEL.ink</title>
    <link rel="icon" href="{{ route('brand.favicon') }}" type="image/png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak]{display:none !important;}</style>
</head>
<body class="bg-slate-50 dark:bg-slate-950">
    <div class="fixed top-4 right-4 z-50">
        <button @click="darkMode = !darkMode" class="rounded-full bg-white dark:bg-slate-800 p-3 shadow-lg hover:shadow-xl transition-all border border-slate-200 dark:border-slate-700">
            <svg x-show="!darkMode" class="h-5 w-5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
            </svg>
            <svg x-show="darkMode" x-cloak class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
        </button>
    </div>
    <div class="flex min-h-screen flex-col items-center justify-center px-4 py-8">
        <div class="w-full max-w-2xl space-y-8 text-center">
            <div class="flex flex-col items-center">
                <img x-show="!darkMode" src="{{ route('brand.logo.dark') }}" alt="HEL.ink" class="h-20 w-20 rounded-full shadow-lg">
                <img x-show="darkMode" x-cloak src="{{ route('brand.logo') }}" alt="HEL.ink" class="h-20 w-20 rounded-full shadow-lg">
                <p class="mt-3 text-sm font-semibold text-slate-700 dark:text-slate-300">HEL.ink</p>
            </div>
            <div class="space-y-4">
                <h1 class="text-4xl font-bold text-slate-900 dark:text-white">Your account has been deleted</h1>
                <p class="text-lg text-slate-600 dark:text-slate-300">We're sad to see you go, but we respect your decision.</p>
            </div>
            <div class="mx-auto max-w-xl space-y-4 rounded-2xl border border-rose-200 bg-rose-50 p-6 text-left dark:border-rose-900/30 dark:bg-rose-900/20">
                <div class="flex items-start gap-3">
                    <span class="text-2xl">⚠️</span>
                    <div class="space-y-2 text-sm text-rose-900 dark:text-rose-200">
                        <p class="font-semibold">Important: What happens next</p>
                        <ul class="list-disc space-y-1 pl-5">
                            <li>All your short links have been permanently deleted</li>
                            <li>Any existing links will now return a <strong>404 error</strong></li>
                            <li>Your account data, analytics, and settings are gone</li>
                            <li>This action cannot be undone</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="space-y-3 pt-4 text-sm text-slate-500 dark:text-slate-400">
                <p>If you deleted your account by mistake or changed your mind, you can create a new account anytime.</p>
                <p>However, your previous links and data cannot be recovered.</p>
            </div>
            <div class="flex flex-col items-center justify-center gap-3 pt-6 sm:flex-row">
                <a href="{{ route('home') }}" class="rounded-full border border-slate-200 px-6 py-3 font-semibold text-slate-700 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                    Back to Home
                </a>
                <a href="{{ route('register') }}" class="rounded-full bg-blue-600 px-6 py-3 font-semibold text-white hover:bg-blue-500">
                    Create New Account
                </a>
            </div>
            <div class="pt-8 text-xs text-slate-400">
                <p>Thank you for using HEL.ink. We hope to see you again someday.</p>
            </div>
        </div>
    </div>
</body>
</html>
