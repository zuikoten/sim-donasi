<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Donasi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        h1 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .text-end {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <h1>Laporan Donasi Masuk</h1>
    <p>Periode: {{ request('time_filter', 'Semua') }} @if (request('start_date'))
            ({{ request('start_date') }} - {{ request('end_date') }})
        @endif
    </p>
    <table>
        <thead>
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
                    <td>{{ $donation->user->display_name }}</td>
                    <td>{{ $donation->program->nama_program }}</td>
                    <td>Rp {{ number_format($donation->nominal, 0, ',', '.') }}</td>
                    <td>{{ $donation->metode_pembayaran }}</td>
                    <td>{{ $donation->status }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-end">Total:</th>
                <th colspan="3">Rp {{ number_format($totalDonations, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>
</body>

</html>
