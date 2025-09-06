<?php

use Illuminate\Foundation\Application;
use App\Http\Middleware\User;
use App\Http\Middleware\Admin;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => Admin::class,
            'user' => User::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Handle unauthenticated user (session expired / not logged in)
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if (!$request->expectsJson()) {
                return redirect()->route('login')->with(
                    'error',
                    'Your session has expired, please log in again.'
                );
            }

            return response()->json([
                'message' => 'Unauthenticated.'
            ], 401);
        });
    })->create();
