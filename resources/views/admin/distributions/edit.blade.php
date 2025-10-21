@extends('layouts.app')

@section('title', 'Edit Penyaluran Donasi')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Penyaluran Donasi</h1>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('distributions.update', $distribution->id) }}">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="beneficiary_id" class="form-label">Penerima Manfaat</label>
                <select class="form-select @error('beneficiary_id') is-invalid @enderror" id="beneficiary_id" name="beneficiary_id" required>
                    <option value="" disabled>-- Pilih Penerima --</option>
                    @foreach($beneficiaries as $beneficiary)
                        <option value="{{ $beneficiary->id }}" {{ old('beneficiary_id', $distribution->beneficiary_id) == $beneficiary->id ? 'selected' : '' }}>
                            {{ $beneficiary->nama }}
                        </option>
                    @endforeach
                </select>
                @error('beneficiary_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="program_id" class="form-label">Program Asal Dana</label>
                <select class="form-select @error('program_id') is-invalid @enderror" id="program_id" name="program_id" required>
                    <option value="" disabled>-- Pilih Program --</option>
                    @foreach($programs as $program)
                        <option value="{{ $program->id }}" {{ old('program_id', $distribution->program_id) == $program->id ? 'selected' : '' }}>
                            {{ $program->nama_program }} (Tersedia: Rp {{ number_format($program->dana_terkumpul, 0, ',', '.') }})
                        </option>
                    @endforeach
                </select>
                @error('program_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="nominal_disalurkan" class="form-label">Nominal Disalurkan (Rp)</label>
                <input type="number" class="form-control @error('nominal_disalurkan') is-invalid @enderror" id="nominal_disalurkan" name="nominal_disalurkan" value="{{ old('nominal_disalurkan', $distribution->nominal_disalurkan) }}" min="1000" required>
                @error('nominal_disalurkan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="tanggal_penyaluran" class="form-label">Tanggal Penyaluran</label>
                <input type="date" class="form-control @error('tanggal_penyaluran') is-invalid @enderror" id="tanggal_penyaluran" name="tanggal_penyaluran" value="{{ old('tanggal_penyaluran', $distribution->tanggal_penyaluran->format('Y-m-d')) }}" required>
                @error('tanggal_penyaluran')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi (Opsional)</label>
                <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi', $distribution->deskripsi) }}</textarea>
                @error('deskripsi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('distributions.index') }}" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection