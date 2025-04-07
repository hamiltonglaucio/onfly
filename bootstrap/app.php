<?php

use App\AuthService\Infrastructure\Middleware\ApiAuthenticate;
use App\AuthService\Infrastructure\Providers\AuthServiceProvider;
use App\TravelService\Infrastructure\Middleware\ForceJwtAuth;
use App\TravelService\Infrastructure\Providers\TravelServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: [
            __DIR__.'/../app/AuthService/UserInterface/Routes/api.php',
            __DIR__.'/../app/TravelService/UserInterface/Routes/api.php',
        ],
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auth.api' => ApiAuthenticate::class,
            'force.jwt' => ForceJwtAuth::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AuthenticationException $e, $request) {
            return response()->json([
                'message' => 'Not authenticated. Missing or invalid JWT token.'
            ], 401);
        });

        $exceptions->render(function (ValidationException $e, $request) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->validator->errors(),
            ], 422);
        });

        $exceptions->render(function (Throwable $e, $request) {});
    })
    ->withProviders([
        AuthServiceProvider::class,
        TravelServiceProvider::class,
    ])
    ->create();
