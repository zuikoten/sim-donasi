<?php

use App\Services\SettingService;
use Illuminate\Support\Facades\Storage;


if (!function_exists('setting')) {
    /**
     * Get setting value
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting($key, $default = null)
    {
        return app(SettingService::class)->get($key, $default);
    }
}

if (!function_exists('asset_url')) {
    /**
     * Get asset URL from storage
     * 
     * @param string|null $path
     * @param string $default
     * @return string
     */
    function asset_url($path, $default = '/images/default.png')
    {
        if (!$path) {
            return asset($default);
        }

        return Storage::url($path);
    }
}
