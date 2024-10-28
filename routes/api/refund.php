<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Refund\RefundRequestController;


Route::prefix('refund')->group(function () {
    Route::get('/', [RefundRequestController::class, 'getAll']);
    Route::post('/', [RefundRequestController::class, 'create']);
    Route::patch('/{id}', [RefundRequestController::class, 'updateStatus']);
    Route::get('/today', [RefundRequestController::class, 'getToday']);
    Route::post('/bydate/all', [RefundRequestController::class, 'getRefundsBetweenDates']);
    Route::get('/get-by-user', [RefundRequestController::class, 'getByUser']);
});
