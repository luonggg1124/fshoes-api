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
            'category' => [
                \App\Repositories\Category\CategoryRepositoryInterface::class,
                \App\Repositories\Category\CategoryRepository::class
            ],
            'product' => [
                \App\Repositories\Product\ProductRepositoryInterface::class,
                \App\Repositories\Product\ProductRepository::class
            ]
        ];

        foreach ($repositories as $repository) {
            $this->app->bind($repository[0], $repository[1]);
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
