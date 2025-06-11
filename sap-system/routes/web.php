<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::middleware(['auth'])->group(function () {
    // Profile routes
    Route::get('/profile', 'ProfileController@show')->name('profile.show');
    Route::get('/profile/edit', 'ProfileController@edit')->name('profile.edit');
    Route::post('/profile/update', 'ProfileController@update')->name('profile.update');

    // SuperAdmin routes
    Route::prefix('superadmin')->middleware(['role:SuperAdmin'])->group(function () {
        Route::resource('companies', 'SuperAdmin\CompanyController')->names([
            'index' => 'superadmin.companies.index',
            'create' => 'superadmin.companies.create',
            'store' => 'superadmin.companies.store',
            'show' => 'superadmin.companies.show',
            'edit' => 'superadmin.companies.edit',
            'update' => 'superadmin.companies.update',
            'destroy' => 'superadmin.companies.destroy'
        ]);

        Route::resource('admins', 'SuperAdmin\AdminCompanyController')->names([
            'index' => 'superadmin.admins.index',
            'create' => 'superadmin.admins.create',
            'store' => 'superadmin.admins.store',
            'show' => 'superadmin.admins.show',
            'edit' => 'superadmin.admins.edit',
            'update' => 'superadmin.admins.update',
            'destroy' => 'superadmin.admins.destroy'
        ]);
    });

    // Company Admin routes
    Route::prefix('companyadmin')->middleware(['role:Admin Perusahaan'])->group(function () {
        Route::resource('employees', 'CompanyAdmin\EmployeeController')->names([
            'index' => 'companyadmin.employees.index',
            'create' => 'companyadmin.employees.create',
            'store' => 'companyadmin.employees.store',
            'show' => 'companyadmin.employees.show',
            'edit' => 'companyadmin.employees.edit',
            'update' => 'companyadmin.employees.update',
            'destroy' => 'companyadmin.employees.destroy'
        ]);

        Route::resource('salaries', 'CompanyAdmin\SalaryController')->names([
            'index' => 'companyadmin.salaries.index',
            'create' => 'companyadmin.salaries.create',
            'store' => 'companyadmin.salaries.store',
            'show' => 'companyadmin.salaries.show',
            'edit' => 'companyadmin.salaries.edit',
            'update' => 'companyadmin.salaries.update',
            'destroy' => 'companyadmin.salaries.destroy'
        ]);

        Route::resource('leaves', 'CompanyAdmin\LeaveController')->names([
            'index' => 'companyadmin.leaves.index',
            'create' => 'companyadmin.leaves.create',
            'store' => 'companyadmin.leaves.store',
            'show' => 'companyadmin.leaves.show',
            'edit' => 'companyadmin.leaves.edit',
            'update' => 'companyadmin.leaves.update',
            'destroy' => 'companyadmin.leaves.destroy'
        ]);

        Route::post('leaves/{leave}/approve', 'CompanyAdmin\LeaveController@approve')->name('companyadmin.leaves.approve');
        Route::post('leaves/{leave}/reject', 'CompanyAdmin\LeaveController@reject')->name('companyadmin.leaves.reject');
    });

    // Employee routes
    Route::prefix('employee')->middleware(['role:Karyawan'])->group(function () {
        Route::resource('leaves', 'Employee\LeaveController')->names([
            'index' => 'employee.leaves.index',
            'create' => 'employee.leaves.create',
            'store' => 'employee.leaves.store',
            'show' => 'employee.leaves.show',
            'edit' => 'employee.leaves.edit',
            'update' => 'employee.leaves.update',
            'destroy' => 'employee.leaves.destroy'
        ]);

        Route::resource('salaries', 'Employee\SalaryController')->names([
            'index' => 'employee.salaries.index',
            'show' => 'employee.salaries.show'
        ]);
    });
});

Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->hasRole('SuperAdmin')) {
            return redirect()->route('superadmin.companies.index');
        } elseif (auth()->user()->hasRole('Admin Perusahaan')) {
            return redirect()->route('companyadmin.employees.index');
        } elseif (auth()->user()->hasRole('Karyawan')) {
            return redirect()->route('employee.leaves.index');
        }
    }
    return view('welcome');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');