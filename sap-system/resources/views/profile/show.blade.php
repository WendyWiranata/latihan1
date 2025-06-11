@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Profil Saya</h3>
        <div class="card-tools">
            <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit Profil
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="text-center">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=7F9CF5&background=EBF4FF" 
                         alt="Profile" class="img-circle img-fluid" style="width: 150px;">
                </div>
            </div>
            <div class="col-md-8">
                <table class="table table-bordered">
                    <tr>
                        <th>Nama</th>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th>Perusahaan</th>
                        <td>{{ $user->company->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Role</th>
                        <td>
                            @foreach($user->roles as $role)
                                <span class="badge badge-primary">{{ $role->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection