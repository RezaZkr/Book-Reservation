<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectTo(
//            guests: function (Request $request) {
//                return route('api.v1.auth.login');
//            },
            users: function (Request $request) {
                return route('api.v1.auth.me');
            },
        );

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
