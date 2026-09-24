<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        View::composer('*', function ($view) {
            try {
                if (Schema::hasTable('site_settings')) {
                    $siteName = SiteSetting::getSiteName();
                    $whatsapp = SiteSetting::get('whatsapp_number', '+447988575682');
                    $phone = SiteSetting::get('phone', '07988575682 / 07508282876');
                    $email = SiteSetting::get('contact_email', 'gracekitchenltd@gmail.com');
                    $address = SiteSetting::get('address', 'Unit 15 Kencot Close, Business Park Kencot Way, DA18 4AB, London, UK');

                    $view->with([
                        'siteName' => $siteName,
                        'whatsappNumber' => $whatsapp,
                        'contactPhone' => $phone,
                        'contactEmail' => $email,
                        'businessAddress' => $address,
                    ]);
                }
            } catch (\Throwable) {
                // Graceful fallback if database is not yet migrated
            }
        });
    }
}
