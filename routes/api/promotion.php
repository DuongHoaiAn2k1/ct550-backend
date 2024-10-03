<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Promotion\PromotionController;


Route::prefix('promotion')->group(function () {
    Route::get('/', [PromotionController::class, 'index']);
    Route::get('/detail/{id}', [PromotionController::class, 'getById']);
    Route::post('/', [PromotionController::class, 'store']);
    Route::patch('/{id}', [PromotionController::class, 'update']);
    Route::delete('/{id}', [PromotionController::class, 'destroy']);
    Route::post('/bydate/all', [PromotionController::class, 'getPromotionByDate']);
    Route::post('/soft/delete/{id}', [PromotionController::class, 'softDelete']);
});
