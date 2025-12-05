@extends('layouts.app')

@section('title', 'Detail Donasi')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Detail Donasi</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('donations.index') }}" class="btn btn-sm btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Informasi Donasi</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="30%"><strong>ID Donasi</strong></td>
                            <td>{{ $donation->id }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal</strong></td>
                            <td>{{ $donation->created_at->format('d F Y, H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Donatur</strong></td>
                            <td>{{ $donation->user->name }} ({{ $donation->user->email }})</td>
                        </tr>
                        <tr>
                            <td><strong>Program</strong></td>
                            <td>{{ $donation->program->nama_program }}</td>
                        </tr>
                        <tr>
                            <td><strong>Nominal</strong></td>
                            <td>Rp {{ number_format($donation->nominal, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Metode Pembayaran</strong></td>
                            <td>{{ $donation->metode_pembayaran }}</td>
                        </tr>
                        <tr>
                            <td><strong>Metode Pembayaran</strong></td>
                            <td>{{ $donation->bankAccount->bank_name }}
                                <small class="text-muted">({{ $donation->bankAccount->account_number }})</small>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Status</strong></td>
                            <td>
                                @if ($donation->status === 'terverifikasi')
                                    <span class="badge bg-success">Terverifikasi</span>
                                @elseif($donation->status === 'menunggu')
                                    <span class="badge bg-warning">Menunggu Verifikasi</span>
                                @else
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Bukti Penerimaan</h5>
                </div>
                <div class="card-body text-center">
                    @if ($donation->bukti_transfer)
                        <a href="{{ Storage::url($donation->bukti_transfer) }}" target="_blank">
                            <img src="{{ Storage::url($donation->bukti_transfer) }}" alt="Bukti Transfer"
                                class="img-fluid rounded" style="max-height: 300px;">
                        </a>
                        <p class="mt-2">
                            <a href="{{ Storage::url($donation->bukti_transfer) }}" target="_blank"
                                class="btn btn-sm btn-outline-primary">Lihat Gambar Asli</a>
                        </p>
                    @else
                        <p class="text-muted">Belum ada bukti transfer.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
