@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Donasi</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp
                                {{ number_format($totalDonations, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-cash-stack fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Program Aktif</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPrograms }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-collection fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Penerima Manfaat</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalBeneficiaries }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Donasi Bulan Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                @if ($monthlyDonations->isNotEmpty())
                                    Rp {{ number_format($monthlyDonations->last()->total, 0, ',', '.') }}
                                @else
                                    Rp 0
                                @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Chart -->
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Grafik Donasi Bulanan</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="donationChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Programs -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Program Terpopuler</h6>
                <!-- Dropdown untuk memilih jumlah data -->
                <div class="d-flex align-items-center">
                    <label for="topProgramsPerPage" class="form-label me-2 mb-0 mb-0 text-sm">Tampilkan:</label>
                    <select id="topProgramsPerPage" class="form-select form-select-sm"
                        onchange="window.location.href=this.value">
                        <option value="{{ request()->fullUrlWithQuery(['topProgramsPerPage' => 5]) }}"
                            {{ request('topProgramsPerPage') == 5 ? 'selected' : '' }}>5</option>
                        <option value="{{ request()->fullUrlWithQuery(['topProgramsPerPage' => 10]) }}"
                            {{ request('topProgramsPerPage') == 10 ? 'selected' : '' }}>10</option>
                        <option value="{{ request()->fullUrlWithQuery(['topProgramsPerPage' => 25]) }}"
                            {{ request('topProgramsPerPage') == 25 ? 'selected' : '' }}>25</option>
                    </select>
                </div>
            </div>
            <div class="card-body">
                @if ($topPrograms->isNotEmpty())
                    @foreach ($topPrograms as $program)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0">{{ $program->nama_program }}</h6>
                                <span class="badge bg-primary">{{ $program->kategori }}</span>
                            </div>
                            <div class="progress mb-2">
                                <div class="progress-bar" role="progressbar"
                                    style="width: {{ $program->progress_percentage }}%"
                                    aria-valuenow="{{ $program->progress_percentage }}" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <small class="text-muted">Terkumpul</small>
                                <small class="text-muted">Target</small>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold text-primary">Rp
                                    {{ number_format($program->donations_sum_nominal, 0, ',', '.') }}</span>
                                <span class="text-muted">Rp {{ number_format($program->target_dana, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-center text-gray-500 mt-3">Belum ada data program.</p>
                @endif
            </div>
            <div class="card-footer text-end">
                {{ $topPrograms->links() }}
            </div>
        </div>
    </div>

    <!-- Donasi Terbaru  -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Donasi Terbaru</h6>
            <!-- Dropdown untuk memilih jumlah data -->
            <div class="d-flex align-items-center">
                <label for="recentPerPage" class="form-label me-2 mb-0 mb-0 text-sm">Tampilkan:</label>
                <select id="recentPerPage" class="form-select form-select-sm" onchange="window.location.href=this.value">
                    <option value="{{ request()->fullUrlWithQuery(['recentPerPage' => 5]) }}"
                        {{ request('recentPerPage') == 5 ? 'selected' : '' }}>5</option>
                    <option value="{{ request()->fullUrlWithQuery(['recentPerPage' => 10]) }}"
                        {{ request('recentPerPage') == 10 ? 'selected' : '' }}>10</option>
                    <option value="{{ request()->fullUrlWithQuery(['recentPerPage' => 25]) }}"
                        {{ request('recentPerPage') == 25 ? 'selected' : '' }}>25</option>
                </select>
            </div>
        </div>
        <div class="card-body">
            @if ($recentDonations->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Donatur</th>
                                <th>Program</th>
                                <th>Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentDonations as $donation)
                                <tr>
                                    <td>{{ $donation->created_at->format('d M Y') }}</td>
                                    <td>{{ $donation->user->display_name }}
                                    </td>
                                    <td>{{ $donation->program->nama_program }}</td>
                                    <td>Rp {{ number_format($donation->nominal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center text-gray-500 mt-3">Belum ada data donasi.</p>
            @endif
        </div>
        <div class="card-footer text-end">
            {{ $recentDonations->links() }}
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Chart.js
        const ctx = document.getElementById('donationChart').getContext('2d');
        const donationChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [
                    @foreach ($monthlyDonations as $donation)
                        '{{ $donation->month }}/{{ $donation->year }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Total Donasi',
                    data: [
                        @foreach ($monthlyDonations as $donation)
                            {{ $donation->total }},
                        @endforeach
                    ],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
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
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    </script>
@endpush
