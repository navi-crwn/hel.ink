<?php

namespace App\Console\Commands;

use App\Models\LoginHistory;
use App\Services\GeoIpService;
use Illuminate\Console\Command;

class UpdateLoginHistoryIsp extends Command
{
    protected $signature = 'login-history:update-isp';

    protected $description = 'Update login histories with ISP data from GeoIP service';

    public function handle()
    {
        $histories = LoginHistory::where(function ($q) {
            $q->whereNull('isp')
                ->orWhere('isp', '')
                ->orWhere('isp', 'Unknown');
        })->get();
        $this->info("Found {$histories->count()} login histories to update...\n");
        $geoService = app(GeoIpService::class);
        $updated = 0;
        $failed = 0;
        foreach ($histories as $history) {
            if (! $history->ip_address) {
                continue;
            }
            try {
                $this->line("Processing: {$history->ip_address}");
                $isp = $geoService->provider($history->ip_address);
                $details = $geoService->details($history->ip_address);
                if (empty($isp)) {
                    $isp = $details['isp'] ?? $details['org'] ?? $details['organization'] ?? null;
                }
                if (empty($isp) && ! empty($details['as'])) {
                    $isp = preg_replace('/^AS\d+\s+/', '', $details['as']);
                }
                if (empty($isp)) {
                    $ip = $history->ip_address;
                    if (preg_match('/^(162\.158\.|172\.(6[4-9]|7[0-1])\.|104\.1[6-9]\.|104\.2[0-4]\.)/', $ip)) {
                        $isp = 'Cloudflare, Inc.';
                    } elseif (preg_match('/^(34\.|35\.)/', $ip)) {
                        $isp = 'Google LLC';
                    } elseif (preg_match('/^(54\.|52\.)/', $ip)) {
                        $isp = 'Amazon.com, Inc.';
                    }
                }
                $history->update([
                    'country_name' => $details['country_name'] ?? $history->country_name,
                    'region' => $details['region'] ?? $history->region,
                    'isp' => $isp ?? 'Unknown ISP',
                ]);
                $this->info('  ✓ Updated ISP: '.($isp ?? 'Unknown ISP'));
                $updated++;
                usleep(200000);
            } catch (\Exception $e) {
                $this->error("  ✗ Failed: {$e->getMessage()}");
                $failed++;
            }
        }
        $this->newLine();
        $this->info('✅ Update complete!');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Processed', $histories->count()],
                ['Successfully Updated', $updated],
                ['Failed', $failed],
            ]
        );

        return 0;
    }
}
