<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">GeoIP Provider Monitor</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Multi-provider failover system with automatic quota management</p>
        </div>

        <div class="mb-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Test Lookup</h3>
                <form id="testForm" class="flex gap-4">
                    @csrf
                    <input 
                        type="text" 
                        id="testIp" 
                        value="8.8.8.8" 
                        placeholder="Enter IP address"
                        class="flex-1 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    >
                    <button 
                        type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        Test Lookup
                    </button>
                </form>
                <div id="testResult" class="mt-4 hidden">
                    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                        <pre id="resultData" class="text-xs text-gray-700 dark:text-gray-300 overflow-auto"></pre>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            @foreach($providers as $provider)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $provider['name'] }}
                            </h3>
                            <span class="px-2 py-1 text-xs rounded-full {{ $provider['available'] ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                {{ $provider['available'] ? 'Active' : 'Disabled' }}
                            </span>
                        </div>

                        <div class="space-y-3">
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-600 dark:text-gray-400">Daily Usage</span>
                                    <span class="text-gray-900 dark:text-white font-medium">
                                        {{ number_format($provider['daily_usage']) }} / {{ number_format($provider['daily_limit']) }}
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    @php
                                        $dailyPercent = $provider['daily_limit'] > 0 
                                            ? min(100, ($provider['daily_usage'] / $provider['daily_limit']) * 100) 
                                            : 0;
                                        $colorClass = $dailyPercent > 80 ? 'bg-red-500' : ($dailyPercent > 50 ? 'bg-yellow-500' : 'bg-green-500');
                                    @endphp
                                    <div class="{{ $colorClass }} h-2 rounded-full" style="width: {{ $dailyPercent }}%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-600 dark:text-gray-400">Monthly Usage</span>
                                    <span class="text-gray-900 dark:text-white font-medium">
                                        {{ number_format($provider['monthly_usage']) }} / {{ number_format($provider['monthly_limit']) }}
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    @php
                                        $monthlyPercent = $provider['monthly_limit'] > 0 
                                            ? min(100, ($provider['monthly_usage'] / $provider['monthly_limit']) * 100) 
                                            : 0;
                                        $colorClass = $monthlyPercent > 80 ? 'bg-red-500' : ($monthlyPercent > 50 ? 'bg-yellow-500' : 'bg-green-500');
                                    @endphp
                                    <div class="{{ $colorClass }} h-2 rounded-full" style="width: {{ $monthlyPercent }}%"></div>
                                </div>
                            </div>

                            <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400">
                                    <span>Priority</span>
                                    <span class="font-mono">{{ $provider['priority'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">How It Works</h3>
                <div class="prose dark:prose-invert max-w-none text-sm text-gray-600 dark:text-gray-400">
                    <ul class="space-y-2">
                        <li><strong>Automatic Failover:</strong> System tries providers in priority order (1 = highest)</li>
                        <li><strong>Quota Management:</strong> Tracks daily and monthly usage per provider</li>
                        <li><strong>Smart Caching:</strong> Each IP is cached for 24 hours to save quota</li>
                        <li><strong>Load Balancing:</strong> FreeIpApi handles most traffic (60 req/min = 86k/day)</li>
                        <li><strong>Fallback Chain:</strong> FreeIpApi → IpApi → IpApiCo → IpInfo → AbstractApi</li>
                        <li><strong>Auto Recovery:</strong> Quotas reset automatically (daily at midnight, monthly at month start)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('testForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const ip = document.getElementById('testIp').value;
            const resultDiv = document.getElementById('testResult');
            const resultData = document.getElementById('resultData');
            
            resultDiv.classList.remove('hidden');
            resultData.textContent = 'Loading...';
            
            try {
                const response = await fetch('{{ route("admin.geoip.test") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value
                    },
                    body: JSON.stringify({ ip })
                });
                
                const data = await response.json();
                resultData.textContent = JSON.stringify(data, null, 2);
            } catch (error) {
                resultData.textContent = 'Error: ' + error.message;
            }
        });
    </script>
</x-app-layout>
