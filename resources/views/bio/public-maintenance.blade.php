<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link in Bio - Under Maintenance | HEL.ink</title>
    <meta name="description" content="Link in Bio feature is currently under development">
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center p-6">
    <div class="w-full max-w-2xl">
        <div class="rounded-3xl bg-white p-8 text-center shadow-2xl">
            <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-yellow-100">
                <svg class="h-12 w-12 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h1 class="mt-6 text-3xl font-bold text-gray-900">Under Construction</h1>
            <p class="mt-4 text-lg text-gray-600">
                The Link in Bio feature is currently being built and tested.
            </p>
            <p class="mt-2 text-base text-gray-500">
                We're working hard to bring you a beautiful, feature-rich bio page experience.
            </p>
            <div class="mt-8 rounded-xl bg-blue-50 p-6">
                <h2 class="text-sm font-semibold uppercase tracking-wider text-blue-900">Coming Soon</h2>
                <div class="mt-4 grid gap-3 text-left sm:grid-cols-2">
                    <div class="flex items-start gap-2 text-sm text-blue-800">
                        <svg class="mt-0.5 h-5 w-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Unlimited links, no fees</span>
                    </div>
                    <div class="flex items-start gap-2 text-sm text-blue-800">
                        <svg class="mt-0.5 h-5 w-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>5 custom themes</span>
                    </div>
                    <div class="flex items-start gap-2 text-sm text-blue-800">
                        <svg class="mt-0.5 h-5 w-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Full analytics</span>
                    </div>
                    <div class="flex items-start gap-2 text-sm text-blue-800">
                        <svg class="mt-0.5 h-5 w-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>QR code generation</span>
                    </div>
                </div>
            </div>
            <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-center">
                <a href="/" class="inline-flex items-center justify-center rounded-full bg-blue-600 px-8 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition">
                    Go to Homepage
                </a>
                @auth
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center rounded-full border border-gray-300 bg-white px-8 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                        My Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-full border border-gray-300 bg-white px-8 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                        Create Account
                    </a>
                @endauth
            </div>
            <div class="mt-8 border-t border-gray-200 pt-6">
                <p class="text-xs text-gray-500">
                    <a href="https://hel.ink" class="font-medium text-blue-600 hover:text-blue-700">HEL.ink</a> - Modern URL Shortening Service
                </p>
            </div>
        </div>
    </div>
</body>
</html>
