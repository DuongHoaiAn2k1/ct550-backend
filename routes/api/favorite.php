<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Favorite\FavoriteController;

Route::prefix('/favorite')->group(function () {
    Route::post('/', [FavoriteController::class, 'create']);
    Route::get('/', [FavoriteController::class, 'get_by_user']);
    Route::get('/all', [FavoriteController::class, 'get_all']);
    Route::delete('/{id}', [FavoriteController::class, 'delete']);
});
