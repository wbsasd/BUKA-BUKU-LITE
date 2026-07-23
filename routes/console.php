<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('membership:sync-expiry')->dailyAt('00:10');
Schedule::command('borrowing:send-reminders')->dailyAt('08:00');
Schedule::command('membership:send-reminders')->dailyAt('08:10');
