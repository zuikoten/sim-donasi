<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'judul',
        'isi',
        'status_baca',
    ];

    protected $casts = [
        'status_baca' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function markAsRead()
    {
        $this->status_baca = true;
        $this->save();
    }
}