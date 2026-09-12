<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('subscriptions:remind')->daily();
Schedule::command('scheduled-emails:send')->everyFiveMinutes();
Schedule::command('paypack:sync-pending')->everyMinute();
