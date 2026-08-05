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
        URL::forceScheme('https');

        // Otomatis redirect HTTP ke HTTPS jika diakses via HTTP di server Railway
        if (request()->header('x-forwarded-proto') === 'http') {
            header('Location: https://' . request()->getHttpHost() . request()->getRequestUri(), true, 301);
            exit();
        }
    }
}
