<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'beneficiary_id',
        'program_id',
        'nominal_disalurkan',
        'tanggal_penyaluran',
        'deskripsi',
    ];

    protected $casts = [
        'nominal_disalurkan' => 'decimal:2',
        'tanggal_penyaluran' => 'date',
    ];

    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    protected static function booted()
    {
        // Hanya kirim notifikasi, dana_terkumpul dihandle oleh trigger
        static::created(function ($distribution) {
            // Create notification for all admins
            $admins = User::whereHas('role', function ($query) {
                $query->whereIn('name', ['superadmin', 'admin']);
            })->get();

            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'judul' => 'Penyaluran Donasi Baru',
                    'isi' => 'Donasi sebesar Rp ' . number_format($distribution->nominal_disalurkan, 0, ',', '.') . ' telah disalurkan kepada ' . $distribution->beneficiary->nama . ' dari program ' . $distribution->program->nama_program . '.',
                    'status_baca' => false,
                ]);
            }
        });

        // Notifikasi saat distribusi diupdate
        static::updated(function ($distribution) {
            if ($distribution->wasChanged(['nominal_disalurkan', 'program_id', 'beneficiary_id'])) {
                $admins = User::whereHas('role', function ($query) {
                    $query->whereIn('name', ['superadmin', 'admin']);
                })->get();

                foreach ($admins as $admin) {
                    Notification::create([
                        'user_id' => $admin->id,
                        'judul' => 'Penyaluran Donasi Diperbarui',
                        'isi' => 'Data penyaluran donasi sebesar Rp ' . number_format($distribution->nominal_disalurkan, 0, ',', '.') . ' telah diperbarui.',
                        'status_baca' => false,
                    ]);
                }
            }
        });
    }
}