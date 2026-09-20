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
                    $whatsapp = SiteSetting::get('whatsapp_number', '+2348031234567');
                    $phone = SiteSetting::get('phone', '+234 803 123 4567');
                    $email = SiteSetting::get('contact_email', 'orders@africankitchen.test');
                    $address = SiteSetting::get('address', '14 Admiralty Way, Lekki Phase 1, Lagos, Nigeria');

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
