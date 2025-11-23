@extends('layouts.guest')

@section('title', 'Beranda')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">

                <!-- TEXT -->
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="hero-glass">
                        <h1 class="hero-title">
                            Bersama Menebar Kebaikan<br>
                            Untuk Masa Depan Umat
                        </h1>

                        <p class="hero-subtitle">
                            Kelola donasi Anda dengan transparansi penuh. Program ZISWAF
                            kami membantu menyalurkan amanah tepat sasaran dan berdampak.
                        </p>

                        <a href="{{ route('login') }}" class="btn btn-light hero-btn mt-3">
                            Mulai Berdonasi
                        </a>
                    </div>
                </div>

                <!-- IMAGE -->
                <div class="col-lg-6 text-center">
                    <img src="{{ asset('public/images/charity.webp') }}" alt="Ilustrasi Donasi" class="hero-img">
                </div>

            </div>
        </div>
    </section>


    <!-- Statistics Section -->
    <section class="py-5 bg-light">
        <div class="container">

            <div class="row text-center g-4">

                <!-- Program Aktif -->
                <div class="col-md-3">
                    <div class="shadow-sm stat-card p-4 rounded-4 bg-white">
                        <div class="icon-circle mx-auto mb-3 bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <h2 class="fw-bold">{{ $programs->count() }}</h2>
                        <p class="text-muted mb-0">Program Aktif</p>
                    </div>
                </div>

                <!-- Dana Terkumpul -->
                <div class="col-md-3">
                    <div class="shadow-sm stat-card p-4 rounded-4 bg-white">
                        <div class="icon-circle mx-auto mb-3 bg-success bg-opacity-10 text-success">
                            <i class="bi bi-wallet2"></i>
                        </div>
                        <h2 class="fw-bold text-success">
                            Rp
                            {{ $programs->sum('dana_terkumpul') > 0 ? number_format($programs->sum('dana_terkumpul'), 0, ',', '.') : 0 }}
                        </h2>
                        <p class="text-muted mb-0">Dana Terkumpul</p>
                    </div>
                </div>

                <!-- Penerima Manfaat -->
                <div class="col-md-3">
                    <div class="shadow-sm stat-card p-4 rounded-4 bg-white">
                        <div class="icon-circle mx-auto mb-3 bg-info bg-opacity-10 text-info">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <h2 class="fw-bold text-info">{{ \App\Models\Beneficiary::count() }}</h2>
                        <p class="text-muted mb-0">Penerima Manfaat</p>
                    </div>
                </div>

                <!-- Donatur -->
                <div class="col-md-3">
                    <div class="shadow-sm stat-card p-4 rounded-4 bg-white">
                        <div class="icon-circle mx-auto mb-3 bg-warning bg-opacity-10 text-warning">
                            <i class="bi bi-person-heart"></i>
                        </div>
                        <h2 class="fw-bold text-warning">
                            {{ \App\Models\Donation::where('status', 'terverifikasi')->count() }}
                        </h2>
                        <p class="text-muted mb-0">Donatur</p>
                    </div>
                </div>

            </div>
        </div>
    </section>



    <!-- Programs Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="fw-bold">Program Donasi</h2>
                    <p class="lead">Pilih program yang ingin Anda dukung</p>
                </div>
            </div>

            <!-- Modern Category Filter Tabs -->
            <div class="row mb-5" id="programs-section">
                <div class="col-12">
                    <div class="category-tabs-wrapper">
                        <ul class="nav nav-pills category-tabs justify-content-center" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link {{ $category == 'all' ? 'active' : '' }}"
                                    href="{{ route('home', ['category' => 'all']) }}#programs-section">
                                    <i class="bi bi-grid-fill me-2"></i>Semua Program
                                </a>
                            </li>
                            @foreach ($categories as $cat)
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link {{ $category == $cat ? 'active' : '' }}"
                                        href="{{ route('home', ['category' => $cat]) }}#programs-section">
                                        @if ($cat == 'Zakat')
                                            <i class="bi bi-moon-stars-fill me-2"></i>
                                        @elseif($cat == 'Infaq')
                                            <i class="bi bi-heart-fill me-2"></i>
                                        @elseif($cat == 'Sedekah')
                                            <i class="bi bi-hand-thumbs-up-fill me-2"></i>
                                        @elseif($cat == 'Wakaf')
                                            <i class="bi bi-building me-2"></i>
                                        @else
                                            <i class="bi bi-circle-fill me-2"></i>
                                        @endif
                                        {{ ucfirst($cat) }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            @if ($programs->count() > 0)
                <!-- Programs Grid -->
                <div class="row" id="programs-container">
                    @foreach ($programs as $program)
                        <div class="col-lg-4 col-md-6 mb-4 program-item" data-category="{{ $program->kategori }}">
                            <div class="card h-100 modern-program-card">
                                <!-- Image Container with Overlay -->
                                <div class="program-image-container">
                                    @if ($program->gambar)
                                        <img src="{{ Storage::url($program->gambar) }}" class="card-img-top"
                                            alt="{{ $program->nama_program }}">
                                    @else
                                        <img src="https://picsum.photos/seed/{{ $program->id }}/400/250.jpg"
                                            class="card-img-top" alt="{{ $program->nama_program }}">
                                    @endif

                                    <!-- Gradient Overlay on hover -->
                                    <div class="image-overlay"></div>

                                    <!-- Status Badge Floating -->
                                    <div class="floating-badge">
                                        <span class="badge badge-category">
                                            <i class="bi bi-folder me-1"></i>{{ $program->kategori }}
                                        </span>
                                        @if ($program->donations_count > 0)
                                            <span class="badge badge-donors">
                                                <i class="bi bi-people-fill me-1"></i>{{ $program->donations_count }}
                                                Donatur
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="card-body d-flex flex-column">
                                    <h5 class="program-title">{{ $program->nama_program }}</h5>
                                    <p class="program-description">{{ Str::limit($program->deskripsi, 100) }}</p>

                                    <div class="mt-auto">
                                        <!-- Progress Section -->
                                        <div class="progress-section">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="progress-label">Terkumpul</span>
                                                <span
                                                    class="progress-percentage">{{ number_format($program->progress_percentage, 1) }}%</span>
                                            </div>
                                            <div class="modern-progress-wrapper">
                                                <div class="modern-progress-bar"
                                                    style="width: {{ min(100, $program->progress_percentage) }}%"
                                                    data-progress="{{ $program->progress_percentage }}">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Amount Display -->
                                        <div class="amount-section">
                                            <div class="amount-item">
                                                <small class="amount-label">Terkumpul</small>
                                                <strong class="amount-value amount-collected">
                                                    Rp
                                                    {{ number_format($program->donations_sum_nominal ?? 0, 0, ',', '.') }}
                                                </strong>
                                            </div>
                                            <div class="amount-item text-end">
                                                <small class="amount-label">Target</small>
                                                <strong class="amount-value">
                                                    Rp {{ number_format($program->target_dana, 0, ',', '.') }}
                                                </strong>
                                            </div>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="action-buttons">
                                            <a href="{{ route('program.detail', $program->id) }}"
                                                class="btn btn-modern-outline-card">
                                                <i class="bi bi-eye me-1"></i>Detail
                                            </a>
                                            <a href="{{ route('login') }}" class="btn btn-modern-primary-card">
                                                <i class="bi bi-heart-fill me-1"></i>Donasi
                                            </a>
                                        </div>
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
                <!-- Empty State -->
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="bi bi-inbox"></i>
                    </div>
                    <h4 class="empty-state-title">Belum Ada Program</h4>
                    <p class="empty-state-text">
                        Belum ada program donasi untuk kategori <strong>{{ ucfirst($category) }}</strong> saat ini.
                    </p>
                    <a href="{{ route('programs.create') }}" class="btn btn-modern-primary">
                        <i class="bi bi-plus-circle-fill me-2"></i>Tambah Program Baru
                    </a>
                </div>
            @endif

            <div class="row mt-4">
                <div class="col-12 text-center">
                    <a href="{{ route('public.reports') }}" class="btn btn-premium-report">
                        <i class="bi bi-file-earmark-bar-graph me-2"></i>
                        Lihat Laporan Transparansi
                    </a>
                </div>
            </div>

        </div>
    </section>



    <!-- How It Works Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="fw-bold">Cara Berdonasi</h2>
                    <p class="lead">Mudah, aman, dan transparan</p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 text-center mb-4">
                    <div class="card h-100 border-0">
                        <div class="card-body">
                            <div class="mb-3">
                                <i class="bi bi-search display-4 text-primary"></i>
                            </div>
                            <h5>Pilih Program</h5>
                            <p>Pilih program donasi yang sesuai dengan keinginan Anda</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 text-center mb-4">
                    <div class="card h-100 border-0">
                        <div class="card-body">
                            <div class="mb-3">
                                <i class="bi bi-credit-card display-4 text-primary"></i>
                            </div>
                            <h5>Isi Form & Transfer</h5>
                            <p>Isi form donasi dan transfer ke rekening yang tersedia</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 text-center mb-4">
                    <div class="card h-100 border-0">
                        <div class="card-body">
                            <div class="mb-3">
                                <i class="bi bi-check-circle display-4 text-primary"></i>
                            </div>
                            <h5>Verifikasi & Penyaluran</h5>
                            <p>Admin akan memverifikasi donasi Anda dan menyalurkannya</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonial Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="fw-bold">Testimoni Donatur</h2>
                    <p class="lead">Apa kata mereka tentang kami</p>
                </div>
            </div>

            @if ($testimonials->count() > 0)
                <div class="row">
                    <div class="col-12">
                        <div class="testimonial-carousel-container">
                            <div class="testimonial-stack" id="testimonialStack">
                                @foreach ($testimonials as $index => $testimonial)
                                    <div class="testimonial-card {{ $index === 0 ? 'active' : ($index === 1 ? 'next' : ($index === 2 ? 'next-2' : 'hidden')) }}"
                                        data-index="{{ $index }}">
                                        <div class="card-inner">
                                            <img src="{{ $testimonial->photo_url }}" alt="{{ $testimonial->name }}"
                                                class="rounded-circle testimonial-photo mb-3">
                                            <div class="mb-3">
                                                {!! $testimonial->stars_html !!}
                                            </div>
                                            <p class="testimonial-comment mb-4">
                                                "{{ $testimonial->comment }}"
                                            </p>
                                            <h5 class="fw-bold mb-1">{{ $testimonial->name }}</h5>
                                            <small class="text-muted">{{ $testimonial->role }}</small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Controls -->
                        <div class="carousel-controls">
                            <button class="carousel-btn" id="prevBtn">
                                <i class="bi bi-chevron-left fs-5"></i>
                            </button>
                            <button class="carousel-btn" id="nextBtn">
                                <i class="bi bi-chevron-right fs-5"></i>
                            </button>
                        </div>

                        <!-- Indicators -->
                        <div class="carousel-indicators-custom" id="indicators"></div>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="bi bi-chat-quote fs-1 text-muted mb-3"></i>
                            <p class="text-muted">Belum ada testimoni dari donatur.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

@endsection

@push('styles')
    <!-- Hero Section-->
    <style>
        /* HERO PREMIUM */
        .hero-section {
            background: linear-gradient(135deg, #0d6efd 0%, #2034c9 100%);
            padding: 90px 0 110px;
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        /* Efek cahaya halus */
        .hero-section::before {
            content: "";
            position: absolute;
            top: -80px;
            left: -80px;
            width: 250px;
            height: 250px;
            background: rgba(255, 255, 255, 0.15);
            filter: blur(80px);
            border-radius: 50%;
        }

        .hero-glass {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(12px);
            padding: 35px;
            border-radius: 22px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.20);
            border: 1px solid rgba(255, 255, 255, 0.25);
        }

        .hero-title {
            font-size: 2.85rem;
            font-weight: 800;
            line-height: 1.2;
            letter-spacing: -0.5px;
        }

        .hero-subtitle {
            font-size: 1.15rem;
            opacity: 0.95;
            margin-top: 15px;
        }

        /* Tombol premium */
        .hero-btn {
            padding: 12px 30px;
            font-size: 1.05rem;
            border-radius: 30px;
            font-weight: 600;
            box-shadow: 0 5px 15px rgba(255, 255, 255, 0.18);
        }

        .hero-img {
            border-radius: 22px;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.25);
            max-width: 100%;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.2rem;
            }

            .hero-glass {
                padding: 25px;
            }
        }
    </style>

    <!-- Statistic Section -->
    <style>
        .stat-card {
            transition: 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
        }

        .icon-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1.6rem;
        }
    </style>

    <!-- Custom CSS for Modern Tabs -->
    <style>
        .category-tabs-wrapper {
            background: white;
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .category-tabs {
            border: none;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .category-tabs .nav-link {
            border: 2px solid #e9ecef;
            border-radius: 50px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            color: #495057;
            transition: all 0.3s ease;
            background: white;
        }

        .category-tabs .nav-link:hover {
            border-color: #0d6efd;
            color: #0d6efd;
            background: #f8f9fa;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(13, 110, 253, 0.15);
        }

        .category-tabs .nav-link.active {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            border-color: #0d6efd;
            color: white;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
        }

        .category-tabs .nav-link i {
            font-size: 1rem;
        }


        /* Responsive adjustments */
        @media (max-width: 768px) {
            .category-tabs .nav-link {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }

            .category-tabs-wrapper {
                padding: 1rem;
            }
        }

        /* Style untuk testimonials section */
        .testimonial-carousel-container {
            position: relative;
            height: 450px;
            perspective: 1000px;
        }

        .testimonial-stack {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .testimonial-card {
            position: absolute;
            width: 70%;
            max-width: 600px;
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
        }

        .testimonial-card.active {
            z-index: 5;
            transform: translateX(0) scale(1);
            opacity: 1;
        }

        .testimonial-card.next {
            z-index: 4;
            transform: translateX(15%) scale(0.9);
            opacity: 0.7;
        }

        .testimonial-card.next-2 {
            z-index: 3;
            transform: translateX(25%) scale(0.8);
            opacity: 0.4;
        }

        .testimonial-card.prev {
            z-index: 4;
            transform: translateX(-15%) scale(0.9);
            opacity: 0.7;
        }

        .testimonial-card.prev-2 {
            z-index: 3;
            transform: translateX(-25%) scale(0.8);
            opacity: 0.4;
        }

        .testimonial-card.hidden {
            z-index: 1;
            transform: translateX(0) scale(0.7);
            opacity: 0;
        }

        .card-inner {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .testimonial-photo {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border: 4px solid #f8f9fa;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .testimonial-comment {
            font-size: 1.1rem;
            line-height: 1.6;
            color: #6c757d;
            font-style: italic;
            min-height: 80px;
        }

        .carousel-controls {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
        }

        .carousel-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: none;
            background: #0d6efd;
            color: white;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .carousel-btn:hover {
            background: #0b5ed7;
            transform: scale(1.1);
        }

        .carousel-indicators-custom {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 20px;
        }

        .indicator-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #dee2e6;
            cursor: pointer;
            transition: all 0.3s;
        }

        .indicator-dot.active {
            background: #0d6efd;
            width: 30px;
            border-radius: 5px;
        }

        @media (max-width: 768px) {
            .testimonial-card {
                width: 90%;
            }

            .card-inner {
                padding: 25px;
            }

            .testimonial-comment {
                font-size: 0.95rem;
            }
        }

        /* Premium Tombol Laporan Transparansi */
        .btn-premium-report {
            display: inline-flex;
            align-items: center;
            gap: 10px;

            font-size: 1.80rem;
            /* lebih besar */
            font-weight: 700;
            padding: 18px 42px;
            border-radius: 14px;

            /* Kontras & Premium */
            background: linear-gradient(135deg, #fcb660, #ff4d00);
            color: #fff !important;
            border: none;

            /* Glow */
            box-shadow: 0 8px 16px rgba(255, 80, 25, 0.35);

            /* Transitions */
            transition: all 0.25s ease-in-out;
        }

        /* Hover: lebih terang, lebih tinggi */
        .btn-premium-report:hover {
            transform: translateY(-4px) scale(1.03);
            box-shadow: 0 12px 24px rgba(255, 80, 25, 0.45);
            background: linear-gradient(135deg, #ffad48, #ff6a25);
        }

        /* Active: sedikit turun */
        .btn-premium-report:active {
            transform: translateY(1px) scale(0.99);
        }
    </style>

    <!-- program grid modern style-->
    <style>
        /* Modern Program Card */
        .modern-program-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: white;
            position: relative;
        }

        .modern-program-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .modern-program-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 20px 50px rgba(102, 126, 234, 0.25);
        }

        .modern-program-card:hover::before {
            opacity: 1;
        }

        /* Image Container */
        .program-image-container {
            position: relative;
            overflow: hidden;
            height: 250px;
            background: #f8f9fa;
        }

        .program-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .modern-program-card:hover .program-image-container img {
            transform: scale(1.1);
        }

        /* Image Overlay */
        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, 0.7) 100%);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .modern-program-card:hover .image-overlay {
            opacity: 1;
        }

        /* Floating Badges */
        .floating-badge {
            position: absolute;
            top: 1rem;
            left: 1rem;
            right: 1rem;
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            z-index: 2;
        }

        .badge-category,
        .badge-donors {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.875rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .badge-category {
            background: rgba(102, 126, 234, 0.9);
            color: white;
        }

        .badge-donors {
            background: rgba(16, 185, 129, 0.9);
            color: white;
        }

        .modern-program-card:hover .badge-category,
        .modern-program-card:hover .badge-donors {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        /* Card Body */
        .modern-program-card .card-body {
            padding: 1.75rem;
        }

        /* Program Title */
        .program-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 0.75rem;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            transition: color 0.3s ease;
        }

        .modern-program-card:hover .program-title {
            color: #667eea;
        }

        /* Program Description */
        .program-description {
            color: #718096;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        /* Progress Section */
        .progress-section {
            margin-bottom: 1.5rem;
        }

        .progress-label {
            font-size: 0.875rem;
            color: #64748b;
            font-weight: 500;
        }

        .progress-percentage {
            font-size: 1.125rem;
            font-weight: 700;
            color: #667eea;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Modern Progress Bar */
        .modern-progress-wrapper {
            height: 12px;
            background: #f1f3f5;
            border-radius: 50px;
            overflow: hidden;
            position: relative;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.06);
        }

        .modern-progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            border-radius: 50px;
            transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.4);
        }

        .modern-progress-bar::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        /* Amount Section */
        .amount-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 12px;
            margin-bottom: 1.25rem;
        }

        .amount-item {
            flex: 1;
        }

        .amount-label {
            display: block;
            color: #64748b;
            font-size: 0.8125rem;
            margin-bottom: 0.25rem;
            font-weight: 500;
        }

        .amount-value {
            display: block;
            color: #2d3748;
            font-size: 0.95rem;
            font-weight: 700;
        }

        .amount-collected {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 0.75rem;
        }

        .btn-modern-outline-card,
        .btn-modern-primary-card {
            flex: 1;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9375rem;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-modern-outline-card {
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
        }

        .btn-modern-outline-card:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-modern-primary-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .btn-modern-primary-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }

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

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 5rem 2rem;
            background: white;
            border-radius: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .empty-state-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        .empty-state-icon i {
            font-size: 3rem;
            color: white;
        }

        .empty-state-title {
            color: #2d3748;
            font-weight: 700;
            margin-bottom: 0.75rem;
        }

        .empty-state-text {
            color: #64748b;
            font-size: 1.0625rem;
            margin-bottom: 2rem;
        }

        .btn-modern-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 1rem 2rem;
            border-radius: 14px;
            font-weight: 600;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
            transition: all 0.3s ease;
        }

        .btn-modern-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(102, 126, 234, 0.4);
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .pagination-wrapper {
                flex-direction: column;
                text-align: center;
            }

            .program-image-container {
                height: 220px;
            }

            .modern-program-card .card-body {
                padding: 1.5rem;
            }

            .amount-section {
                padding: 0.875rem;
            }

            .amount-value {
                font-size: 0.875rem;
            }
        }

        /* Animation on Scroll (Optional) */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .program-item {
            animation: fadeInUp 0.6s ease-out;
            animation-fill-mode: both;
        }

        .program-item:nth-child(1) {
            animation-delay: 0.1s;
        }

        .program-item:nth-child(2) {
            animation-delay: 0.2s;
        }

        .program-item:nth-child(3) {
            animation-delay: 0.3s;
        }

        .program-item:nth-child(4) {
            animation-delay: 0.4s;
        }

        .program-item:nth-child(5) {
            animation-delay: 0.5s;
        }

        .program-item:nth-child(6) {
            animation-delay: 0.6s;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Category filter
            const categoryFilters = document.querySelectorAll('.category-filter');
            const programItems = document.querySelectorAll('.program-item');

            categoryFilters.forEach(filter => {
                filter.addEventListener('click', function() {
                    // Update active state
                    categoryFilters.forEach(f => f.classList.remove('active'));
                    this.classList.add('active');

                    const category = this.getAttribute('data-category');

                    // Filter programs
                    programItems.forEach(item => {
                        if (category === 'all' || item.getAttribute('data-category') ===
                            category) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>

    <script>
        class StackedCarousel {
            constructor() {
                this.cards = document.querySelectorAll('.testimonial-card');
                this.currentIndex = 0;
                this.totalCards = this.cards.length;
                this.autoPlayInterval = null;
                this.isAnimating = false;

                if (this.totalCards > 0) {
                    this.init();
                }
            }

            init() {
                this.createIndicators();
                this.attachEventListeners();
                this.startAutoPlay();
            }

            createIndicators() {
                const indicatorsContainer = document.getElementById('indicators');
                for (let i = 0; i < this.totalCards; i++) {
                    const dot = document.createElement('div');
                    dot.className = `indicator-dot ${i === 0 ? 'active' : ''}`;
                    dot.addEventListener('click', () => this.goToSlide(i));
                    indicatorsContainer.appendChild(dot);
                }
            }

            attachEventListeners() {
                const prevBtn = document.getElementById('prevBtn');
                const nextBtn = document.getElementById('nextBtn');

                if (prevBtn) prevBtn.addEventListener('click', () => this.prev());
                if (nextBtn) nextBtn.addEventListener('click', () => this.next());

                this.cards.forEach(card => {
                    card.addEventListener('click', () => {
                        const index = parseInt(card.dataset.index);
                        if (index !== this.currentIndex) {
                            this.goToSlide(index);
                        }
                    });
                });

                const container = document.querySelector('.testimonial-carousel-container');
                if (container) {
                    container.addEventListener('mouseenter', () => this.stopAutoPlay());
                    container.addEventListener('mouseleave', () => this.startAutoPlay());
                }
            }

            updateCards() {
                if (this.isAnimating) return;
                this.isAnimating = true;

                this.cards.forEach((card, index) => {
                    card.className = 'testimonial-card';

                    const diff = (index - this.currentIndex + this.totalCards) % this.totalCards;

                    if (diff === 0) {
                        card.classList.add('active');
                    } else if (diff === 1) {
                        card.classList.add('next');
                    } else if (diff === 2) {
                        card.classList.add('next-2');
                    } else if (diff === this.totalCards - 1) {
                        card.classList.add('prev');
                    } else if (diff === this.totalCards - 2) {
                        card.classList.add('prev-2');
                    } else {
                        card.classList.add('hidden');
                    }
                });

                this.updateIndicators();

                setTimeout(() => {
                    this.isAnimating = false;
                }, 600);
            }

            updateIndicators() {
                const dots = document.querySelectorAll('.indicator-dot');
                dots.forEach((dot, index) => {
                    dot.classList.toggle('active', index === this.currentIndex);
                });
            }

            next() {
                this.currentIndex = (this.currentIndex + 1) % this.totalCards;
                this.updateCards();
                this.resetAutoPlay();
            }

            prev() {
                this.currentIndex = (this.currentIndex - 1 + this.totalCards) % this.totalCards;
                this.updateCards();
                this.resetAutoPlay();
            }

            goToSlide(index) {
                if (index === this.currentIndex || this.isAnimating) return;
                this.currentIndex = index;
                this.updateCards();
                this.resetAutoPlay();
            }

            startAutoPlay() {
                this.autoPlayInterval = setInterval(() => this.next(), 4000);
            }

            stopAutoPlay() {
                if (this.autoPlayInterval) {
                    clearInterval(this.autoPlayInterval);
                    this.autoPlayInterval = null;
                }
            }

            resetAutoPlay() {
                this.stopAutoPlay();
                this.startAutoPlay();
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            new StackedCarousel();
        });
    </script>
@endpush
