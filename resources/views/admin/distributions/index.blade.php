@extends('layouts.app')

@section('title', 'Manajemen Penyaluran Donasi')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Manajemen Penyaluran Donasi</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('distributions.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-circle"></i> Catat Penyaluran Baru
        </a>
    </div>
</div>

<!-- Filter Card -->
<div class="card mb-3">
    <div class="card-header">
        <h5 class="mb-0">Filter & Tampilan</h5>
    </div>
    <div class="card-body">
        <form id="filterForm" method="GET" action="{{ route('distributions.index') }}">
            <div class="row g-3 align-items-end">

                <!-- Filter Urutan -->
                <div class="col-md-3">
                    <label for="sort" class="form-label">Urutan Berdasarkan</label>
                    <select id="sort" name="sort" class="form-select form-select-sm">
                        <option value="nominal_desc" {{ request('sort') == 'nominal_desc' ? 'selected' : '' }}>Nominal Terbesar → Terkecil</option>
                        <option value="nominal_asc" {{ request('sort') == 'nominal_asc' ? 'selected' : '' }}>Nominal Terkecil → Terbesar</option>
                        <option value="tgl_desc" {{ request('sort') == 'tgl_desc' ? 'selected' : '' }}>Tanggal Terbaru</option>
                        <option value="tgl_asc" {{ request('sort') == 'tgl_asc' ? 'selected' : '' }}>Tanggal Terlama</option>
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
        @if($distributions->count() > 0)
            <!-- Tabel dengan Sticky Header -->
            <div class="table-responsive" style="max-height: 70vh; overflow-y: auto;">
                <table class="table table-striped table-hover table-sticky-header">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Penerima</th>
                            <th>Program</th>
                            <th>Nominal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($distributions as $distribution)
                        <tr>
                            <td>{{ $distribution->tanggal_penyaluran->format('d M Y') }}</td>
                            <td>{{ $distribution->beneficiary->nama }}</td>
                            <td>
                                <a href="{{ route('programs.show', $distribution->program->id) }}" class="text-decoration-none">
                                    {{ $distribution->program->nama_program }}
                                </a>
                            </td>
                            <td>Rp {{ number_format($distribution->nominal_disalurkan, 0, ',', '.') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('distributions.show', $distribution->id) }}" class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('distributions.edit', $distribution->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('distributions.destroy', $distribution->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data penyaluran ini?')">
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
        @else
            <div class="text-center py-4">
                <p>Belum ada data penyaluran donasi.</p>
                <a href="{{ route('distributions.create') }}" class="btn btn-primary">Catat Penyaluran Pertama</a>
            </div>
        @endif
    </div>
    <!-- Start Pagination -->
    <div class="card-footer d-flex justify-content-between align-items-center">
        <span class="text-muted">
            Menampilkan {{ $distributions->firstItem() }} hingga {{ $distributions->lastItem() }} dari {{ $distributions->total() }} data.
        </span>
        {{ $distributions->appends(request()->query())->links() }}

    </div>
    <!-- End Pagination -->
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