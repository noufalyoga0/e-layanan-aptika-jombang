<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\URL;

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
        // Hanya paksa HTTPS jika di luar environment local (seperti di server hosting)
        if (config('app.env') !== 'local') {
            URL::forceScheme('https');

            // Otomatis redirect HTTP ke HTTPS jika diakses via HTTP di server hosting
            if (request()->header('x-forwarded-proto') === 'http') {
                header('Location: https://' . request()->getHttpHost() . request()->getRequestUri(), true, 301);
                exit();
            }
        }
    }
}
