<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'company-crud',
            'admin-crud',
            'employee-crud',
            'salary-crud',
            'leave-crud',
            'apply-leave',
            'view-salary',
            'view-profile'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $superAdmin = Role::create(['name' => 'SuperAdmin']);
        $superAdmin->givePermissionTo([
            'company-crud',
            'admin-crud',
            'view-profile'
        ]);

        $companyAdmin = Role::create(['name' => 'Admin Perusahaan']);
        $companyAdmin->givePermissionTo([
            'employee-crud',
            'salary-crud',
            'leave-crud',
            'view-profile'
        ]);

        $employee = Role::create(['name' => 'Karyawan']);
        $employee->givePermissionTo([
            'apply-leave',
            'view-salary',
            'view-profile'
        ]);
    }
}