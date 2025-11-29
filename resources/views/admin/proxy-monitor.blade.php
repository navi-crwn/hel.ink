<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-800 dark:text-slate-200">
            {{ __('Proxy Detection Monitor') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="rounded-lg bg-green-50 p-4 dark:bg-green-900/20">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="overflow-hidden bg-white shadow-sm dark:bg-slate-800 sm:rounded-lg">
                <div class="p-6 text-slate-900 dark:text-slate-100">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-lg font-medium">Test IP Address</h3>
                        <form action="{{ route('admin.proxy.reset-stats') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Reset all detector statistics?')" class="rounded-lg bg-red-600 px-4 py-2 text-sm text-white hover:bg-red-700">
                                Reset Stats
                            </button>
                        </form>
                    </div>
                    
                    <div x-data="proxyTester()">
                        <div class="flex gap-3">
                            <input x-model="testIp" type="text" placeholder="Enter IP address (e.g., 8.8.8.8)" class="flex-1 rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300" @keyup.enter="testProxy">
                            <button @click="testProxy" :disabled="loading" class="rounded-lg bg-blue-600 px-6 py-2 text-white hover:bg-blue-700 disabled:opacity-50">
                                <span x-show="!loading">Test</span>
                                <span x-show="loading">Testing...</span>
                            </button>
                        </div>

                        <div x-show="result" x-transition class="mt-4 rounded-lg border p-4" :class="result?.is_proxy ? 'border-red-200 bg-red-50 dark:bg-red-900/20' : 'border-green-200 bg-green-50 dark:bg-green-900/20'">
                            <div class="mb-3 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <svg x-show="result?.is_proxy" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    <svg x-show="!result?.is_proxy" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="font-semibold" x-text="result?.is_proxy ? 'Proxy/VPN Detected' : 'Clean IP'"></span>
                                </div>
                                <span class="text-sm text-slate-600 dark:text-slate-400" x-text="'IP: ' + testIp"></span>
                            </div>
                            
                            <div class="grid gap-3 sm:grid-cols-3">
                                <div>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Type</p>
                                    <p class="font-medium" x-text="result?.type || 'Unknown'"></p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Confidence</p>
                                    <p class="font-medium" x-text="result?.confidence + '%'"></p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Consensus</p>
                                    <p class="font-medium" x-text="result?.consensus_strength || 'N/A'"></p>
                                </div>
                            </div>

                            <div x-show="result?.detectors" class="mt-3 border-t pt-3">
                                <p class="mb-2 text-xs font-semibold text-slate-600 dark:text-slate-400">Individual Detector Results:</p>
                                <div class="grid gap-2 sm:grid-cols-2">
                                    <template x-for="(detection, name) in result?.detectors" :key="name">
                                        <div class="rounded border px-3 py-2 text-sm" :class="detection.is_proxy ? 'border-red-200 bg-red-50/50 dark:bg-red-900/10' : 'border-green-200 bg-green-50/50 dark:bg-green-900/10'">
                                            <div class="flex items-center justify-between">
                                                <span class="font-medium" x-text="name"></span>
                                                <span class="text-xs" :class="detection.is_proxy ? 'text-red-600' : 'text-green-600'" x-text="detection.is_proxy ? '⚠ Proxy' : '✓ Clean'"></span>
                                            </div>
                                            <p class="text-xs text-slate-500" x-text="'Confidence: ' + detection.confidence + '%'"></p>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div x-show="error" x-text="error" class="mt-4 rounded-lg border border-red-300 bg-red-50 p-3 text-sm text-red-700 dark:bg-red-900/20"></div>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($detectorStats as $name => $data)
                    <div class="overflow-hidden bg-white shadow-sm dark:bg-slate-800 sm:rounded-lg">
                        <div class="p-6">
                            <div class="mb-4 flex items-start justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $name }}</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Priority: {{ $data['priority'] }}</p>
                                </div>
                                @if($data['enabled'])
                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                        Active
                                    </span>
                                @else
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600 dark:bg-slate-700 dark:text-slate-300">
                                        Disabled
                                    </span>
                                @endif
                            </div>

                            <div class="mb-4 rounded-lg bg-slate-50 p-3 dark:bg-slate-900">
                                <p class="text-xs text-slate-500 dark:text-slate-400">Daily Quota</p>
                                <p class="text-lg font-bold text-slate-900 dark:text-slate-100">{{ $data['quota'] }}</p>
                            </div>

                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-slate-600 dark:text-slate-400">Total Checks</span>
                                    <span class="font-semibold text-slate-900 dark:text-slate-100">{{ number_format($data['stats']['total_checks']) }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-slate-600 dark:text-slate-400">Positive Detections</span>
                                    <span class="font-semibold text-red-600 dark:text-red-400">{{ number_format($data['stats']['positive_detections']) }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-slate-600 dark:text-slate-400">Detection Rate</span>
                                    <span class="font-semibold text-slate-900 dark:text-slate-100">{{ $data['detection_rate'] }}%</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-slate-600 dark:text-slate-400">Errors</span>
                                    <span class="font-semibold text-orange-600 dark:text-orange-400">{{ number_format($data['stats']['errors']) }}</span>
                                </div>
                                @if($data['stats']['last_check'])
                                    <div class="border-t pt-2 dark:border-slate-700">
                                        <p class="text-xs text-slate-500 dark:text-slate-400">Last Check</p>
                                        <p class="text-sm text-slate-700 dark:text-slate-300">{{ \Carbon\Carbon::parse($data['stats']['last_check'])->diffForHumans() }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="overflow-hidden bg-white shadow-sm dark:bg-slate-800 sm:rounded-lg">
                <div class="p-6 text-slate-900 dark:text-slate-100">
                    <h3 class="mb-4 text-lg font-medium">About Proxy Detection</h3>
                    <div class="prose prose-slate dark:prose-invert max-w-none">
                        <p class="text-sm text-slate-600 dark:text-slate-400">
                            The proxy detection system uses a voting consensus from multiple detection services. 
                            Each detector provides a confidence score, and the final result is determined by weighted voting:
                        </p>
                        <ul class="mt-3 space-y-1 text-sm text-slate-600 dark:text-slate-400">
                            <li><strong>ProxyCheck:</strong> Free tier with 1,000 requests/day</li>
                            <li><strong>IPHub:</strong> Free tier with 1,000 requests/day</li>
                            <li><strong>IPQualityScore:</strong> Free tier with 5,000 requests/month (166/day avg)</li>
                            <li><strong>VPNApi:</strong> Free tier with 1,000 requests/day</li>
                            <li><strong>ISP Pattern:</strong> Local detection, unlimited (fallback)</li>
                        </ul>
                        <p class="mt-3 text-sm text-slate-600 dark:text-slate-400">
                            <strong>Confidence Levels:</strong> High (>70%), Medium (40-70%), Low (<40%)
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function proxyTester() {
            return {
                testIp: '',
                loading: false,
                result: null,
                error: null,

                async testProxy() {
                    if (!this.testIp) {
                        this.error = 'Please enter an IP address';
                        return;
                    }

                    this.loading = true;
                    this.error = null;
                    this.result = null;

                    try {
                        const response = await fetch('{{ route("admin.proxy.test") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ ip: this.testIp })
                        });

                        const data = await response.json();
                        
                        if (data.success) {
                            this.result = data.result;
                        } else {
                            this.error = data.message || 'Test failed';
                        }
                    } catch (err) {
                        this.error = 'Network error: ' + err.message;
                    } finally {
                        this.loading = false;
                    }
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
