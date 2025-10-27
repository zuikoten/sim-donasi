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
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get active bank accounts
     */
    public static function getActive()
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
     * Delete logo when model is deleted
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($bank) {
            if ($bank->bank_logo && Storage::exists($bank->bank_logo)) {
                Storage::delete($bank->bank_logo);
            }
        });
    }
}
