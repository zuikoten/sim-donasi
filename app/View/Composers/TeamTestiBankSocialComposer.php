<?php

namespace App\View\Composers;

use App\Models\Team;
use App\Models\Testimonial;
use App\Models\BankAccount;
use App\Models\SocialMedia;
use Illuminate\View\View;

class TeamTestiBankSocialComposer
{
    public function compose(View $view)
    {
        // Hanya load data yang dibutuhkan oleh view spesifik
        if ($view->getName() === 'admin.settings._team') {
            $view->with('teams', Team::orderBy('order', 'asc')->get());
        }

        if ($view->getName() === 'admin.settings._testimonial') {
            $view->with('testimonials', Testimonial::orderBy('order', 'asc')->get());
        }

        if ($view->getName() === 'admin.settings._bank') {
            $view->with('bankAccounts', BankAccount::orderBy('id', 'desc')->get());
        }

        if ($view->getName() === 'admin.settings._social') {
            $view->with('socialMedia', SocialMedia::orderBy('order', 'asc')->get());
        }
    }
}
