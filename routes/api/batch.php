<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Batch\BatchController;
use App\Http\Controllers\API\Batch\BatchPromotionController;


Route::prefix('batches')->group(function () {
    Route::get('/', [BatchController::class, 'index']);
    Route::get('/hidden', [BatchController::class, 'getHiddenList']);
    Route::get('/expiring-soon', [BatchController::class, 'getExpiringSoon']);
    Route::get('/expired', [BatchController::class, 'getExpired']);
    Route::get('/{batch_id}', [BatchController::class, 'show']);
    Route::post('/', [BatchController::class, 'create']);
    Route::patch('/{batch_id}', [BatchController::class, 'update']);
    Route::delete('/{batch_id}', [BatchController::class, 'destroy']);
    Route::post('/reduce/product', [BatchController::class, 'reduceStock']);
    Route::post('/check/product', [BatchController::class, 'checkStockAvailability']);
    Route::post('/check/product/stock/{product_id}', [BatchController::class, 'checkProductInStock']);
    Route::patch('/status/{batch_id}', [BatchController::class, 'updateStatus']);
    Route::get('/{batch_id}/detail', [BatchController::class, 'getBatchDetailsById']);


    Route::prefix('promotion')->group(function () {
        Route::get('/all', [BatchPromotionController::class, 'getAllPromotions']);
        Route::get('/{batch_id}', [BatchPromotionController::class, 'show']);
        Route::post('/create', [BatchPromotionController::class, 'store']);
        Route::patch('/{batch_promotion_id}', [BatchPromotionController::class, 'update']);
        Route::delete('/{batch_promotion_id}', [BatchPromotionController::class, 'destroy']);
    });
});
