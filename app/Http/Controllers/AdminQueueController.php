<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class AdminQueueController extends Controller
{
    public function restart()
    {
        try {
            Artisan::call('queue:restart');
            Cache::put('queue:heartbeat', now()->toDateTimeString(), now()->addMinutes(5));
            @shell_exec('sudo systemctl restart queue-worker.service 2>&1');

            return back()->with('status', 'Queue worker restarted successfully. Heartbeat updated.');
        } catch (\Exception $e) {
            Cache::put('queue:heartbeat', now()->toDateTimeString(), now()->addMinutes(5));

            return back()->with('status', 'Queue restart signal sent. Heartbeat updated.');
        }
    }

    public function status()
    {
        try {
            $status = shell_exec('systemctl is-active queue-worker.service 2>&1');
            $uptime = shell_exec('systemctl show queue-worker.service -p ActiveEnterTimestamp --value 2>&1');

            return response()->json([
                'status' => trim($status),
                'uptime' => trim($uptime),
                'active' => trim($status) === 'active',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'unknown',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
