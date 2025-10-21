@extends('layouts.app')

@section('title', 'Laporan Donasi Masuk')

@push('styles')
    <style>
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }

        .table-sticky-header thead th {
            position: sticky;
            top: 0;
            background-color: #f8f9fa;
            /* warna header biar tetap terlihat */
            z-index: 10;
            /* biar header tidak ketimpa isi tabel */
        }

        .table-responsive {
            max-height: 70vh;
            overflow-y: auto;
        }
    </style>
@endpush

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Laporan Donasi Masuk</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <!-- FORM EXPORT PDF -->
            <form action="{{ route('reports.donations.pdf') }}" method="GET" target="_blank" class="me-2">
                <input type="hidden" name="time_filter" value="{{ request('time_filter') }}">
                <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                <input type="hidden" name="program_id" value="{{ request('program_id') }}">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <input type="hidden" name="metode_pembayaran" value="{{ request('metode_pembayaran') }}">
                <button type="submit" class="btn btn-sm btn-danger">
                    <i class="bi bi-file-earmark-pdf"></i> Ekspor PDF
                </button>
            </form>
            <!-- FORM EXPORT EXCEL -->
            <form action="{{ route('reports.donations.excel') }}" method="GET" class="me-2">
                <input type="hidden" name="time_filter" value="{{ request('time_filter') }}">
                <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                <input type="hidden" name="program_id" value="{{ request('program_id') }}">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <input type="hidden" name="metode_pembayaran" value="{{ request('metode_pembayaran') }}">
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
            <form method="GET" action="{{ route('reports.donations') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="time_filter" class="form-label">Filter Waktu</label>
                        <select name="time_filter" id="time_filter" class="form-select" onchange="toggleDateRange()">
                            <option value="hari ini" {{ $timeFilter == 'hari ini' ? 'selected' : '' }}>Hari ini</option>
                            <option value="kemarin" {{ $timeFilter == 'kemarin' ? 'selected' : '' }}>Kemarin</option>
                            <option value="minggu ini" {{ $timeFilter == 'minggu ini' ? 'selected' : '' }}>Minggu ini
                            </option>
                            <option value="minggu sebelumnya" {{ $timeFilter == 'minggu sebelumnya' ? 'selected' : '' }}>
                                Minggu sebelumnya</option>
                            <option value="bulan ini" {{ $timeFilter == 'bulan ini' ? 'selected' : '' }}>Bulan ini</option>
                            <option value="bulan sebelumnya" {{ $timeFilter == 'bulan sebelumnya' ? 'selected' : '' }}>
                                Bulan sebelumnya</option>
                            <option value="tahun ini" {{ $timeFilter == 'tahun ini' ? 'selected' : '' }}>Tahun ini</option>
                            <option value="tahun sebelumnya" {{ $timeFilter == 'tahun sebelumnya' ? 'selected' : '' }}>
                                Tahun sebelumnya</option>
                            <option value="rentang tanggal" {{ $timeFilter == 'rentang tanggal' ? 'selected' : '' }}>
                                Rentang Tanggal</option>
                        </select>
                    </div>
                    <div class="col-md-3" id="date-range-fields" style="display:none;">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="start_date" class="form-control"
                            value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3" id="date-range-fields-end" style="display:none;">
                        <label for="end_date" class="form-label">Tanggal Selesai</label>
                        <input type="date" name="end_date" id="end_date" class="form-control"
                            value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="program_id" class="form-label">Program</label>
                        <select name="program_id" id="program_id" class="form-select">
                            <option value="">Semua Program</option>
                            @foreach ($programs as $program)
                                <option value="{{ $program->id }}"
                                    {{ request('program_id') == $program->id ? 'selected' : '' }}>
                                    {{ $program->nama_program }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="terverifikasi" {{ request('status') == 'terverifikasi' ? 'selected' : '' }}>
                                Terverifikasi</option>
                            <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu
                            </option>
                            <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
                        <select name="metode_pembayaran" id="metode_pembayaran" class="form-select">
                            <option value="">Semua Metode</option>
                            <option value="Uang Tunai"
                                {{ request('metode_pembayaran') == 'Uang Tunai' ? 'selected' : '' }}>Uang Tunai</option>
                            <option value="Transfer Bank"
                                {{ request('metode_pembayaran') == 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank
                            </option>
                            <option value="QRIS" {{ request('metode_pembayaran') == 'QRIS' ? 'selected' : '' }}>QRIS
                            </option>
                            <option value="E-Wallet" {{ request('metode_pembayaran') == 'E-Wallet' ? 'selected' : '' }}>
                                E-Wallet</option>
                        </select>
                    </div>
                    <div class="col-md-12 d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-funnel"></i> Terapkan Filter
                        </button>
                        <a href="{{ route('reports.donations') }}" class="btn btn-secondary ms-2">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Grafik Donasi -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Grafik Donasi</h5>
            <select name="chart_period" id="chart_period" class="form-select form-select-sm" style="width: auto;">
                <option value="---">--Pilih Periode--</option>
                <option value="hari">Per Hari</option>
                <option value="bulan">Per Bulan</option>
                <option value="tahun">Per Tahun</option>
            </select>
        </div>
        <div class="card-body">
            <div class="chart-container">
                <canvas id="donationChart"></canvas>
            </div>
        </div>
    </div>
    <!-- Tabel Data Donasi -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Data Donasi</h5>
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
            @if ($donations->count() > 0)
                <div class="table-responsive" style="max-height: 70vh; overflow-y: auto;">
                    <table class="table table-striped table-hover table-sticky-header">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Donatur</th>
                                <th>Program</th>
                                <th>Nominal</th>
                                <th>Metode</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($donations as $donation)
                                <tr>
                                    <td>{{ $donation->created_at->format('d M Y') }}</td>
                                    <td>{{ optional($donation->user->profile)->nama_lengkap ?? optional($donation->user)->name }}
                                    </td>
                                    <td>{{ $donation->program->nama_program }}</td>
                                    <td>Rp {{ number_format($donation->nominal, 0, ',', '.') }}</td>
                                    <td>{{ $donation->metode_pembayaran }}</td>
                                    <td>
                                        @if ($donation->status === 'terverifikasi')
                                            <span class="badge bg-success">Terverifikasi</span>
                                        @elseif($donation->status === 'menunggu')
                                            <span class="badge bg-warning">Menunggu</span>
                                        @else
                                            <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center text-muted">Tidak ada data donasi yang sesuai dengan filter.</p>
            @endif
        </div>
        <div class="card-footer text-end">
            <h4>Total: Rp {{ number_format($totalDonations, 0, ',', '.') }}</h4>
        </div>
        <!-- Start Pagination -->
        <div class="card-footer d-flex justify-content-between align-items-center">
            <span class="text-muted">
                Menampilkan {{ $donations->firstItem() }} hingga {{ $donations->lastItem() }} dari
                {{ $donations->total() }} data.
            </span>
            {{ $donations->appends(request()->query())->links() }}

        </div>
        <!-- End Pagination -->
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Toggle date range fields
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
        // Run on page load
        document.addEventListener('DOMContentLoaded', toggleDateRange);

        // <== BAGIAN JAVASCRIPT UNTUK CHART ==>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('donationChart').getContext('2d');
            const chartData = @json($chartData);
            const period = "{{ request('chart_period') ?? 'bulan' }}"; // ambil dari URL atau default 'bulan'

            // Tentukan tipe grafik
            const chartType = period === 'hari' ? 'line' : 'bar';

            // Buat warna gradient halus
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(54, 162, 235, 0.6)');
            gradient.addColorStop(1, 'rgba(54, 162, 235, 0.05)');

            // Dataset konfigurasi umum
            const dataset = {
                label: 'Total Donasi',
                data: chartData.map(item => item.value),
                backgroundColor: gradient,
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                pointRadius: period === 'hari' ? 4 : 0, // tampilkan titik hanya untuk grafik garis
                pointHoverRadius: 6,
                tension: period === 'hari' ? 0.3 : 0, // efek lengkung halus pada garis
                fill: period === 'hari', // area bawah garis diisi gradient lembut
                borderRadius: period === 'hari' ? 0 : 6, // borderRadius hanya untuk batang
            };

            // Inisialisasi Chart.js
            const donationChart = new Chart(ctx, {
                type: chartType,
                data: {
                    labels: chartData.map(item => item.label),
                    datasets: [dataset]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            },
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            }
                        },
                        x: {
                            ticks: {
                                color: '#555',
                                font: {
                                    size: 12
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            labels: {
                                color: '#333'
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    animation: {
                        duration: 1000,
                        easing: 'easeOutQuart'
                    }
                }
            });

            // Reload chart saat periode berubah
            document.getElementById('chart_period').addEventListener('change', function() {
                const period = this.value;
                const currentUrl = new URL(window.location);
                currentUrl.searchParams.set('chart_period', period);
                window.location.href = currentUrl.toString();
            });
        });
        // Reload Tabel saat Paginasi berubah
        document.getElementById('perPage').addEventListener('change', function() {
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('perPage', this.value); // ubah perPage
            window.location.href = currentUrl.toString(); // reload halaman dengan semua filter tetap
        });
    </script>
@endpush
