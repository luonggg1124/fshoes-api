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
            ],
            'order' => [
                \App\Services\Order\OrderServiceInterface::class,
                \App\Services\Order\OrderService::class
            ],
            'order-detail' => [
                \App\Services\OrderDetail\OrderDetailServiceInterface::class,
                \App\Services\OrderDetail\OrderDetailService::class
            ],
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
