<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\SendBirthdayNotificationJob;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

//Schedule::job(new SendBirthdayNotificationJob())->dailyAt('18:49');
Schedule::command('app:check-birthdays')->dailyAt('00:01');
