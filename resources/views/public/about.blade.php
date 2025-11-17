@extends('layouts.guest')

@section('title', 'Tentang Kami')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section-about">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="hero-glass px-4 py-5">
                        <h1 class="hero-title">Tentang Yayasan Kami</h1>
                        <p class="hero-subtitle">
                            Bersama membangun masa depan yang lebih baik melalui kebaikan dan kepedulian yang tulus.
                        </p>

                        <div class="mt-4">
                            <a href="{{ route('home') }}" class="btn btn-light hero-btn me-2">
                                <i class="bi bi-search me-1"></i> Lihat Program
                            </a>
                            <a href="{{ route('contact') }}" class="btn btn-outline-light hero-btn">
                                <i class="bi bi-envelope me-1"></i> Hubungi Kami
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 text-center">
                    <img src="{{ asset('images/gedung-ptd.jpg') }}" alt="About Us" class="hero-img-about">
                </div>
            </div>
        </div>
    </section>


    <!-- Visi Misi Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <h2 class="fw-bold display-6">Misi & Visi Kami</h2>
                <p class="lead text-muted">Fondasi yang menjadi arah langkah kami dalam menebar manfaat.</p>
            </div>

            <div class="row g-4">
                <!-- Visi -->
                <div class="col-md-6">
                    <div class="card mission-vision-card p-4 text-center h-100">
                        <div class="icon-wrapper mb-4">
                            <i class="bi bi-lightbulb-fill"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Visi Kami</h4>
                        <p class="text-muted">
                            Terwujudnya masyarakat yang lebih peduli, berdaya, dan sejahtera melalui program ZISWAF
                            yang inovatif dan berdampak luas.
                        </p>
                    </div>
                </div>

                <!-- Misi -->
                <div class="col-md-6">
                    <div class="card mission-vision-card p-4 text-center h-100">
                        <div class="icon-wrapper mb-4">
                            <i class="bi bi-list-check"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Misi Kami</h4>
                        <p class="text-muted">
                            Menjadi jembatan kebaikan yang menghubungkan para dermawan dengan mereka yang membutuhkan,
                            dengan penyaluran yang tepat sasaran, transparan, dan berkelanjutan.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- Values Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <h2 class="fw-bold display-6">Nilai-Nilai Kami</h2>
                <p class="lead text-muted">Prinsip yang membentuk cara kami bekerja dan melayani.</p>
            </div>

            <div class="row g-4 justify-content-center">
                <!-- Value 1 -->
                <div class="col-md-4">
                    <div class="value-card text-center p-4 h-100">
                        <div class="icon-wrapper mb-4">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Amanah</h5>
                        <p class="text-muted">
                            Menjaga setiap amanah donatur dengan penuh tanggung jawab, transparansi, dan integritas.
                        </p>
                    </div>
                </div>

                <!-- Value 2 -->
                <div class="col-md-4">
                    <div class="value-card text-center p-4 h-100">
                        <div class="icon-wrapper mb-4">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Profesional</h5>
                        <p class="text-muted">
                            Mengelola setiap program dengan kualitas layanan terbaik untuk hasil yang maksimal.
                        </p>
                    </div>
                </div>

                <!-- Value 3 -->
                <div class="col-md-4">
                    <div class="value-card text-center p-4 h-100">
                        <div class="icon-wrapper mb-4">
                            <i class="bi bi-heart"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Empati</h5>
                        <p class="text-muted">
                            Bekerja dengan kepedulian mendalam terhadap kondisi dan harapan penerima manfaat.
                        </p>
                    </div>
                </div>
                <!-- Value 4 -->
                <div class="col-md-4">
                    <div class="value-card text-center p-4 h-100">
                        <div class="icon-wrapper mb-4">
                            <i class="bi bi-heart"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Empati</h5>
                        <p class="text-muted">
                            Bekerja dengan kepedulian mendalam terhadap kondisi dan harapan penerima manfaat.
                        </p>
                    </div>
                </div>
                <!-- Value 5 -->
                <div class="col-md-4">
                    <div class="value-card text-center p-4 h-100">
                        <div class="icon-wrapper mb-4">
                            <i class="bi bi-heart"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Empati</h5>
                        <p class="text-muted">
                            Bekerja dengan kepedulian mendalam terhadap kondisi dan harapan penerima manfaat.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Team Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="fw-bold">Tim Kami</h2>
                    <p class="lead text-muted">Orang-orang hebat di balik layanan kebaikan ini.</p>
                </div>
            </div>

            @if ($teams->count() > 0)
                <div class="row g-4 justify-content-center">
                    @foreach ($teams as $team)
                        <div class="col-md-4 col-lg-3">
                            <div class="card border-0 h-100 shadow-sm rounded-4 overflow-hidden team-card">
                                <div class="team-img-wrapper">
                                    <img src="{{ $team->photo_url }}" class="card-img-top" alt="{{ $team->name }}">
                                </div>

                                <div class="card-body text-center">
                                    <h5 class="fw-bold mb-1">{{ $team->name }}</h5>
                                    <p class="text-primary small mb-2">{{ $team->position }}</p>

                                    @if ($team->description)
                                        <p class="text-muted small">{{ $team->description }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="row">
                    <div class="col-12 text-center">
                        <p class="text-muted">Belum ada data tim yang tersedia.</p>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="py-5 cta-section text-white position-relative">
        <div class="cta-overlay"></div>

        <div class="container text-center position-relative">
            <h2 class="fw-bold mb-3">Bergabunglah Bersama Kami</h2>
            <p class="lead mb-4">
                Setiap kontribusi Anda, sekecil apa pun, dapat membawa perubahan besar bagi mereka yang membutuhkan.
            </p>
            <div class="mt-3">
                <a href="{{ route('home') }}" class="btn btn-light btn-lg me-2 rounded-pill px-4 shadow-sm">
                    <i class="bi bi-search"></i> Lihat Program
                </a>
                <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg rounded-pill px-4">
                    <i class="bi bi-envelope"></i> Hubungi Kami
                </a>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        /* HERO SECTION */
        .hero-section-about {
            background: linear-gradient(135deg, #0d6efd 0%, #1a73e8 50%, #0a58ca 100%);
            color: #fff;
            padding: 80px 0 100px;
            position: relative;
            overflow: hidden;
        }

        /* soft light blob (dekor) */
        .hero-section-about::before {
            content: "";
            position: absolute;
            top: -80px;
            left: -60px;
            width: 260px;
            height: 260px;
            background: rgba(255, 255, 255, 0.08);
            filter: blur(72px);
            border-radius: 50%;
        }

        /* glass panel for text */
        .hero-glass {
            background: rgba(255, 255, 255, 0.10);
            backdrop-filter: blur(10px);
            padding: 32px;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.14);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.18);
        }

        /* typography */
        .hero-title {
            font-size: 2.6rem;
            font-weight: 800;
            line-height: 1.12;
            margin-bottom: 12px;
        }

        .hero-subtitle {
            font-size: 1.05rem;
            opacity: 0.95;
        }

        /* buttons */
        .hero-btn {
            padding: 12px 26px;
            border-radius: 999px;
            font-weight: 700;
            box-shadow: 0 6px 18px rgba(255, 255, 255, 0.06);
        }

        .hero-btn i {
            transition: transform .25s;
        }

        .hero-btn:hover i {
            transform: translateX(4px);
        }

        /* hero image */
        .hero-img-about {
            width: 100%;
            height: auto;
            max-height: 380px;
            border-radius: 18px;
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.25);
            object-fit: cover;
            object-position: center;
            display: block;
        }

        /* responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 1.9rem;
            }

            .hero-glass {
                padding: 22px;
            }

            .hero-section-about {
                padding: 50px 0 70px;
            }
        }
    </style>

    <!-- Visi Misi Section-->
    <style>
        .mission-vision-card {
            background: rgba(255, 255, 255, 0.65);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 1.2rem;
            transition: all .3s ease;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        .mission-vision-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        }

        .icon-wrapper {
            width: 85px;
            height: 85px;
            margin: 0 auto;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0d6efd 0%, #20c997 100%);
            color: white;
            font-size: 2.5rem;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
        }
    </style>

    <!-- Values Section-->
    <style>
        .value-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(8px);
            border-radius: 1.2rem;
            border: 1px solid rgba(255, 255, 255, 0.4);
            transition: all .3s ease;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.06);
        }

        .value-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.12);
        }

        .icon-wrapper {
            width: 85px;
            height: 85px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0d6efd 0%, #20c997 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2.4rem;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }
    </style>

    <!-- Team Section -->
    <style>
        /* Wrapper gambar supaya responsif dan cropping proporsional */
        .team-img-wrapper {
            height: 280px;
            overflow: hidden;
        }

        .team-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Card hover effect */
        .team-card {
            transition: .3s ease;
            background: #fff;
        }

        .team-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }
    </style>
    <!-- Call To Action Section-->
    <style>
        .cta-section {
            background: linear-gradient(135deg, #0056b3, #007bff);
            border-radius: 0;
            overflow: hidden;
        }

        /* Overlay lembut */
        .cta-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.15);
        }

        .cta-section h2,
        .cta-section p {
            position: relative;
            z-index: 2;
        }

        /* Hover efek tombol */
        .cta-section .btn-light:hover {
            background: #f8f9fa;
            transform: translateY(-3px);
            box-shadow: 0 8px 18px rgba(255, 255, 255, 0.5);
        }

        .cta-section .btn-outline-light:hover {
            background: #ffffff;
            color: #0d6efd !important;
            transform: translateY(-3px);
            box-shadow: 0 8px 18px rgba(255, 255, 255, 0.5);
        }

        .cta-section .btn {
            transition: .3s ease;
        }
    </style>
@endpush
