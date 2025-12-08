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

    public function distributions()
    {
        return $this->hasMany(Distribution::class);
    }

    public function beneficiaries()
    {
        return $this->hasManyThrough(
            Beneficiary::class,
            Distribution::class,
            'program_id',
            'id',
            'id',
            'beneficiary_id'
        );
    }

    /**
     * Get total verified donations
     */
    public function getTotalDonasiAttribute()
    {
        return $this->donations()
            ->where('status', 'terverifikasi')
            ->sum('nominal');
    }

    /**
     * Get total distributions
     */
    public function getTotalDistribusiAttribute()
    {
        return $this->distributions()
            ->sum('nominal_disalurkan');
    }

    /**
     * Get actual available funds (should match dana_terkumpul)
     * Formula: Total Donations - Total Distributions
     */
    public function getDanatersediaAttribute()
    {
        return $this->total_donasi - $this->total_distribusi;
    }

    /**
     * Get progress percentage
     */
    public function getProgressPercentageAttribute()
    {
        $totalDonasi = $this->total_donasi;
        return $this->target_dana > 0
            ? min(100, ($totalDonasi / $this->target_dana) * 100)
            : 0;
    }

    /**
     * Check if program has sufficient funds for distribution
     */
    public function hasSufficientFunds($amount)
    {
        return $this->dana_terkumpul >= $amount;
    }

    /**
     * Scope untuk program aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Scope untuk program dengan target tercapai
     */
    public function scopeCompleted($query)
    {
        return $query->whereRaw('dana_terkumpul >= target_dana');
    }
}
