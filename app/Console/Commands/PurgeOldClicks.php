<?php

namespace App\Console\Commands;

use App\Models\LinkClick;
use Illuminate\Console\Command;

class PurgeOldClicks extends Command
{
    protected $signature = 'clicks:purge';

    protected $description = 'Delete link click logs older than 30 days';

    public function handle(): int
    {
        $count = LinkClick::where('clicked_at', '<', now()->subDays(30))->delete();
        $this->info("Purged {$count} old click entries.");

        return self::SUCCESS;
    }
}
