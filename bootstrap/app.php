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
        // PERBAIKAN SINTAKS LARAVEL 11 YANG BENAR:
        // Kita gunakan closure function untuk mengunci redirect jika user sudah login (authenticated)
        $middleware->redirectUsersTo(function () {
            return '/admin/dashboard';
        });

        // Dan jika tamu (guest) mencoba masuk ke halaman terproteksi, otomatis dilempar ke login
        $middleware->redirectTo(function () {
            return '/admin/login';
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();