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
        Permission::create(['name' => 'manage wishlist']);
        Permission::create(['name' => 'place order']);
        Permission::create(['name' => 'make payment']);
        Permission::create(['name' => 'cancel order']);
        Permission::create(['name' => 'message store']);
        Permission::create(['name' => 'review products']);
        Permission::create(['name' => 'update profile']);
        Permission::create(['name' => 'view order history']);
        Permission::create(['name' => 'register as affiliate marketer']);
        Permission::create(['name' => 'manage affiliate products']);
        Permission::create(['name' => 'share affiliate links']);
        Permission::create(['name' => 'manage products']);
        Permission::create(['name' => 'delete products']);
        Permission::create(['name' => 'manage orders']);
        Permission::create(['name' => 'view sales reports']);
        Permission::create(['name' => 'manage customers']);
        Permission::create(['name' => 'manage reviews']);
        Permission::create(['name' => 'manage inventory']);
        Permission::create(['name' => 'manage promotions']);
        Permission::create(['name' => 'manage shipping']);
        Permission::create(['name' => 'manage staff']);
        Permission::create(['name' => 'manage affiliate marketers']);

        // Tạo các vai trò và gán quyền

        $normalUser = Role::create(['name' => 'normal_user']);
        $normalUser->givePermissionTo([
            'view products',
            'search products',
            'add to cart',
            'manage wishlist',
            'place order',
            'make payment',
            'cancel order',
            'message store',
            'review products',
            'update profile',
            'view order history',
            'register as affiliate marketer'
        ]);

        $loyalCustomer = Role::create(['name' => 'loyal_customer']);
        $loyalCustomer->givePermissionTo([
            'view products',
            'search products',
            'add to cart',
            'manage wishlist',
            'place order',
            'make payment',
            'cancel order',
            'message store',
            'review products',
            'update profile',
            'view order history',
            'register as affiliate marketer'
        ]);

        $affiliateMarketer = Role::create(['name' => 'affiliate_marketer']);
        $affiliateMarketer->givePermissionTo([
            'view products',
            'search products',
            'add to cart',
            'manage wishlist',
            'place order',
            'make payment',
            'cancel order',
            'message store',
            'review products',
            'update profile',
            'view order history',
            'register as affiliate marketer',
            'manage affiliate products',
            'share affiliate links'
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
