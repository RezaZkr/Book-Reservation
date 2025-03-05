<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectTo(
            guests: function (Request $request) {
                if ($request->isJson() || $request->ajax() || $request->wantsJson()) {
                    return route('api.v1.auth.login');
                }
                return route('welcome');
            },
            users: function (Request $request) {
                return route('api.v1.auth.me');
            },
        );

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
