<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class QueueHealthCheck extends Command
{
    protected $signature = 'queue:health';

    protected $description = 'Checks the queue heartbeat and warns if stale.';

    public function handle(): int
    {
        $heartbeat = cache('queue:heartbeat');

        if (! $heartbeat) {
            $this->warn('Queue worker heartbeat stale or missing – check supervisor if this persists.');
            return self::SUCCESS;
        }

        $this->info('Queue heartbeat OK at '.$heartbeat);
        return self::SUCCESS;
    }
}
