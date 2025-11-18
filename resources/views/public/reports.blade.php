@extends('layouts.guest')

@section('title', 'Laporan Transparansi')

@section('content')
    <!-- Hero Section -->
    <section class="hero-report-section text-white py-5">
        <div class="container py-5">
            <div class="row justify-content-center text-center">
                <div class="col-lg-10">
                    <h1 class="display-4 fw-bold mb-3 hero-title">
                        Laporan Transparansi
                    </h1>
                    <p class="lead hero-subtitle">
                        Komitmen kami adalah memberikan kejelasan atas setiap donasi yang Anda percayakan
                        kepada kami.
                    </p>
                </div>
            </div>
        </div>
    </section>


    <!-- Statistics Section -->
    <section class="py-5">
        <div class="container">
            <div class="row g-4 text-center">

                <!-- Program Aktif -->
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-grid-fill"></i>
                        </div>
                        <h2 class="stat-number text-primary">
                            {{ \App\Models\Program::where('status', 'aktif')->count() }}
                        </h2>
                        <p class="stat-label">Program Aktif</p>
                    </div>
                </div>

                <!-- Total Dana Terkumpul -->
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-icon bg-success bg-opacity-10 text-success">
                            <i class="bi bi-cash-coin"></i>
                        </div>
                        <h2 class="stat-number text-success">
                            Rp {{ number_format($totalDonations, 0, ',', '.') }}
                        </h2>
                        <p class="stat-label">Total Terkumpul</p>
                    </div>
                </div>

                <!-- Total Dana Tersalurkan -->
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-icon bg-info bg-opacity-10 text-info">
                            <i class="bi bi-send-check-fill"></i>
                        </div>
                        <h2 class="stat-number text-info">
                            Rp {{ number_format($totalDistributed, 0, ',', '.') }}
                        </h2>
                        <p class="stat-label">Tersalurkan</p>
                    </div>
                </div>

                <!-- Penerima Manfaat -->
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <h2 class="stat-number text-warning">
                            {{ \App\Models\Beneficiary::count() }}
                        </h2>
                        <p class="stat-label">Penerima Manfaat</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Programs Report Section -->
    <section class="py-5 bg-light">
        <div class="container">

            <!-- Title -->
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="fw-bold display-6">Ringkasan Program Donasi</h2>
                    <p class="lead text-muted">Perkembangan terbaru dari setiap program yang sedang berjalan.</p>
                </div>
            </div>

            <!-- Premium Sorting Tabs -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="glass-card p-4">
                        <form method="GET" action="{{ route('public.reports') }}" id="sortForm">
                            <div class="row g-4 align-items-center">

                                <!-- Sort Dropdown -->
                                <div class="col-md-6">
                                    <label class="fw-bold mb-2">Urutkan Berdasarkan:</label>
                                    <div class="premium-select-wrapper">
                                        <select name="sort" id="sort" class="form-select premium-select"
                                            onchange="document.getElementById('sortForm').submit()">
                                            <option value="popular" {{ $sort == 'popular' ? 'selected' : '' }}>
                                                ⭐ Program Favorit
                                            </option>
                                            <option value="least" {{ $sort == 'least' ? 'selected' : '' }}>
                                                🌱 Program Sedang Tumbuh
                                            </option>
                                            <option value="name_asc" {{ $sort == 'name_asc' ? 'selected' : '' }}>
                                                🔤 Nama Program (A-Z)
                                            </option>
                                            <option value="name_desc" {{ $sort == 'name_desc' ? 'selected' : '' }}>
                                                🔡 Nama Program (Z-A)
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Category Filter -->
                                <div class="col-md-6">
                                    <label class="fw-bold mb-2">Filter Kategori:</label>
                                    <div class="premium-select-wrapper">
                                        <select name="category" id="category" class="form-select premium-select"
                                            onchange="document.getElementById('sortForm').submit()">
                                            <option value="all" {{ $category == 'all' ? 'selected' : '' }}>
                                                🔎 Semua Kategori
                                            </option>

                                            @foreach ($categories as $cat)
                                                <option value="{{ $cat }}"
                                                    {{ $category == $cat ? 'selected' : '' }}>
                                                    {{ ucfirst($cat) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Program Cards -->
            @if ($programs->count() > 0)
                <div class="row g-4">
                    @foreach ($programs as $program)
                        <div class="col-lg-6">
                            <div class="program-card h-100">

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="fw-bold mb-0">{{ $program->nama_program }}</h5>
                                    <span class="badge bg-gradient-primary">{{ ucfirst($program->kategori) }}</span>
                                </div>

                                <!-- Progress -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-semibold">Terkumpul</span>
                                        <span class="fw-bold text-dark">
                                            {{ number_format($program->progress_percentage, 1) }}%
                                        </span>
                                    </div>

                                    <div class="progress premium-progress mt-2">
                                        <div class="progress-bar" role="progressbar"
                                            style="width: {{ min(100, $program->progress_percentage) }}%;">
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between mt-2">
                                        <small class="fw-bold text-success">
                                            Rp {{ number_format($program->donations_sum_nominal ?? 0, 0, ',', '.') }}
                                        </small>
                                        <small class="text-muted">
                                            Target: Rp {{ number_format($program->target_dana, 0, ',', '.') }}
                                        </small>
                                    </div>
                                </div>

                                <!-- Stats Row -->
                                <div class="row text-center pt-3 border-top">
                                    <div class="col-6">
                                        <h6 class="text-success mb-0">
                                            Rp {{ number_format($program->donations_sum_nominal ?? 0, 0, ',', '.') }}
                                        </h6>
                                        <small class="text-muted">Dana Masuk</small>
                                        <div class="mt-1">
                                            <span class="badge bg-info">
                                                {{ $program->donations_count ?? 0 }} Donasi
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <h6 class="text-info mb-0">
                                            Rp
                                            {{ number_format($program->distributions_sum_nominal_disalurkan ?? 0, 0, ',', '.') }}
                                        </h6>
                                        <small class="text-muted">Dana Disalurkan</small>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Informasi Pagination -->
                <div class="pagination-wrapper">
                    <div class="pagination-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Menampilkan <strong>{{ $programs->firstItem() }}</strong> hingga
                        <strong>{{ $programs->lastItem() }}</strong> dari
                        <strong>{{ $programs->total() }}</strong> program
                    </div>
                    <div class="pagination-links">
                        {{ $programs->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="alert alert-info glass-card p-4">
                        <i class="bi bi-info-circle me-2"></i>
                        Belum ada program yang cocok dengan filter ini.
                    </div>
                </div>
            @endif

        </div>
    </section>

    <!-- Akses Laporan lebih detail -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">

                    <div class="glass-info-card text-center p-5">

                        <h4 class="fw-bold mb-3">Ingin Melihat Laporan Lebih Detail?</h4>

                        <p class="text-muted mb-4">
                            Untuk akses laporan lengkap (termasuk nama donatur, detail penyaluran, dan laporan PDF/Excel),
                            silakan masuk ke akun Anda.
                        </p>

                        <a href="{{ route('login') }}" class="btn btn-premium-login">
                            <i class="bi bi-box-arrow-in-right me-2"></i>
                            Masuk ke Akun
                        </a>

                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <!-- HERO SECTION -->
    <style>
        /* HERO SECTION STYLE */
        .hero-report-section {
            background: linear-gradient(135deg, #0d6efd 0%, #2034c9 100%);
            position: relative;
            overflow: hidden;
        }

        /* Glow gradient overlay */
        .hero-report-section::before {
            content: "";
            position: absolute;
            top: -120px;
            right: -120px;
            width: 280px;
            height: 280px;
            background: rgba(255, 255, 255, 0.18);
            filter: blur(80px);
            border-radius: 50%;
        }

        /* Heading Shine */
        .hero-title {
            text-shadow: 0 5px 18px rgba(0, 0, 0, 0.25);
            letter-spacing: 0.5px;
        }

        .hero-subtitle {
            opacity: 0.92;
            font-weight: 300;
        }
    </style>

    <!-- Statistik Section -->
    <style>
        .stat-card {
            background: rgba(255, 255, 255, 0.65);
            backdrop-filter: blur(10px);
            border-radius: 18px;
            padding: 35px 20px;
            transition: all 0.3s ease-in-out;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        }

        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 1.6rem;
        }

        .stat-number {
            font-size: 1.9rem;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .stat-label {
            font-size: 0.95rem;
            color: #555;
            margin: 0;
        }
    </style>

    <!-- Program Section -->
    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(12px);
            border-radius: 16px;
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.07);
        }

        .premium-select-wrapper {
            position: relative;
        }

        .premium-select {
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 1rem;
            background: #fff;
            border: 1px solid #ddd;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.04);
        }

        .premium-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.15);
        }

        .program-card {
            background: #fff;
            border-radius: 18px;
            padding: 28px;
            border: none;
            box-shadow: 0 8px 28px rgba(0, 0, 0, 0.08);
            transition: 0.25s ease;
        }

        .program-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 36px rgba(0, 0, 0, 0.12);
        }

        .bg-gradient-primary {
            background: linear-gradient(45deg, #0d6efd, #4dabff);
        }

        .premium-progress {
            height: 14px;
            border-radius: 20px;
            background: #e9ecef;
        }

        .premium-progress .progress-bar {
            background: linear-gradient(90deg, #0d6efd, #38b6ff);
            border-radius: 20px;
        }
    </style>

    <style>
        /* Section Laporan Detail - Glass Style  */
        .glass-info-card {
            background: rgba(255, 255, 255, 0.78);
            backdrop-filter: blur(14px);
            border-radius: 22px;
            box-shadow: 0 10px 32px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.6);
            transition: 0.25s ease;
        }

        .glass-info-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 40px rgba(0, 0, 0, 0.12);
        }

        /* Premium Login Button */
        .btn-premium-login {
            display: inline-block;
            padding: 14px 32px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 14px;
            color: #fff;
            background: linear-gradient(45deg, #0d6efd, #4dabff);
            box-shadow: 0 6px 18px rgba(13, 110, 253, 0.35);
            transition: 0.3s ease;
        }

        .btn-premium-login:hover {
            color: #fff;
            transform: translateY(-3px);
            box-shadow: 0 10px 28px rgba(13, 110, 253, 0.45);
        }
    </style>

    <style>
        /* Pagination Wrapper */
        .pagination-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 3rem;
            padding: 1.5rem;
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
            flex-wrap: wrap;
            gap: 1rem;
        }

        .pagination-info {
            color: #64748b;
            font-size: 0.9375rem;
        }

        .pagination-info strong {
            color: #667eea;
            font-weight: 700;
        }

        .pagination-links .pagination {
            margin: 0;
        }

        .pagination-links .page-link {
            border: none;
            border-radius: 8px;
            margin: 0 0.25rem;
            color: #667eea;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .pagination-links .page-link:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
        }

        .pagination-links .page-item.active .page-link {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
    </style>
@endpush
