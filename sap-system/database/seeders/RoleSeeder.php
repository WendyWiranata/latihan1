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

       // Buat permission hanya jika belum ada
          $permissions = ['company-crud', 'admin-crud', 'employee-crud'];
    foreach ($permissions as $perm) {
        Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
    }
      // Buat role SuperAdmin
    $role = Role::firstOrCreate(['name' => 'SuperAdmin', 'guard_name' => 'web']);
    $role->givePermissionTo(Permission::all());

    // Buat user SuperAdmin
    User::firstOrCreate([
        'email' => 'superadmin@example.com'
    ], [
        'name' => 'Super Admin',
        'password' => bcrypt('password'),
        'company_id' => 1
    ])->assignRole('SuperAdmin');
    
        Permission::firstOrCreate(['name' => 'company-crud', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'admin-crud', 'guard_name' => 'web']);
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
              $superAdmin = Role::firstOrCreate(['name' => 'SuperAdmin', 'guard_name' => 'web']);
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
             $user = \App\Models\User::firstOrCreate([
            'email' => 'superadmin@example.com'
        ], [
            'name' => 'Super Admin',
            'password' => bcrypt('password'),
            'company_id' => 1
        ]);
        $user->assignRole('SuperAdmin');
    }
}