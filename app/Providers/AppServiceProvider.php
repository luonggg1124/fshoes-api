<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Review\ReviewRepositoryInterface;
use App\Repositories\Review\ReviewRepository;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

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
            'variation' => [
                \App\Services\Product\Variation\VariationServiceInterface::class,
                \App\Services\Product\Variation\VariationService::class
            ],
            'image' => [
                \App\Services\Image\ImageServiceInterface::class,
                \App\Services\Image\ImageService::class
            ]
            ,
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

            'attribute' => [
                \App\Services\Attribute\AttributeServiceInterface::class,
                \App\Services\Attribute\AttributeService::class,
            ],
            'attributeValue' => [
                \App\Services\Attribute\Value\AttributeValueServiceInterface::class,
                \App\Services\Attribute\Value\AttributeValueService::class
            ],
            'review' => [
                \App\Services\Review\ReviewServiceInterface::class,
                \App\Services\Review\ReviewService::class,
            ],
            'order-history'=>[
                \App\Services\OrderHistory\OrderHistoryServiceInterface::class,
                \App\Services\OrderHistory\OrderHistoryService::class
            ],
            'groups'=>[
                \App\Services\Groups\GroupsServiceInterface::class,
                \App\Services\Groups\GroupsService::class
            ],
            'topics'=>[
                \App\Services\Topic\TopicServiceInterface::class,
                \App\Services\Topic\TopicsService::class
            ],
            'posts'=>[
                \App\Services\Post\PostServiceInterface::class,
                \App\Services\Post\PostService::class
            ],
            'sales' => [
                \App\Services\Sale\SaleServiceInterface::class,
                \App\Services\Sale\SaleService::class
            ]
            ,
            'vouchers' => [
                \App\Services\Voucher\VoucherServiceInterface::class,
                \App\Services\Voucher\VoucherService::class
            ],
            'statistics' => [
                \App\Services\Statistics\StatisticsServiceInterface::class,
                \App\Services\Statistics\StatisticsService::class
            ]
        ];

        foreach ($services as $service) {
            $this->app->bind($service[0], $service[1]);
            $this->app->singleton(ReviewRepositoryInterface::class, ReviewRepository::class);
        }
    }


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
        $lang = Cache::get('language');
        if(!$lang || !in_array($lang, ['vi','en'])){
            $lang = 'vi';
        }
        App::setLocale($lang);
    }
}
