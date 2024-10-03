<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Order\OrderDetailController;

Route::prefix('/orderDetail')->group(function () {
    Route::get('/{id}', [OrderDetailController::class, 'get']);
    Route::post('/', [OrderDetailController::class, 'create']);
    Route::get('/statistics/sales', [OrderDetailController::class, 'sales_statistics']);
    Route::post('/check/user/{id}', [OrderDetailController::class, 'checkUserPurchasedProduct']);
    Route::post('/order/{id}', [OrderDetailController::class, 'revertStock']);
});
