@extends('layouts.guest')

@section('title', $program->nama_program)

@push('styles')
    <style>
        /* Modern Program Header */
        .program-header {
            background: linear-gradient(135deg, #0d6efd 0%, #2034c9 100%);
            position: relative;
            overflow: hidden;
        }

        .program-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .modern-breadcrumb {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 0.75rem 1.25rem;
            border-radius: 50px;
            display: inline-flex;
            margin-bottom: 2rem;
        }

        .modern-breadcrumb .breadcrumb {
            margin: 0;
            background: transparent;
            padding: 0;
        }

        .modern-breadcrumb .breadcrumb-item+.breadcrumb-item::before {
            color: rgba(255, 255, 255, 0.7);
        }

        .program-image-wrapper {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            margin-bottom: 2rem;
        }

        .program-image-wrapper::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 100px;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.5), transparent);
        }

        .modern-badge {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            backdrop-filter: blur(10px);
            background: rgba(248, 152, 8, 0.95);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Modern Progress Card */
        .progress-card {
            border: none;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            background: white;
            position: relative;
        }

        .progress-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        }

        .progress-card .card-body {
            padding: 2rem;
        }

        .modern-progress {
            height: 14px;
            border-radius: 50px;
            background: #f1f3f5;
            overflow: hidden;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.06);
        }

        .modern-progress .progress-bar {
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            border-radius: 50px;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.4);
        }

        .amount-display {
            font-size: 1.25rem;
            font-weight: 700;
            color: #667eea;
        }

        /* Modern Cards */
        .modern-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: all 0.3s ease;
            background: white;
        }

        .modern-card:hover {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            transform: translateY(-4px);
        }

        .modern-card .card-body {
            padding: 2rem;
        }

        .modern-card .card-title {
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }

        .modern-card .card-title::before {
            content: '';
            width: 4px;
            height: 24px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 4px;
            margin-right: 12px;
        }

        /* Modern Table */
        .modern-table {
            border: none;
        }

        .modern-table tr {
            border-bottom: 1px solid #f1f3f5;
            transition: background 0.2s ease;
        }

        .modern-table tr:hover {
            background: #f8f9fa;
        }

        .modern-table td {
            padding: 1rem;
            border: none;
        }

        .modern-table td:first-child {
            font-weight: 600;
            color: #64748b;
        }

        /* Modern Badges */
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.875rem;
        }

        /* Contact Info Box */
        .contact-item {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 1rem;
            transition: all 0.3s ease;
        }

        .contact-item:hover {
            background: white;
            border-color: #667eea;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.1);
        }

        .contact-item a {
            color: #2d3748;
            transition: color 0.3s ease;
        }

        .contact-item:hover a {
            color: #667eea;
        }

        .contact-item .bi {
            color: #667eea;
        }

        /* Modern Buttons */
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

        .btn-modern-outline {
            border: 2px solid #667eea;
            color: #667eea;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-modern-outline:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        /* Related Program Cards */
        .program-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            height: 100%;
        }

        .program-card:hover {
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
            transform: translateY(-8px);
        }

        .program-card img {
            transition: transform 0.5s ease;
        }

        .program-card:hover img {
            transform: scale(1.05);
        }

        .program-card .card-img-top {
            height: 250px;
            object-fit: cover;
        }

        .program-card .badge {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
        }

        /* Alert Modern */
        .modern-alert {
            border: none;
            border-radius: 14px;
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-left: 4px solid #2196f3;
            padding: 1rem 1.25rem;
            box-shadow: 0 4px 12px rgba(33, 150, 243, 0.15);
        }

        /* Ordered List Modern */
        .modern-card ol {
            counter-reset: item;
            list-style: none;
            padding-left: 0;
        }

        .modern-card ol li {
            counter-increment: item;
            margin-bottom: 1rem;
            padding-left: 3rem;
            position: relative;
        }

        .modern-card ol li::before {
            content: counter(item);
            position: absolute;
            left: 0;
            top: 0;
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.875rem;
        }

        /* Section Spacing */
        section {
            position: relative;
        }

        .bg-light {
            background: #f8f9fa !important;
        }
    </style>
@endpush

