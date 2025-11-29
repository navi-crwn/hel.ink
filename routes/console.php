<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('clicks:purge')->daily();
Schedule::command('geolite:update')->weeklyOn(1, '02:00');
Schedule::command('queue:health')->everyFifteenMinutes();
Schedule::command('backup:snapshot')->dailyAt('03:00');
Schedule::job(new \App\Jobs\QueueHeartbeat)->everyTwoMinutes();
