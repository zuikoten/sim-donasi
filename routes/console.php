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

// Schedule untuk recalculate dana program
// Validasi dan fix otomatis setiap hari jam 2 pagi
Schedule::command('program:recalculate-dana --fix')
    ->dailyAt('00:01')
    ->appendOutputTo(storage_path('logs/recalculate-dana.log'));
//php artisan program:recalculate-dana (untuk cek status akurasi)
//Test schedule (run sekali)
//php artisan schedule:run
//Test schedule specific command
//php artisan schedule:test


// Lihat semua scheduled tasks
//php artisan schedule:list
