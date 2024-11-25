<?php

use Illuminate\Support\Facades\Route;



Route::get('v1/statistics/overall',[\App\Http\Controllers\Api\Statistics\StatisticsController::class,'index']);
