<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Product\ProductController;
use App\Http\Controllers\API\Product\ProductBatchController;

Route::prefix('/product')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    // Route::get('/category/group', [ProductController::class, 'indexGroupedByCategory']);
    Route::post('/name', [ProductController::class, 'getProductByCategoryName']);
    // Route::post('/get/name/list', [ProductController::class, 'getProductByName']);
    Route::get('/{id}', [ProductController::class, 'get']);
    Route::post('/decrease/product/quantity', [ProductController::class, 'decreaseProductQuantity']);
    Route::post('/increase/product/quantity', [ProductController::class, 'increaseProductQuantity']);
    Route::get('/category/{id}', [ProductController::class, 'getProductByCategoryId']);
    Route::post("/", [ProductController::class, 'create']);
    Route::post('/{id}', [ProductController::class, 'update']);
    Route::delete('/{id}', [ProductController::class, 'delete']);
    Route::get('/price/{id}', [ProductController::class, 'getPrice']);
    Route::post('/increase/view/{id}', [ProductController::class, 'increase_product_view_count']);
    Route::patch('/{id}', [ProductController::class, 'updateQuantity']);
    Route::get('/review/list', [ProductController::class, 'getProductsWithReviews']);
    Route::post('/search-ai/query', [ProductController::class, 'searchAI']);

    Route::prefix('/batch')->group(function () {
        Route::get('/list', [ProductBatchController::class, 'index']);
    });
});
