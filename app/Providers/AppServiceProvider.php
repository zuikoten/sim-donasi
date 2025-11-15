<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use App\View\Composers\SettingsComposer;
use App\Services\ContactService;
use App\Services\SettingService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Carbon::setLocale('id');
        Paginator::useBootstrap();
        //Paginator::defaultView('pagination::bootstrap-5');

        View::composer(['admin.*', 'auth.*', 'components.*', 'donatur.*', 'profile.*', 'public.*', 'layouts.guest'], SettingsComposer::class);
    }
}
