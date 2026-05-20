<?php

namespace App\Providers;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public function boot(): void
    {
        // Memaksa pagination menggunakan style Bootstrap agar lebih ringkas
        Paginator::useBootstrapFive();
    }
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
}
