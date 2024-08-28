<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Cart\CartController;
use App\Http\Controllers\API\User\UserController;
use App\Http\Controllers\API\Admin\AdminController;
use App\Http\Controllers\API\Batch\BatchController;
use App\Http\Controllers\API\Order\OrderController;
use App\Http\Controllers\API\Review\ReviewController;
use App\Http\Controllers\API\Product\ProductController;
use App\Http\Controllers\API\Category\CategoryController;
use App\Http\Controllers\API\Favorite\FavoriteController;
use App\Http\Controllers\API\Notification\NotificationController;
use App\Http\Controllers\API\Order\OrderDetailController;
use App\Http\Controllers\API\Product\ProductBatchController;
use App\Http\Controllers\API\Role\RolePermissionController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('adminLogin', [AuthController::class, 'adminLogin']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
    Route::post('check', [AuthController::class, 'checkRefreshTokenExpiration']);
    Route::get('google', [AuthController::class, 'redirectToGoogle']);
    Route::get('google/callback', [AuthController::class, 'handleGoogleCallback']);
    Route::get('facebook', [AuthController::class, 'redirectToFacebook']);
    Route::get('facebook/callback', [AuthController::class, 'handleFacebookCallback']);
});

Route::prefix('/user')->group(function () {
    Route::get('/list-user', [UserController::class, 'getListUser']);
    Route::get('list-user/{role}', [UserController::class, 'getListUsersByRole']);
    Route::get('/', [UserController::class, 'getAll']);
    Route::get('/{id}', [UserController::class, 'index']);
    Route::patch('/update', [UserController::class, 'update']);
    Route::patch('/password', [UserController::class, 'update_pass']);
    Route::delete('/{id}', [UserController::class, 'delete_user']);
    Route::post('/register', [UserController::class, 'register']);
    Route::patch('/address', [UserController::class, 'createAddress']);
    Route::delete('/address/{index}', [UserController::class, 'deleteAddress']);
    Route::patch('/point/increase', [UserController::class, 'incrementPoint']);
    Route::patch('/point/decrease', [UserController::class, 'decrementPoint']);
    Route::get('/point/get', [UserController::class, 'getCurrentPoint']);
    Route::patch('/point/restore', [UserController::class, 'restorePoint']);
    Route::post("/filter/users", [UserController::class, 'filter_users']);
});

Route::prefix('/admin')->group(function () {
    Route::post('/register', [AdminController::class, 'register']);
});

Route::prefix('/category')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::get('/{id}', [CategoryController::class, 'get']);
    Route::post('/', [CategoryController::class, 'create']);
    Route::patch('/{id}', [CategoryController::class, 'update']);
    Route::delete('/{id}', [CategoryController::class, 'delete']);
});

Route::prefix('/product')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/category/group', [ProductController::class, 'indexGroupedByCategory']);
    Route::post('/name', [ProductController::class, 'getProductByCategoryName']);
    Route::post('/get/name/list', [ProductController::class, 'getProductByName']);
    Route::get('/{id}', [ProductController::class, 'get']);
    Route::post('/decrease/product/quantity', [ProductController::class, 'decreaseProductQuantity']);
    Route::post('/increase/product/quantity', [ProductController::class, 'increaseProductQuantity']);
    Route::get('/category/{id}', [ProductController::class, 'getProductByCategoryId']);
    Route::post("/", [ProductController::class, 'create']);
    Route::post('/{id}', [ProductController::class, 'update']);
    Route::delete('/{id}', [ProductController::class, 'delete']);
    Route::get('/price/{id}', [ProductController::class, 'getPrice']);
    Route::post('/increase/view/{id}', [ProductController::class, 'increase_product_view_count']);
    Route::post('/condition/list/product', [ProductController::class, 'getProductByCondition']);
    Route::patch('/{id}', [ProductController::class, 'updateQuantity']);
    Route::get('/review/list', [ProductController::class, 'getProductsWithReviews']);

    Route::prefix('/batch')->group(function () {
        Route::get('/list', [ProductBatchController::class, 'index']);
    });
});

Route::prefix('/cart')->group(function () {
    // Route::get('/', [CartController::class, 'index']);
    Route::get('/', [CartController::class, 'get']);
    Route::get('/user/count', [CartController::class, 'count']);
    Route::post('/', [CartController::class, 'create']);
    // Route::patch('/{id}', [CartController::class, 'update']);
    Route::patch('/decrease/{id}', [CartController::class, 'decrease']);
    Route::patch('/increase/{id}', [CartController::class, 'increase']);
    Route::delete('/{id}', [CartController::class, 'delete']);
    Route::delete('/user/all', [CartController::class, 'delete_by_user_id']);
});

Route::prefix('/order')->group(function () {
    Route::get('/{id}', [OrderController::class, 'get']);
    Route::get('/user/get',  [OrderController::class, 'get_by_user']);
    Route::get('/user/{id}', [OrderController::class, 'get_by_user_id']);
    Route::get('/get/all', [OrderController::class, 'getAll']);
    Route::get('/today/all', [OrderController::class, 'get_order_today']);
    Route::post('/bydate/all', [OrderController::class, 'get_orders_between_dates']);
    Route::post('/', [OrderController::class, 'create']);
    Route::delete('/{id}', [OrderController::class, 'delete']);
    Route::get('/count/order', [OrderController::class, 'count']);
    Route::patch('/{id}', [OrderController::class, 'cancel']);
    Route::patch('update/{id}', [OrderController::class, 'update_status']);
    Route::get('/get/order/user', [OrderController::class, 'list_user_order']);
    Route::post('/condition/list/order', [OrderController::class, 'getOrderByCondition']);
    Route::post('/condition/calculate/cost', [OrderController::class, 'calculateTotalCostAndShippingFee']);
});

