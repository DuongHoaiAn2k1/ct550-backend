<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\User\UserController;


Route::prefix('/user')->group(function () {
    Route::post('/reset/password/user', [UserController::class, 'resetPassword']);
    Route::get('/list-user', [UserController::class, 'getListUser']);
    Route::get('/list-user/{role}', [UserController::class, 'getListUsersByRole']);
    Route::get('/', [UserController::class, 'getAll']);
    Route::get('/{id}', [UserController::class, 'index']);
    Route::post('/update', [UserController::class, 'update'])->middleware('permission:update profile');
    Route::patch('/password', [UserController::class, 'update_pass'])->middleware('permission:update profile');
    Route::delete('/{id}', [UserController::class, 'delete_user']);
    Route::post('/register', [UserController::class, 'register']);
    Route::patch('/address', [UserController::class, 'createAddress'])->middleware('permission:manage address');
    Route::delete('/address/{index}', [UserController::class, 'deleteAddress'])->middleware('permission:manage address');
    Route::patch('/point/increase', [UserController::class, 'incrementPoint']);
    Route::patch('/point/decrease', [UserController::class, 'decrementPoint']);
    Route::get('/point/get', [UserController::class, 'getCurrentPoint']);
    Route::patch('/point/restore', [UserController::class, 'restorePoint']);
    Route::get('/role/user/get-list', [UserController::class, 'getListUserWithRole']);
    Route::patch('/update/name-phone', [UserController::class, 'updateNameAndPhone']);
    Route::get('/affiliate/get', [UserController::class, 'getListAffiliate']);
});
