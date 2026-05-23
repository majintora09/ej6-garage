<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
            if (array_key_exists('currentCarProfile', $view->getData())) {
                return;
            }

            $currentCarProfile = null;

            if (auth()->check()) {
                try {
                    $currentCarProfile = auth()->user()->activeCar();
                } catch (\Throwable $e) {
                    $currentCarProfile = null;
                }
            }

            $view->with('currentCarProfile', $currentCarProfile);
        });
    }
}
