<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Order\OrderController;


Route::middleware(['auth', 'role:admin|staff|normal_user|loyal_customer'])->prefix('/order')->group(function () {
    Route::get('/{id}', [OrderController::class, 'get']);
    Route::get('/bill/{id}', [OrderController::class, 'getByBillId']);
    Route::get('/user/get',  [OrderController::class, 'get_by_user']);
    Route::get('/user/{id}', [OrderController::class, 'get_by_user_id']);
    Route::get('/get/all', [OrderController::class, 'getAll']);
    Route::get('/today/all', [OrderController::class, 'get_order_today']);
    Route::post('/bydate/all', [OrderController::class, 'get_orders_between_dates']);
    Route::post('/', [OrderController::class, 'create'])->middleware('permission:order');
    Route::post('/send-email/{order_id}/', [OrderController::class, 'sendOrderConfirmationEmail']);
    Route::delete('/{id}', [OrderController::class, 'delete']);
    Route::get('/count/order', [OrderController::class, 'count']);
    Route::patch('/{id}', [OrderController::class, 'cancel'])->middleware('permission:cancel order');
    Route::patch('update/{id}', [OrderController::class, 'update_status']);
    Route::patch('bill/update/{id}', [OrderController::class, 'updateStatusByBillId']);
    Route::get('/get/order/user', [OrderController::class, 'list_user_order']);
    Route::post('/condition/list/order', [OrderController::class, 'getOrderByCondition']);
    Route::post('/condition/calculate/cost', [OrderController::class, 'calculateTotalCostAndShippingFee']);
});
