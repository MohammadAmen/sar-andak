<?php

use App\Http\Middleware\EnsureApiUserActive;
use App\Http\Middleware\EnsureSuperAdminProviderScope;
use App\Http\Middleware\SuperAdminAuth;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'super-admin.auth' => SuperAdminAuth::class,
            'super-admin.provider-scope' => EnsureSuperAdminProviderScope::class,
            'api.user-active' => EnsureApiUserActive::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
