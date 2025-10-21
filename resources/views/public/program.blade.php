@extends('layouts.guest')

@section('title', $program->nama_program)

@section('content')
<!-- Program Header -->
<section class="bg-primary text-white py-5">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Program</a></li>
                <li class="breadcrumb-item active text-white">{{ $program->nama_program }}</li>
            </ol>
        </nav>
        <div class="row align-items-center">
            <div class="col-lg-8">
                <!-- Custom gambar -->
                <div class="mb-4">
                    @if($program->gambar)
                        <img src="{{ Storage::url($program->gambar) }}" 
                            class="img-fluid rounded" 
                            style="height: 30vh; width: 100%; object-fit: cover; object-position: center;" 
                            alt="{{ $program->nama_program }}">
                    @else
                        
                        <img src="https://picsum.photos/seed/{{ $program->id }}/1200/400.jpg" 
                            class="img-fluid rounded" 
                            style="height: 30vh; width: 100%; object-fit: cover; object-position: center;" 
                            alt="{{ $program->nama_program }}">
                    @endif
                </div>
                <!-- Custom gambar -->
                <h1 class="display-5 fw-bold">{{ $program->nama_program }}</h1>
                <p class="lead">{{ $program->deskripsi }}</p>
                <div class="d-flex align-items-center mt-3">
                    <span class="badge bg-light text-dark me-2">{{ $program->kategori }}</span>
                    <span class="badge bg-light text-dark me-2">
                        @if($program->status === 'aktif')
                            Aktif
                        @elseif($program->status === 'selesai')
                            Selesai
                        @else
                            Ditutup
                        @endif
                    </span>
                    <span class="badge bg-light text-dark">
                        <i class="bi bi-calendar"></i> {{ $program->tanggal_mulai->format('d M Y') }} - {{ $program->tanggal_selesai->format('d M Y') }}
                    </span>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Progress Donasi</h5>
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
                        <a href="{{ route('login') }}" class="btn btn-primary w-100">Donasi Sekarang</a>
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
                <div class="card mb-4">
                    <div class="card-body">
                        <h3 class="card-title">Tentang Program</h3>
                        <p>{{ $program->deskripsi }}</p>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-body">
                        <h3 class="card-title">Informasi Program</h3>
                        <table class="table">
                            <tr>
                                <td width="30%">Kategori</td>
                                <td>{{ $program->kategori }}</td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>
                                    @if($program->status === 'aktif')
                                        <span class="badge bg-success">Aktif</span>
                                    @elseif($program->status === 'selesai')
                                        <span class="badge bg-primary">Selesai</span>
                                    @else
                                        <span class="badge bg-danger">Ditutup</span>
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
                                <td>Rp {{ number_format($program->target_dana, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>Dana Terkumpul</td>
                                <td>Rp {{ number_format($program->dana_terkumpul, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Donasi Terbaru</h3>
                        @if($donations->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Donatur</th>
                                            <th>Nominal</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($donations as $donation)
                                        <tr>
                                            <td>{{ $donation->user->name }}</td>
                                            <td>Rp {{ number_format($donation->nominal, 0, ',', '.') }}</td>
                                            <td>{{ $donation->created_at->format('d M Y') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p>Belum ada donasi untuk program ini.</p>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card mb-4">
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
                        <a href="{{ route('login') }}" class="btn btn-primary w-100">Donasi Sekarang</a>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Rekening Donasi</h5>
                        <div class="mb-3">
                            <h6>Bank BCA</h6>
                            <p class="mb-0">No. Rekening: 1234567890</p>
                            <p class="mb-0">a.n. Yayasan Contoh</p>
                        </div>
                        <div class="mb-3">
                            <h6>Bank Mandiri</h6>
                            <p class="mb-0">No. Rekening: 0987654321</p>
                            <p class="mb-0">a.n. Yayasan Contoh</p>
                        </div>
                        <div class="alert alert-info">
                            <small>Setelah transfer, jangan lupa upload bukti transfer pada form donasi.</small>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Hubungi Kami</h5>
                        <p class="mb-2"><i class="bi bi-telephone"></i> (021) 1234567</p>
                        <p class="mb-2"><i class="bi bi-envelope"></i> info@donasi.local</p>
                        <p class="mb-0"><i class="bi bi-geo-alt"></i> Jl. Contoh No. 123, Jakarta</p>
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
                <h3>Program Lainnya</h3>
            </div>
        </div>
        
        <div class="row">
            @php
                $relatedPrograms = \App\Models\Program::where('id', '!=', $program->id)
                    ->where('status', 'aktif')
                    ->where('kategori', $program->kategori)
                    ->limit(3)
                    ->get();
                
                if($relatedPrograms->count() < 3) {
                    $relatedPrograms = \App\Models\Program::where('id', '!=', $program->id)
                        ->where('status', 'aktif')
                        ->limit(3)
                        ->get();
                }
            @endphp
            
            @forelse($relatedPrograms as $relatedProgram)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    <img src="https://picsum.photos/seed/{{ $relatedProgram->id }}/400/250.jpg" class="card-img-top" alt="{{ $relatedProgram->nama_program }}">
                    <div class="card-body d-flex flex-column">
                        <span class="badge bg-primary mb-2 align-self-start">{{ $relatedProgram->kategori }}</span>
                        <h5 class="card-title">{{ $relatedProgram->nama_program }}</h5>
                        <p class="card-text">{{ Str::limit($relatedProgram->deskripsi, 100) }}</p>
                        
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Terkumpul</span>
                                <span>{{ $relatedProgram->progress_percentage }}%</span>
                            </div>
                            <div class="progress mb-3">
                                <div class="progress-bar" role="progressbar" style="width: {{ $relatedProgram->progress_percentage }}%" aria-valuenow="{{ $relatedProgram->progress_percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span>Rp {{ number_format($relatedProgram->dana_terkumpul, 0, ',', '.') }}</span>
                                <span>Rp {{ number_format($relatedProgram->target_dana, 0, ',', '.') }}</span>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('program.detail', $relatedProgram->id) }}" class="btn btn-outline-primary">Detail</a>
                                <a href="{{ route('login') }}" class="btn btn-primary">Donasi</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <p>Belum ada program lain yang tersedia saat ini.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>
@endsection