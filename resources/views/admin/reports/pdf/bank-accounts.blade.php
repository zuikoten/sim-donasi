{{-- File: resources/views/admin/reports/pdf/bank-accounts.blade.php --}}
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Penerimaan Per Rekening</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
        }

        h1 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 5px;
        }

        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 20px;
        }

        .summary-box {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .bank-section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }

        .bank-header {
            background-color: #4a5568;
            color: white;
            padding: 8px;
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .bank-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 5px;
            background-color: #f0f0f0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #e2e8f0;
            font-weight: bold;
            font-size: 10px;
        }

        td {
            font-size: 10px;
        }

        .text-end {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .total-row {
            background-color: #fff3cd;
            font-weight: bold;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #999;
            padding: 10px;
            border-top: 1px solid #ddd;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <h1>Laporan Penerimaan Per Rekening Bank</h1>
    <p class="subtitle">Periode:
        @if ($timeFilter == 'hari ini')
            Hari Ini ({{ now()->format('d M Y') }})
        @elseif($timeFilter == 'kemarin')
            Kemarin ({{ now()->subDay()->format('d M Y') }})
        @elseif($timeFilter == 'minggu ini')
            Minggu Ini ({{ now()->startOfWeek()->format('d M Y') }} - {{ now()->endOfWeek()->format('d M Y') }})
        @elseif($timeFilter == 'bulan ini')
            Bulan Ini ({{ now()->format('M Y') }})
        @elseif($timeFilter == 'tahun ini')
            Tahun Ini ({{ now()->format('Y') }})
        @elseif($timeFilter == 'rentang tanggal' && request('start_date') && request('end_date'))
            {{ \Carbon\Carbon::parse(request('start_date'))->format('d M Y') }} -
            {{ \Carbon\Carbon::parse(request('end_date'))->format('d M Y') }}
        @else
            {{ ucfirst($timeFilter) }}
        @endif
    </p>

    <!-- Summary Box -->
    <div class="summary-box">
        <table style="border: none; margin: 0;">
            <tr style="border: none;">
                <td style="border: none; width: 50%;"><strong>Total Penerimaan:</strong></td>
                <td style="border: none; width: 50%; text-align: right;">Rp
                    {{ number_format($totalPenerimaan, 0, ',', '.') }}</td>
            </tr>
            <tr style="border: none;">
                <td style="border: none;"><strong>Jumlah Rekening:</strong></td>
                <td style="border: none; text-align: right;">{{ $bankBreakdown->count() }} Bank</td>
            </tr>
        </table>
    </div>

    <!-- Detail Per Bank -->
    @foreach ($bankBreakdown as $index => $item)
        <div class="bank-section">
            <!-- Bank Header -->
            <div class="bank-header">
                @if ($item['bank']->is_cash)
                    💵 {{ $item['bank']->bank_name }}
                @else
                    🏦 {{ $item['bank']->bank_name }} - {{ $item['bank']->account_number }}
                @endif
            </div>

            <!-- Bank Info -->
            <div class="bank-info">
                <div>
                    <strong>Pemegang Rekening:</strong> {{ $item['bank']->account_holder }}
                </div>
                <div>
                    <strong>Total Penerimaan:</strong> Rp {{ number_format($item['total'], 0, ',', '.') }}
                </div>
            </div>

            <!-- Breakdown Per Program -->
            @if ($item['programs']->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th style="width: 35%;">Nama Program</th>
                            <th style="width: 15%;">Kategori</th>
                            <th style="width: 20%;" class="text-end">Total Penerimaan</th>
                            <th style="width: 15%;" class="text-center">Jumlah Transaksi</th>
                            <th style="width: 20%;" class="text-end">Rata-rata</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($item['programs'] as $pIndex => $program)
                            <tr>
                                <td class="text-center">{{ $pIndex + 1 }}</td>
                                <td>{{ $program['program_name'] }}</td>
                                <td>{{ $program['program_kategori'] }}</td>
                                <td class="text-end">Rp {{ number_format($program['total'], 0, ',', '.') }}</td>
                                <td class="text-center">{{ $program['count'] }}</td>
                                <td class="text-end">Rp {{ number_format($program['average'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="total-row">
                            <td colspan="3" class="text-end"><strong>Subtotal
                                    {{ $item['bank']->bank_name }}:</strong></td>
                            <td class="text-end"><strong>Rp {{ number_format($item['total'], 0, ',', '.') }}</strong>
                            </td>
                            <td class="text-center"><strong>{{ $item['count'] }}</strong></td>
                            <td class="text-end"><strong>Rp {{ number_format($item['average'], 0, ',', '.') }}</strong>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            @else
                <p style="text-align: center; color: #999; padding: 20px;">Tidak ada transaksi untuk bank ini.</p>
            @endif
        </div>

        <!-- Page break setelah setiap 2 bank (opsional, sesuaikan dengan kebutuhan) -->
        @if (($index + 1) % 2 == 0 && $index + 1 < $bankBreakdown->count())
            <div class="page-break"></div>
        @endif
    @endforeach

    <!-- Grand Total -->
    <div style="margin-top: 20px; padding: 10px; background-color: #fef3c7; border: 2px solid #f59e0b;">
        <table style="border: none; margin: 0;">
            <tr style="border: none;">
                <td style="border: none; width: 70%; font-size: 14px;"><strong>GRAND TOTAL PENERIMAAN:</strong></td>
                <td style="border: none; width: 30%; text-align: right; font-size: 14px;"><strong>Rp
                        {{ number_format($totalPenerimaan, 0, ',', '.') }}</strong></td>
            </tr>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        Dicetak pada: {{ now()->format('d F Y H:i') }} | Sistem Informasi Manajemen Donasi
    </div>
</body>

</html>
