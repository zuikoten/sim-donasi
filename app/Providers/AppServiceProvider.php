<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use App\View\Composers\SettingsComposer;
use App\View\Composers\TeamTestiBankSocialComposer;
use App\Services\ContactFieldService;
use App\Services\SettingService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->singleton(ContactFieldService::class, function ($app) {
            return new ContactFieldService($app->make(SettingService::class));
        });
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
        View::composer(['admin.*', 'auth.*', 'components.*', 'donatur.*', 'profile.*', 'public.*', 'layouts.guest'], TeamTestiBankSocialComposer::class);
    }
}
