<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'program_id',
        'nominal',
        'metode_pembayaran',
        'bank_account_id',
        'keterangan_tambahan',
        'bukti_transfer',
        'status',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }

    protected static function booted()
    {
        static::updated(function ($donation) {
            if ($donation->wasChanged('status') && $donation->status === 'terverifikasi') {
                $program = $donation->program;
                $program->dana_terkumpul += $donation->nominal;
                $program->save();

                // Create notification for user
                Notification::create([
                    'user_id' => $donation->user_id,
                    'judul' => 'Donasi Terverifikasi',
                    'isi' => 'Donasi Anda sebesar Rp ' . number_format($donation->nominal, 0, ',', '.') . ' pada program ' . $donation->program->nama_program . ' telah diverifikasi.',
                    'status_baca' => false,
                ]);
            }
        });
    }
}
