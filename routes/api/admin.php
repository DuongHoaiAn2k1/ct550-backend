<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Admin\AdminController;

Route::prefix('/admin')->group(function () {
    Route::get('/profile/{id}', [AdminController::class, 'getProfile']);
    Route::post('/register', [AdminController::class, 'register']);
    Route::post('/create-staff', [AdminController::class, 'createStaff']);
    Route::get('/staff', [AdminController::class, 'getListStaff']);
    Route::delete('/staff/{id}', [AdminController::class, 'deleteStaff']);
});
