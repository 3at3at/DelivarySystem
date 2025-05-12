<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'adminpanel' => \App\Http\Middleware\AdminAuthMiddleware::class,
        'checkadmin' => \App\Http\Middleware\CheckAdminLoginMiddleware::class, 
    ]);
})


    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AuthenticationException $e, $request) {
            if ($request->is('driver/*')) {
                abort(401, 'Unauthorized access');
            }

            // Default behavior for non-driver routes
            return redirect()->guest(route('login'));
        });

    })
    ->create();
