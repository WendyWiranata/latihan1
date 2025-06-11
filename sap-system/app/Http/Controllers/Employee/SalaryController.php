<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Salary;

class SalaryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Karyawan']);
    }

    public function index()
    {
        $employeeId = auth()->user()->employee->id;
        $salaries = Salary::where('employee_id', $employeeId)
            ->orderBy('payment_date', 'desc')
            ->get();
            
        return view('employee.salaries.index', compact('salaries'));
    }

    public function show(Salary $salary)
    {
        $this->checkEmployee($salary);
        return view('employee.salaries.show', compact('salary'));
    }

    private function checkEmployee($salary)
    {
        if ($salary->employee_id !== auth()->user()->employee->id) {
            abort(403, 'Unauthorized action.');
        }
    }
}