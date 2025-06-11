<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\Salary;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(RoleSeeder::class);

        // Create SuperAdmin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password'),
        ]);
        $superAdmin->assignRole('SuperAdmin');

        // Create Company and Admin
        $company = Company::create([
            'name' => 'PT. Contoh Perusahaan',
            'address' => 'Jl. Contoh No. 123, Jakarta',
            'phone' => '02112345678',
            'email' => 'info@contoh.com',
            'description' => 'Perusahaan contoh untuk sistem SAP',
        ]);

        $admin = User::create([
            'name' => 'Admin Perusahaan',
            'email' => 'admin@contoh.com',
            'password' => Hash::make('password'),
            'company_id' => $company->id,
        ]);
        $admin->assignRole('Admin Perusahaan');

        // Create Employees
        $employee1 = Employee::create([
            'company_id' => $company->id,
            'employee_id' => 'EMP001',
            'name' => 'Karyawan Satu',
            'email' => 'karyawan1@contoh.com',
            'phone' => '081234567891',
            'address' => 'Jl. Karyawan No. 1',
            'department' => 'IT',
            'position' => 'Programmer',
            'join_date' => '2020-01-01',
        ]);

        $employee2 = Employee::create([
            'company_id' => $company->id,
            'employee_id' => 'EMP002',
            'name' => 'Karyawan Dua',
            'email' => 'karyawan2@contoh.com',
            'phone' => '081234567892',
            'address' => 'Jl. Karyawan No. 2',
            'department' => 'HRD',
            'position' => 'HR Staff',
            'join_date' => '2020-02-01',
        ]);

        // Create user accounts for employees
        $user1 = User::create([
            'name' => $employee1->name,
            'email' => $employee1->email,
            'password' => Hash::make('password'),
            'company_id' => $company->id,
        ]);
        $user1->assignRole('Karyawan');
        $employee1->update(['user_id' => $user1->id]);

        $user2 = User::create([
            'name' => $employee2->name,
            'email' => $employee2->email,
            'password' => Hash::make('password'),
            'company_id' => $company->id,
        ]);
        $user2->assignRole('Karyawan');
        $employee2->update(['user_id' => $user2->id]);

        // Create Salaries
        Salary::create([
            'company_id' => $company->id,
            'employee_id' => $employee1->id,
            'basic_salary' => 5000000,
            'allowance' => 1000000,
            'bonus' => 500000,
            'tax' => 500000,
            'total_salary' => 6000000,
            'payment_date' => now()->subMonth(),
            'payment_method' => 'Transfer Bank',
        ]);

        Salary::create([
            'company_id' => $company->id,
            'employee_id' => $employee2->id,
            'basic_salary' => 4500000,
            'allowance' => 800000,
            'bonus' => 300000,
            'tax' => 400000,
            'total_salary' => 5200000,
            'payment_date' => now()->subMonth(),
            'payment_method' => 'Transfer Bank',
        ]);

        // Create Leaves
        Leave::create([
            'company_id' => $company->id,
            'employee_id' => $employee1->id,
            'leave_type' => 'Cuti Tahunan',
            'start_date' => now()->addDays(5),
            'end_date' => now()->addDays(7),
            'days' => 3,
            'reason' => 'Liburan keluarga',
            'status' => 'pending',
        ]);

        Leave::create([
            'company_id' => $company->id,
            'employee_id' => $employee2->id,
            'leave_type' => 'Cuti Sakit',
            'start_date' => now()->subDays(3),
            'end_date' => now()->subDays(1),
            'days' => 3,
            'reason' => 'Demam tinggi',
            'status' => 'approved',
        ]);
    }
}