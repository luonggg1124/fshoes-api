<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Statistics\StatisticsController;
use App\Http\Controllers\TestController;

Route::group(['middleware' => ['auth:api','is_admin','user_banned']], function () {
    Route::get('v1/statistics/overall', [StatisticsController::class, 'index']);
    Route::get('v1/statistics/data/orders/diagram', [StatisticsController::class, 'forDiagram']);
    Route::get('v1/statistics/product/bestselling', [StatisticsController::class, 'bestSellingProduct']);
    Route::get('v1/statistics/revenue/year',[StatisticsController::class, 'revenueOfYear']);
    Route::get('my/voucher',[App\Http\Controllers\Api\VouchersController::class,'myVoucher']);
    Route::get('v1/statistics/count/order/waitings',[StatisticsController::class, 'countWaitingConfirmOrders']);
});

Route::get('change/language',[TestController::class,'changeLanguage']);