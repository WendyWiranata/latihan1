@extends('layouts.app')

@section('title', 'Daftar Karyawan')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Karyawan</h3>
        <div class="card-tools">
            <a href="{{ route('companyadmin.employees.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Karyawan
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Karyawan</th>
                    <th>Nama</th>
                    <th>Departemen</th>
                    <th>Posisi</th>
                    <th>Tanggal Bergabung</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employees as $employee)
                <tr>
                    <td>{{ $employee->employee_id }}</td>
                    <td>{{ $employee->name }}</td>
                    <td>{{ $employee->department }}</td>
                    <td>{{ $employee->position }}</td>
                    <td>{{ $employee->join_date->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('companyadmin.employees.show', $employee->id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('companyadmin.employees.edit', $employee->id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('companyadmin.employees.destroy', $employee->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus karyawan ini?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection