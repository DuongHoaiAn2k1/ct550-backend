<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Affiliate\CommissionController;
use App\Http\Controllers\API\Affiliate\AffiliateLinkController;
use App\Http\Controllers\API\Affiliate\AffiliateSaleController;
use App\Http\Controllers\API\Affiliate\AffiliateWalletController;
use App\Http\Controllers\API\Affiliate\AffiliateRequestController;
use App\Http\Controllers\API\Affiliate\AffiliateWithdrawalController;

Route::middleware(['auth', 'role:normal_user|loyal_customer|admin|staff'])->prefix('affiliate')->group(function () {
    Route::prefix('/request')->group(function () {
        Route::post('/create', [AffiliateRequestController::class, 'create'])->middleware('permission:affiliate register');
        Route::post('/update', [AffiliateRequestController::class, 'update']);
        Route::get('/list', [AffiliateRequestController::class, 'getAll']);
        Route::patch('/approved/{affiliate_request_id}', [AffiliateRequestController::class, 'approved'])->middleware('permission:manage affiliate marketers');
        Route::patch('/rejected/{affiliate_request_id}', [AffiliateRequestController::class, 'rejected'])->middleware('permission:manage affiliate marketers');
        Route::get('/status/check', [AffiliateRequestController::class, 'checkStatusUser']);
    });

    Route::middleware(['auth', 'role:admin|affiliate_marketer'])->prefix('/commission')->group(function () {
        Route::get('/list', [CommissionController::class, 'index']);
        Route::post('/create', [CommissionController::class, 'create'])->middleware('permission:manage commission');
        Route::patch('/update/{commission_id}', [CommissionController::class, 'update'])->middleware('permission:manage commission');
        Route::delete('/delete/{commission_id}', [CommissionController::class, 'delete'])->middleware('permission:manage commission');
    });

    Route::prefix('/link')->group(function () {
        Route::post('/generate/{product_id}', [AffiliateLinkController::class, 'generateLink'])->middleware('permission:get affiliate link');
        Route::get('/get-by-user', [AffiliateLinkController::class, 'getByUser']);
    });

    Route::prefix('/sale')->group(function () {
        Route::get('/list', [AffiliateSaleController::class, 'index']);
        Route::post('/create', [AffiliateSaleController::class, 'store']);
        Route::get('/get-by-user', [AffiliateSaleController::class, 'getByUser']);
        Route::patch('/update/{order_id}', [AffiliateSaleController::class, 'changOrderStatus']);
    });

    Route::prefix('/wallet')->group(function () {
        Route::get('/get-balance', [AffiliateWalletController::class, 'getBalance']);
    });

    Route::prefix('/withdrawal')->group(function () {
        Route::post('/create', [AffiliateWithdrawalController::class, 'create'])->middleware('permission:create withdrawal request');
        Route::get('/get-by-user', [AffiliateWithdrawalController::class, 'getByUser']);
        Route::get('/get-all', [AffiliateWithdrawalController::class, 'getAll']);
        Route::patch('/update/{affiliate_withdrawal_id}', [AffiliateWithdrawalController::class, 'done'])->middleware('permission:manage affiliate marketers');
        Route::delete('/delete/{affiliate_withdrawal_id}', [AffiliateWithdrawalController::class, 'delete'])->middleware('permission:manage affiliate marketers');
    });
});
