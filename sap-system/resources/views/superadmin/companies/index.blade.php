@extends('layouts.app')

@section('title', 'Daftar Perusahaan')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Perusahaan</h3>
        <div class="card-tools">
            <a href="{{ route('superadmin.companies.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Perusahaan
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Telepon</th>
                    <th>Email</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($companies as $company)
                <tr>
                    <td>{{ $company->name }}</td>
                    <td>{{ $company->address }}</td>
                    <td>{{ $company->phone }}</td>
                    <td>{{ $company->email }}</td>
                    <td>
                        <a href="{{ route('superadmin.companies.show', $company->id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('superadmin.companies.edit', $company->id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('superadmin.companies.destroy', $company->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus perusahaan ini?')">
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