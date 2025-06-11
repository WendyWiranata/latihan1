<?php

namespace App\Http\Controllers\CompanyAdmin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Leave;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Admin Perusahaan']);
    }

    public function index()
    {
        $companyId = auth()->user()->company_id;
        $leaves = Leave::with('employee')
            ->where('company_id', $companyId)
            ->get();
            
        return view('companyadmin.leaves.index', compact('leaves'));
    }

    public function create()
    {
        $companyId = auth()->user()->company_id;
        $employees = Employee::where('company_id', $companyId)->get();
        return view('companyadmin.leaves.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        $days = (strtotime($request->end_date) - strtotime($request->start_date)) / (60 * 60 * 24) + 1;

        Leave::create([
            'company_id' => auth()->user()->company_id,
            'employee_id' => $request->employee_id,
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'days' => $days,
            'reason' => $request->reason,
            'status' => 'approved',
        ]);

        return redirect()->route('companyadmin.leaves.index')
            ->with('success', 'Data cuti berhasil ditambahkan.');
    }

    public function show(Leave $leave)
    {
        $this->checkCompany($leave);
        return view('companyadmin.leaves.show', compact('leave'));
    }

    public function edit(Leave $leave)
    {
        $this->checkCompany($leave);
        $companyId = auth()->user()->company_id;
        $employees = Employee::where('company_id', $companyId)->get();
        return view('companyadmin.leaves.edit', compact('leave', 'employees'));
    }

    public function update(Request $request, Leave $leave)
    {
        $this->checkCompany($leave);

        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
            'status' => 'required|in:pending,approved,rejected',
            'admin_notes' => 'nullable|string',
        ]);

        $days = (strtotime($request->end_date) - strtotime($request->start_date)) / (60 * 60 * 24) + 1;

        $leave->update([
            'employee_id' => $request->employee_id,
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'days' => $days,
            'reason' => $request->reason,
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
        ]);

        return redirect()->route('companyadmin.leaves.index')
            ->with('success', 'Data cuti berhasil diperbarui.');
    }

    public function destroy(Leave $leave)
    {
        $this->checkCompany($leave);
        $leave->delete();

        return redirect()->route('companyadmin.leaves.index')
            ->with('success', 'Data cuti berhasil dihapus.');
    }

    public function approve(Leave $leave)
    {
        $this->checkCompany($leave);
        $leave->update(['status' => 'approved']);

        return redirect()->back()
            ->with('success', 'Cuti telah disetujui.');
    }

    public function reject(Leave $leave)
    {
        $this->checkCompany($leave);
        $leave->update(['status' => 'rejected']);

        return redirect()->back()
            ->with('success', 'Cuti telah ditolak.');
    }

    private function checkCompany($leave)
    {
        if ($leave->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized action.');
        }
    }
}