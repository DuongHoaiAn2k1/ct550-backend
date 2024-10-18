<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Favorite\FavoriteController;

Route::middleware(['auth', 'role:normal_user|loyal_user'])->prefix('/favorite')->group(function () {
    Route::post('/', [FavoriteController::class, 'create'])->middleware('permission:favorite product');
    Route::get('/', [FavoriteController::class, 'get_by_user']);
    Route::get('/all', [FavoriteController::class, 'get_all']);
    Route::delete('/{id}', [FavoriteController::class, 'delete'])->middleware('permission:favorite product');
});
