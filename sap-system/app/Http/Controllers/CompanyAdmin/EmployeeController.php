<?php

namespace App\Http\Controllers\CompanyAdmin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Admin Perusahaan']);
    }

    public function index()
    {
        $companyId = auth()->user()->company_id;
        $employees = Employee::where('company_id', $companyId)->get();
        return view('companyadmin.employees.index', compact('employees'));
    }

    public function create()
    {
        return view('companyadmin.employees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|string|unique:employees',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email|unique:users,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'department' => 'required|string',
            'position' => 'required|string',
            'join_date' => 'required|date',
        ]);

        $employee = Employee::create([
            'company_id' => auth()->user()->company_id,
            'employee_id' => $request->employee_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'department' => $request->department,
            'position' => $request->position,
            'join_date' => $request->join_date,
        ]);

        // Create user account for employee
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('password123'), // Default password
            'company_id' => auth()->user()->company_id,
        ]);

        $user->assignRole('Karyawan');

        // Link user to employee
        $employee->update(['user_id' => $user->id]);

        return redirect()->route('companyadmin.employees.index')
            ->with('success', 'Karyawan berhasil ditambahkan.');
    }

    public function show(Employee $employee)
    {
        $this->checkCompany($employee);
        return view('companyadmin.employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $this->checkCompany($employee);
        return view('companyadmin.employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $this->checkCompany($employee);

        $request->validate([
            'employee_id' => 'required|string|unique:employees,employee_id,' . $employee->id,
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->id . '|unique:users,email,' . $employee->user_id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'department' => 'required|string',
            'position' => 'required|string',
            'join_date' => 'required|date',
        ]);

        $employee->update($request->all());

        // Update user account
        if ($employee->user) {
            $employee->user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);
        }

        return redirect()->route('companyadmin.employees.index')
            ->with('success', 'Karyawan berhasil diperbarui.');
    }

    public function destroy(Employee $employee)
    {
        $this->checkCompany($employee);

        if ($employee->user) {
            $employee->user->delete();
        }

        $employee->delete();

        return redirect()->route('companyadmin.employees.index')
            ->with('success', 'Karyawan berhasil dihapus.');
    }

    private function checkCompany($employee)
    {
        if ($employee->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized action.');
        }
    }
}