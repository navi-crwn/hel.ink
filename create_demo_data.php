<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Link;
use App\Models\LinkClick;
use App\Models\Folder;
use App\Models\Tag;
use Illuminate\Support\Facades\Hash;

$user = User::where('email', 'demo@hel.ink')->first();
$folders = $user->folders;
$tags = $user->tags;

// Create 10 links with various data
$linksData = [
    [
        'slug' => 'summer-sale',
        'target_url' => 'https://example.com/summer-sale-2025',
        'folder_id' => $folders[0]->id,
        'tag_ids' => [$tags[1]->id],
        'clicks' => 850,
        'title' => 'Summer Sale Campaign 2025'
    ],
    [
        'slug' => 'product-launch',
        'target_url' => 'https://example.com/new-product-launch',
        'folder_id' => $folders[1]->id,
        'tag_ids' => [$tags[0]->id],
        'clicks' => 1250,
        'title' => 'New Product Launch Page'
    ],
    [
        'slug' => 'webinar',
        'target_url' => 'https://zoom.us/j/123456789',
        'folder_id' => $folders[1]->id,
        'tag_ids' => [$tags[2]->id],
        'clicks' => 320,
        'title' => 'Monthly Team Webinar',
        'password' => 'webinar2025'
    ],
    [
        'slug' => 'instagram',
        'target_url' => 'https://instagram.com/yourcompany',
        'folder_id' => $folders[2]->id,
        'tag_ids' => [],
        'clicks' => 450,
        'title' => 'Instagram Profile'
    ],
    [
        'slug' => 'promo-dec',
        'target_url' => 'https://example.com/december-promo',
        'folder_id' => $folders[0]->id,
        'tag_ids' => [$tags[1]->id],
        'clicks' => 95,
        'title' => 'December Special Promo',
        'expires_at' => now()->addDays(30)
    ],
    [
        'slug' => 'docs',
        'target_url' => 'https://docs.google.com/document/d/example',
        'folder_id' => $folders[1]->id,
        'tag_ids' => [$tags[2]->id],
        'clicks' => 180,
        'title' => 'Project Documentation'
    ],
    [
        'slug' => 'survey',
        'target_url' => 'https://forms.gle/abc123',
        'folder_id' => $folders[0]->id,
        'tag_ids' => [$tags[0]->id],
        'clicks' => 520,
        'title' => 'Customer Satisfaction Survey'
    ],
    [
        'slug' => 'youtube',
        'target_url' => 'https://youtube.com/@yourcompany',
        'folder_id' => $folders[2]->id,
        'tag_ids' => [],
        'clicks' => 280,
        'title' => 'YouTube Channel'
    ],
    [
        'slug' => 'event-2025',
        'target_url' => 'https://eventbrite.com/event/example',
        'folder_id' => $folders[0]->id,
        'tag_ids' => [$tags[0]->id, $tags[1]->id],
        'clicks' => 75,
        'title' => 'Annual Conference 2025'
    ],
    [
        'slug' => 'portfolio',
        'target_url' => 'https://behance.net/yourcompany',
        'folder_id' => $folders[1]->id,
        'tag_ids' => [$tags[2]->id],
        'clicks' => 210,
        'title' => 'Design Portfolio'
    ]
];

$countries = ['US', 'ID', 'GB', 'AU', 'SG', 'MY', 'JP', 'DE', 'FR', 'CA'];
$cities = ['Jakarta', 'New York', 'London', 'Sydney', 'Singapore', 'Kuala Lumpur', 'Tokyo', 'Berlin', 'Paris', 'Toronto'];
$browsers = ['Chrome', 'Firefox', 'Safari', 'Edge', 'Opera'];
$os = ['Windows', 'macOS', 'Linux', 'iOS', 'Android'];
$devices = ['Desktop', 'Mobile', 'Tablet'];
$referrers = ['https://google.com', 'https://facebook.com', 'https://twitter.com', 'https://instagram.com', 'Direct'];

foreach ($linksData as $linkData) {
    $link = Link::create([
        'user_id' => $user->id,
        'slug' => $linkData['slug'],
        'target_url' => $linkData['target_url'],
        'folder_id' => $linkData['folder_id'],
        'title' => $linkData['title'],
        'redirect_type' => '302',
        'status' => 'active',
        'password' => isset($linkData['password']) ? Hash::make($linkData['password']) : null,
        'expires_at' => $linkData['expires_at'] ?? null,
        'created_at' => now()->subDays(rand(5, 30))
    ]);
    
    // Attach tags
    if (!empty($linkData['tag_ids'])) {
        $link->tags()->attach($linkData['tag_ids']);
    }
    
    // Generate realistic click data over last 10 days
    $clickCount = $linkData['clicks'];
    $daysBack = 10; // Focus on last 10 days for performance chart
    
    // Distribute clicks across 10 days with realistic patterns
    $dailyDistribution = [];
    for ($day = 0; $day < $daysBack; $day++) {
        // More clicks on recent days, less on older days (realistic growth pattern)
        $weight = 1 + ($daysBack - $day) * 0.3; // Newer days get more weight
        $dailyDistribution[$day] = max(1, round($clickCount * $weight / array_sum(range(1, $daysBack + ($daysBack * 0.3)))));
    }
    
    // Normalize to match total clicks
    $totalDistributed = array_sum($dailyDistribution);
    $ratio = $clickCount / $totalDistributed;
    foreach ($dailyDistribution as $day => $count) {
        $dailyDistribution[$day] = round($count * $ratio);
    }
    
    // Create clicks distributed over 10 days
    foreach ($dailyDistribution as $daysAgo => $dailyClicks) {
        for ($i = 0; $i < $dailyClicks; $i++) {
            $countryIndex = array_rand($countries);
            LinkClick::create([
                'link_id' => $link->id,
                'ip_address' => rand(1, 255) . '.' . rand(1, 255) . '.' . rand(1, 255) . '.' . rand(1, 255),
                'user_agent' => $browsers[array_rand($browsers)] . ' on ' . $os[array_rand($os)],
                'referer' => $referrers[array_rand($referrers)],
                'country' => $countries[$countryIndex],
                'city' => $cities[$countryIndex],
                'device_type' => $devices[array_rand($devices)],
                'browser' => $browsers[array_rand($browsers)],
                'os' => $os[array_rand($os)],
                'isp' => rand(0, 10) > 7 ? 'PT Telekomunikasi Indonesia' : 'Unknown',
                'is_proxy' => rand(0, 100) > 95 ? 1 : 0,
                'created_at' => now()->subDays($daysAgo)->subHours(rand(0, 23))->subMinutes(rand(0, 59))
            ]);
        }
    }
    
    echo "Created link: {$link->slug} with {$clickCount} clicks\n";
}

echo "\n✅ Demo data created successfully!\n\n";
echo "📧 Email: demo@hel.ink\n";
echo "🔑 Password: Demo123456\n";
echo "📊 Total Links: 10\n";
echo "👥 Total Clicks: 3,230\n";
