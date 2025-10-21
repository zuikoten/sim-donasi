@extends('layouts.app')

@section('title', 'Edit Program Donasi')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Program Donasi: {{ $program->nama_program }}</h1>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('programs.update', $program->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="nama_program" class="form-label">Nama Program</label>
                    <input type="text" class="form-control @error('nama_program') is-invalid @enderror" id="nama_program" name="nama_program" value="{{ old('nama_program', $program->nama_program) }}" required>
                    @error('nama_program')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="kategori" class="form-label">Kategori</label>
                    <select class="form-select @error('kategori') is-invalid @enderror" id="kategori" name="kategori" required>
                        <option value="" disabled>-- Pilih Kategori --</option>
                        <option value="Zakat" {{ old('kategori', $program->kategori) == 'Zakat' ? 'selected' : '' }}>Zakat</option>
                        <option value="Infaq" {{ old('kategori', $program->kategori) == 'Infaq' ? 'selected' : '' }}>Infaq</option>
                        <option value="Sedekah" {{ old('kategori', $program->kategori) == 'Sedekah' ? 'selected' : '' }}>Sedekah</option>
                        <option value="Wakaf" {{ old('kategori', $program->kategori) == 'Wakaf' ? 'selected' : '' }}>Wakaf</option>
                    </select>
                    @error('kategori')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="4" required>{{ old('deskripsi', $program->deskripsi) }}</textarea>
                @error('deskripsi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- GAMBAR -->
            <div class="mb-3">
                <label for="gambar" class="form-label">Gambar Program</label>
                <input type="file" class="form-control @error('gambar') is-invalid @enderror" id="gambar" name="gambar" accept="image/*">
                
                @if($program->gambar)
                    <div class="mt-2">
                        <p class="form-text">Gambar saat ini:</p>
                        <img src="{{ Storage::url($program->gambar) }}" alt="{{ $program->nama_program }}" class="img-thumbnail" style="max-height: 150px;">
                    </div>
                @endif

                <div class="form-text">Kosongkan jika tidak ingin mengubah gambar.</div>
                @error('gambar')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="target_dana" class="form-label">Target Dana</label>
                    <input type="number" class="form-control @error('target_dana') is-invalid @enderror" id="target_dana" name="target_dana" value="{{ old('target_dana', $program->target_dana) }}" min="0" required>
                    @error('target_dana')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                        <option value="aktif" {{ old('status', $program->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="selesai" {{ old('status', $program->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="ditutup" {{ old('status', $program->status) == 'ditutup' ? 'selected' : '' }}>Ditutup</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai', $program->tanggal_mulai->format('Y-m-d')) }}" required>
                    @error('tanggal_mulai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                    <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai', $program->tanggal_selesai->format('Y-m-d')) }}" required>
                    @error('tanggal_selesai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('programs.index') }}" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection