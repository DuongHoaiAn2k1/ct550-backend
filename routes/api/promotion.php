<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Promotion\PromotionController;


Route::middleware(['auth', 'role:admin|staff'])->prefix('promotion')->group(function () {
    Route::get('/', [PromotionController::class, 'index']);
    Route::get('/detail/{id}', [PromotionController::class, 'getById']);
    Route::post('/', [PromotionController::class, 'store'])->middleware('permission:manage promotions');
    Route::post('/batch', [PromotionController::class, 'storeBatch']);
    Route::patch('/{id}', [PromotionController::class, 'update']);
    Route::delete('/{id}', [PromotionController::class, 'destroy']);
    Route::post('/bydate/all', [PromotionController::class, 'getPromotionByDate'])->middleware('permission:manage promotions');
    Route::post('/soft/delete/{id}', [PromotionController::class, 'softDelete'])->middleware('permission:manage promotions');
    Route::patch('/end/{id}', [PromotionController::class, 'endPromotion']);
});
