<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Batch\BatchController;


Route::middleware(['auth', 'role:admin|staff|normal_user|loyal_user'])->prefix('batches')->group(function () {
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
});
