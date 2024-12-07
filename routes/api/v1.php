<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Statistics\StatisticsController;





Route::group(['middleware' => ['auth:api']], function () {
    Route::get('v1/statistics/overall', [StatisticsController::class, 'index']);
    Route::get('v1/statistics/data/orders/diagram', [StatisticsController::class, 'forDiagram']);
    Route::get('v1/statistics/product/bestselling', [StatisticsController::class, 'bestSellingProduct']);
    Route::get('v1/statistics/revenue/year',[StatisticsController::class, 'revenueOfYear']);
    Route::get('my/voucher',[App\Http\Controllers\Api\VouchersController::class,'myVoucher']);
    
});

