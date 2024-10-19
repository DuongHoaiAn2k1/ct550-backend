<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Tạo các quyền
        Permission::create(['name' => 'add to cart']);
        Permission::create(['name' => 'order']);
        Permission::create(['name' => 'cancel order']);
        Permission::create(['name' => 'message store']);
        Permission::create(['name' => 'review products']);
        Permission::create(['name' => 'update profile']);
        Permission::create(['name' => 'affiliate register']);
        Permission::create(['name' => 'manage products']);
        Permission::create(['name' => 'manage orders']);
        Permission::create(['name' => 'view sales reports']);
        Permission::create(['name' => 'manage customers']);
        Permission::create(['name' => 'manage reviews']);
        Permission::create(['name' => 'manage promotions']);
        Permission::create(['name' => 'manage staff']);
        Permission::create(['name' => 'manage affiliate marketers']);
        Permission::create(['name' => 'increase cart']);
        Permission::create(['name' => 'decrease cart']);
        Permission::create(['name' => 'delete cart']);
        Permission::create(['name' => 'favorite product']);
        Permission::create(['name' => 'update password']);
        Permission::create(['name' => 'manage address']);
        Permission::create(['name' => 'manage commission']);
        Permission::create(['name' => 'get affiliate link']);

        // Tạo các vai trò và gán quyền

        $normalUser = Role::create(['name' => 'normal_user']);
        $normalUser->givePermissionTo([
            'add to cart',
            'order',
            'cancel order',
            'message store',
            'review products',
            'update profile',
            'affiliate register',
            'increase cart',
            'decrease cart',
            'delete cart',
            'favorite product',
            'update password',
            'manage address'
        ]);

        $loyalCustomer = Role::create(['name' => 'loyal_customer']);
        $loyalCustomer->givePermissionTo([
            'add to cart',
            'order',
            'cancel order',
            'message store',
            'review products',
            'update profile',
            'affiliate register',
            'increase cart',
            'decrease cart',
            'delete cart',
            'favorite product',
            'update password',
            'manage address'
        ]);

        $affiliateMarketer = Role::create(['name' => 'affiliate_marketer']);
        $affiliateMarketer->givePermissionTo([
            'get affiliate link',
            'create withdrawal request',
        ]);

        $staff = Role::create(['name' => 'staff']);
        $staff->givePermissionTo([
            'manage products',
            'delete products',
            'manage orders',
            'view sales reports'
        ]);

        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());
    }
}
