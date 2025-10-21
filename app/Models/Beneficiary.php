<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'alamat',
        'nomor_telepon',
        'keterangan',
    ];

    public function distributions()
    {
        return $this->hasMany(Distribution::class);
    }
}
