<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Notification\NotificationController;


Route::prefix('notification')->group(function () {
    Route::get('/', [NotificationController::class, 'getAll']);
    Route::get('/user', [NotificationController::class, 'getByUser']);
    Route::post('/', [NotificationController::class, 'create']);
    Route::delete('/{id}', [NotificationController::class, 'delete']);
    Route::get('/admin/{type}', [NotificationController::class, 'getByAdminType']);
    Route::post('/admin/read/all', [NotificationController::class, 'adminReadAll']);
    Route::post('/user/read/all', [NotificationController::class, 'userReadAll']);
});