Route::prefix('/orderDetail')->group(function () {
    Route::get('/{id}', [OrderDetailController::class, 'get']);
    Route::post('/', [OrderDetailController::class, 'create']);
    Route::get('/statistics/sales', [OrderDetailController::class, 'sales_statistics']);
    Route::post('/check/user/{id}', [OrderDetailController::class, 'checkUserPurchasedProduct']);
    Route::post('/order/{id}', [OrderDetailController::class, 'revertStock']);
});


Route::prefix('/favorite')->group(function () {
    Route::post('/', [FavoriteController::class, 'create']);
    Route::get('/', [FavoriteController::class, 'get_by_user']);
    Route::get('/all', [FavoriteController::class, 'get_all']);
    Route::delete('/{id}', [FavoriteController::class, 'delete']);
});

Route::prefix('/review')->group(function () {
    Route::get('/', [ReviewController::class, 'getAll']);
    Route::get('/{id}', [ReviewController::class, 'getByProductId']);
    Route::post('/', [ReviewController::class, 'create']);
    Route::post('/check/{id}', [ReviewController::class, 'userHasReviewedProduct']);
    Route::delete('/{id}', [ReviewController::class, 'delete']);
});

Route::middleware(['auth:api', 'role:admin|staff|normal_user'])->prefix('/assign-role')->group(function () {
    Route::get('/', [RolePermissionController::class, 'index']);
    Route::get('/roles', [RolePermissionController::class, 'getRoles']);
    Route::get('/permissions', [RolePermissionController::class, 'getPermissions']);
    Route::post('/roles', [RolePermissionController::class, 'createRole']);
    Route::patch('/roles/{role}', [RolePermissionController::class, 'updateRole']);
    Route::delete('/roles/{role}', [RolePermissionController::class, 'deleteRole']);
    Route::post('/permissions', [RolePermissionController::class, 'createPermission']);
    Route::patch('/permissions/{permission}', [RolePermissionController::class, 'updatePermission']);
    Route::delete('/permissions/{permission}', [RolePermissionController::class, 'deletePermission']);
    Route::get('/roles/{role}/permissions', [RolePermissionController::class, 'getPermissionsByRole']);
    Route::get('roles/name/{name}/permissions', [RolePermissionController::class, 'getPermissionsByRoleName']);
    Route::patch('/roles/{role}/permissions', [RolePermissionController::class, 'updatePermissionsForRole']);
});

Route::prefix('batches')->group(function () {
    Route::get('/', [BatchController::class, 'index']);
    Route::get('/{batch_id}', [BatchController::class, 'show']);
    Route::post('/', [BatchController::class, 'create']);
    Route::patch('/{batch_id}', [BatchController::class, 'update']);
    Route::delete('/{batch_id}', [BatchController::class, 'destroy']);
    Route::post('/reduce/product', [BatchController::class, 'reduceStock']);
});

Route::prefix('notification')->group(function () {
    Route::get('/', [NotificationController::class, 'getAll']);
    Route::get('/user', [NotificationController::class, 'getByUser']);
    Route::post('/', [NotificationController::class, 'create']);
    Route::delete('/{id}', [NotificationController::class, 'delete']);
    Route::get('/admin/{type}', [NotificationController::class, 'getByAdminType']);
    Route::post('/admin/read/all', [NotificationController::class, 'adminReadAll']);
});

// Route::middleware(['auth:api', 'role:admin|staff|normal_user'])->prefix('/assign-role')->group(function () {
//     Route::get('/', [RolePermissionController::class, 'index']);
//     Route::get('/roles', [RolePermissionController::class, 'getRoles']);
//     Route::get('/permissions', [RolePermissionController::class, 'getPermissions']);
//     Route::post('/roles', [RolePermissionController::class, 'createRole']);
//     Route::patch('/roles/{role}', [RolePermissionController::class, 'updateRole']);
//     Route::delete('/roles/{role}', [RolePermissionController::class, 'deleteRole']);
//     Route::post('/permissions', [RolePermissionController::class, 'createPermission']);
//     Route::patch('/permissions/{permission}', [RolePermissionController::class, 'updatePermission']);
//     Route::delete('/permissions/{permission}', [RolePermissionController::class, 'deletePermission']);
//     Route::get('/roles/{role}/permissions', [RolePermissionController::class, 'getPermissionsByRole']);
//     Route::get('roles/name/{name}/permissions', [RolePermissionController::class, 'getPermissionsByRoleName']);
//     Route::patch('/roles/{role}/permissions', [RolePermissionController::class, 'updatePermissionsForRole']);
// });



//Example ::
// Route::middleware(['auth:sanctum', 'role:admin|staff'])->group(function () {

//     // Route mà cả admin và staff đều có thể truy cập, nhưng cần quyền view dashboard
//     Route::get('/dashboard', [DashboardController::class, 'index'])
//         ->middleware('permission:view dashboard');

//     // Route này yêu cầu quyền manage products
//     Route::post('/product', [ProductController::class, 'create'])
//         ->middleware('permission:manage products');

//     // Route này yêu cầu quyền delete orders
//     Route::delete('/order/{id}', [OrderController::class, 'delete'])
//         ->middleware('permission:delete orders');
// });
