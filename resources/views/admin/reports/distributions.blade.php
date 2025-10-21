@extends('layouts.app')

@section('title', 'Laporan Penyaluran Dana')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Laporan Penyaluran Dana</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
         <!-- FORM EXPORT PDF -->
        <form action="{{ route('reports.programs.pdf') }}" method="GET" target="_blank" class="me-2">
            <input type="hidden" name="kategori" value="{{ request('kategori') }}">
            <input type="hidden" name="sort" value="{{ request('sort') }}">
            <input type="hidden" name="perPage" value="{{ request('perPage') }}">
            <button type="submit" class="btn btn-sm btn-danger">
                <i class="bi bi-file-earmark-pdf"></i> Ekspor PDF
            </button>
        </form>

        <!-- FORM EXPORT EXCEL -->
        <form action="{{ route('reports.programs.excel') }}" method="GET">
            <input type="hidden" name="kategori" value="{{ request('kategori') }}">
            <input type="hidden" name="sort" value="{{ request('sort') }}">
            <input type="hidden" name="perPage" value="{{ request('perPage') }}">
            <button type="submit" class="btn btn-sm btn-success">
                <i class="bi bi-file-earmark-excel"></i> Ekspor Excel
            </button>
        </form>
    </div>
</div>

<!-- Panel Filter -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Filter Laporan</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('reports.distributions') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="time_filter" class="form-label">Filter Waktu</label>
                    <select name="time_filter" id="time_filter" class="form-select" onchange="toggleDateRange()">
                        <option value="hari ini" {{ $timeFilter == 'hari ini' ? 'selected' : '' }}>Hari ini</option>
                        <option value="kemarin" {{ $timeFilter == 'kemarin' ? 'selected' : '' }}>Kemarin</option>
                        <option value="minggu ini" {{ $timeFilter == 'minggu ini' ? 'selected' : '' }}>Minggu ini</option>
                        <option value="minggu sebelumnya" {{ $timeFilter == 'minggu sebelumnya' ? 'selected' : '' }}>Minggu sebelumnya</option>
                        <option value="bulan ini" {{ $timeFilter == 'bulan ini' ? 'selected' : '' }}>Bulan ini</option>
                        <option value="bulan sebelumnya" {{ $timeFilter == 'bulan sebelumnya' ? 'selected' : '' }}>Bulan sebelumnya</option>
                        <option value="tahun ini" {{ $timeFilter == 'tahun ini' ? 'selected' : '' }}>Tahun ini</option>
                        <option value="tahun sebelumnya" {{ $timeFilter == 'tahun sebelumnya' ? 'selected' : '' }}>Tahun sebelumnya</option>
                        <option value="rentang tanggal" {{ $timeFilter == 'rentang tanggal' ? 'selected' : '' }}>Rentang Tanggal</option>
                    </select>
                </div>
                <div class="col-md-4" id="date-range-fields" style="display:none;">
                    <label for="start_date" class="form-label">Tanggal Mulai</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-4" id="date-range-fields-end" style="display:none;">
                    <label for="end_date" class="form-label">Tanggal Selesai</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-4">
                    <label for="program_id" class="form-label">Program Asal Dana</label>
                    <select name="program_id" id="program_id" class="form-select">
                        <option value="">Semua Program</option>
                        @foreach($programs as $program)
                            <option value="{{ $program->id }}" {{ request('program_id') == $program->id ? 'selected' : '' }}>{{ $program->nama_program }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12 d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-funnel"></i> Terapkan Filter
                    </button>
                    <a href="{{ route('reports.distributions') }}" class="btn btn-secondary ms-2">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Data Penyaluran -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Data Penyaluran</h5>
    </div>
    <div class="card-body">
        <!-- Dropdown untuk memilih jumlah data per halaman -->
        <div class="col-md-3">
            <label for="perPage" class="form-label">Tampilkan</label>
            <select id="perPage" class="form-select form-select-sm">
                <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100</option>
                <option value="500" {{ request('perPage') == 500 ? 'selected' : '' }}>500</option>
                <option value="999999" {{ request('perPage') == 999999 ? 'selected' : '' }}>Semua</option>
            </select>
        </div>
        <!-- Akhir Dropdown -->
        @if($distributions->count() > 0)
            <div class="table-responsive" style="max-height: 70vh; overflow-y: auto;">
                <table class="table table-striped table-hover table-sticky-header">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Penerima</th>
                            <th>Program Asal Dana</th>
                            <th>Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($distributions as $distribution)
                        <tr>
                            <td>{{ $distribution->tanggal_penyaluran->format('d M Y') }}</td>
                            <td>{{ $distribution->beneficiary->nama }}</td>
                            <td>{{ $distribution->program->nama_program }}</td>
                            <td>Rp {{ number_format($distribution->nominal_disalurkan, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center text-muted">Tidak ada data penyaluran yang sesuai dengan filter.</p>
        @endif
    </div>
    <div class="card-footer text-end">
        <h4>Total: Rp {{ number_format($totalDistributed, 0, ',', '.') }}</h4>
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

@push('scripts')
<script>
    function toggleDateRange() {
        const filter = document.getElementById('time_filter').value;
        const dateFields = document.getElementById('date-range-fields');
        const dateFieldsEnd = document.getElementById('date-range-fields-end');
        if (filter === 'rentang tanggal') {
            dateFields.style.display = 'block';
            dateFieldsEnd.style.display = 'block';
        } else {
            dateFields.style.display = 'none';
            dateFieldsEnd.style.display = 'none';
        }
    }
    document.addEventListener('DOMContentLoaded', toggleDateRange);

        document.getElementById('perPage').addEventListener('change', function() {
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('perPage', this.value); // ubah perPage
        window.location.href = currentUrl.toString(); // reload halaman dengan semua filter tetap
    });
</script>
@endpush