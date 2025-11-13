@extends('layouts.app')

@section('title', 'Detail Program Donasi')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Detail Program: {{ $program->nama_program }}</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('programs.index') }}" class="btn btn-sm btn-secondary me-2">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('programs.edit', $program->id) }}" class="btn btn-sm btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Informasi Program</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="30%"><strong>Nama Program</strong></td>
                            <td>{{ $program->nama_program }}</td>
                        </tr>
                        <tr>
                            <td><strong>Kategori</strong></td>
                            <td><span class="badge bg-info">{{ $program->kategori }}</span></td>
                        </tr>
                        <tr>
                            <td><strong>Status</strong></td>
                            <td>
                                @if ($program->status === 'aktif')
                                    <span class="badge bg-success">Aktif</span>
                                @elseif($program->status === 'selesai')
                                    <span class="badge bg-primary">Selesai</span>
                                @else
                                    <span class="badge bg-danger">Ditutup</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Deskripsi</strong></td>
                            <td>{{ $program->deskripsi }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Mulai</strong></td>
                            <td>{{ $program->tanggal_mulai->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Selesai</strong></td>
                            <td>{{ $program->tanggal_selesai->format('d F Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Progress Dana</h5>
                </div>
                <div class="card-body">
                    <h4>Terkumpul Rp <span
                            style="font-weight: bolder; color:#0d6efd">{{ number_format($totalDonasiProgram, 0, ',', '.') }}</span>
                    </h4>
                    <h6 class="text-muted">dari target Rp
                        <span>{{ number_format($program->target_dana, 0, ',', '.') }}</span>
                    </h6>
                    <div class="progress mb-2">
                        <div class="progress-bar" role="progressbar" style="width: {{ $program->progress_percentage }}%;"
                            aria-valuenow="{{ $program->progress_percentage }}" aria-valuemin="0" aria-valuemax="100">
                            {{ $program->progress_percentage }}%</div>
                    </div>
                    <hr>
                    <h4>Sisa Dana <span style="font-weight: bolder; color:#29b011">Rp
                            {{ number_format($totalDonasiProgram - $totalDistribusiProgram, 0, ',', '.') }}</span>
                    </h4>
                    <h6>Tersalurkan Rp {{ number_format($totalDistribusiProgram, 0, ',', '.') }}</h6>
                </div>
            </div>
        </div>
    </div>
@endsection
