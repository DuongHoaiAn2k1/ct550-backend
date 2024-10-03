<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Payment\VNPayController;


Route::prefix('payment')->group(function () {
    Route::post('/vnpay-payment', [VNPayController::class, 'createPayment']);
    Route::post('/vnpay-return', [VNPayController::class, 'handleReturn']);
    Route::get('/vnpay-ipn', [VNPayController::class, 'handleIPN']);
});
