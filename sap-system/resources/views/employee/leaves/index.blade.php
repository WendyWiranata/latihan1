@extends('layouts.app')

@section('title', 'Daftar Cuti')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Cuti</h3>
        <div class="card-tools">
            <a href="{{ route('employee.leaves.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Ajukan Cuti
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Jenis Cuti</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Durasi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($leaves as $leave)
                <tr>
                    <td>{{ $leave->leave_type }}</td>
                    <td>{{ $leave->start_date->format('d/m/Y') }}</td>
                    <td>{{ $leave->end_date->format('d/m/Y') }}</td>
                    <td>{{ $leave->days }} hari</td>
                    <td>
                        @if($leave->status == 'pending')
                            <span class="badge badge-warning">Menunggu</span>
                        @elseif($leave->status == 'approved')
                            <span class="badge badge-success">Disetujui</span>
                        @else
                            <span class="badge badge-danger">Ditolak</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('employee.leaves.show', $leave->id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection