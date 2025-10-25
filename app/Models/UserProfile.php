<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'foto',
        'nama_lengkap',
        'jenis_kelamin',
        'tanggal_lahir',
        'no_telepon',
        'alamat',
        'pekerjaan',
    ];

    protected $dates = [
        'tanggal_lahir',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTanggalLahirFormattedAttribute()
    {
        if (empty($this->tanggal_lahir)) {
            return null; // biar di Blade bisa fallback ke teks default
        }

        return Carbon::parse($this->tanggal_lahir)->translatedFormat('d F Y');
    }
}
