<?php

namespace App\Providers;

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
        try {
            // Auto create sqlite file if sqlite driver is used
            if (config('database.default') === 'sqlite') {
                $dbPath = config('database.connections.sqlite.database');
                if ($dbPath && $dbPath !== ':memory:' && !file_exists($dbPath)) {
                    @mkdir(dirname($dbPath), 0755, true);
                    @touch($dbPath);
                }
            }

            // Auto migrate and seed if database is empty or users table missing
            if (!\Schema::hasTable('users') || \App\Models\User::count() === 0) {
                \Artisan::call('migrate:fresh', [
                    '--seed'  => true,
                    '--force' => true,
                ]);
            }
        } catch (\Throwable $e) {
            // Safe fallback
        }
    }
}
