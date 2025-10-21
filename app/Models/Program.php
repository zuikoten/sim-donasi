<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_program',
        'deskripsi',
        'gambar',
        'kategori',
        'target_dana',
        'dana_terkumpul',
        'status',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    protected $casts = [
        'target_dana' => 'decimal:2',
        'dana_terkumpul' => 'decimal:2',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function beneficiaries()
    {
        return $this->hasManyThrough(
            Beneficiary::class, // Model target
            Distribution::class, // Model perantara
            'program_id', // Foreign key di tabel distributions
            'id', // Local key di tabel programs
            'id', // Local key di tabel beneficiaries
            'beneficiary_id' // Foreign key di tabel distributions
        );
    }

    public function distributions()
    {
        return $this->hasMany(Distribution::class);
    }

    public function getTotalDonasiAttribute()
    {
        return $this->donations()
            ->where('status', 'terverifikasi')
            ->sum('nominal');
    }

    public function getProgressPercentageAttribute()
    {
        $totalDonasi = $this->total_donasi;
        return $this->target_dana > 0
            ? min(100, ($totalDonasi / $this->target_dana) * 100)
            : 0;
    }
}