@section('content')
    <!-- Program Header -->
    <section class="program-header text-white py-5 position-relative">
        <div class="container position-relative" style="z-index: 1;">
            <nav aria-label="breadcrumb">
                <div class="modern-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Program</a></li>
                        <li class="breadcrumb-item active text-white">{{ $program->nama_program }}</li>
                    </ol>
                </div>
            </nav>
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <!-- Custom gambar -->
                    <div class="program-image-wrapper">
                        @if ($program->gambar)
                            <img src="{{ Storage::url($program->gambar) }}" class="img-fluid"
                                style="height: 30vh; width: 100%; object-fit: cover; object-position: center;"
                                alt="{{ $program->nama_program }}">
                        @else
                            <img src="https://picsum.photos/seed/{{ $program->id }}/1200/400.jpg" class="img-fluid"
                                style="height: 30vh; width: 100%; object-fit: cover; object-position: center;"
                                alt="{{ $program->nama_program }}">
                        @endif
                    </div>
                    <h1 class="display-5 fw-bold mb-3">{{ $program->nama_program }}</h1>
                    <p class="lead mb-4">{{ $program->deskripsi }}</p>
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <span class="modern-badge">
                            <i class="bi bi-folder me-2"></i>{{ $program->kategori }}
                        </span>
                        <span class="modern-badge">
                            <i class="bi bi-circle-fill me-2"
                                style="font-size: 0.5rem; color: 
                                @if ($program->status === 'aktif') #10b981
                                @elseif($program->status === 'selesai') #667eea
                                @else #ef4444 @endif;"></i>
                            @if ($program->status === 'aktif')
                                Aktif
                            @elseif($program->status === 'selesai')
                                Selesai
                            @else
                                Ditutup
                            @endif
                        </span>
                        <span class="modern-badge">
                            <i class="bi bi-calendar me-2"></i>{{ $program->tanggal_mulai->format('d M Y') }} -
                            {{ $program->tanggal_selesai->format('d M Y') }}
                        </span>
                    </div>
                </div>
                <div class="col-lg-4 mt-4 mt-lg-0">
                    <div class="progress-card card">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Progress Donasi</h5>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Terkumpul</span>
                                <span class="fw-bold" style="color: #667eea;">{{ $program->progress_percentage }}%</span>
                            </div>
                            <div class="progress modern-progress mb-4">
                                <div class="progress-bar" role="progressbar"
                                    style="width: {{ $program->progress_percentage }}%"
                                    aria-valuenow="{{ $program->progress_percentage }}" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex justify-content-between mb-4">
                                <div>
                                    <small class="text-muted d-block">Terkumpul</small>
                                    <span class="amount-display">Rp
                                        {{ number_format($totalDonasiProgram, 0, ',', '.') }}</span>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted d-block">Target</small>
                                    <span class="fw-bold">Rp {{ number_format($program->target_dana, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            <a href="{{ route('login') }}" class="btn btn-modern-primary w-100">
                                <i class="bi bi-heart-fill me-2"></i>Donasi Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Program Details -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="modern-card mb-4">
                        <div class="card-body">
                            <h3 class="card-title">Tentang Program</h3>
                            <p class="text-muted">{{ $program->deskripsi }}</p>
                        </div>
                    </div>

                    <div class="modern-card mb-4">
                        <div class="card-body">
                            <h3 class="card-title">Informasi Program</h3>
                            <table class="table modern-table">
                                <tr>
                                    <td width="30%">Kategori</td>
                                    <td>{{ $program->kategori }}</td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td>
                                        @if ($program->status === 'aktif')
                                            <span class="status-badge bg-success text-white">
                                                <i class="bi bi-check-circle me-1"></i>Aktif
                                            </span>
                                        @elseif($program->status === 'selesai')
                                            <span class="status-badge bg-primary text-white">
                                                <i class="bi bi-flag-fill me-1"></i>Selesai
                                            </span>
                                        @else
                                            <span class="status-badge bg-danger text-white">
                                                <i class="bi bi-x-circle me-1"></i>Ditutup
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tanggal Mulai</td>
                                    <td>{{ $program->tanggal_mulai->format('d F Y') }}</td>
                                </tr>
                                <tr>
                                    <td>Tanggal Selesai</td>
                                    <td>{{ $program->tanggal_selesai->format('d F Y') }}</td>
                                </tr>
                                <tr>
                                    <td>Target Dana</td>
                                    <td class="fw-bold">Rp {{ number_format($program->target_dana, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Dana Terkumpul</td>
                                    <td class="fw-bold text-success">Rp
                                        {{ number_format($totalDonasiProgram, 0, ',', '.') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="modern-card">
                        <div class="card-body">
                            <h3 class="card-title">Donasi Terbaru</h3>
                            @if ($donations->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table modern-table">
                                        <thead>
                                            <tr>
                                                <th>Donatur</th>
                                                <th>Nominal</th>
                                                <th>Tanggal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($donations as $donation)
                                                <tr>
                                                    <td>
                                                        <i class="bi bi-person-circle me-2 text-muted"></i>
                                                        {{ $donation->user->name }}
                                                    </td>
                                                    <td class="fw-bold text-success">Rp
                                                        {{ number_format($donation->nominal, 0, ',', '.') }}</td>
                                                    <td class="text-muted">{{ $donation->created_at->format('d M Y') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="bi bi-inbox display-4 text-muted mb-3"></i>
                                    <p class="text-muted">Belum ada donasi untuk program ini.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="modern-card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Cara Berdonasi</h5>
                            <ol>
                                <li>Login atau daftar akun terlebih dahulu</li>
                                <li>Klik tombol "Donasi Sekarang"</li>
                                <li>Isi form donasi dengan nominal yang diinginkan</li>
                                <li>Transfer ke rekening yang tersedia</li>
                                <li>Upload bukti transfer</li>
                                <li>Tunggu verifikasi dari admin</li>
                            </ol>
                            <a href="{{ route('login') }}" class="btn btn-modern-primary w-100 mt-3">
                                <i class="bi bi-heart-fill me-2"></i>Donasi Sekarang
                            </a>
                        </div>
                    </div>

                    <div class="modern-card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Rekening Donasi</h5>
                            <x-bank-list :bankAccounts="$bankAccounts" />
                            <div class="modern-alert mt-3">
                                <i class="bi bi-info-circle me-2"></i>
                                <small>Setelah transfer, jangan lupa upload bukti transfer pada form donasi.</small>
                            </div>
                        </div>
                    </div>

                    <div class="modern-card">
                        <div class="card-body">
                            <h5 class="card-title">Hubungi Kami</h5>

                            {{-- TELEPON --}}
                            <div class="contact-item mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-telephone-fill fs-5 me-3 mt-1"></i>
                                    <div class="flex-grow-1">
                                        @if (isset($phones) && !empty($phones))
                                            @foreach ($phones as $index => $ph)
                                                <div class="pb-2 mb-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                                                    <a href="tel:+{{ $ph['raw'] }}"
                                                        class="text-decoration-none d-block
                           {{ $ph['is_primary'] ? 'fw-bold' : '' }}">
                                                        {{ $ph['formatted'] }}
                                                        @if ($ph['is_primary'])
                                                            <i class="bi bi-star-fill text-warning ms-1"></i>
                                                        @endif
                                                    </a>
                                                </div>
                                            @endforeach
                                        @elseif(isset($settings['office_phone']))
                                            @php
                                                $phoneSetting = App\Models\Setting::where(
                                                    'key',
                                                    'office_phone',
                                                )->first();
                                            @endphp
                                            <a href="tel:+{{ $settings['office_phone'] }}" class="text-decoration-none">
                                                {{ $phoneSetting ? $phoneSetting->formatted_office_phone : $settings['office_phone'] }}
                                            </a>
                                        @else
                                            <span class="text-muted fst-italic">Telepon belum diatur</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- EMAIL --}}
                            <div class="contact-item mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-envelope-fill fs-5 me-3 mt-1"></i>
                                    <div class="flex-grow-1">
                                        @if (isset($emails) && !empty($emails))
                                            @foreach ($emails as $em)
                                                <div class="pb-2 mb-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                                                    <a href="mailto:{{ $em['value'] }}"
                                                        class="text-decoration-none d-block
                           {{ $em['is_primary'] ? 'fw-bold' : '' }}">
                                                        {{ $em['value'] }}
                                                        @if ($em['is_primary'])
                                                            <i class="bi bi-star-fill text-warning ms-1"></i>
                                                        @endif
                                                    </a>
                                                </div>
                                            @endforeach
                                        @elseif(isset($settings['office_email']))
                                            <a href="mailto:{{ $settings['office_email'] }}"
                                                class="text-decoration-none">
                                                {{ $settings['office_email'] }}
                                            </a>
                                        @else
                                            <span class="text-muted fst-italic">Email belum diatur</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- ALAMAT --}}
                            <div class="contact-item">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-geo-alt-fill fs-5 me-3 mt-1"></i>
                                    <div class="flex-grow-1">
                                        @if (isset($addresses) && !empty($addresses))
                                            @foreach ($addresses as $addr)
                                                <div class="pb-2 mb-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                                                    <span class="{{ $addr['is_primary'] ? 'fw-bold' : '' }}">
                                                        {{ $addr['value'] }}
                                                    </span>
                                                    @if ($addr['is_primary'])
                                                        <i class="bi bi-star-fill text-warning ms-1"></i>
                                                    @endif
                                                </div>
                                            @endforeach
                                        @elseif(isset($settings['office_address']))
                                            {{ $settings['office_address'] }}
                                        @else
                                            <span class="text-muted fst-italic">Alamat belum diatur</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Programs -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12 mb-4">
                    <h3 class="fw-bold">Program Lainnya</h3>
                    <p class="text-muted">Jelajahi program donasi lainnya yang membutuhkan bantuan Anda</p>
                </div>
            </div>

            <div class="row">
                @php
                    $relatedPrograms = \App\Models\Program::where('id', '!=', $program->id)
                        ->where('status', 'aktif')
                        ->where('kategori', $program->kategori)
                        ->limit(3)
                        ->get();

                    if ($relatedPrograms->count() < 3) {
                        $relatedPrograms = \App\Models\Program::where('id', '!=', $program->id)
                            ->where('status', 'aktif')
                            ->limit(3)
                            ->get();
                    }
                @endphp

                @forelse($relatedPrograms as $relatedProgram)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card program-card">
                            <div class="overflow-hidden">
                                <img src="https://picsum.photos/seed/{{ $relatedProgram->id }}/400/250.jpg"
                                    class="card-img-top" alt="{{ $relatedProgram->nama_program }}">
                            </div>
                            <div class="card-body d-flex flex-column">
                                <span class="badge bg-primary mb-2 align-self-start">
                                    {{ $relatedProgram->kategori }}
                                </span>
                                <h5 class="card-title fw-bold">{{ $relatedProgram->nama_program }}</h5>
                                <p class="card-text text-muted">{{ Str::limit($relatedProgram->deskripsi, 100) }}</p>

                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted small">Terkumpul</span>
                                        <span class="fw-bold small"
                                            style="color: #667eea;">{{ $relatedProgram->progress_percentage }}%</span>
                                    </div>
                                    <div class="progress modern-progress mb-3" style="height: 8px;">
                                        <div class="progress-bar" role="progressbar"
                                            style="width: {{ $relatedProgram->progress_percentage }}%"
                                            aria-valuenow="{{ $relatedProgram->progress_percentage }}" aria-valuemin="0"
                                            aria-valuemax="100"></div>
                                    </div>
                                    <div class="d-flex justify-content-between mb-3">
                                        <div>
                                            <small class="text-muted d-block">Terkumpul</small>
                                            <span class="fw-bold small">Rp
                                                {{ number_format($relatedProgram->dana_terkumpul, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="text-end">
                                            <small class="text-muted d-block">Target</small>
                                            <span class="small">Rp
                                                {{ number_format($relatedProgram->target_dana, 0, ',', '.') }}</span>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <a href="{{ route('program.detail', $relatedProgram->id) }}"
                                            class="btn btn-modern-outline flex-grow-1">
                                            <i class="bi bi-eye me-1"></i>Detail
                                        </a>
                                        <a href="{{ route('login') }}" class="btn btn-modern-primary flex-grow-1">
                                            <i class="bi bi-heart-fill me-1"></i>Donasi
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="bi bi-inbox display-4 text-muted mb-3"></i>
                            <p class="text-muted">Belum ada program lain yang tersedia saat ini.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
