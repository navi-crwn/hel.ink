<?php

namespace Database\Seeders;

use App\Models\Link;
use App\Models\LinkClick;
use App\Models\User;
use Illuminate\Database\Seeder;

class AnalyticsDummySeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        if (! $user) {
            $this->command->error('No user found! Please create a user first.');

            return;
        }
        $this->command->info('Creating dummy analytics data for: '.$user->name);
        $countries = [
            'US' => 'United States',
            'GB' => 'United Kingdom',
            'ID' => 'Indonesia',
            'SG' => 'Singapore',
            'JP' => 'Japan',
            'AU' => 'Australia',
            'DE' => 'Germany',
            'FR' => 'France',
            'CA' => 'Canada',
            'BR' => 'Brazil',
            'IN' => 'India',
            'NL' => 'Netherlands',
        ];
        $cities = [
            'US' => ['New York', 'Los Angeles', 'Chicago', 'Houston', 'Phoenix'],
            'GB' => ['London', 'Manchester', 'Birmingham', 'Leeds', 'Glasgow'],
            'ID' => ['Jakarta', 'Surabaya', 'Bandung', 'Medan', 'Semarang'],
            'SG' => ['Singapore', 'Jurong East', 'Woodlands', 'Tampines', 'Bedok'],
            'JP' => ['Tokyo', 'Osaka', 'Kyoto', 'Nagoya', 'Sapporo'],
            'AU' => ['Sydney', 'Melbourne', 'Brisbane', 'Perth', 'Adelaide'],
            'DE' => ['Berlin', 'Munich', 'Hamburg', 'Frankfurt', 'Cologne'],
            'FR' => ['Paris', 'Lyon', 'Marseille', 'Toulouse', 'Nice'],
            'CA' => ['Toronto', 'Vancouver', 'Montreal', 'Calgary', 'Ottawa'],
            'BR' => ['São Paulo', 'Rio de Janeiro', 'Brasília', 'Salvador', 'Fortaleza'],
            'IN' => ['Mumbai', 'Delhi', 'Bangalore', 'Hyderabad', 'Chennai'],
            'NL' => ['Amsterdam', 'Rotterdam', 'The Hague', 'Utrecht', 'Eindhoven'],
        ];
        $isps = [
            'Cloudflare, Inc.',
            'Google LLC',
            'Amazon.com',
            'Comcast Cable',
            'Telkom Indonesia',
            'Singtel',
            'NTT Communications',
            'Telstra',
            'AT&T Services',
            'Verizon',
        ];
        $userAgents = [
            'mobile' => [
                'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.0 Mobile/15E148 Safari/604.1',
                'Mozilla/5.0 (Linux; Android 11; SM-G991B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.120 Mobile Safari/537.36',
                'Mozilla/5.0 (iPad; CPU OS 14_7_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.1.2 Mobile/15E148 Safari/604.1',
            ],
            'desktop' => [
                'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:89.0) Gecko/20100101 Firefox/89.0',
                'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.1.1 Safari/605.1.15',
            ],
        ];
        $referers = [
            'https://google.com',
            'https://facebook.com',
            'https://twitter.com',
            'https://linkedin.com',
            '(direct)',
            'https://reddit.com',
        ];
        // Create 5 test links
        $this->command->info('Creating test links...');
        for ($i = 1; $i <= 5; $i++) {
            $link = Link::create([
                'user_id' => $user->id,
                'slug' => 'analytics-test-'.$i,
                'target_url' => 'https://example.com/test-page-'.$i,
                'status' => 'active',
                'redirect_type' => '302',
                'clicks' => 0,
            ]);
            $this->command->info("  ✓ Created link: {$link->slug}");
            // Create 15-40 clicks per link
            $clickCount = rand(15, 40);
            for ($j = 0; $j < $clickCount; $j++) {
                $countryCode = array_rand($countries);
                $countryName = $countries[$countryCode];
                $city = $cities[$countryCode][array_rand($cities[$countryCode])];
                $isMobile = rand(0, 1);
                $deviceType = $isMobile ? 'mobile' : 'desktop';
                LinkClick::create([
                    'link_id' => $link->id,
                    'ip_address' => rand(1, 255).'.'.rand(1, 255).'.'.rand(1, 255).'.'.rand(1, 255),
                    'country' => $countryCode,
                    'country_name' => $countryName,
                    'city' => $city,
                    'region' => $city.' Region',
                    'isp' => $isps[array_rand($isps)],
                    'is_proxy' => rand(0, 100) > 85 ? 1 : 0,
                    'proxy_type' => null,
                    'proxy_confidence' => null,
                    'user_agent' => $userAgents[$deviceType][array_rand($userAgents[$deviceType])],
                    'referer' => $referers[array_rand($referers)],
                    'clicked_at' => now()->subHours(rand(1, 72)),
                    'created_at' => now()->subHours(rand(1, 72)),
                    'updated_at' => now(),
                ]);
                $link->increment('clicks');
            }
            $this->command->info("    Added {$clickCount} clicks");
        }
        // Summary
        $totalLinks = Link::where('user_id', $user->id)->count();
        $totalClicks = LinkClick::whereHas('link', fn ($q) => $q->where('user_id', $user->id))->count();
        $uniqueCountries = LinkClick::whereHas('link', fn ($q) => $q->where('user_id', $user->id))
            ->distinct('country')
            ->count('country');
        $this->command->info("\n✅ Dummy data created successfully!");
        $this->command->table(
            ['Metric', 'Count'],
            [
                ['Total Links', $totalLinks],
                ['Total Clicks', $totalClicks],
                ['Unique Countries', $uniqueCountries],
            ]
        );
        $this->command->info("\nCountry distribution:");
        $countryStats = LinkClick::whereHas('link', fn ($q) => $q->where('user_id', $user->id))
            ->selectRaw('country, COUNT(*) as total')
            ->groupBy('country')
            ->orderByDesc('total')
            ->get();
        foreach ($countryStats as $stat) {
            $this->command->line("  {$stat->country}: {$stat->total} clicks");
        }
    }
}
