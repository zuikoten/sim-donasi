<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class BankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_name',
        'account_number',
        'account_holder',
        'bank_logo',
        'qris_image',
        'is_active',
        'is_cash',         // ← TAMBAHAN BARU
        'account_type',    // ← TAMBAHAN BARU
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_cash' => 'boolean',  // ← TAMBAHAN BARU
    ];

    /**
     * Get active bank accounts (exclude cash)
     */
    public static function getActive()
    {
        return self::where('is_active', true)
            ->where('is_cash', false)
            ->get();
    }

    /**
     * Get cash account only
     */
    public static function getCashAccount()
    {
        return self::where('is_cash', true)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Get or create cash account (safety fallback)
     */
    public static function getOrCreateCashAccount()
    {
        return self::firstOrCreate(
            ['is_cash' => true],
            [
                'bank_name' => 'Kas Tunai',
                'account_number' => '-',
                'account_holder' => 'Organisasi',
                'is_active' => true,
                'account_type' => 'cash',
            ]
        );
    }

    /**
     * Get all active accounts (cash + banks)
     */
    public static function getAllActive()
    {
        return self::where('is_active', true)->get();
    }

    /**
     * Get bank logo URL
     */
    public function getBankLogoUrlAttribute()
    {
        return $this->bank_logo ? Storage::url($this->bank_logo) : asset('images/default-bank.png');
    }

    /**
     * Get QRIS image URL
     */
    public function getQrisImageUrlAttribute()
    {
        return $this->qris_image ? Storage::url($this->qris_image) : null;
    }

    /**
     * Check if this is cash account
     */
    public function isCash()
    {
        return $this->is_cash === true;
    }

    /**
     * Check if this is bank account
     */
    public function isBank()
    {
        return $this->account_type === 'bank' || ($this->is_cash === false && !$this->account_type);
    }

    /**
     * Scope: Only cash accounts
     */
    public function scopeCashOnly($query)
    {
        return $query->where('is_cash', true);
    }

    /**
     * Scope: Only bank accounts (exclude cash)
     */
    public function scopeBankOnly($query)
    {
        return $query->where('is_cash', false);
    }

    /**
     * Scope: Active accounts
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Relationship: Donations using this account
     */
    public function donations()
    {
        return $this->hasMany(Donation::class, 'bank_account_id');
    }

    /**
     * Get total donations amount for this account
     */
    public function getTotalDonationsAttribute()
    {
        return $this->donations()
            ->where('status', 'terverifikasi')
            ->sum('nominal');
    }

    /**
     * Delete logo when model is deleted
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($bank) {
            // Prevent deleting cash account
            if ($bank->is_cash) {
                throw new \Exception('Kas Tunai tidak dapat dihapus!');
            }

            // Delete associated files
            if ($bank->bank_logo && Storage::exists($bank->bank_logo)) {
                Storage::delete($bank->bank_logo);
            }

            if ($bank->qris_image && Storage::exists($bank->qris_image)) {
                Storage::delete($bank->qris_image);
            }
        });
    }
}
