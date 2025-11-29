<?php

namespace App\Console\Commands;

use App\Models\Link;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class SnapshotBackup extends Command
{
    protected $signature = 'backup:snapshot';

    protected $description = 'Create a lightweight JSON snapshot of critical tables.';

    public function handle(): int
    {
        $snapshot = [
            'generated_at' => now()->toDateTimeString(),
            'users' => User::select('id', 'name', 'email', 'role', 'status')->get(),
            'links' => Link::select('id', 'slug', 'target_url', 'status', 'user_id')->get(),
        ];

        Storage::disk('local')->put('backups/snapshot-'.now()->format('Ymd-His').'.json', json_encode($snapshot));

        $this->info('Backup snapshot stored in storage/app/backups.');
        return self::SUCCESS;
    }
}
