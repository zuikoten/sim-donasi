@extends('layouts.app')

@section('title', 'Manajemen Role')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Manajemen Role</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('roles.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Role Baru
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if($roles->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Nama Role</th>
                            <th>Jumlah Pengguna</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $role)
                        <tr>
                            <td>{{ $role->name }}</td>
                            <td>{{ $role->users->count() }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('roles.show', $role->id) }}" class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if($role->users->count() == 0)
                                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus role ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-sm btn-outline-secondary" disabled title="Tidak dapat dihapus, role masih digunakan">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-4">
                <p>Belum ada data role.</p>
                <a href="{{ route('roles.create') }}" class="btn btn-primary">Tambah Role Pertama</a>
            </div>
        @endif
    </div>
</div>
@endsection