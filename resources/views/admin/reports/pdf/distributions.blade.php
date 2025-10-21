<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Distribusi</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-end { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <h1>Laporan Distribusi</h1>
    <p>Periode: {{ request('time_filter', 'Semua') }} @if(request('start_date')) ({{ request('start_date') }} - {{ request('end_date') }}) @endif</p>
    <table>
        <thead>
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
        <tfoot>
            <tr>
                <th colspan="3" class="text-end">Total:</th>
                <th colspan="3">Rp {{ number_format($totalDistributed, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>