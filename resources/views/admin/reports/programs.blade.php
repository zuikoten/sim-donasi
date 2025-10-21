@extends('layouts.app')

@section('title', 'Laporan Program')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Laporan Ringkasan Program</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('reports.programs.pdf') }}" class="btn btn-sm btn-danger me-2" target="_blank">
            <i class="bi bi-file-earmark-pdf"></i> Ekspor PDF
        </a>
        <a href="{{ route('reports.programs.excel') }}" class="btn btn-sm btn-success">
            <i class="bi bi-file-earmark-excel"></i> Ekspor Excel
        </a>
    </div>
</div>

<!-- Filter Card -->
<div class="card mb-3">
    <div class="card-header">
        <h5 class="mb-0">Filter & Tampilan</h5>
    </div>
    <div class="card-body">
        <form id="filterForm" method="GET" action="{{ route('reports.programs') }}">
            <div class="row g-3 align-items-end">
                <!-- Filter Kategori -->
                <div class="col-md-4">
                    <label for="kategori" class="form-label">Kategori Program</label>
                    <select id="kategori" name="kategori" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        <option value="Zakat" {{ request('kategori') == 'Zakat' ? 'selected' : '' }}>Zakat</option>
                        <option value="Infaq" {{ request('kategori') == 'Infaq' ? 'selected' : '' }}>Infaq</option>
                        <option value="Sedekah" {{ request('kategori') == 'Sedekah' ? 'selected' : '' }}>Sedekah</option>
                        <option value="Wakaf" {{ request('kategori') == 'Wakaf' ? 'selected' : '' }}>Wakaf</option>
                    </select>
                </div>

                <!-- Filter Urutan -->
                <div class="col-md-4">
                    <label for="sort" class="form-label">Urutkan Berdasarkan</label>
                    <select id="sort" name="sort" class="form-select form-select-sm">
                        <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Donasi Terbesar → Terkecil</option>
                        <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Donasi Terkecil → Terbesar</option>
                    </select>
                </div>

                <!-- Dropdown PerPage -->
                <div class="col-md-4">
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

<!-- Table Card -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Ringkasan Keuangan per Program</h5>
    </div>
    <div class="card-body p-0">
        @if($programs->count() > 0)
            <div class="table-responsive" style="max-height: 70vh; overflow-y: auto;">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light sticky-top" style="z-index: 2;">
                        <tr>
                            <th>Nama Program</th>
                            <th>Jumlah Donatur</th>
                            <th>Dana Masuk</th>
                            <th>Dana Keluar</th>
                            <th>Sisa Dana</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($programs as $program)
                        <tr>
                            <td>{{ $program->nama_program }}</td>
                            <td>{{ $program->donations_count }}</td>
                            <td>Rp {{ number_format($program->donations_sum_nominal, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($program->distributions_sum_nominal_disalurkan, 0, ',', '.') }}</td>
                            <td><strong>Rp {{ number_format($program->donations_sum_nominal - $program->distributions_sum_nominal_disalurkan, 0, ',', '.') }}</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center text-muted py-4 mb-0">Belum ada data program.</p>
        @endif
    </div>

    <!-- Pagination -->
    <div class="card-footer d-flex justify-content-between align-items-center">
        <small class="text-muted">
            Menampilkan {{ $programs->firstItem() }} hingga {{ $programs->lastItem() }} dari {{ $programs->total() }} data.
        </small>
        {{ $programs->appends(request()->query())->links() }}
    </div>
</div>
<!-- End Table Card -->
@endsection

@push('scripts')
<script>
    // Auto-submit saat dropdown filter berubah
    document.querySelectorAll('#kategori, #sort, #perPage').forEach(select => {
        select.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });
</script>
@endpush
