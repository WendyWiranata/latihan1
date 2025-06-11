<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminCompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:SuperAdmin']);
    }

    public function index()
    {
        $admins = User::role('Admin Perusahaan')->with('company')->get();
        return view('superadmin.admins.index', compact('admins'));
    }

    public function create()
    {
        $companies = Company::all();
        return view('superadmin.admins.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'company_id' => 'required|exists:companies,id',
        ]);

        $admin = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'company_id' => $request->company_id,
        ]);

        $admin->assignRole('Admin Perusahaan');

        return redirect()->route('superadmin.admins.index')
            ->with('success', 'Admin perusahaan berhasil ditambahkan.');
    }

    public function show(User $admin)
    {
        return view('superadmin.admins.show', compact('admin'));
    }

    public function edit(User $admin)
    {
        $companies = Company::all();
        return view('superadmin.admins.edit', compact('admin', 'companies'));
    }

    public function update(Request $request, User $admin)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $admin->id,
            'password' => 'nullable|string|min:8|confirmed',
            'company_id' => 'required|exists:companies,id',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'company_id' => $request->company_id,
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        return redirect()->route('superadmin.admins.index')
            ->with('success', 'Admin perusahaan berhasil diperbarui.');
    }

    public function destroy(User $admin)
    {
        $admin->delete();

        return redirect()->route('superadmin.admins.index')
            ->with('success', 'Admin perusahaan berhasil dihapus.');
    }
}