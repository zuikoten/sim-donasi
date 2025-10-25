<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Carbon;



/**
 * App\Models\User
 *
 * @method bool isSuperAdmin()
 * @method bool isAdmin()
 * @method bool isDonatur()
 */

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    // --- METHOD HELPER UNTUK CEK ROLE ---
    public function isSuperAdmin()
    {
        return $this->role && $this->role->name === 'superadmin';
    }

    public function isAdmin()
    {
        return $this->role && $this->role->name === 'admin';
    }

    public function isDonatur()
    {
        return $this->role && $this->role->name === 'donatur';
    }

    public function profile()
    {
        return $this->hasOne(\App\Models\UserProfile::class);
    }

    public function getDisplayNameAttribute()
    {
        return $this->profile->nama_lengkap ?? $this->name;
    }
}
