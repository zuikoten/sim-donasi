<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class DonationsExport implements FromCollection, WithHeadings, WithColumnFormatting
{
    protected $donations;
    protected $totalDonations;

    /**
     * Terima data hasil filter dari controller
     */
    public function __construct(Collection $donations, $totalDonations)
    {
        $this->donations = $donations;
        $this->totalDonations = $totalDonations;
    }

    /**
     * Koleksi data yang akan diexport ke Excel
     */
    public function collection()
    {
        // Ubah data koleksi ke bentuk array
        $rows = $this->donations->map(function ($donation) {
            return [
                'Tanggal'            => $donation->created_at->format('d M Y'),
                'Donatur'            => optional($donation->user)->display_name ?? '-',
                'Program'            => optional($donation->program)->nama_program ?? '-',
                'Nominal (Rp)'       => $donation->nominal,
                'Metode Pembayaran'  => $donation->metode_pembayaran,
                'Status'             => ucfirst($donation->status),
            ];
        });

        // Tambahkan baris kosong + total di akhir file
        $rows->push([
            'Tanggal'            => '',
            'Donatur'            => '',
            'Program'            => 'TOTAL',
            'Nominal (Rp)'       => $this->totalDonations,
            'Metode Pembayaran'  => '',
            'Status'             => '',
        ]);

        return $rows;
    }

    /**
     * Header kolom
     */
    public function headings(): array
    {
        return [
            'Tanggal',
            'Donatur',
            'Program',
            'Nominal (Rp)',
            'Metode Pembayaran',
            'Status',
        ];
    }

    /**
     * Format kolom (Rp)
     */
    public function columnFormats(): array
    {
        return [
            'D' => '"Rp"#,##0', // kolom nominal
        ];
    }
}
