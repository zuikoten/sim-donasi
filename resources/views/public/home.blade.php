@extends('layouts.guest')

@section('title', 'Beranda')

@section('content')
    <!-- Hero Section -->
    <section class="bg-primary text-white py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold">Bersama Menebar Kebaikan</h1>
                    <p class="lead my-4">Bergabunglah dalam program ZISWAF kami untuk membantu mereka yang membutuhkan.
                        Setiap donasi Anda akan disalurkan secara transparan dan tepat sasaran.</p>
                    <a href="{{ route('login') }}" class="btn btn-light btn-lg">Donasi Sekarang</a>
                </div>
                <div class="col-lg-6">
                    <img src="https://picsum.photos/seed/donation/600/400.jpg" alt="Donation" class="img-fluid rounded">
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
                            <h2 class="text-primary">{{ $programs->count() }}</h2>
                            <p>Program Aktif</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h2 class="text-success">Rp
                                {{ $programs->sum('dana_terkumpul') > 0 ? number_format($programs->sum('dana_terkumpul'), 0, ',', '.') : 0 }}
                            </h2>
                            <p>Dana Terkumpul</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h2 class="text-info">{{ \App\Models\Beneficiary::count() }}</h2>
                            <p>Penerima Manfaat</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h2 class="text-warning">{{ \App\Models\Donation::where('status', 'terverifikasi')->count() }}
                            </h2>
                            <p>Donatur</p>
                        </div>
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
            <div class="row mb-5">
                <div class="col-12">
                    <div class="category-tabs-wrapper">
                        <ul class="nav nav-pills category-tabs justify-content-center" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link {{ $category == 'all' ? 'active' : '' }}"
                                    href="{{ route('home', ['category' => 'all']) }}">
                                    <i class="bi bi-grid-fill me-2"></i>Semua Program
                                </a>
                            </li>
                            @foreach ($categories as $cat)
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link {{ $category == $cat ? 'active' : '' }}"
                                        href="{{ route('home', ['category' => $cat]) }}">
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
                    <a href="{{ route('public.reports') }}" class="btn btn-lg btn-outline-primary">
                        <i class="bi bi-file-earmark-bar-graph me-2"></i>Lihat Laporan Transparansi
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
                    @foreach ($testimonials as $testimonial)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex mb-3">
                                        <img src="{{ $testimonial->photo_url }}" alt="{{ $testimonial->name }}"
                                            class="rounded-circle me-3"
                                            style="width: 50px; height: 50px; object-fit: cover;">
                                        <div>
                                            <h6 class="mb-0">{{ $testimonial->name }}</h6>
                                            <small class="text-muted">{{ $testimonial->role }}</small>
                                        </div>
                                    </div>
                                    <p class="mb-3">{{ $testimonial->comment }}</p>
                                    <div>
                                        {!! $testimonial->stars_html !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">
                                Menampilkan {{ $testimonials->firstItem() }} hingga {{ $testimonials->lastItem() }} dari
                                {{ $testimonials->total() }} testimoni
                            </span>
                            {{ $testimonials->links() }}
                        </div>
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
@endpush
