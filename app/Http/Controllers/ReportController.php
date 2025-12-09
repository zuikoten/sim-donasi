<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Program;
use App\Models\Distribution;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DonationsExport;
use App\Exports\DistributionsExport;
use App\Exports\ProgramsExport;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:superadmin,admin');
    }

    public function donations(Request $request)
    {
        $query = Donation::with(['user', 'program']);

        // Filter Waktu
        $timeFilter = $request->input('time_filter', 'hari ini'); // Default 'hari ini'
        $query = $this->applyTimeFilter($query, $timeFilter, 'created_at', $request);

        // Filter lainnya
        if ($request->filled('status') && $request->input('status') != '') {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('metode_pembayaran') && $request->input('metode_pembayaran') != '') {
            $query->where('metode_pembayaran', $request->input('metode_pembayaran'));
        }

        if ($request->filled('program_id') && $request->input('program_id') != '') {
            $query->where('program_id', $request->input('program_id'));
        }

        // Ambil nilai perPage dari request, default 25
        $perPage = $request->input('perPage', 25);

        // Clone query sebelum diubah untuk grafik
        $baseQuery = clone $query;

        // Jalankan query dengan pagination
        $donations = $query->orderBy('created_at', 'desc')->paginate($perPage);

        // Total donasi hanya berdasarkan filter yang aktif
        $totalDonations = (clone $baseQuery)->sum('nominal');

        // Data untuk dropdown dan grafik
        $programs = Program::all();
        $chartData = $this->getChartData($baseQuery, $request->input('chart_period', 'bulan'), $timeFilter);

        return view('admin.reports.donations', compact(
            'donations',
            'programs',
            'totalDonations',
            'timeFilter',
            'chartData',
            'perPage'
        ));
    }

    public function distributions(Request $request)
    {
        $query = Distribution::with(['beneficiary', 'program']);

        $timeFilter = $request->input('time_filter', 'hari ini');
        $query = $this->applyTimeFilter($query, $timeFilter, 'tanggal_penyaluran', $request);

        if ($request->filled('program_id') && $request->input('program_id') != '') {
            $query->where('program_id', $request->input('program_id'));
        }

        // Ambil nilai perPage dari request, default 25
        $perPage = $request->input('perPage', 25);

        //Clone Query
        $baseQuery = clone $query;

        // Jalankan query dengan pagination
        $distributions = $query->orderBy('tanggal_penyaluran', 'desc')->paginate($perPage)->withQueryString();

        // total seluruh nilai distribusi hasil filter
        $totalDistributed = (clone $baseQuery)->sum('nominal_disalurkan');

        $programs = Program::all();


        return view('admin.reports.distributions', compact(
            'distributions',
            'programs',
            'totalDistributed',
            'timeFilter'
        ));
    }

    public function programs(Request $request)
    {
        // Ambil nilai perPage dari request, default 25
        $perPage = $request->input('perPage', 25);

        // Ambil filter kategori dan urutan
        $kategori = $request->input('kategori');
        $sort = $request->input('sort', 'desc'); // default: terbesar dulu

        // Query awal dengan relasi dan agregat
        $programs = Program::withCount(['donations' => function ($query) {
            $query->where('status', 'terverifikasi');
        }])
            ->withSum(['donations' => function ($query) {
                $query->where('status', 'terverifikasi');
            }], 'nominal')
            ->withSum('distributions', 'nominal_disalurkan');

        // Filter kategori (Zakat, Infaq, Sedekah, Wakaf)
        if (!empty($kategori)) {
            $programs->where('kategori', $kategori);
        }

        // Sorting berdasarkan total donasi (besar ke kecil / kecil ke besar)
        if ($sort === 'asc') {
            $programs->orderBy('donations_sum_nominal', 'asc');
        } else {
            $programs->orderBy('donations_sum_nominal', 'desc');
        }

        // Paginasi
        $programs = $programs->paginate($perPage);

        return view('admin.reports.programs', compact('programs', 'kategori', 'sort', 'perPage'));
    }





    /**
     * Helper untuk menerapkan filter waktu
     */
    private function applyTimeFilter($query, $filter, $dateColumn, Request $request)
    {
        $now = Carbon::now();

        $now = Carbon::now()->copy(); // biar subDay(), subWeek() dsb tidak ubah $now asli

        switch ($filter) {
            case 'hari ini':
                $query->whereDate($dateColumn, $now->toDateString());
                break;
            case 'kemarin':
                $query->whereDate($dateColumn, $now->subDay()->toDateString());
                break;
            case 'minggu ini':
                $query->whereBetween($dateColumn, [$now->startOfWeek()->toDateString(), $now->endOfWeek()->toDateString()]);
                break;
            case 'minggu sebelumnya':
                $query->whereBetween($dateColumn, [$now->subWeek()->startOfWeek()->toDateString(), $now->subWeek()->endOfWeek()->toDateString()]);
                break;
            case 'bulan ini':
                $query->whereMonth($dateColumn, $now->month)->whereYear($dateColumn, $now->year);
                break;
            case 'bulan sebelumnya':
                $query->whereMonth($dateColumn, $now->subMonth()->month)->whereYear($dateColumn, $now->year);
                break;
            case 'tahun ini':
                $query->whereYear($dateColumn, $now->year);
                break;
            case 'tahun sebelumnya':
                $query->whereYear($dateColumn, $now->subYear()->year);
                break;
            case 'rentang tanggal':
                if ($request->filled('start_date') && $request->filled('end_date')) {
                    $start = Carbon::parse($request->input('start_date'))->startOfDay();
                    $end = Carbon::parse($request->input('end_date'))->endOfDay();
                    $query->whereBetween($dateColumn, [$start, $end]);
                }
                break;
        }

        return $query;
    }

    /**
     * Helper untuk data grafik
     */
    private function getChartData($query, $period, $timeFilter = null)
    {
        $cloneQuery = clone $query;
        $cloneQuery->reorder();
        $now = Carbon::now();

        // Tentukan rentang waktu berdasarkan filter waktu
        switch ($timeFilter) {
            case 'tahun sebelumnya':
                $startDate = $now->copy()->subYear()->startOfYear();
                $endDate = $now->copy()->subYear()->endOfYear();
                break;
            case 'tahun ini':
                $startDate = $now->copy()->startOfYear();
                $endDate = $now->copy()->endOfYear();
                break;
            case 'bulan sebelumnya':
                $startDate = $now->copy()->subMonth()->startOfMonth();
                $endDate = $now->copy()->subMonth()->endOfMonth();
                break;
            case 'bulan ini':
                $startDate = $now->copy()->startOfMonth();
                $endDate = $now->copy()->endOfMonth();
                break;
            case 'minggu ini':
                $startDate = $now->copy()->startOfWeek();
                $endDate = $now->copy()->endOfWeek();
                break;
            case 'minggu sebelumnya':
                $startDate = $now->copy()->subWeek()->startOfWeek();
                $endDate = $now->copy()->subWeek()->endOfWeek();
                break;
            default:
                // Default: 12 bulan terakhir
                $startDate = $now->copy()->subMonths(11)->startOfMonth();
                $endDate = $now->copy()->endOfMonth();
                break;
        }

        // === Grafik Per Hari ===
        if ($period === 'hari') {
            $diffDays = $startDate->diffInDays($endDate);
            $rangeDays = min($diffDays, 30); // maksimal 31 hari agar grafik tidak padat

            $data = $cloneQuery
                ->selectRaw('DATE(created_at) as label, SUM(nominal) as value')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('label')
                ->orderBy('label', 'asc')
                ->get();

            $periodRange = collect();
            for ($i = 0; $i <= $rangeDays; $i++) {
                $date = $startDate->copy()->addDays($i)->format('Y-m-d');
                $value = $data->firstWhere('label', $date)->value ?? 0;
                $periodRange->push([
                    'label' => Carbon::parse($date)->format('d M'),
                    'value' => $value
                ]);
            }

            return $periodRange;
        }

        // === Grafik Per Tahun ===
        if ($period === 'tahun') {
            return $cloneQuery
                ->selectRaw('YEAR(created_at) as label, SUM(nominal) as value')
                ->groupBy('label')
                ->orderBy('label', 'asc')
                ->get();
        }

        // === Grafik Per Bulan ===
        $data = $cloneQuery
            ->selectRaw(
                'DATE_FORMAT(created_at, "%Y-%m") as label_key, ' .
                    'DATE_FORMAT(created_at, "%M %Y") as label, ' .
                    'SUM(nominal) as value'
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('label_key', 'label')
            ->orderBy('label_key', 'asc')
            ->get();

        $monthDiff = $startDate->diffInMonths($endDate) + 1;

        $periodRange = collect();
        for ($i = 0; $i < $monthDiff; $i++) {
            $month = $startDate->copy()->addMonths($i);
            $labelKey = $month->format('Y-m');
            $label = $month->translatedFormat('M Y');
            $value = $data->firstWhere('label_key', $labelKey)->value ?? 0;
            $periodRange->push([
                'label' => $label,
                'value' => $value
            ]);
        }

        return $periodRange;
    }



    public function exportDonationsPDF(Request $request)
    {
        $query = Donation::with(['user.profile', 'program']);

        // === Terapkan filter waktu ===
        $timeFilter = $request->input('time_filter', 'hari ini'); // default
        $query = $this->applyTimeFilter($query, $timeFilter, 'created_at', $request);

        // === Filter status ===
        if ($request->filled('status') && $request->input('status') != '') {
            $query->where('status', $request->input('status'));
        }

        // === Filter metode pembayaran ===
        if ($request->filled('metode_pembayaran') && $request->input('metode_pembayaran') != '') {
            $query->where('metode_pembayaran', $request->input('metode_pembayaran'));
        }

        // === Filter program ===
        if ($request->filled('program_id') && $request->input('program_id') != '') {
            $query->where('program_id', $request->input('program_id'));
        }

        // === Ambil hasilnya ===
        $donations = $query->orderBy('created_at', 'desc')->get();

        // === Total sesuai filter ===
        $totalDonations = $donations->sum('nominal');

        // === Generate PDF ===
        $pdf = PDF::loadView('admin.reports.pdf.donations', compact('donations', 'totalDonations', 'timeFilter'));

        // Nama file dinamis
        return $pdf->download('laporan-donasi-' . date('Y-m-d') . '.pdf');
    }


    public function exportDonationsExcel(Request $request)
    {
        // Query dasar
        $query = Donation::with(['user', 'program']);

        // Filter waktu
        if ($request->input('time_filter')) {
            $timeFilter = $request->input('time_filter');

            switch ($timeFilter) {
                case 'hari ini':
                    $query->whereDate('created_at', now());
                    break;
                case 'kemarin':
                    $query->whereDate('created_at', now()->subDay());
                    break;
                case 'minggu ini':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'minggu sebelumnya':
                    $query->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()]);
                    break;
                case 'bulan ini':
                    $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                    break;
                case 'bulan sebelumnya':
                    $query->whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year);
                    break;
                case 'tahun ini':
                    $query->whereYear('created_at', now()->year);
                    break;
                case 'tahun sebelumnya':
                    $query->whereYear('created_at', now()->subYear()->year);
                    break;
                case 'rentang tanggal':
                    if ($request->filled(['start_date', 'end_date'])) {
                        $query->whereBetween('created_at', [$request->input('start_date'), $request->input('end_date')]);
                    }
                    break;
            }
        }

        // Filter program
        if ($request->filled('program_id')) {
            $query->where('program_id', $request->input('program_id'));
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter metode pembayaran
        if ($request->filled('metode_pembayaran')) {
            $query->where('metode_pembayaran', $request->input('metode_pembayaran'));
        }

        // Ambil data hasil filter
        $donations = $query->orderBy('created_at', 'desc')->get();

        // Hitung total nominal
        $totalDonations = $donations->sum('nominal');

        // Kirim ke export
        return Excel::download(new DonationsExport($donations, $totalDonations), 'laporan-donasi-' . date('Y-m-d') . '.xlsx');
    }


    public function exportDistributionsPDF(Request $request)
    {
        $query = Distribution::with(['beneficiary', 'program']);

        // Filter waktu
        if ($request->input('time_filter')) {
            $query = $this->applyTimeFilter($query, $request->input('time_filter'), 'tanggal_penyaluran', $request);
        }

        // Filter program
        if ($request->filled('program_id') && $request->input('program_id') != '') {
            $query->where('program_id', $request->input('program_id'));
        }

        // Ambil hasil
        $distributions = $query->orderBy('tanggal_penyaluran', 'desc')->get();
        $totalDistributed = $distributions->sum('nominal_disalurkan');

        // Buat PDF
        $pdf = PDF::loadView('admin.reports.pdf.distributions', compact('distributions', 'totalDistributed'));

        return $pdf->download('laporan-penyaluran-' . date('Y-m-d') . '.pdf');
    }


    public function exportDistributionsExcel(Request $request)
    {
        $query = Distribution::with(['beneficiary', 'program']);

        // terapkan filter seperti biasa
        $query = $this->applyTimeFilter($query, $request->input('time_filter'), 'tanggal_penyaluran', $request);

        if ($request->filled('program_id') && $request->input('program_id') != '') {
            $query->where('program_id', $request->input('program_id'));
        }

        $distributions = $query->orderBy('tanggal_penyaluran', 'desc')->get();
        $totalDistributed = $distributions->sum('nominal_disalurkan');

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\DistributionsExport($distributions, $totalDistributed),
            'laporan-penyaluran-' . date('Y-m-d') . '.xlsx'
        );
    }



    /**
     * Ekspor laporan program ke PDF
     */
    public function exportProgramsPDF(Request $request)
    {
        // Gunakan helper yang sama dengan halaman utama agar filter dan urutan sama
        $programs = $this->getFilteredPrograms($request)->get();

        $pdf = PDF::loadView('admin.reports.pdf.programs', compact('programs'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-program-' . date('Y-m-d') . '.pdf');
    }


    /**
     * Ekspor laporan program ke Excel
     */
    public function exportProgramsExcel(Request $request)
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\ProgramsExport($request), 'laporan-program-' . date('Y-m-d') . '.xlsx');
    }

    private function getFilteredPrograms(Request $request)
    {
        $kategori = $request->input('kategori');
        $sort = $request->input('sort', 'desc');

        $programs = Program::withCount(['donations' => function ($query) {
            $query->where('status', 'terverifikasi');
        }])
            ->withSum(['donations' => function ($query) {
                $query->where('status', 'terverifikasi');
            }], 'nominal')
            ->withSum('distributions', 'nominal_disalurkan');

        if (!empty($kategori)) {
            $programs->where('kategori', $kategori);
        }

        if ($sort === 'asc') {
            $programs->orderBy('donations_sum_nominal', 'asc');
        } else {
            $programs->orderBy('donations_sum_nominal', 'desc');
        }

        return $programs;
    }
    /**
     * Laporan Penerimaan Per Rekening Bank
     */
    public function bankAccounts(Request $request)
    {
        // Filter waktu
        $timeFilter = $request->input('time_filter', 'bulan ini');

        // Filter bank account
        $bankAccountId = $request->input('bank_account_id');

        // Filter status (default: terverifikasi)
        $status = $request->input('status', 'terverifikasi');

        // Query base untuk donations
        $query = Donation::with(['user', 'program', 'bankAccount'])
            ->where('status', $status);

        // Apply time filter
        $query = $this->applyTimeFilter($query, $timeFilter, 'created_at', $request);

        // Filter by specific bank if selected
        if ($bankAccountId && $bankAccountId != '') {
            $query->where('bank_account_id', $bankAccountId);
        }

        // Get all donations for calculation
        $donations = $query->get();

        // Get all bank accounts (active)
        $bankAccounts = BankAccount::where('is_active', true)
            ->withCount(['donations' => function ($q) use ($timeFilter, $status, $request) {
                $q->where('status', $status);
                $this->applyTimeFilter($q, $timeFilter, 'created_at', $request);
            }])
            ->withSum(['donations as donations_sum_nominal' => function ($q) use ($timeFilter, $status, $request) {
                $q->where('status', $status);
                $this->applyTimeFilter($q, $timeFilter, 'created_at', $request);
            }], 'nominal')
            ->having('donations_count', '>', 0) // Only show banks with donations
            ->orderBy('donations_sum_nominal', 'desc')
            ->get();

        // Calculate summary statistics
        $totalPenerimaan = $donations->sum('nominal');
        $totalTransaksi = $donations->count();
        $totalBankAktif = $bankAccounts->count();
        $rataRataPerTransaksi = $totalTransaksi > 0 ? $totalPenerimaan / $totalTransaksi : 0;

        // Get top bank
        $topBank = $bankAccounts->first();

        // Breakdown per bank with programs
        $bankBreakdown = $bankAccounts->map(function ($bank) use ($timeFilter, $status, $request) {
            // Get donations for this bank
            $bankDonations = Donation::where('bank_account_id', $bank->id)
                ->where('status', $status);

            $this->applyTimeFilter($bankDonations, $timeFilter, 'created_at', $request);

            // Group by program
            $programBreakdown = $bankDonations->get()->groupBy('program_id')->map(function ($donations, $programId) {
                $program = Program::find($programId);
                return [
                    'program_name' => $program ? $program->nama_program : 'Unknown',
                    'program_kategori' => $program ? $program->kategori : '-',
                    'total' => $donations->sum('nominal'),
                    'count' => $donations->count(),
                    'average' => $donations->avg('nominal'),
                ];
            })->sortByDesc('total')->values();

            return [
                'bank' => $bank,
                'total' => $bank->donations_sum_nominal ?? 0,
                'count' => $bank->donations_count ?? 0,
                'average' => $bank->donations_count > 0 ? ($bank->donations_sum_nominal / $bank->donations_count) : 0,
                'programs' => $programBreakdown,
            ];
        });

        // Chart data for pie chart
        $chartData = $bankAccounts->map(function ($bank) {
            return [
                'label' => $bank->bank_name . "\n" . ' (' . $bank->account_holder . ')',
                'value' => $bank->donations_sum_nominal ?? 0,
            ];
        });

        // Pagination for transactions table (if needed)
        $perPage = $request->input('perPage', 25);
        $transactionsQuery = Donation::with(['user', 'program', 'bankAccount'])
            ->where('status', $status);

        $this->applyTimeFilter($transactionsQuery, $timeFilter, 'created_at', $request);

        if ($bankAccountId && $bankAccountId != '') {
            $transactionsQuery->where('bank_account_id', $bankAccountId);
        }

        $transactions = $transactionsQuery->orderBy('created_at', 'desc')->paginate($perPage);

        // All bank accounts for dropdown
        $allBankAccounts = BankAccount::where('is_active', true)->get();

        return view('admin.reports.bank-accounts', compact(
            'bankBreakdown',
            'totalPenerimaan',
            'totalTransaksi',
            'totalBankAktif',
            'rataRataPerTransaksi',
            'topBank',
            'chartData',
            'transactions',
            'allBankAccounts',
            'timeFilter',
            'bankAccountId',
            'status',
            'perPage'
        ));
    }

    /**
     * Export Bank Report to PDF
     */
    public function exportBankAccountsPDF(Request $request)
    {
        $timeFilter = $request->input('time_filter', 'bulan ini');
        $bankAccountId = $request->input('bank_account_id');
        $status = $request->input('status', 'terverifikasi');

        // Query base
        $query = Donation::with(['user', 'program', 'bankAccount'])
            ->where('status', $status);

        $query = $this->applyTimeFilter($query, $timeFilter, 'created_at', $request);

        if ($bankAccountId && $bankAccountId != '') {
            $query->where('bank_account_id', $bankAccountId);
        }

        $donations = $query->get();
        $totalPenerimaan = $donations->sum('nominal');

        // Get bank accounts with donations
        $bankAccountsQuery = BankAccount::where('is_active', true);

        if ($bankAccountId && $bankAccountId != '') {
            $bankAccountsQuery->where('id', $bankAccountId);
        }

        $bankAccounts = $bankAccountsQuery->get();

        // Breakdown per bank
        $bankBreakdown = collect();

        foreach ($bankAccounts as $bank) {
            // Get donations for this specific bank with time filter
            $bankDonationsQuery = Donation::where('bank_account_id', $bank->id)
                ->where('status', $status)
                ->with(['program']);

            $this->applyTimeFilter($bankDonationsQuery, $timeFilter, 'created_at', $request);

            $bankDonations = $bankDonationsQuery->get();

            // Skip if no donations
            if ($bankDonations->isEmpty()) {
                continue;
            }

            // Group by program
            $programBreakdown = $bankDonations->groupBy('program_id')->map(function ($donations, $programId) {
                $program = Program::find($programId);
                $total = $donations->sum('nominal');
                $count = $donations->count();

                return [
                    'program_name' => $program ? $program->nama_program : 'Unknown',
                    'program_kategori' => $program ? $program->kategori : '-',
                    'total' => $total,
                    'count' => $count,
                    'average' => $count > 0 ? $total / $count : 0,
                ];
            })->sortByDesc('total')->values();

            $totalBank = $bankDonations->sum('nominal');
            $countBank = $bankDonations->count();

            $bankBreakdown->push([
                'bank' => $bank,
                'total' => $totalBank,
                'count' => $countBank,
                'average' => $countBank > 0 ? $totalBank / $countBank : 0,
                'programs' => $programBreakdown,
            ]);
        }

        $pdf = PDF::loadView('admin.reports.pdf.bank-accounts', compact(
            'bankBreakdown',
            'totalPenerimaan',
            'timeFilter'
        ))->setPaper('a4', 'portrait');

        return $pdf->download('laporan-penerimaan-per-rekening-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export Bank Report to Excel
     */
    public function exportBankAccountsExcel(Request $request)
    {
        return Excel::download(new \App\Exports\BankAccountsExport($request), 'laporan-penerimaan-per-rekening-' . date('Y-m-d') . '.xlsx');
    }
}
