<?php


use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        using: function () {
            Route::middleware('web')->group(base_path('routes/web.php'));
            Route::middleware('api')->prefix('api')->group(base_path('routes/api.php'));
            Route::middleware('api')->prefix('api')->group(base_path('routes/api/v1.php'));
        },
//        web: __DIR__ . '/../routes/web.php',
//        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'is_admin' => App\Http\Middleware\IsAdmin::class,
            'customize_throttle' => App\Http\Middleware\CustomThrottleMiddleware::class,
            'user_banned' => App\Http\Middleware\UserBanned::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
