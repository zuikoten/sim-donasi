@extends('layouts.app')

@section('title', 'Detail Penyaluran Donasi')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Detail Penyaluran Donasi</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('distributions.index') }}" class="btn btn-sm btn-secondary me-2">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <a href="{{ route('distributions.edit', $distribution->id) }}" class="btn btn-sm btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Informasi Penyaluran</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="30%"><strong>Tanggal Penyaluran</strong></td>
                        <td>{{ $distribution->tanggal_penyaluran->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Penerima Manfaat</strong></td>
                        <td>
                            <a href="{{ route('beneficiaries.show', $distribution->beneficiary->id) }}" class="text-decoration-none">
                                {{ $distribution->beneficiary->nama }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Program Asal Dana</strong></td>
                        <td>
                            <a href="{{ route('programs.show', $distribution->program->id) }}" class="text-decoration-none">
                                {{ $distribution->program->nama_program }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Nominal Disalurkan</strong></td>
                        <td>Rp {{ number_format($distribution->nominal_disalurkan, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Deskripsi</strong></td>
                        <td>{{ $distribution->deskripsi ?: '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informasi Penerima</h5>
            </div>
            <div class="card-body">
                <h6>{{ $distribution->beneficiary->nama }}</h6>
                <p class="text-muted mb-2">{{ Str::limit($distribution->beneficiary->alamat, 100) }}</p>
                <hr>
                <p class="mb-2">Total Donasi yang Telah Diterima:</p>
                <h5>Rp {{ number_format($distribution->beneficiary->distributions->sum('nominal_disalurkan'), 0, ',', '.') }}</h5>
            </div>
        </div>
    </div>
</div>
@endsection