<?php

namespace App\Providers;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{

    public function boot(): void
    {
        // Memaksa pagination menggunakan style Bootstrap agar lebih ringkas
        Paginator::useBootstrapFive();

        if(config('app.env') !== 'local') {
            URL::forceScheme('https');
        }
    }
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
}
