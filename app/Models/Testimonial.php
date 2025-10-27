<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'photo',
        'name',
        'role',
        'rating',
        'comment',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'rating' => 'integer',
    ];

    /**
     * Get active testimonials ordered
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
     * Get star rating as HTML
     */
    public function getStarsHtmlAttribute()
    {
        $html = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $this->rating) {
                $html .= '<i class="bi bi-star-fill text-warning"></i>';
            } else {
                $html .= '<i class="bi bi-star text-warning"></i>';
            }
        }
        return $html;
    }

    /**
     * Delete photo when model is deleted
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($testimonial) {
            if ($testimonial->photo && Storage::exists($testimonial->photo)) {
                Storage::delete($testimonial->photo);
            }
        });
    }
}