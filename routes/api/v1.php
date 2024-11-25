<?php

use Illuminate\Support\Facades\Route;





Route::group(['middleware' => ['auth:api']], function () {
    Route::get('v1/statistics/overall', [\App\Http\Controllers\Api\Statistics\StatisticsController::class, 'index']);
    Route::get('v1/statistics/data/orders/diagram', [\App\Http\Controllers\Api\Statistics\StatisticsController::class, 'forDiagram']);
    Route::get('v1/statistics/product/bestselling', [\App\Http\Controllers\Api\Statistics\StatisticsController::class, 'bestSellingProduct']);
});


