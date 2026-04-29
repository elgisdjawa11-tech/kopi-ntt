<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // Tambahkan baris ini

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Tambahkan baris ini agar semua link otomatis jadi HTTPS di Railway
        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }
    }
}