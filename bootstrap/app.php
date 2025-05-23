<?php

use App\Providers\AppServiceProvider;
use App\Providers\AuthServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up', // Rute health check bawaan
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Daftarkan alias untuk middleware
        $middleware->alias([
            'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
            'admin' => \App\Http\Middleware\CheckAdmin::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class, // Ditambahkan untuk RoleMiddleware
            'guest' => \Illuminate\Auth\Middleware\RedirectIfAuthenticated::class, // Ditambahkan untuk middleware guest
        ]);

        // Grup middleware untuk rute web
        $middleware->group('web', [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        // Opsional: Tambahkan grup middleware untuk API jika diperlukan
        $middleware->group('api', [
            \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);
    })
    ->withProviders([
        AppServiceProvider::class,
        AuthServiceProvider::class,
    ])
    ->withExceptions(function (Exceptions $exceptions) {
        // Tambahkan logika penanganan error untuk debugging
        $exceptions->reportable(function (Throwable $e) {
            \Log::error('Exception caught', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
        });
    })
    ->create();