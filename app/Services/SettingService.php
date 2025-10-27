<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    /**
     * Get setting value by key
     */
    public function get($key, $default = null)
    {
        return Cache::rememberForever("setting_{$key}", function () use ($key, $default) {
            return Setting::get($key, $default);
        });
    }

    /**
     * Set or update setting
     */
    public function set($key, $value, $type = 'text')
    {
        Cache::forget("setting_{$key}");
        return Setting::set($key, $value, $type);
    }

    /**
     * Get all settings as array
     */
    public function all()
    {
        return Cache::rememberForever('all_settings', function () {
            return Setting::getAllSettings();
        });
    }

    /**
     * Clear all settings cache
     */
    public function clearCache()
    {
        Cache::flush();
    }
}
