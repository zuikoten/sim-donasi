@extends('layouts.app')

@section('title', 'Laporan Penerimaan Per Rekening')

@push('styles')
    <style>
        .chart-container {
            position: relative;
            height: 350px;
            width: 100%;
        }

        .bank-card {
            transition: all 0.3s ease;
            border-left: 4px solid #0d6efd;
        }

        .bank-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .stat-card {
            border-radius: 12px;
            padding: 1.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            margin-bottom: 1rem;
        }

        .stat-card.success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }

        .stat-card.warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .stat-card.info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .program-mini-table {
            font-size: 0.9rem;
        }

        .program-mini-table td {
            padding: 0.5rem;
            border-bottom: 1px solid #eee;
        }
    </style>
@endpush

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="bi bi-bank me-2"></i>Laporan Penerimaan Per Rekening</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <!-- FORM EXPORT PDF -->
            <form action="{//{ route('reports.bank-accounts.pdf') }}" method="GET" target="_blank" class="me-2">
                <input type="hidden" name="time_filter" value="{{ request('time_filter') }}">
                <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                <input type="hidden" name="bank_account_id" value="{{ request('bank_account_id') }}">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <button type="submit" class="btn btn-sm btn-danger">
                    <i class="bi bi-file-earmark-pdf"></i> Ekspor PDF
                </button>
            </form>
            <!-- FORM EXPORT EXCEL -->
            <form action="{//{ route('reports.bank-accounts.excel') }}" method="GET" class="me-2">
                <input type="hidden" name="time_filter" value="{{ request('time_filter') }}">
                <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                <input type="hidden" name="bank_account_id" value="{{ request('bank_account_id') }}">
                <input type="hidden" name="status" value="{{ request('status') }}">
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
            <form method="GET" action="{{ route('reports.bank-accounts') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="bank_account_id" class="form-label">Rekening Bank</label>
                        <select name="bank_account_id" id="bank_account_id" class="form-select">
                            <option value="">Semua Rekening</option>
                            @foreach ($allBankAccounts as $bank)
                                <option value="{{ $bank->id }}" {{ $bankAccountId == $bank->id ? 'selected' : '' }}>
                                    {{ $bank->bank_name }} - {{ $bank->account_number }}
                                </option>
                            @endforeach
                        </select>
                    </div>

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

                    <div class="col-md-2" id="date-range-fields" style="display:none;">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="start_date" class="form-control"
                            value="{{ request('start_date') }}">
                    </div>

                    <div class="col-md-2" id="date-range-fields-end" style="display:none;">
                        <label for="end_date" class="form-label">Tanggal Selesai</label>
                        <input type="date" name="end_date" id="end_date" class="form-control"
                            value="{{ request('end_date') }}">
                    </div>

                    <div class="col-md-2">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="terverifikasi" {{ $status == 'terverifikasi' ? 'selected' : '' }}>Terverifikasi
                            </option>
                            <option value="menunggu" {{ $status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="" {{ $status == '' ? 'selected' : '' }}>Semua Status</option>
                        </select>
                    </div>

                    <div class="col-md-12 d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-funnel"></i> Terapkan Filter
                        </button>
                        <a href="{{ route('reports.bank-accounts') }}" class="btn btn-secondary ms-2">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0 opacity-75">Total Penerimaan</h6>
                        <h3 class="mb-0 mt-2">Rp {{ number_format($totalPenerimaan, 0, ',', '.') }}</h3>
                    </div>
                    <i class="bi bi-cash-stack" style="font-size: 2.5rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card success">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0 opacity-75">Jumlah Transaksi</h6>
                        <h3 class="mb-0 mt-2">{{ number_format($totalTransaksi) }}</h3>
                    </div>
                    <i class="bi bi-receipt" style="font-size: 2.5rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card warning">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0 opacity-75">Bank Aktif</h6>
                        <h3 class="mb-0 mt-2">{{ $totalBankAktif }} Rekening</h3>
                    </div>
                    <i class="bi bi-bank" style="font-size: 2.5rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card info">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0 opacity-75">Rata-rata/Transaksi</h6>
                        <h3 class="mb-0 mt-2">Rp {{ number_format($rataRataPerTransaksi, 0, ',', '.') }}</h3>
                    </div>
                    <i class="bi bi-graph-up" style="font-size: 2.5rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>

    @if ($topBank)
        <div class="alert alert-info border-0 shadow-sm mb-4">
            <i class="bi bi-trophy-fill me-2"></i>
            <strong>Bank Terbanyak:</strong> {{ $topBank->bank_name }} dengan total penerimaan
            <strong>Rp {{ number_format($topBank->donations_sum_nominal, 0, ',', '.') }}</strong>
        </div>
    @endif

    <!-- Chart Distribusi -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Distribusi Penerimaan Per Bank</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="bankPieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Ranking Bank</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="bankBarChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Per Bank -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Detail Per Rekening</h5>
        </div>
        <div class="card-body">
            @if ($bankBreakdown->count() > 0)
                <div class="accordion" id="bankAccordion">
                    @foreach ($bankBreakdown as $index => $item)
                        <div class="accordion-item bank-card mb-3">
                            <h2 class="accordion-header" id="heading{{ $index }}">
                                <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}"
                                    aria-expanded="{{ $index == 0 ? 'true' : 'false' }}">
                                    <div class="d-flex justify-content-between align-items-center w-100 me-3">
                                        <div>
                                            <h6 class="mb-0">
                                                @if ($item['bank']->is_cash)
                                                    💵 {{ $item['bank']->bank_name }}
                                                @else
                                                    🏦 {{ $item['bank']->bank_name }} -
                                                    {{ $item['bank']->account_number }}
                                                @endif
                                            </h6>
                                            <small class="text-muted">{{ $item['bank']->account_holder }}</small>
                                        </div>
                                        <div class="text-end">
                                            <h5 class="mb-0 text-primary">Rp
                                                {{ number_format($item['total'], 0, ',', '.') }}</h5>
                                            <small class="text-muted">{{ $item['count'] }} transaksi</small>
                                        </div>
                                    </div>
                                </button>
                            </h2>
                            <div id="collapse{{ $index }}"
                                class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}"
                                data-bs-parent="#bankAccordion">
                                <div class="accordion-body">
                                    <h6 class="mb-3">Breakdown Per Program:</h6>
                                    @if ($item['programs']->count() > 0)
                                        <table class="table table-sm program-mini-table">
                                            <thead>
                                                <tr>
                                                    <th>Program</th>
                                                    <th>Kategori</th>
                                                    <th class="text-end">Total</th>
                                                    <th class="text-center">Transaksi</th>
                                                    <th class="text-end">Rata-rata</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($item['programs'] as $program)
                                                    <tr>
                                                        <td>{{ $program['program_name'] }}</td>
                                                        <td><span
                                                                class="badge bg-secondary">{{ $program['program_kategori'] }}</span>
                                                        </td>
                                                        <td class="text-end fw-bold">Rp
                                                            {{ number_format($program['total'], 0, ',', '.') }}</td>
                                                        <td class="text-center">{{ $program['count'] }}</td>
                                                        <td class="text-end">Rp
                                                            {{ number_format($program['average'], 0, ',', '.') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <p class="text-muted text-center">Tidak ada data program.</p>
                                    @endif

                                    <div class="mt-3 p-3 bg-light rounded">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <small class="text-muted">Total Penerimaan:</small>
                                                <h6 class="mb-0">Rp {{ number_format($item['total'], 0, ',', '.') }}
                                                </h6>
                                            </div>
                                            <div class="col-md-4">
                                                <small class="text-muted">Jumlah Transaksi:</small>
                                                <h6 class="mb-0">{{ $item['count'] }}</h6>
                                            </div>
                                            <div class="col-md-4">
                                                <small class="text-muted">Rata-rata:</small>
                                                <h6 class="mb-0">Rp {{ number_format($item['average'], 0, ',', '.') }}
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-center text-muted">Tidak ada data penerimaan untuk filter yang dipilih.</p>
            @endif
        </div>
    </div>

    <!-- Tabel Detail Transaksi -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Detail Transaksi</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="perPage" class="form-label">Tampilkan</label>
                <select id="perPage" class="form-select form-select-sm" style="width: auto;">
                    <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                </select>
            </div>

            @if ($transactions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Donatur</th>
                                <th>Bank</th>
                                <th>Program</th>
                                <th class="text-end">Nominal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $donation)
                                <tr>
                                    <td>{{ $donation->created_at->format('d M Y') }}</td>
                                    <td>{{ $donation->user->name }}</td>
                                    <td>{{ $donation->bankAccount->bank_name }}</td>
                                    <td>{{ $donation->program->nama_program }}</td>
                                    <td class="text-end">Rp {{ number_format($donation->nominal, 0, ',', '.') }}</td>
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
                <p class="text-center text-muted">Tidak ada transaksi untuk ditampilkan.</p>
            @endif
        </div>
        <div class="card-footer d-flex justify-content-between align-items-center">
            <span class="text-muted">
                Menampilkan {{ $transactions->firstItem() }} hingga {{ $transactions->lastItem() }} dari
                {{ $transactions->total() }} data.
            </span>
            {{ $transactions->appends(request()->query())->links() }}
        </div>
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
        document.addEventListener('DOMContentLoaded', toggleDateRange);

        // Chart data
        const chartData = @json($chartData);

        // Pie Chart
        const pieCtx = document.getElementById('bankPieChart').getContext('2d');
        new Chart(pieCtx, {
            type: 'doughnut',
            data: {
                labels: chartData.map(item => item.label),
                datasets: [{
                    data: chartData.map(item => item.value),
                    backgroundColor: [
                        '#667eea', '#764ba2', '#f093fb', '#4facfe',
                        '#43e97b', '#fa709a', '#fee140', '#30cfd0'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return label + ': Rp ' + value.toLocaleString('id-ID') + ' (' + percentage +
                                    '%)';
                            }
                        }
                    }
                }
            }
        });

        // Bar Chart
        const barCtx = document.getElementById('bankBarChart').getContext('2d');
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: chartData.map(item => item.label),
                datasets: [{
                    label: 'Total Penerimaan',
                    data: chartData.map(item => item.value),
                    backgroundColor: 'rgba(102, 126, 234, 0.6)',
                    borderColor: 'rgba(102, 126, 234, 1)',
                    borderWidth: 2,
                    borderRadius: 6
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.parsed.x.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });

        // perPage change
        document.getElementById('perPage').addEventListener('change', function() {
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('perPage', this.value);
            window.location.href = currentUrl.toString();
        });
    </script>
@endpush
