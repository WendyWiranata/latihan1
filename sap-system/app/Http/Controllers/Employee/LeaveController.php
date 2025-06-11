<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Karyawan']);
    }

    public function index()
    {
        $employeeId = auth()->user()->employee->id;
        $leaves = Leave::where('employee_id', $employeeId)->get();
        return view('employee.leaves.index', compact('leaves'));
    }

    public function create()
    {
        return view('employee.leaves.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'leave_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        $days = (strtotime($request->end_date) - strtotime($request->start_date)) / (60 * 60 * 24) + 1;

        Leave::create([
            'company_id' => auth()->user()->company_id,
            'employee_id' => auth()->user()->employee->id,
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'days' => $days,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->route('employee.leaves.index')
            ->with('success', 'Pengajuan cuti berhasil dikirim.');
    }

    public function show(Leave $leave)
    {
        $this->checkEmployee($leave);
        return view('employee.leaves.show', compact('leave'));
    }

    private function checkEmployee($leave)
    {
        if ($leave->employee_id !== auth()->user()->employee->id) {
            abort(403, 'Unauthorized action.');
        }
    }
}