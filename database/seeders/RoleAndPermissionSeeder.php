<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions =[
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',

            'user-list',
            'user-create',
            'user-edit',
            'user-delete',

            'permission-list',
            'permission-create',
            'permission-edit',
            'permission-delete',

            'package-list',
            'package-create',
            'package-edit',
            'package-delete',

            'category-list',
            'category-create',
            'category-edit',
            'category-delete',

            'sub-category-list',
            'sub-category-create',
            'sub-category-edit',
            'sub-category-delete',

            'brand-list',
            'brand-create',
            'brand-edit',
            'brand-delete',

            'product-list',
            'product-create',
            'product-edit',
            'product-delete',
        ];

        $applicationPermissions=[
            'inventory-list',
            'inventory-create',
            'inventory-edit',
            'inventory-delete',
        ];


        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        foreach($applicationPermissions as $shopPermission) {
            Permission::create(['name' => $shopPermission,'guard_name'=>'application']);
        }

        $superAdminRole = Role::create(['name' => "SuperAdmin"]);
        $superAdminRole->givePermissionTo($permissions);

        $shopOwnerRole=Role::create(['name' => "ShopOwner"]);


    }
}
