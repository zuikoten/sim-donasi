<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'photo',
        'name',
        'position',
        'description',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get active teams ordered
     */
    public static function getActive()
    {
        return self::where('is_active', true)
            ->orderBy('order', 'asc')
            ->get();
    }

    /**
     * Get photo URL
     */
    public function getPhotoUrlAttribute()
    {
        return $this->photo ? Storage::url($this->photo) : asset('images/default-avatar.png');
    }

    /**
     * Delete photo when model is deleted
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($team) {
            if ($team->photo && Storage::exists($team->photo)) {
                Storage::delete($team->photo);
            }
        });
    }
}
