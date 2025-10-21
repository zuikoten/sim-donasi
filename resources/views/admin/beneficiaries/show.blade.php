@extends('layouts.app')

@section('title', 'Detail Penerima Manfaat')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Detail Penerima Manfaat: {{ $beneficiary->nama }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('beneficiaries.index') }}" class="btn btn-sm btn-secondary me-2">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <a href="{{ route('beneficiaries.edit', $beneficiary->id) }}" class="btn btn-sm btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Informasi Penerima</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="30%"><strong>Nama Lengkap</strong></td>
                        <td>{{ $beneficiary->nama }}</td>
                    </tr>
                    <tr>
                        <td><strong>Alamat</strong></td>
                        <td>{{ $beneficiary->alamat }}</td>
                    </tr>
                    <tr>
                        <td><strong>Nomor Telepon</strong></td>
                        <td>{{ $beneficiary->nomor_telepon ?: '-' }}</td>
                    </tr>

                    <tr>
                        <td><strong>Keterangan</strong></td>
                        <td>{{ $beneficiary->keterangan ?: '-' }}</td>
                    </tr>
                    
                    <tr>
                        <td><strong>Tanggal Ditambahkan</strong></td>
                        <td>{{ $beneficiary->created_at->format('d F Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Penyaluran Dana</h5>
            </div>
            <div class="card-body">
                <p class="mb-2">Total Donasi yang Telah Disalurkan:</p>
                <h4>Rp {{ number_format($beneficiary->distributions->sum('nominal_disalurkan'), 0, ',', '.') }}</h4>
                <hr>
                <a href="{{ route('distributions.create') }}?beneficiary_id={{ $beneficiary->id }}" class="btn btn-primary btn-sm w-100">
                    <i class="bi bi-plus-circle"></i> Salurkan Dana
                </a>
            </div>
        </div>
    </div>
</div>
@endsection