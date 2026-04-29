<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // 1. MENDAFTARKAN ALIAS MIDDLEWARE
        // Agar kamu bisa menggunakan middleware 'role:admin' di file routes/web.php
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        // 2. MENGECUALIKAN CSRF (PENTING UNTUK MIDTRANS)
        // Midtrans mengirim data POST dari server mereka, jadi tidak punya token CSRF Laravel.
        // Kita harus membuka 'pintu khusus' ini agar status pesanan bisa otomatis berubah di Admin.
        $middleware->validateCsrfTokens(except: [
            '/midtrans-callback',
            'midtrans-callback', // Ditulis dua versi (dengan/tanpa slash) agar lebih aman
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();