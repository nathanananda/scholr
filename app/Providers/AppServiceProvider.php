<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\View\Composers\NotificationViewComposer;

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
        // Atau kalau mau semua layout sekaligus
        View::composer([
            'penyalur.layout.layout',
            'penerima.layout.layout',
            'admin.layout.layout',
        ], NotificationViewComposer::class);
    }
}
