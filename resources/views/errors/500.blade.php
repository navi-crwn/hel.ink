<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ route('brand.favicon') }}" type="image/png" sizes="32x32">
    <link rel="apple-touch-icon" href="{{ route('brand.favicon') }}">
    <title>Something Went Wrong - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'media'
        }
    </script>
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; }
        .container-fallback { max-width: 42rem; margin: 0 auto; padding: 2rem; }
        .card-fallback { background: white; border-radius: 1rem; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); padding: 2rem; }
        .btn-fallback { display: inline-block; background: #3b82f6; color: white; padding: 0.75rem 1.5rem; border-radius: 0.5rem; text-decoration: none; }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-2xl w-full">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 md:p-12">
                <div class="flex justify-center mb-6">
                    <div class="rounded-full bg-red-100 dark:bg-red-900/30 p-4">
                        <svg class="h-16 w-16 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                </div>

                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-3">
                        Oops! Something Went Wrong
                    </h1>
                    <p class="text-lg text-gray-600 dark:text-gray-400 mb-2">
                        We're sorry, but we encountered an unexpected error.
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-500">
                        Our team has been notified and we're working on fixing this issue.
                    </p>
                </div>

                @if(config('app.debug'))
                <div class="mb-6 rounded-lg bg-gray-100 dark:bg-gray-700 p-4">
                    <p class="text-xs font-mono text-gray-700 dark:text-gray-300">
                        Error Code: {{ $exception->getCode() ?: 500 }}
                    </p>
                    <p class="text-xs font-mono text-gray-600 dark:text-gray-400 mt-1">
                        {{ $exception->getMessage() }}
                    </p>
                </div>
                @endif

                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ url('/') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-6 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Go to Homepage
                    </a>
                    <button onclick="window.history.back()" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-6 py-3 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Go Back
                    </button>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 text-center">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Need help? Contact us at 
                        <a href="mailto:{{ config('mail.from.address') }}" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">
                            {{ config('mail.from.address') }}
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
