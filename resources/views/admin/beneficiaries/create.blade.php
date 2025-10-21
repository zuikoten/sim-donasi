@extends('layouts.app')

@section('title', 'Tambah Penerima Manfaat')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Tambah Penerima Manfaat Baru</h1>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('beneficiaries.store') }}">
            @csrf
            
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" required>
                @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat Lengkap</label>
                <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3" required>{{ old('alamat') }}</textarea>
                @error('alamat')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="nomor_telepon" class="form-label">Nomor Telepon</label>
                <input type="text" class="form-control @error('nomor_telepon') is-invalid @enderror" id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon') }}">
                @error('nomor_telepon')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan (Opsional)</label>
                <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="2">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>


            <div class="d-flex justify-content-end">
                <a href="{{ route('beneficiaries.index') }}" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection