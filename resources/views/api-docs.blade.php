<x-app-layout>
    <x-slot name="header">
        <x-page-header title="API Documentation" subtitle="Integrate hel.ink with ShareX, CLI tools, and custom apps">
            <x-slot name="actions">
                <a href="{{ route('settings') }}#api" class="rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                    Manage API Tokens
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-4xl space-y-6 sm:px-6 lg:px-8">
            
            <!-- Quick Start -->
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-3">🚀 Quick Start</h2>
                <ol class="space-y-3 text-sm text-slate-600 dark:text-slate-400">
                    <li><strong>1.</strong> Go to <a href="{{ route('settings') }}#api" class="text-blue-600 hover:underline dark:text-blue-400">Settings → API & Integrations</a></li>
                    <li><strong>2.</strong> Create a new API token with a memorable name</li>
                    <li><strong>3.</strong> Copy your token (you'll only see it once!)</li>
                    <li><strong>4.</strong> Use it in your requests with <code class="rounded bg-slate-100 px-2 py-0.5 font-mono text-xs dark:bg-slate-800">Authorization: Bearer YOUR_TOKEN</code></li>
                </ol>
            </div>

            <!-- ShareX Integration -->
            <div class="rounded-xl border border-blue-200 bg-blue-50 p-6 dark:border-blue-800 dark:bg-blue-900/20">
                <h2 class="text-xl font-bold text-blue-900 dark:text-blue-200 mb-3">📸 ShareX Integration</h2>
                <p class="text-sm text-blue-800 dark:text-blue-300 mb-4">
                    ShareX is a free screenshot and screen recording tool for Windows. Auto-upload and shorten your screenshots in one click!
                </p>
                <div class="space-y-3">
                    <div>
                        <h3 class="text-sm font-semibold text-blue-900 dark:text-blue-200 mb-2">Setup Steps:</h3>
                        <ol class="space-y-2 text-sm text-blue-800 dark:text-blue-300">
                            <li><strong>1.</strong> Create an API token in settings</li>
                            <li><strong>2.</strong> Download the ShareX config file below</li>
                            <li><strong>3.</strong> Open the .sxcu file with ShareX</li>
                            <li><strong>4.</strong> Replace <code class="rounded bg-blue-100 px-2 py-0.5 font-mono text-xs dark:bg-blue-950">YOUR_API_TOKEN_HERE</code> with your actual token</li>
                            <li><strong>5.</strong> Take a screenshot - it will auto-upload and return a short link!</li>
                        </ol>
                    </div>
                    <form method="POST" action="{{ route('api.sharex-config') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-500">
                            ⬇️ Download ShareX Config (.sxcu)
                        </button>
                    </form>
                </div>
            </div>

            <!-- API Endpoint -->
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-3">🔗 API Endpoint</h2>
                
                <div class="space-y-4">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-2">POST /api/shorten</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-3">Create a shortened URL</p>
                        
                        <div class="rounded-lg bg-slate-50 p-4 dark:bg-slate-800">
                            <p class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">Headers:</p>
                            <pre class="overflow-x-auto text-xs"><code>Authorization: Bearer YOUR_API_TOKEN
Content-Type: application/json</code></pre>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">Request Body:</p>
                        <div class="rounded-lg bg-slate-50 p-4 dark:bg-slate-800">
                            <pre class="overflow-x-auto text-xs"><code>{
  "url": "https://example.com/very/long/url",
  "alias": "my-custom-slug",
  "folder": "Marketing",
  "expires_at": "2025-12-31",
  "password": "secret123"
}</code></pre>
                        </div>
                        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                            <strong>Fields:</strong> url (required), alias (optional, min 3 chars), folder (optional), expires_at (optional), password (optional). Leave alias empty for random 6-character slug.
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">Success Response (201):</p>
                        <div class="rounded-lg bg-emerald-50 p-4 dark:bg-emerald-900/20">
                            <pre class="overflow-x-auto text-xs"><code>{
  "success": true,
  "data": {
    "url": "https://hel.ink/abc123",
    "slug": "abc123",
    "target_url": "https://example.com/very/long/url",
    "created_at": "2025-12-01T13:30:00Z",
    "expires_at": null
  },
  "message": "Link created successfully"
}</code></pre>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">Error Response (401/422/429):</p>
                        <div class="rounded-lg bg-red-50 p-4 dark:bg-red-900/20">
                            <pre class="overflow-x-auto text-xs"><code>{
  "success": false,
  "error": "Unauthorized",
  "message": "Invalid API token"
}</code></pre>
                        </div>
                    </div>
                </div>
            </div>

            <!-- cURL Example -->
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-3">💻 cURL Example</h2>
                <div class="rounded-lg bg-slate-50 p-4 dark:bg-slate-800">
                    <pre class="overflow-x-auto text-xs"><code>curl -X POST {{ url('/api/shorten') }} \
  -H "Authorization: Bearer YOUR_API_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "url": "https://example.com/page",
    "alias": "mylink"
  }'</code></pre>
                </div>
            </div>

            <!-- Rate Limiting -->
            <div class="rounded-xl border border-amber-200 bg-amber-50 p-6 dark:border-amber-800 dark:bg-amber-900/20">
                <h2 class="text-xl font-bold text-amber-900 dark:text-amber-200 mb-3">⏱️ Rate Limiting</h2>
                <ul class="space-y-2 text-sm text-amber-800 dark:text-amber-300">
                    <li><strong>Default limit:</strong> 100 requests per hour per token</li>
                    <li><strong>Reset:</strong> Limit resets every hour</li>
                    <li><strong>Response code:</strong> 429 (Too Many Requests) when exceeded</li>
                    <li><strong>Tip:</strong> Create multiple tokens for different apps if needed</li>
                </ul>
            </div>

            <!-- Other Tools -->
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-3">🛠️ Compatible Tools</h2>
                <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">
                    The same API works with these screenshot and automation tools:
                </p>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div class="rounded-lg border border-slate-200 p-3 dark:border-slate-700">
                        <strong class="text-slate-900 dark:text-white">Flameshot</strong>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Linux screenshot tool</p>
                    </div>
                    <div class="rounded-lg border border-slate-200 p-3 dark:border-slate-700">
                        <strong class="text-slate-900 dark:text-white">Greenshot</strong>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Windows screenshot tool</p>
                    </div>
                    <div class="rounded-lg border border-slate-200 p-3 dark:border-slate-700">
                        <strong class="text-slate-900 dark:text-white">Alfred Workflow</strong>
                        <p class="text-xs text-slate-500 dark:text-slate-400">macOS productivity app</p>
                    </div>
                    <div class="rounded-lg border border-slate-200 p-3 dark:border-slate-700">
                        <strong class="text-slate-900 dark:text-white">Custom Scripts</strong>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Python, Node.js, etc.</p>
                    </div>
                </div>
            </div>

            <!-- Support -->
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-6 dark:border-slate-700 dark:bg-slate-800/50">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-3">💬 Need Help?</h2>
                <p class="text-sm text-slate-600 dark:text-slate-400 mb-3">
                    Having trouble with the API? We're here to help!
                </p>
                <div class="flex gap-3">
                    <a href="mailto:support@hel.ink" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-white dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-700">
                        📧 Email Support
                    </a>
                    <a href="https://github.com/navi-crwn/hel.ink/issues" target="_blank" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-white dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-700">
                        🐛 Report Issue
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
