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
            'cart'=>    [
                \App\Repositories\Cart\CartRepositoryInterface::class ,
                \App\Repositories\Cart\CartRepository::class
            ],
            'order'=>    [
                \App\Repositories\Order\OrderRepositoryInterface::class ,
                \App\Repositories\Order\OrderRepository::class
            ],
            'order-detail'=>    [
                \App\Repositories\OrderDetail\OrderDetailRepositoryInterface::class ,
                \App\Repositories\OrderDetail\OrderDetailRepository::class
            ],
            'category' => [
                \App\Repositories\Category\CategoryRepositoryInterface::class,
                \App\Repositories\Category\CategoryRepository::class
            ],
            'product' => [
                \App\Repositories\Product\ProductRepositoryInterface::class,
                \App\Repositories\Product\ProductRepository::class
            ],
            'variation' => [
                \App\Repositories\Product\Variation\VariationRepositoryInterface::class,
                \App\Repositories\Product\Variation\VariationRepository::class
            ],
            'image' => [
                \App\Repositories\Image\ImageRepositoryInterface::class,
                \App\Repositories\Image\ImageRepository::class
            ],
            'attribute' => [
                \App\Repositories\Attribute\AttributeRepositoryInterface::class,
                \App\Repositories\Attribute\AttributeRepository::class
            ],
            'attributeValue' => [
                \App\Repositories\Attribute\Value\AttributeValueRepositoryInterface::class,
                \App\Repositories\Attribute\Value\AttributeValueRepository::class
            ],
            'user' => [
                \App\Repositories\User\UserRepositoryInterface::class,
                \App\Repositories\User\UserRepository::class
            ],
            'review' => [
                \App\Repositories\Review\ReviewRepositoryInterface::class,
                \App\Repositories\Review\ReviewRepository::class
            ],
            'order-history'=>[
                \App\Repositories\OrderHistory\OrderHistoryRepositoryInterface::class,
                \App\Repositories\OrderHistory\OrderHistoryRepository::class
            ],
            'groups'=>[
                \App\Repositories\Groups\GroupsRepositoryInterface::class,
                \App\Repositories\Groups\GroupsRepository::class
            ],
            'topics'=>[
                \App\Repositories\Topic\TopicsRepositoryInterface::class,
                \App\Repositories\Topic\TopicsRepository::class
            ],
            'posts'=>[
                \App\Repositories\Post\PostRepositoryInterface::class,
                \App\Repositories\Post\PostRepository::class
            ],
            'sales'=>[
                \App\Repositories\Sale\SaleRepositoryInterface::class,
                \App\Repositories\Sale\SaleRepository::class
            ]
            ,
            'vouchers'=>[
                \App\Repositories\Voucher\VouchersRepositoryInterface::class,
                \App\Repositories\Voucher\VouchersRepository::class
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
