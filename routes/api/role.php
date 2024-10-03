<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Role\RolePermissionController;


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
