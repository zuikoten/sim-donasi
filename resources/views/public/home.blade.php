@extends('layouts.guest')

@section('title', 'Beranda')

@section('content')
<!-- Hero Section -->
<section class="bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold">Bersama Menebar Kebaikan</h1>
                <p class="lead my-4">Bergabunglah dalam program ZISWAF kami untuk membantu mereka yang membutuhkan. Setiap donasi Anda akan disalurkan secara transparan dan tepat sasaran.</p>
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
                        <h2 class="text-success">Rp {{ $programs->sum('dana_terkumpul') > 0 ? number_format($programs->sum('dana_terkumpul'), 0, ',', '.') : 0 }}</h2>
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
                        <h2 class="text-warning">{{ \App\Models\Donation::where('status', 'terverifikasi')->count() }}</h2>
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
        
        <!-- Category Filter -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex flex-wrap justify-content-center">
                    <button class="btn btn-outline-primary m-1 category-filter active" data-category="all">Semua</button>
                    <button class="btn btn-outline-primary m-1 category-filter" data-category="Zakat">Zakat</button>
                    <button class="btn btn-outline-primary m-1 category-filter" data-category="Infaq">Infaq</button>
                    <button class="btn btn-outline-primary m-1 category-filter" data-category="Sedekah">Sedekah</button>
                    <button class="btn btn-outline-primary m-1 category-filter" data-category="Wakaf">Wakaf</button>
                </div>
            </div>
        </div>
        
        <div class="row" id="programs-container">
            @forelse($programs as $program)
            <div class="col-lg-4 col-md-6 mb-4 program-item" data-category="{{ $program->kategori }}">
                <div class="card h-100">
                    <!-- Gambar Custom -->
                    @if($program->gambar)
                        <img src="{{ Storage::url($program->gambar) }}" class="card-img-top" alt="{{ $program->nama_program }}" style="height: 250px; object-fit: cover;">
                    @else
                        <img src="https://picsum.photos/seed/default/400/250.jpg" class="card-img-top" alt="{{ $program->nama_program }}">
                    @endif
                    <!-- gambar custom -->
                    
                    <div class="card-body d-flex flex-column">
                        <span class="badge bg-primary mb-2 align-self-start">{{ $program->kategori }}</span>
                        <h5 class="card-title">{{ $program->nama_program }}</h5>
                        <p class="card-text">{{ Str::limit($program->deskripsi, 100) }}</p>
                        
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Terkumpul</span>
                                <span>{{ $program->progress_percentage }}%</span>
                            </div>
                            <div class="progress mb-3">
                                <div class="progress-bar" role="progressbar" style="width: {{ $program->progress_percentage }}%" aria-valuenow="{{ $program->progress_percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span>Rp {{ number_format($program->dana_terkumpul, 0, ',', '.') }}</span>
                                <span>Rp {{ number_format($program->target_dana, 0, ',', '.') }}</span>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('program.detail', $program->id) }}" class="btn btn-outline-primary">Detail</a>
                                <a href="{{ route('login') }}" class="btn btn-primary">Donasi</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center">
                <p>Belum ada program donasi yang tersedia saat ini.</p>
            </div>
            @endforelse
        </div>
        
        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="{{ route('public.reports') }}" class="btn btn-lg btn-outline-primary">Lihat Laporan Transparansi</a>
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
        
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex mb-3">
                            <img src="https://picsum.photos/seed/person1/50/50.jpg" alt="Person" class="rounded-circle me-3">
                            <div>
                                <h6 class="mb-0">Ahmad Rizki</h6>
                                <small class="text-muted">Donatur</small>
                            </div>
                        </div>
                        <p>"Sistem donasi yang transparan dan mudah digunakan. Saya bisa melihat langsung bagaimana donasi saya disalurkan."</p>
                        <div class="text-warning">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex mb-3">
                            <img src="https://picsum.photos/seed/person2/50/50.jpg" alt="Person" class="rounded-circle me-3">
                            <div>
                                <h6 class="mb-0">Siti Nurhaliza</h6>
                                <small class="text-muted">Donatur</small>
                            </div>
                        </div>
                        <p>"Proses verifikasi donasi cepat dan admin sangat responsif. Saya merasa nyaman berdonasi melalui platform ini."</p>
                        <div class="text-warning">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex mb-3">
                            <img src="https://picsum.photos/seed/person3/50/50.jpg" alt="Person" class="rounded-circle me-3">
                            <div>
                                <h6 class="mb-0">Budi Santoso</h6>
                                <small class="text-muted">Donatur</small>
                            </div>
                        </div>
                        <p>"Laporan transparansi yang detail membuat saya yakin bahwa donasi saya benar-benar sampai kepada yang membutuhkan."</p>
                        <div class="text-warning">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

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
                    if (category === 'all' || item.getAttribute('data-category') === category) {
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