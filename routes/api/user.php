<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\User\UserController;


Route::prefix('/user')->group(function () {
    Route::get('/list-user', [UserController::class, 'getListUser']);
    Route::get('list-user/{role}', [UserController::class, 'getListUsersByRole']);
    Route::get('/', [UserController::class, 'getAll']);
    Route::get('/{id}', [UserController::class, 'index']);
    Route::post('/update', [UserController::class, 'update']);
    Route::patch('/password', [UserController::class, 'update_pass']);
    Route::delete('/{id}', [UserController::class, 'delete_user']);
    Route::post('/register', [UserController::class, 'register']);
    Route::patch('/address', [UserController::class, 'createAddress']);
    Route::delete('/address/{index}', [UserController::class, 'deleteAddress']);
    Route::patch('/point/increase', [UserController::class, 'incrementPoint']);
    Route::patch('/point/decrease', [UserController::class, 'decrementPoint']);
    Route::get('/point/get', [UserController::class, 'getCurrentPoint']);
    Route::patch('/point/restore', [UserController::class, 'restorePoint']);
    Route::get('/role/user/get-list', [UserController::class, 'getListUserWithRole']);
    Route::patch('/update/name-phone', [UserController::class, 'updateNameAndPhone']);
    Route::get('/affiliate/get', [UserController::class, 'getListAffiliate']);
});
