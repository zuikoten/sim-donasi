<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class DistributionsExport implements FromCollection, WithHeadings, WithColumnFormatting
{
    protected Collection $distributions;
    protected $totalDistributed;

    /**
     * Terima koleksi hasil filter dari controller
     *
     * @param \Illuminate\Support\Collection $distributions
     * @param mixed $totalDistributed
     */
    public function __construct(Collection $distributions, $totalDistributed = null)
    {
        $this->distributions = $distributions;
        $this->totalDistributed = $totalDistributed;
    }

    /**
     * Kembalikan koleksi yg sudah diformat untuk Excel
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $rows = $this->distributions->map(function ($distribution) {
            return [
                'Tanggal' => $distribution->tanggal_penyaluran
                    ? $distribution->tanggal_penyaluran->format('d M Y')
                    : '-',
                'Penerima Manfaat' => optional($distribution->beneficiary)->nama ?? '-',
                'Program Asal Dana' => optional($distribution->program)->nama_program ?? '-',
                'Nominal Disalurkan (Rp)' => (float) $distribution->nominal_disalurkan,
            ];
        });

        // Jika ingin menambahkan baris TOTAL di akhir (opsional)
        if (!is_null($this->totalDistributed)) {
            $rows->push([
                'Tanggal' => '',
                'Penerima Manfaat' => '',
                'Program Asal Dana' => 'TOTAL',
                'Nominal Disalurkan (Rp)' => (float) $this->totalDistributed,
            ]);
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Penerima Manfaat',
            'Program Asal Dana',
            'Nominal Disalurkan (Rp)',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
}
