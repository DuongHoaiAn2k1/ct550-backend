<?php

namespace App\Http\Controllers\API\Role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{
    public function index()
    {
        try {
            $roles = Role::with('permissions')->get();
            $permissions = Permission::all();

            return response()->json([
                'status' => 'Success',
                'roles' => $roles,
                'permissions' => $permissions,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getRoles()
    {
        try {
            $roles = Role::with('permissions')->get();
            return response()->json([
                'status' => "Success",
                'message' => "Lấy danh sách role thành công",
                'data' => $roles
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getPermissions()
    {
        try {
            $permissions = Permission::all();
            return response()->json([
                'status' => "Success",
                'message' => "Lấy danh sách quyền thành công",
                'data' => $permissions
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function createRole(Request $request)
    {
        try {
            $request->validate(['name' => 'required|unique:roles,name', 'description' => 'required']);

            $role = Role::create(['name' => $request->name, 'description' => $request->description]);

            return response()->json([
                'status' => "Success",
                'message' => 'Role created successfully.',
                'role' => $role
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function updateRole(Request $request, $roleId)
    {
        try {
            $role = Role::findById($roleId);

            $role->update(['name' => $request->name, 'description' => $request->description]);

            return response()->json([
                'status' => "Success",
                'message' => 'Role updated successfully.',
                'role' => $role
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function deleteRole($roleId)
    {
        try {
            $role = Role::findById($roleId);
            $role->delete();

            return response()->json([
                'status' => "Success",
                'message' => 'Role deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function createPermission(Request $request)
    {
        try {
            $request->validate(['name' => 'required|unique:permissions,name', 'description' => 'required']);
            $permission = Permission::create(['name' => $request->name, 'description' => $request->description]);

            return response()->json([
                'status' => "Success",
                'message' => 'Permission created successfully.',
                'permission' => $permission
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function deletePermission($permissionId)
    {
        try {
            $permission = Permission::findById($permissionId);
            $permission->delete();

            return response()->json([
                'status' => "Success",
                'message' => 'Permission deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function updatePermission(Request $request, $permissionId)
    {
        try {
            $permission = Permission::findById($permissionId);
            $permission->update(['name' => $request->name, 'description' => $request->description]);

            return response()->json([
                'status' => "Success",
                'message' => 'Permission updated successfully.',
                'permission' => $permission
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getPermissionsByRole($roleId)
    {
        try {
            $role = Role::findById($roleId);
            $permissions = $role->permissions;

            return response()->json([
                'status' => "Success",
                'message' => "Lấy danh sách quyền theo vai trò thành công",
                'data' => $permissions
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getPermissionsByRoleName($roleName)
    {
        try {
            $role = Role::where('name', $roleName)->first();

            if (!$role) {
                return response()->json(['message' => 'Vai trò không tồn tại.'], 404);
            }

            $permissions = $role->permissions;

            return response()->json([
                'status' => "Success",
                'message' => "Lấy danh sách quyền theo tên vai trò thành công",
                'data' => $permissions
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    public function updatePermissionsForRole(Request $request, $roleId)
    {
        try {
            $role = Role::findById($roleId);

            if (!$role) {
                return response()->json(['message' => 'Vai trò không tồn tại.'], 404);
            }

            $permissionIds = $request->permission_ids;

            $permissions = Permission::whereIn('id', $permissionIds)->get();

            $role->syncPermissions($permissions);

            return response()->json([
                'status' => "Success",
                'message' => 'Cập nhật danh sách quyền cho vai trò thành công.',
                'role' => $role,
                'permissions' => $role->permissions
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
