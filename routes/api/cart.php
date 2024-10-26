<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Cart\CartController;

Route::middleware(['auth', 'role:normal_user|loyal_customer'])->prefix('/cart')->group(function () {
    Route::get('/', [CartController::class, 'get']);
    Route::get('/user/count', [CartController::class, 'count']);
    Route::post('/', [CartController::class, 'create'])->middleware('permission:add to cart');
    Route::patch('/decrease/{id}', [CartController::class, 'decrease'])->middleware('permission:decrease cart');
    Route::patch('/increase/{id}', [CartController::class, 'increase'])->middleware('permission:increase cart');
    Route::delete('/{id}', [CartController::class, 'delete'])->middleware('permission:delete cart');
    Route::delete('/user/all', [CartController::class, 'delete_by_user_id']);
});
