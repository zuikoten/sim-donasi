<?php

namespace App\Exports;

use App\Models\Program;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ProgramsExport implements FromCollection, WithHeadings, WithColumnFormatting
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Program::withCount(['donations' => function ($query) {
            $query->where('status', 'terverifikasi');
        }])
            ->withSum(['donations' => function ($query) {
                $query->where('status', 'terverifikasi');
            }], 'nominal')
            ->withSum('distributions', 'nominal_disalurkan')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($program) {
                return [
                    'Nama Program'          => $program->nama_program,
                    'Jumlah Donatur'        => $program->donations_count,
                    'Dana Masuk (Rp)'       => $program->donations_sum_nominal,
                    'Dana Keluar (Rp)'      => $program->distributions_sum_nominal_disalurkan,
                    'Sisa Dana (Rp)'        => $program->donations_sum_nominal - $program->distributions_sum_nominal_disalurkan,
                ];
            });
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Nama Program',
            'Jumlah Donatur',
            'Dana Masuk (Rp)',
            'Dana Keluar (Rp)',
            'Sisa Dana (Rp)',
        ];
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_NUMBER,
            'C' => '"Rp"#,##0',
            'D' => '"Rp"#,##0',
            'E' => '"Rp"#,##0',
        ];
    }
}
