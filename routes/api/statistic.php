<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Statistic\StatisticController;

Route::prefix('/statistic')->group(function () {
    Route::post('/filter-user', [StatisticController::class, 'filterUser']);
    Route::post('/statistic-product', [StatisticController::class, 'statisticProduct']);
    Route::post('/statistic-order', [StatisticController::class, 'statisticOrder']);
    Route::post('/monthly-revenue', [StatisticController::class, 'monthlyRevenue']);
    Route::post('/user-province', [StatisticController::class, 'statisticUserByProvince']);
    Route::post('/profit-by-month', [StatisticController::class, 'profitByMonth']);
    Route::post('/total-sale-by-month', [StatisticController::class, 'totalSalesByMonth']);
    Route::post('/total-sale-by-year', [StatisticController::class, 'totalSalesByYear']);
    Route::post('/total-profit-by-month', [StatisticController::class, 'totalProfitByMonth']);
    Route::post('/total-profit-by-year', [StatisticController::class, 'totalProfitByYear']);
});
