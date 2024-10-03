<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Cart\CartController;

Route::prefix('/cart')->group(function () {
    // Route::get('/', [CartController::class, 'index']);
    Route::get('/', [CartController::class, 'get']);
    Route::get('/user/count', [CartController::class, 'count']);
    Route::post('/', [CartController::class, 'create']);
    // Route::patch('/{id}', [CartController::class, 'update']);
    Route::patch('/decrease/{id}', [CartController::class, 'decrease']);
    Route::patch('/increase/{id}', [CartController::class, 'increase']);
    Route::delete('/{id}', [CartController::class, 'delete']);
    Route::delete('/user/all', [CartController::class, 'delete_by_user_id']);
});
