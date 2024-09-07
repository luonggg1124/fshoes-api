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
            'product' => [
                \App\Services\Product\ProductServiceInterface::class,
                \App\Services\Product\ProductService::class
            ],
            'user' => [
                \App\Services\User\UserServiceInterface::class,
                \App\Services\User\UserService::class
            ],
            'attribute' => [
                \App\Services\Attribute\AttributeServiceInterface::class,
                \App\Services\Attribute\AttributeService::class,
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
