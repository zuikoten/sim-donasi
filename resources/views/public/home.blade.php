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
                    <img src="{{ asset('images/charity.webp') }}" alt="Ilustrasi Donasi" class="hero-img">
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
                            <div class="card h-100 program-card">
                                <!-- Gambar Custom -->
                                @if ($program->gambar)
                                    <img src="{{ Storage::url($program->gambar) }}" class="card-img-top"
                                        alt="{{ $program->nama_program }}" style="height: 250px; object-fit: cover;">
                                @else
                                    <img src="https://picsum.photos/seed/{{ $program->id }}/400/250.jpg"
                                        class="card-img-top" alt="{{ $program->nama_program }}"
                                        style="height: 250px; object-fit: cover;">
                                @endif

                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge bg-primary">{{ $program->kategori }}</span>
                                        @if ($program->donations_count > 0)
                                            <span class="badge bg-success">
                                                <i class="bi bi-people-fill me-1"></i>{{ $program->donations_count }}
                                                Donatur
                                            </span>
                                        @endif
                                    </div>

                                    <h5 class="card-title">{{ $program->nama_program }}</h5>
                                    <p class="card-text text-muted">{{ Str::limit($program->deskripsi, 100) }}</p>

                                    <div class="mt-auto">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="fw-bold">Terkumpul</span>
                                            <span
                                                class="text-primary fw-bold">{{ number_format($program->progress_percentage, 1) }}%</span>
                                        </div>
                                        <div class="progress mb-3" style="height: 10px;">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                style="width: {{ min(100, $program->progress_percentage) }}%"
                                                aria-valuenow="{{ $program->progress_percentage }}" aria-valuemin="0"
                                                aria-valuemax="100">
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between mb-3">
                                            <div>
                                                <small class="text-muted d-block">Terkumpul</small>
                                                <strong class="text-success">Rp
                                                    {{ number_format($program->donations_sum_nominal ?? 0, 0, ',', '.') }}</strong>
                                            </div>
                                            <div class="text-end">
                                                <small class="text-muted d-block">Target</small>
                                                <strong>Rp {{ number_format($program->target_dana, 0, ',', '.') }}</strong>
                                            </div>
                                        </div>

                                        <div class="d-flex gap-2">
                                            <a href="{{ route('program.detail', $program->id) }}"
                                                class="btn btn-outline-primary flex-fill">
                                                <i class="bi bi-info-circle me-1"></i>Detail
                                            </a>
                                            <a href="{{ route('login') }}" class="btn btn-primary flex-fill">
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
                <div class="d-flex justify-content-between align-items-center mt-4 mb-4 flex-wrap">
                    <span class="text-muted mb-2 mb-md-0">
                        Menampilkan {{ $programs->firstItem() }} hingga {{ $programs->lastItem() }} dari
                        {{ $programs->total() }} program.
                    </span>
                    {{ $programs->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <div class="alert alert-info d-inline-block">
                        <i class="bi bi-info-circle me-2"></i>
                        Belum ada program donasi untuk kategori <strong>{{ ucfirst($category) }}</strong> saat ini.
                    </div>
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
            background: linear-gradient(135deg, #0d6efd 0%, #1a73e8 50%, #0a58ca 100%);
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

        /* Program Card Hover Effect */
        .program-card {
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .program-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .program-card .card-img-top {
            transition: all 0.3s ease;
        }

        .program-card:hover .card-img-top {
            transform: scale(1.05);
        }

        .program-card .card-img-top {
            overflow: hidden;
        }

        /* Progress bar animation */
        .progress-bar {
            transition: width 1s ease-in-out;
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
