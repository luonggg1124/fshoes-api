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
        $bindings = [
            // Repositories
            \App\Repositories\Products\ProductRepositoryInterface::class => \App\Repositories\Products\ProductRepository::class,
            \App\Repositories\ProductVariations\ProductVariationsRepositoryInterface::class => \App\Repositories\ProductVariations\ProductVariationsRepository::class,
            \App\Repositories\ProductImages\ProductImagesRepositoryInterface::class => \App\Repositories\ProductImages\ProductImagesRepositoryInterface::class,
            \App\Repositories\Attribute\AttributesRepositoryInterface::class => \App\Repositories\Attribute\AttributesRepository::class,
            \App\Repositories\AttributeValues\AttributeValuesRepositoryInterface::class => \App\Repositories\AttributeValues\AttributeValuesRepository::class,
            
            // Services
            \App\Services\Products\ProductServiceInterface::class => \App\Services\Products\ProductService::class,
            \App\Services\ProductVariations\ProductVariationsServiceInterface::class => \App\Services\ProductVariations\ProductVariationsService::class,
            \App\Services\ProductImages\ProductImagesServiceInterface::class => \App\Services\ProductImages\ProductImagesService::class,
            \App\Services\Attribute\AttributesServiceInterface::class => \App\Services\Attribute\AttributesService::class,
            \App\Services\AttributeValues\AttributeValuesServiceInterface::class=> \App\Services\AttributeValues\AttributeValuesService::class,

        ];

        //binding all
        foreach ($bindings as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
    }
}
