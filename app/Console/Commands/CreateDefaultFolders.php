<?php

namespace App\Console\Commands;

use App\Models\Folder;
use App\Models\User;
use Illuminate\Console\Command;

class CreateDefaultFolders extends Command
{
    protected $signature = 'folders:create-default';

    protected $description = 'Create default folder for all users who don\'t have one';

    public function handle()
    {
        $this->info('Creating default folders for users...');
        $users = User::all();
        $created = 0;
        foreach ($users as $user) {
            $hasDefaultFolder = Folder::where('user_id', $user->id)
                ->where('is_default', true)
                ->exists();
            if (! $hasDefaultFolder) {
                Folder::create([
                    'name' => 'Default',
                    'user_id' => $user->id,
                    'is_default' => true,
                ]);
                $created++;
                $this->info("Created default folder for user: {$user->email}");
            }
        }
        $this->info("Done! Created {$created} default folders.");

        return Command::SUCCESS;
    }
}
