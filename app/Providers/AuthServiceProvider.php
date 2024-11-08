<?php

namespace App\Providers;

use App\Policies\CategoryPolicy;
use App\Policies\GroupPolicy;
use App\Policies\ImagePolicy;
use App\Policies\OrderPolicy;
use App\Policies\ProductPolicy;
use App\Policies\ReviewPolicy;
use App\Policies\TopicPolicy;
use App\Policies\UserPolicy;
use App\Policies\VoucherPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //GROUP
        Gate::define('group.view' , [GroupPolicy::class , 'view']);
        Gate::define('group.create' , [GroupPolicy::class , 'create']);
        Gate::define('group.update' , [GroupPolicy::class , 'update']);
        Gate::define('group.delete' , [GroupPolicy::class , 'delete']);
        Gate::define('group.restore' , [GroupPolicy::class , 'restore']);
        Gate::define('group.forceDelete' , [GroupPolicy::class , 'forceDelete']);

        //VOUCHER
        Gate::define('voucher.view' , [VoucherPolicy::class , 'view']);
        Gate::define('voucher.create' , [VoucherPolicy::class , 'create']);
        Gate::define('voucher.update' , [VoucherPolicy::class , 'update']);
        Gate::define('voucher.delete' , [VoucherPolicy::class , 'delete']);
        Gate::define('voucher.restore' , [VoucherPolicy::class , 'restore']);
        Gate::define('voucher.forceDelete' , [VoucherPolicy::class , 'forceDelete']);

        //ORDER
        Gate::define('order.view' , [OrderPolicy::class , 'view']);
        Gate::define('order.detail' , [OrderPolicy::class , 'detail']);
        Gate::define('order.create' , [OrderPolicy::class , 'create']);
        Gate::define('order.update' , [OrderPolicy::class , 'update']);


        //TOPIC
        Gate::define('topic.view' , [TopicPolicy::class , 'view']);
        Gate::define('topic.create' , [TopicPolicy::class , 'create']);
        Gate::define('topic.update' , [TopicPolicy::class , 'update']);
        Gate::define('topic.delete' , [TopicPolicy::class , 'delete']);
        Gate::define('topic.restore' , [TopicPolicy::class , 'restore']);
        Gate::define('topic.forceDelete' , [TopicPolicy::class , 'forceDelete']);


        //POSTS
        Gate::define('post.view' , [TopicPolicy::class , 'view']);
        Gate::define('post.create' , [TopicPolicy::class , 'create']);
        Gate::define('post.update' , [TopicPolicy::class , 'update']);
        Gate::define('post.delete' , [TopicPolicy::class , 'delete']);
        Gate::define('post.restore' , [TopicPolicy::class , 'restore']);
        Gate::define('post.forceDelete' , [TopicPolicy::class , 'forceDelete']);


        //USER
        Gate::define('user.view' , [UserPolicy::class , 'view']);
        Gate::define('user.detail' , [UserPolicy::class , 'detail']);
        Gate::define('user.create' , [UserPolicy::class , 'create']);
        Gate::define('user.update' , [UserPolicy::class , 'update']);
        Gate::define('user.delete' , [UserPolicy::class , 'delete']);


        //CATEGORY
        Gate::define('category.view' , [CategoryPolicy::class , 'view']);
        Gate::define('category.create' , [CategoryPolicy::class , 'create']);
        Gate::define('category.update' , [CategoryPolicy::class , 'update']);
        Gate::define('category.delete' , [CategoryPolicy::class , 'delete']);
//        Gate::define('category.restore' , [CategoryPolicy::class , 'restore']);
        Gate::define('category.forceDelete' , [CategoryPolicy::class , 'forceDelete']);


        //REVIEW
        Gate::define('review.view' , [ReviewPolicy::class , 'view']);
        Gate::define('review.create' , [ReviewPolicy::class , 'create']);
        Gate::define('review.update' , [ReviewPolicy::class , 'update']);
        Gate::define('review.delete' , [ReviewPolicy::class , 'delete']);


        //IMAGE
        Gate::define('image.view' , [ImagePolicy::class , 'view']);
        Gate::define('image.create' , [ImagePolicy::class , 'create']);
        Gate::define('image.update' , [ImagePolicy::class , 'update']);
        Gate::define('image.delete' , [ImagePolicy::class , 'delete']);


        //PRODUCT
        Gate::define('product.view' , [ProductPolicy::class , 'view']);
        Gate::define('product.detail' , [ProductPolicy::class , 'detail']);
        Gate::define('product.create' , [ProductPolicy::class , 'create']);
        Gate::define('product.update' , [ProductPolicy::class , 'update']);
        Gate::define('product.delete' , [ProductPolicy::class , 'delete']);
        Gate::define('product.restore' , [ProductPolicy::class , 'restore']);
        Gate::define('product.forceDelete' , [ProductPolicy::class , 'forceDelete']);

    }
}
