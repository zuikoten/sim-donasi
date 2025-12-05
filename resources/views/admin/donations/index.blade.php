@extends('layouts.app')

@section('title', 'Manajemen Donasi')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Manajemen Donasi</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('donations.create') }}" class="btn btn-sm btn-primary fw-bold me-2 mt-2">
                <i class="bi bi-plus-circle"></i> Mulai Donasimu
            </a>
            @if (Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                <a href="{{ route('admin.donations.create') }}" class="btn btn-sm btn-warning fw-bold mt-2">
                    <i class="bi bi-plus-circle"></i> Tambahkan Donasi dari Donatur
                </a>
            @endif
        </div>

    </div>

    <div class="card">
        <div class="card-body">
            @if ($donations->count() > 0)
                <!-- Dropdown untuk memilih jumlah data per halaman -->
                <div class="row mb-3">
                    <div class="col-md-2">
                        <label for="perPage" class="form-label">Tampilkan</label>
                        <select id="perPage" class="form-select form-select-sm"
                            onchange="window.location.href=this.value">
                            <option value="{{ route('donations.index', ['perPage' => 25]) }}"
                                {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                            <option value="{{ route('donations.index', ['perPage' => 50]) }}"
                                {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                            <option value="{{ route('donations.index', ['perPage' => 100]) }}"
                                {{ request('perPage') == 100 ? 'selected' : '' }}>100</option>
                            <option value="{{ route('donations.index', ['perPage' => 500]) }}"
                                {{ request('perPage') == 500 ? 'selected' : '' }}>500</option>
                            <option value="{{ route('donations.index', ['perPage' => 999999]) }}"
                                {{ request('perPage') == 999999 ? 'selected' : '' }}>Semua</option>
                        </select>
                    </div>
                </div>

                <!-- Tabel dengan Sticky Header -->
                <div class="table-responsive" style="max-height: 70vh; overflow-y: auto;">
                    <table class="table table-striped table-hover table-sticky-header">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Donatur</th>
                                <th>Program</th>
                                <th>Nominal</th>
                                <th>Metode</th>
                                <th>Rekening</th>
                                <th>Status</th>
                                <th>Bukti</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($donations as $donation)
                                <tr>
                                    <td>{{ $donation->created_at->format('d M Y') }}</td>
                                    <td>{{ $donation->user->display_name }}
                                    </td>
                                    <td>{{ $donation->program->nama_program }}</td>
                                    <td>Rp {{ number_format($donation->nominal, 0, ',', '.') }}</td>
                                    <td>{{ $donation->metode_pembayaran }}</td>
                                    <!-- KOLOM BANK -->
                                    <td>
                                        @if ($donation->bankAccount)
                                            <small class="text-muted">{{ $donation->bankAccount->bank_name }}</small><br>
                                            ({{ $donation->bankAccount->account_number }})
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($donation->status === 'terverifikasi')
                                            <span class="badge bg-success">Terverifikasi</span>
                                        @elseif($donation->status === 'menunggu')
                                            <span class="badge bg-warning">Menunggu</span>
                                        @else
                                            <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($donation->bukti_transfer)
                                            <a href="{{ Storage::url($donation->bukti_transfer) }}" target="_blank"
                                                class="btn btn-sm btn-outline-info">
                                                <i class="bi bi-eye"></i> Lihat
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('donations.show', $donation->id) }}"
                                                class="btn btn-sm btn-outline-primary" title="Detail">
                                                <i class="bi bi-info-circle"></i>
                                            </a>
                                            @if (auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
                                                @if ($donation->status === 'menunggu')
                                                    <div class="btn-group" role="group">
                                                        <form action="{{ route('donations.verify', $donation->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="status" value="terverifikasi">
                                                            <button type="submit" class="btn btn-sm btn-success"
                                                                title="Verifikasi"
                                                                onclick="return confirm('Sudah periksa bukti pembayaran dan lanjut Verifikasi donasi ini?')">
                                                                <i class="bi bi-check-circle"></i>
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('donations.verify', $donation->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="status" value="ditolak">
                                                            <button type="submit" class="btn btn-sm btn-danger"
                                                                title="Tolak"
                                                                onclick="return confirm('Apakah Anda yakin ingin menolak donasi ini?')">
                                                                <i class="bi bi-x-circle"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Informasi Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <span class="text-muted">
                        Menampilkan {{ $donations->firstItem() }} hingga {{ $donations->lastItem() }} dari
                        {{ $donations->total() }} data.
                    </span>
                    {{ $donations->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <p>Belum ada data donasi.</p>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* CSS untuk Sticky Header Tabel */
        .table-sticky-header thead th {
            position: sticky;
            top: 0;
            z-index: 10;
            background-color: #f8f9fa;
            /* Warna latar yang sama dengan .table-light */
        }
    </style>
@endpush
