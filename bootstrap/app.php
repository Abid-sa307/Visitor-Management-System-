<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\CompanyMiddleware;
use App\Http\Middleware\CheckMasterPageAccess; // ✅ Add this import

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // 🔥 Register route middleware here
        $middleware->alias([
            'company' => CompanyMiddleware::class,
            'role' => RoleMiddleware::class,
            'master_page' => CheckMasterPageAccess::class, // ✅ Add this
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
