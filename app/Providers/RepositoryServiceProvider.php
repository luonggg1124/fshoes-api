<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $repositories = [
            'category' => [\App\Repositories\Category\CategoryRepositoryInterface::class, \App\Repositories\Category\CategoryRepository::class],
            'cart'=>    [\App\Repositories\Cart\CartRepositoryInterface::class , \App\Repositories\Cart\CartRepository::class],
            'order'=>    [\App\Repositories\Order\OrderRepositoryInterface::class , \App\Repositories\Order\OrderRepository::class],
            'order-detail'=>    [\App\Repositories\OrderDetail\OrderDetailRepositoryInterface::class , \App\Repositories\OrderDetail\OrderDetailRepository::class]

        ];

        foreach ($repositories as $repository) {
            $this->app->bind($repository[0],$repository[1]);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
