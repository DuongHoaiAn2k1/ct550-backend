<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Message\MessageController;


Route::middleware(['auth', 'role:normal_user|loyal_customer|admin|staff'])->prefix('messages')->group(function () {
    Route::get('/{id}', [MessageController::class, 'getUserMessages']);
    Route::get('/user/message', [MessageController::class, 'getMessage']);
    Route::get('/count/unread', [MessageController::class, 'countUnUserUnreadMessage']);
    Route::get('/admin/{id}', [MessageController::class, 'adminReadMessage']);
    Route::get('/all', [MessageController::class, 'getAllUserMessagesByRole']);
    Route::post('/', [MessageController::class, 'store'])->middleware('permission:message store');
    Route::delete('/{id}', [MessageController::class, 'destroy']);
    Route::get('/user/all', [MessageController::class, 'getUsersWithMessages']);
});
