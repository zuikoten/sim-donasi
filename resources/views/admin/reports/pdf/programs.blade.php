<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Program</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .text-end { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <h1>Laporan Ringkasan Program</h1>
    <p>Tanggal Cetak: {{ now()->format('d F Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>Nama Program</th>
                <th class="text-center">Jumlah Donatur</th>
                <th class="text-end">Dana Masuk</th>
                <th class="text-end">Dana Keluar</th>
                <th class="text-end">Sisa Dana</th>
            </tr>
        </thead>
        <tbody>
            @forelse($programs as $program)
            <tr>
                <td>{{ $program->nama_program }}</td>
                <td class="text-center">{{ $program->donations_count }}</td>
                <td class="text-end">Rp {{ number_format($program->donations_sum_nominal, 0, ',', '.') }}</td>
                <td class="text-end">Rp {{ number_format($program->distributions_sum_nominal_disalurkan, 0, ',', '.') }}</td>
                <td class="text-end"><strong>Rp {{ number_format($program->donations_sum_nominal - $program->distributions_sum_nominal_disalurkan, 0, ',', '.') }}</strong></td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Tidak ada data program untuk ditampilkan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>