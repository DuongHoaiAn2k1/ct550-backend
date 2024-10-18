<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Promotion\ProductPromotionController;


Route::middleware(['auth', 'role:admin|staff'])->prefix('product-promotions')->group(function () {
    Route::get('/', [ProductPromotionController::class, 'index']);
    Route::post('/', [ProductPromotionController::class, 'store']);
    Route::patch('/{id}', [ProductPromotionController::class, 'update']);
    Route::delete('/{id}', [ProductPromotionController::class, 'destroy']);
    Route::get('/promotion/{id}', [ProductPromotionController::class, 'getByPromotion']);
});
