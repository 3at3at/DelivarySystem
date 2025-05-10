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
    $middleware->alias([
        'adminpanel' => \App\Http\Middleware\AdminAuthMiddleware::class,
        'checkadmin' => \App\Http\Middleware\CheckAdminLoginMiddleware::class, // ✅ Correct
    ]);
})

    ->withExceptions(function (Exceptions $exceptions) {
        // Exception handling if needed
    })
    ->create();
