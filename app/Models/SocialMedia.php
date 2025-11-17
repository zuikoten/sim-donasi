<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialMedia extends Model
{
    use HasFactory;

    protected $table = 'social_media';

    protected $fillable = [
        'platform_name',
        'url',
        'icon_class',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Get popular social media icons
     */
    public static function getPopularIcons(): array
    {
        return [
            'bi bi-facebook' => 'Facebook',
            'bi bi-instagram' => 'Instagram',
            'bi bi-twitter-x' => 'Twitter / X',
            'bi bi-linkedin' => 'LinkedIn',
            'bi bi-youtube' => 'YouTube',
            'bi bi-tiktok' => 'TikTok',
            'bi bi-whatsapp' => 'WhatsApp',
            'bi bi-telegram' => 'Telegram',
            'bi bi-discord' => 'Discord',
            'bi bi-github' => 'GitHub',
            'bi bi-reddit' => 'Reddit',
            'bi bi-twitch' => 'Twitch',
            'bi bi-snapchat' => 'Snapchat',
            'bi bi-pinterest' => 'Pinterest',
            'bi bi-threads' => 'Threads',
        ];
    }

    // Di Model SocialMedia, tambahkan method:
    public function getColorClass(): string
    {
        $colorMap = [
            'facebook' => 'primary',
            'instagram' => 'danger',
            'twitter' => 'info',
            'x' => 'dark',
            'linkedin' => 'primary',
            'youtube' => 'danger',
            'tiktok' => 'dark',
            'whatsapp' => 'success',
            'telegram' => 'info',
            'github' => 'dark',
            'discord' => 'primary',
        ];

        $platform = strtolower($this->platform_name);

        foreach ($colorMap as $key => $color) {
            if (str_contains($platform, $key)) {
                return $color;
            }
        }

        return 'secondary'; // default
    }
}
