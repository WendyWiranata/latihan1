<?php

namespace App\Http\Controllers\CompanyAdmin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Salary;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Admin Perusahaan']);
    }

    public function index()
    {
        $companyId = auth()->user()->company_id;
        $salaries = Salary::with('employee')
            ->where('company_id', $companyId)
            ->get();
            
        return view('companyadmin.salaries.index', compact('salaries'));
    }

    public function create()
    {
        $companyId = auth()->user()->company_id;
        $employees = Employee::where('company_id', $companyId)->get();
        return view('companyadmin.salaries.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'basic_salary' => 'required|numeric|min:0',
            'allowance' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $totalSalary = $request->basic_salary 
            + ($request->allowance ?? 0) 
            + ($request->bonus ?? 0) 
            - ($request->tax ?? 0);

        Salary::create([
            'company_id' => auth()->user()->company_id,
            'employee_id' => $request->employee_id,
            'basic_salary' => $request->basic_salary,
            'allowance' => $request->allowance ?? 0,
            'bonus' => $request->bonus ?? 0,
            'tax' => $request->tax ?? 0,
            'total_salary' => $totalSalary,
            'payment_date' => $request->payment_date,
            'payment_method' => $request->payment_method,
            'notes' => $request->notes,
        ]);

        return redirect()->route('companyadmin.salaries.index')
            ->with('success', 'Data gaji berhasil ditambahkan.');
    }

    public function show(Salary $salary)
    {
        $this->checkCompany($salary);
        return view('companyadmin.salaries.show', compact('salary'));
    }

    public function edit(Salary $salary)
    {
        $this->checkCompany($salary);
        $companyId = auth()->user()->company_id;
        $employees = Employee::where('company_id', $companyId)->get();
        return view('companyadmin.salaries.edit', compact('salary', 'employees'));
    }

    public function update(Request $request, Salary $salary)
    {
        $this->checkCompany($salary);

        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'basic_salary' => 'required|numeric|min:0',
            'allowance' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $totalSalary = $request->basic_salary 
            + ($request->allowance ?? 0) 
            + ($request->bonus ?? 0) 
            - ($request->tax ?? 0);

        $salary->update([
            'employee_id' => $request->employee_id,
            'basic_salary' => $request->basic_salary,
            'allowance' => $request->allowance ?? 0,
            'bonus' => $request->bonus ?? 0,
            'tax' => $request->tax ?? 0,
            'total_salary' => $totalSalary,
            'payment_date' => $request->payment_date,
            'payment_method' => $request->payment_method,
            'notes' => $request->notes,
        ]);

        return redirect()->route('companyadmin.salaries.index')
            ->with('success', 'Data gaji berhasil diperbarui.');
    }

    public function destroy(Salary $salary)
    {
        $this->checkCompany($salary);
        $salary->delete();

        return redirect()->route('companyadmin.salaries.index')
            ->with('success', 'Data gaji berhasil dihapus.');
    }

    private function checkCompany($salary)
    {
        if ($salary->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized action.');
        }
    }
}