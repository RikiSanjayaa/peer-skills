<?php

use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\PreventAdminAccess;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => IsAdmin::class,
            'no-admin' => PreventAdminAccess::class,
        ]);
        //
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\CheckSuspension::class,
        ]);
        // ... alias admin yang kemarin ...
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
