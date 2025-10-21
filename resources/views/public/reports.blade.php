@extends('layouts.guest')

@section('title', 'Laporan Transparansi')

@section('content')
<!-- Hero Section -->
<section class="bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12 text-center">
                <h1 class="display-4 fw-bold">Laporan Transparansi</h1>
                <p class="lead">Komitmen kami adalah memberikan kejelasan atas setiap donasi yang Anda percayakan kepada kami.</p>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h2 class="text-primary">{{ \App\Models\Program::where('status', 'aktif')->count() }}</h2>
                        <p>Program Aktif</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h2 class="text-success">Rp {{ number_format($totalDonations, 0, ',', '.') }}</h2>
                        <p>Total Dana Terkumpul</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h2 class="text-info">Rp {{ number_format($totalDistributed, 0, ',', '.') }}</h2>
                        <p>Total Dana Tersalurkan</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h2 class="text-warning">{{ \App\Models\Beneficiary::count() }}</h2>
                        <p>Penerima Manfaat</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Programs Report Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="fw-bold">Ringkasan Program Donasi</h2>
                <p class="lead">Berikut adalah perkembangan dari setiap program yang kami jalankan.</p>
            </div>
        </div>
        
        <div class="row">
            @forelse($programs as $program)
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0">{{ $program->nama_program }}</h5>
                            <span class="badge bg-primary">{{ $program->kategori }}</span>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Terkumpul</span>
                                <span>{{ $program->progress_percentage }}%</span>
                            </div>
                            <div class="progress mb-2">
                                <div class="progress-bar" role="progressbar" style="width: {{ $program->progress_percentage }}%" aria-valuenow="{{ $program->progress_percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <small>Rp {{ number_format($program->dana_terkumpul, 0, ',', '.') }}</small>
                                <small>Rp {{ number_format($program->target_dana, 0, ',', '.') }}</small>
                            </div>
                        </div>

                        <div class="row text-center">
                            <div class="col-6 border-end">
                                <h6 class="text-success">Rp {{ number_format($program->donations_sum_nominal, 0, ',', '.') }}</h6>
                                <small class="text-muted">Dana Masuk</small>
                            </div>
                            <div class="col-6">
                                <h6 class="text-info">Rp {{ number_format($program->distributions_sum_nominal_disalurkan, 0, ',', '.') }}</h6>
                                <small class="text-muted">Dana Keluar</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center">
                <p>Belum ada data program yang dapat ditampilkan.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- How to Access Detailed Reports Section -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="card-title">Ingin Melihat Laporan Lebih Detail?</h5>
                        <p class="card-text">Untuk akses laporan lengkap (termasuk nama donatur, detail penyaluran, dan laporan PDF/Excel), silakan masuk ke akun Anda.</p>
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            <i class="bi bi-box-arrow-in-right"></i> Masuk ke Akun
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection