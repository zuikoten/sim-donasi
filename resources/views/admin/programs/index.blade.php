@extends('layouts.app')

@section('title', 'Manajemen Program Donasi')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Manajemen Program Donasi</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('programs.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Program Baru
        </a>
    </div>
</div>
<!-- Filter Card -->
<div class="card mb-3">
    <div class="card-header">
        <h5 class="mb-0">Filter & Tampilan</h5>
    </div>
    <div class="card-body">
        <form id="filterForm" method="GET" action="{{ route('programs.index') }}">
            <div class="row g-3 align-items-end">

                <!-- Filter Urutan -->
                <div class="col-md-3">
                    <label for="sort" class="form-label">Urutan Berdasarkan</label>
                    <select id="sort" name="sort" class="form-select form-select-sm">
                        <option value="dana_desc" {{ request('sort') == 'dana_desc' ? 'selected' : '' }}>Dana Terkumpul Terbesar → Terkecil</option>
                        <option value="dana_asc" {{ request('sort') == 'dana_asc' ? 'selected' : '' }}>Dana Terkumpul Terkecil → Terbesar</option>
                        <option value="target_desc" {{ request('sort') == 'target_desc' ? 'selected' : '' }}>Target Dana Terbesar → Terkecil</option>
                        <option value="target_asc" {{ request('sort') == 'target_asc' ? 'selected' : '' }}>Target Dana Terkecil → Terbesar</option>
                    </select>
                </div>

                <!-- Dropdown PerPage -->
                <div class="col-md-3">
                    <label for="perPage" class="form-label">Tampilkan</label>
                    <select id="perPage" name="perPage" class="form-select form-select-sm">
                        <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100</option>
                        <option value="500" {{ request('perPage') == 500 ? 'selected' : '' }}>500</option>
                        <option value="999999" {{ request('perPage') == 999999 ? 'selected' : '' }}>Semua</option>
                    </select>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- End Filter Card -->

<div class="card">
    <div class="card-body">
        @if($programs->count() > 0)
            <!-- Tabel dengan Sticky Header -->
            <div class="table-responsive" style="max-height: 70vh; overflow-y: auto;">
                <table class="table table-striped table-hover table-sticky-header">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Program</th>
                            <th>Kategori</th>
                            <th>Target Dana</th>
                            <th>Terkumpul</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($programs as $key => $program)
                        <tr>
                            <td>{{ ($programs->currentPage() - 1) * $programs->perPage() + $key + 1 }}</td>
                            <td>{{ $program->nama_program }}</td>
                            <td><span class="badge bg-info">{{ $program->kategori }}</span></td>
                            <td>Rp {{ number_format($program->target_dana, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($program->dana_terkumpul, 0, ',', '.') }}</td>
                            <td>
                                @if($program->status === 'aktif')
                                    <span class="badge bg-success">Aktif</span>
                                @elseif($program->status === 'selesai')
                                    <span class="badge bg-primary">Selesai</span>
                                @else
                                    <span class="badge bg-danger">Ditutup</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('programs.show', $program->id) }}" class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('programs.edit', $program->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('programs.destroy', $program->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus program ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Informasi Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <span class="text-muted">
                    Menampilkan {{ $programs->firstItem() }} hingga {{ $programs->lastItem() }} dari {{ $programs->total() }} data.
                </span>
                {{ $programs->links() }}
            </div>
        @else
            <div class="text-center py-4">
                <p>Belum ada data program donasi.</p>
                <a href="{{ route('programs.create') }}" class="btn btn-primary">Tambah Program Pertama</a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    /* CSS untuk Sticky Header Tabel */
    .table-sticky-header thead th {
        position: sticky;
        top: 0;
        z-index: 10;
        background-color: #f8f9fa; /* Warna latar yang sama dengan .table-light */
    }
</style>
@endpush

@push('scripts')
<script>
   // Saat sort atau perPage berubah, kirim form filter
    document.querySelectorAll('#sort, #perPage').forEach(select => {
        select.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });
</script>
@endpush