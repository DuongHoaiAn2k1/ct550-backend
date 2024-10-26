<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Review\ReviewController;

Route::middleware(['auth', 'role:normal_user|loyal_customer|admin|staff'])->prefix('/review')->group(function () {
    Route::get('/', [ReviewController::class, 'getAll']);
    Route::get('/{id}', [ReviewController::class, 'getByProductId']);
    Route::post('/', [ReviewController::class, 'create'])->middleware('permission:review products');
    Route::post('/check/{id}', [ReviewController::class, 'userHasReviewedProduct']);
    Route::delete('/{id}', [ReviewController::class, 'delete']);
});
