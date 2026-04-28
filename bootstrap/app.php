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
        
        // 1. MENDAFTARKAN ALIAS MIDDLEWARE (Milik Kamu Sebelumnya - Sangat Penting)
        // Ini agar Laravel tahu bahwa kata 'role' merujuk ke file RoleMiddleware Anda
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        // 2. KODE BARU: Membuka pintu khusus untuk Midtrans agar pesanan otomatis masuk ke Admin
        $middleware->validateCsrfTokens(except: [
            'midtrans-callback',
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();