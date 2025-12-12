<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ theme: localStorage.getItem('theme') || 'light' }" x-bind:class="theme">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Link Preview - {{ config('app.name') }}</title>
    <link rel="icon" href="{{ route('brand.favicon') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta http-equiv="refresh" content="5;url={{ $targetUrl }}">
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900" 
      x-data="{ 
          countdown: 5,
          init() {
              const interval = setInterval(() => {
                  this.countdown--;
                  if (this.countdown <= 0) {
                      clearInterval(interval);
                      window.location.href = '{{ $targetUrl }}';
                  }
              }, 1000);
          }
      }">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-2xl w-full">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 md:p-12">
                <div class="text-center mb-8">
                    <a href="{{ url('/') }}" class="inline-flex items-center justify-center mb-6">
                        <img x-show="theme === 'dark'" src="{{ asset('images/Logo.png') }}" alt="Logo" class="h-12">
                        <img x-show="theme === 'light'" src="{{ asset('images/Logo-dark.png') }}" alt="Logo" class="h-12">
                    </a>
                    <div class="mb-6">
                        <svg class="w-16 h-16 mx-auto text-blue-500 dark:text-blue-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                            Link Preview
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400">
                            You are about to be redirected to an external website
                        </p>
                    </div>
                </div>
                <div class="space-y-6">
                    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                        <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Destination URL:
                        </h2>
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                            </svg>
                            <a href="{{ $targetUrl }}" 
                               class="text-blue-600 dark:text-blue-400 hover:underline break-all text-sm"
                               target="_blank"
                               rel="noopener noreferrer">
                                {{ $targetUrl }}
                            </a>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-blue-100 dark:bg-blue-900/30 mb-4">
                            <span class="text-5xl font-bold text-blue-600 dark:text-blue-400" x-text="countdown"></span>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                            Redirecting in <span x-text="countdown"></span> seconds...
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3 pt-4">
                        <a href="{{ $targetUrl }}" 
                           class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                            Continue Now
                        </a>
                        <a href="{{ url('/') }}" 
                           class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-medium rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Go Back
                        </a>
                    </div>
                </div>
                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <div class="flex-1">
                                <h3 class="text-sm font-semibold text-yellow-800 dark:text-yellow-300 mb-1">
                                    Safety Warning
                                </h3>
                                <p class="text-xs text-yellow-700 dark:text-yellow-400">
                                    {{ config('app.name') }} is not responsible for the content of external websites. 
                                    Please ensure you trust the destination before proceeding.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-6 text-center">
                    <button @click="theme = (theme === 'dark' ? 'light' : 'dark'); localStorage.setItem('theme', theme)" 
                            class="inline-flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                        <svg x-show="theme === 'dark'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <svg x-show="theme === 'light'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                        <span>Toggle Theme</span>
                    </button>
                </div>
            </div>
            <div class="text-center mt-6">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
