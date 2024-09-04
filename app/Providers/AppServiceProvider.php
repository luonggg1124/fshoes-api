<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $services = [
            'category' => [
                \App\Services\Category\CategoryServiceInterface::class,
                \App\Services\Category\CategoryService::class
            ],
            'user' => [
                \App\Services\User\UserServiceInterface::class,
                \App\Services\User\UserService::class
            ]
        ];

        foreach ($services as $service) {
            $this->app->bind($service[0], $service[1]);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }
}
