@extends('layouts.guest')

@section('title', 'Tentang Kami')

@section('content')
<!-- Hero Section -->
<section class="bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold">Tentang Yayasan Kami</h1>
                <p class="lead">Bersama membangun masa depan yang lebih baik melalui kebaikan dan kepedulian yang tulus.</p>
            </div>
            <div class="col-lg-6">
                <img src="https://picsum.photos/seed/about/600/400.jpg" alt="About Us" class="img-fluid rounded">
            </div>
        </div>
    </div>
</section>

<!-- Mission & Vision Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-bullseye display-4 text-primary"></i>
                        </div>
                        <h5 class="card-title">Misi Kami</h5>
                        <p class="card-text">Menjadi jembatan kebaikan yang menghubungkan para dermawan dengan mereka yang membutuhkan, dengan menyalurkan bantuan secara tepat sasaran, transparan, dan berkelanjutan untuk mewujudkan kesejahteraan bersama.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-eye display-4 text-success"></i>
                        </div>
                        <h5 class="card-title">Visi Kami</h5>
                        <p class="card-text">Terwujudnya masyarakat yang lebih peduli, berdaya, dan sejahtera melalui pemberdayaan program ZISWAF yang inovatif dan berdampak luas.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="fw-bold">Nilai-Nilai Kami</h2>
                <p class="lead">Prinsip-prinsip yang memandu setiap langkah kami.</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 bg-transparent">
                    <div class="card-body text-center">
                        <i class="bi bi-shield-check display-4 text-warning mb-3"></i>
                        <h5 class="card-title">Amanah</h5>
                        <p class="card-text">Kami menjaga setiap amanah donatur dengan penuh tanggung jawab dan transparansi.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 bg-transparent">
                    <div class="card-body text-center">
                        <i class="bi bi-graph-up display-4 text-info mb-3"></i>
                        <h5 class="card-title">Profesional</h5>
                        <p class="card-text">Kami mengelola program dengan standar profesionalisme yang tinggi untuk hasil yang maksimal.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 bg-transparent">
                    <div class="card-body text-center">
                        <i class="bi bi-heart display-4 text-danger mb-3"></i>
                        <h5 class="card-title">Empati</h5>
                        <p class="card-text">Kami bergerak dengan dasar kepedulian dan kepekaan terhadap penderitaan sesama.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="fw-bold">Tim Kami</h2>
                <p class="lead">Orang-orang hebat di balik layanan kebaikan ini.</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card">
                    <img src="https://picsum.photos/seed/person1/400/400.jpg" class="card-img-top" alt="Team Member">
                    <div class="card-body text-center">
                        <h5 class="card-title">Ahmad Fadli</h5>
                        <p class="card-text"><small class="text-muted">Ketua Yayasan</small></p>
                        <p class="card-text">Berpengalaman lebih dari 15 tahun di bidang filantropi dan pemberdayaan masyarakat.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <img src="https://picsum.photos/seed/person2/400/400.jpg" class="card-img-top" alt="Team Member">
                    <div class="card-body text-center">
                        <h5 class="card-title">Siti Nurhaliza</h5>
                        <p class="card-text"><small class="text-muted">Direktur Program</small></p>
                        <p class="card-text">Ahli dalam merancang dan mengelola program pemberdayaan yang inovatif dan berkelanjutan.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <img src="https://picsum.photos/seed/person3/400/400.jpg" class="card-img-top" alt="Team Member">
                    <div class="card-body text-center">
                        <h5 class="card-title">Budi Santoso</h5>
                        <p class="card-text"><small class="text-muted">Direktur Keuangan</small></p>
                        <p class="card-text">Bertanggung jawab atas transparansi dan akuntabilitas keuangan yayasan.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="fw-bold">Bergabunglah Bersama Kami</h2>
        <p class="lead">Setiap kontribusi Anda, sekecil apa pun, memiliki dampak yang besar bagi mereka yang membutuhkan.</p>
        <div class="mt-4">
            <a href="{{ route('home') }}" class="btn btn-light btn-lg me-2">
                <i class="bi bi-search"></i> Lihat Program
            </a>
            <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg">
                <i class="bi bi-envelope"></i> Hubungi Kami
            </a>
        </div>
    </div>
</section>
@endsection