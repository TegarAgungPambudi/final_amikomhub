<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule: Release expired ticket reservations every minute
Schedule::command('reservations:release-expired')
    ->everyMinute()
    ->withoutOverlapping();
