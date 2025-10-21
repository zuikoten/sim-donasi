@extends('layouts.app')

@section('title', 'Dashboard Donatur')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard Donatur</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('donations.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle"></i> Donasi Sekarang
            </a>
        </div>
    </div>

    <!-- Selamat Datang -->
    <div class="alert alert-info" role="alert">
        <h4 class="alert-heading">Selamat Datang, {{ Auth::user()->profile->nama_lengkap ?? Auth::user()->name }}!</h4>
        <p>Terima kasih atas kebaikan Anda. Mari terus berbagi untuk mereka yang membutuhkan.</p>
    </div>

    <!-- Statistik Cards -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Donasi</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalDonations }} Kali</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-hand-thumbs-up fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Donasi
                                (Terverifikasi)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp
                                {{ number_format($totalAmount, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-cash-stack fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Menunggu Verifikasi</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingDonations }} Donasi</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-hourglass-split fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Donasi Terakhir -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Riwayat Donasi Terakhir</h6>
            <a href="{{ route('donations.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="bi bi-eye"></i> Lihat Semua
            </a>
        </div>
        <div class="card-body">
            @if ($recentDonations->count() > 0)
                <!-- Dropdown PerPage -->
                <div class="col-md-3">
                    <label for="perPage" class="form-label">Tampilkan</label>
                    <select id="perPage" name="perPage" class="form-select form-select-sm">
                        <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100</option>
                        <option value="500" {{ request('perPage') == 500 ? 'selected' : '' }}>500</option>
                        <option value="999999" {{ request('perPage') == 999999 ? 'selected' : '' }}>Semua</option>
                    </select>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Program</th>
                                <th>Nominal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentDonations as $donation)
                                <tr>
                                    <td>{{ $donation->created_at->format('d M Y') }}</td>
                                    <td>{{ $donation->program->nama_program }}</td>
                                    <td>Rp {{ number_format($donation->nominal, 0, ',', '.') }}</td>
                                    <td>
                                        @if ($donation->status === 'terverifikasi')
                                            <span class="badge bg-success">Terverifikasi</span>
                                        @elseif($donation->status === 'menunggu')
                                            <span class="badge bg-warning">Menunggu</span>
                                        @else
                                            <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Start Pagination -->
                <div class="card-footer d-flex justify-content-between align-items-center">
                    <span class="text-muted">
                        Menampilkan {{ $recentDonations->firstItem() }} hingga {{ $recentDonations->lastItem() }} dari
                        {{ $recentDonations->total() }} data.
                    </span>
                    {{ $recentDonations->appends(request()->query())->links() }}

                </div>
                <!-- End Pagination -->
            @else
                <p class="text-center text-gray-500">Anda belum pernah berdonasi. <a
                        href="{{ route('donations.create') }}">Mulai donasi sekarang!</a></p>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Reload Tabel saat Paginasi berubah
        document.getElementById('perPage').addEventListener('change', function() {
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('perPage', this.value); // ubah perPage
            window.location.href = currentUrl.toString(); // reload halaman dengan semua filter tetap
        });
    </script>
@endpush
