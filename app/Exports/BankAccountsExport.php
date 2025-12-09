<?php

namespace App\Exports;

use App\Models\Donation;
use App\Models\BankAccount;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Carbon\Carbon;

class BankAccountsExport implements WithMultipleSheets
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Return array of sheets
     */
    public function sheets(): array
    {
        $sheets = [];
        
        // Sheet 1: Summary
        $sheets[] = new BankAccountsSummarySheet($this->request);
        
        // Sheet 2+: Detail per Bank
        $timeFilter = $this->request->input('time_filter', 'bulan ini');
        $status = $this->request->input('status', 'terverifikasi');
        
        $query = BankAccount::where('is_active', true)
            ->withCount(['donations' => function($q) use ($timeFilter, $status) {
                $q->where('status', $status);
                $this->applyTimeFilterStatic($q, $timeFilter, $this->request);
            }]);
        
        $bankAccounts = $query->having('donations_count', '>', 0)->get();
        
        foreach ($bankAccounts as $bank) {
            $sheets[] = new BankAccountDetailSheet($this->request, $bank);
        }
        
        return $sheets;
    }

    /**
     * Static helper untuk apply time filter
     */
    public static function applyTimeFilterStatic($query, $timeFilter, Request $request = null)
    {
        $now = Carbon::now();
        
        switch ($timeFilter) {
            case 'hari ini':
                $query->whereDate('created_at', $now->toDateString());
                break;
            case 'kemarin':
                $query->whereDate('created_at', $now->subDay()->toDateString());
                break;
            case 'minggu ini':
                $query->whereBetween('created_at', [$now->startOfWeek(), $now->endOfWeek()]);
                break;
            case 'minggu sebelumnya':
                $query->whereBetween('created_at', [$now->subWeek()->startOfWeek(), $now->subWeek()->endOfWeek()]);
                break;
            case 'bulan ini':
                $query->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year);
                break;
            case 'bulan sebelumnya':
                $query->whereMonth('created_at', $now->subMonth()->month)->whereYear('created_at', $now->year);
                break;
            case 'tahun ini':
                $query->whereYear('created_at', $now->year);
                break;
            case 'tahun sebelumnya':
                $query->whereYear('created_at', $now->subYear()->year);
                break;
            case 'rentang tanggal':
                if ($request && $request->filled('start_date') && $request->filled('end_date')) {
                    $start = Carbon::parse($request->input('start_date'))->startOfDay();
                    $end = Carbon::parse($request->input('end_date'))->endOfDay();
                    $query->whereBetween('created_at', [$start, $end]);
                }
                break;
        }
        
        return $query;
    }
}

/**
 * Sheet 1: Summary
 */
class BankAccountsSummarySheet implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $timeFilter = $this->request->input('time_filter', 'bulan ini');
        $status = $this->request->input('status', 'terverifikasi');
        
        $bankAccounts = BankAccount::where('is_active', true)
            ->withCount(['donations' => function($q) use ($timeFilter, $status) {
                $q->where('status', $status);
                BankAccountsExport::applyTimeFilterStatic($q, $timeFilter, $this->request);
            }])
            ->withSum(['donations as donations_sum_nominal' => function($q) use ($timeFilter, $status) {
                $q->where('status', $status);
                BankAccountsExport::applyTimeFilterStatic($q, $timeFilter, $this->request);
            }], 'nominal')
            ->having('donations_count', '>', 0)
            ->orderBy('donations_sum_nominal', 'desc')
            ->get();
        
        $rows = $bankAccounts->map(function ($bank, $index) {
            $average = $bank->donations_count > 0 ? $bank->donations_sum_nominal / $bank->donations_count : 0;
            
            return [
                'No' => $index + 1,
                'Nama Bank' => $bank->bank_name,
                'No Rekening' => $bank->account_number ?: '-',
                'Pemegang Rekening' => $bank->account_holder,
                'Total Penerimaan (Rp)' => $bank->donations_sum_nominal ?? 0,
                'Jumlah Transaksi' => $bank->donations_count ?? 0,
                'Rata-rata (Rp)' => $average,
            ];
        });
        
        // Add grand total
        $grandTotal = $bankAccounts->sum('donations_sum_nominal');
        $totalTransactions = $bankAccounts->sum('donations_count');
        
        $rows->push([
            'No' => '',
            'Nama Bank' => '',
            'No Rekening' => '',
            'Pemegang Rekening' => 'GRAND TOTAL',
            'Total Penerimaan (Rp)' => $grandTotal,
            'Jumlah Transaksi' => $totalTransactions,
            'Rata-rata (Rp)' => $totalTransactions > 0 ? $grandTotal / $totalTransactions : 0,
        ]);
        
        return $rows;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Bank',
            'No Rekening',
            'Pemegang Rekening',
            'Total Penerimaan (Rp)',
            'Jumlah Transaksi',
            'Rata-rata (Rp)',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E2E8F0']]],
        ];
    }

    public function title(): string
    {
        return 'Ringkasan';
    }
}

/**
 * Sheet 2+: Detail per Bank
 */
class BankAccountDetailSheet implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    protected $request;
    protected $bank;

    public function __construct(Request $request, BankAccount $bank)
    {
        $this->request = $request;
        $this->bank = $bank;
    }

    public function collection()
    {
        $timeFilter = $this->request->input('time_filter', 'bulan ini');
        $status = $this->request->input('status', 'terverifikasi');
        
        $query = Donation::where('bank_account_id', $this->bank->id)
            ->where('status', $status)
            ->with(['user', 'program']);
        
        BankAccountsExport::applyTimeFilterStatic($query, $timeFilter, $this->request);
        
        $donations = $query->orderBy('created_at', 'desc')->get();
        
        $rows = $donations->map(function ($donation) {
            return [
                'Tanggal' => $donation->created_at->format('d M Y'),
                'Donatur' => $donation->user->name ?? '-',
                'Program' => $donation->program->nama_program ?? '-',
                'Kategori' => $donation->program->kategori ?? '-',
                'Nominal (Rp)' => $donation->nominal,
                'Metode' => $donation->metode_pembayaran,
                'Status' => ucfirst($donation->status),
            ];
        });
        
        // Add total row
        $total = $donations->sum('nominal');
        $rows->push([
            'Tanggal' => '',
            'Donatur' => '',
            'Program' => '',
            'Kategori' => 'TOTAL',
            'Nominal (Rp)' => $total,
            'Metode' => '',
            'Status' => '',
        ]);
        
        return $rows;
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Donatur',
            'Program',
            'Kategori',
            'Nominal (Rp)',
            'Metode',
            'Status',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4A5568']]],
        ];
    }

    public function title(): string
    {
        // Limit to 31 characters (Excel sheet name limit)
        $title = $this->bank->bank_name;
        return substr($title, 0, 31);
    }
}