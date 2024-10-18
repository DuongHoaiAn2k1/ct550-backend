<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Admin\AdminController;

Route::prefix('/admin')->group(function () {
    Route::get('/profile/{id}', [AdminController::class, 'getProfile']);
    Route::post('/register', [AdminController::class, 'register']);
    Route::post('/create-staff', [AdminController::class, 'createStaff'])->middleware('permission:manage staff');
    Route::get('/staff', [AdminController::class, 'getListStaff'])->middleware('permission:manage staff');
    Route::delete('/staff/{id}', [AdminController::class, 'deleteStaff'])->middleware('permission:manage staff');
});
