<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Batch\BatchController;


Route::prefix('batches')->group(function () {
    Route::get('/', [BatchController::class, 'index']);
    Route::get('/{batch_id}', [BatchController::class, 'show']);
    Route::post('/', [BatchController::class, 'create']);
    Route::patch('/{batch_id}', [BatchController::class, 'update']);
    Route::delete('/{batch_id}', [BatchController::class, 'destroy']);
    Route::post('/reduce/product', [BatchController::class, 'reduceStock']);
    Route::post('/check/product', [BatchController::class, 'checkStockAvailability']);
});
