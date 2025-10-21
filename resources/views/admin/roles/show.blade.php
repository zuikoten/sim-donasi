@extends('layouts.app')

@section('title', 'Detail Role')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Detail Role: {{ $role->name }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('roles.index') }}" class="btn btn-sm btn-secondary me-2">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Informasi Role</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Nama Role</strong></td>
                        <td>{{ $role->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Jumlah Pengguna</strong></td>
                        <td>{{ $role->users->count() }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Pengguna dengan Role Ini</h5>
            </div>
            <div class="card-body">
                @if($role->users->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($role->users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->status === 'aktif')
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Non-aktif</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center text-muted">Belum ada pengguna yang menggunakan role ini.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection