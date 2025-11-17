<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class ContactFieldService
{
    protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    /**
     * Get all fields by type (address, phone, email)
     */
    public function getFieldsByType($type)
    {
        $cacheKey = "contact_fields_{$type}";

        return Cache::remember($cacheKey, 3600, function () use ($type) {
            $prefix = $this->getKeyPrefix($type);
            $settings = Setting::where('key', 'like', "{$prefix}_%")
                ->where('key', 'not like', "{$prefix}_primary")
                ->get()
                ->keyBy('key');

            $fields = [];
            $primaryIndex = $this->settingService->get("{$prefix}_primary");

            foreach ($settings as $key => $setting) {
                // Escape prefix untuk regex (karena bisa ada underscore)
                $escapedPrefix = preg_quote($prefix, '/');

                if (preg_match("/^{$escapedPrefix}_(\d+)$/", $key, $matches)) {
                    $index = $matches[1];
                    $value = $setting->value;

                    $fields[$index] = [
                        'index' => $index,
                        'value' => $value,
                        'is_primary' => $primaryIndex == $index,
                        'raw' => $value,
                    ];

                    // Format phone for display
                    if ($type === 'phone') {
                        $fields[$index]['formatted'] = $this->formatPhoneNumber($value);
                    }
                }
            }

            // Sort by index
            ksort($fields);

            return $fields;
        });
    }

    /**
     * Get primary field
     */
    public function getPrimaryField($type)
    {
        $fields = $this->getFieldsByType($type);
        $prefix = $this->getKeyPrefix($type);
        $primaryIndex = $this->settingService->get("{$prefix}_primary");

        if ($primaryIndex && isset($fields[$primaryIndex])) {
            return $fields[$primaryIndex];
        }

        // If no primary set, return first field
        return !empty($fields) ? reset($fields) : null;
    }

    /**
     * Get fields for public display (respects display mode)
     */
    public function getPublicFields($type)
    {
        $displayMode = $this->getDisplayMode($type);

        if ($displayMode === 'primary_only') {
            $primary = $this->getPrimaryField($type);
            return $primary ? [$primary] : [];
        }

        return $this->getFieldsByType($type);
    }

    /**
     * Add new field
     */
    public function addField($type, $value)
    {
        $prefix = $this->getKeyPrefix($type);
        $nextIndex = $this->getNextIndex($type);

        // Clean phone number if type is phone
        if ($type === 'phone') {
            $value = $this->cleanPhoneNumber($value);
        }

        // Save field
        $this->settingService->set("{$prefix}_{$nextIndex}", $value);

        // If this is the first field, set as primary
        $fields = $this->getFieldsByType($type);
        if (count($fields) === 0) {
            $this->setPrimary($type, $nextIndex);
        }

        $this->clearCache($type);

        return $nextIndex;
    }

    /**
     * Update existing field
     */
    public function updateField($type, $index, $value)
    {
        $prefix = $this->getKeyPrefix($type);

        // Clean phone number if type is phone
        if ($type === 'phone') {
            $value = $this->cleanPhoneNumber($value);
        }

        $this->settingService->set("{$prefix}_{$index}", $value);
        $this->clearCache($type);
    }

    /**
     * Delete field
     */
    public function deleteField($type, $index)
    {
        $prefix = $this->getKeyPrefix($type);
        $primaryIndex = $this->settingService->get("{$prefix}_primary");

        // Delete the field
        Setting::where('key', "{$prefix}_{$index}")->delete();
        Cache::forget("setting_{$prefix}_{$index}");

        // If deleted field was primary, set new primary
        if ($primaryIndex == $index) {
            $remainingFields = $this->getFieldsByType($type);

            if (!empty($remainingFields)) {
                // Set first remaining field as primary
                $firstIndex = array_key_first($remainingFields);
                $this->setPrimary($type, $firstIndex);
            } else {
                // No fields left, clear primary
                Setting::where('key', "{$prefix}_primary")->delete();
                Cache::forget("setting_{$prefix}_primary");
            }
        }

        $this->clearCache($type);
    }

    /**
     * Set field as primary
     */
    public function setPrimary($type, $index)
    {
        $prefix = $this->getKeyPrefix($type);
        $this->settingService->set("{$prefix}_primary", $index);
        $this->clearCache($type);
    }

    /**
     * Get display mode for type
     */
    public function getDisplayMode($type)
    {
        return $this->settingService->get("contact_display_{$type}s", 'primary_only');
    }

    /**
     * Set display mode for type
     */
    public function setDisplayMode($type, $mode)
    {
        if (!in_array($mode, ['primary_only', 'all'])) {
            throw new \Exception('Invalid display mode');
        }

        $this->settingService->set("contact_display_{$type}s", $mode);
        $this->clearCache($type);
    }

    /**
     * Get next available index for type
     */
    public function getNextIndex($type)
    {
        $prefix = $this->getKeyPrefix($type);
        $settings = Setting::where('key', 'like', "{$prefix}_%")
            ->where('key', 'not like', "{$prefix}_primary")
            ->get();

        $maxIndex = 0;
        $escapedPrefix = preg_quote($prefix, '/');

        foreach ($settings as $setting) {
            if (preg_match("/^{$escapedPrefix}_(\d+)$/", $setting->key, $matches)) {
                $maxIndex = max($maxIndex, (int)$matches[1]);
            }
        }

        return (string)($maxIndex + 1);
    }

    /**
     * Batch save fields (for form submission)
     */
    public function batchSaveFields($type, array $fields, $primaryIndex = null)
    {
        $prefix = $this->getKeyPrefix($type);

        // Get existing field indexes
        $existingFields = $this->getFieldsByType($type);
        $existingIndexes = array_keys($existingFields);

        // Track submitted indexes
        $submittedIndexes = array_keys($fields);

        // Delete fields that were removed
        $toDelete = array_diff($existingIndexes, $submittedIndexes);
        foreach ($toDelete as $index) {
            $this->deleteField($type, $index);
        }

        // Update or create fields
        foreach ($fields as $index => $value) {
            if (empty($value)) {
                continue; // Skip empty values
            }

            if ($type === 'phone') {
                $value = $this->cleanPhoneNumber($value);
            }

            $this->settingService->set("{$prefix}_{$index}", $value);
        }

        // Set primary
        if ($primaryIndex && isset($fields[$primaryIndex])) {
            $this->setPrimary($type, $primaryIndex);
        } elseif (empty($primaryIndex) && !empty($fields)) {
            // Auto-set first as primary if none specified
            $firstIndex = array_key_first($fields);
            $this->setPrimary($type, $firstIndex);
        }

        $this->clearCache($type);
    }

    /**
     * Get key prefix for type
     */
    protected function getKeyPrefix($type)
    {
        $prefixes = [
            'address' => 'office_address',
            'phone' => 'office_phone',
            'email' => 'office_email',
        ];

        if (!isset($prefixes[$type])) {
            throw new \Exception("Invalid field type: {$type}");
        }

        return $prefixes[$type];
    }

    /**
     * Clean phone number (remove formatting)
     */
    protected function cleanPhoneNumber($phone)
    {
        $cleaned = preg_replace('/\D/', '', $phone);

        if (str_starts_with($cleaned, '0')) {
            $cleaned = '62' . substr($cleaned, 1);
        } elseif (!str_starts_with($cleaned, '62')) {
            $cleaned = '62' . $cleaned;
        }

        return $cleaned;
    }

    /**
     * Format phone number for display
     */
    protected function formatPhoneNumber($phone)
    {
        if (!preg_match('/^62\d+$/', $phone)) {
            return $phone;
        }

        $formatted = '+62 ';
        $numbers = substr($phone, 2);

        if (strlen($numbers) > 3) {
            $formatted .= substr($numbers, 0, 3);
        }
        if (strlen($numbers) > 3) {
            $formatted .= '-' . substr($numbers, 3, 4);
        }
        if (strlen($numbers) > 7) {
            $formatted .= '-' . substr($numbers, 7);
        }

        return $formatted;
    }

    /**
     * Clear cache for type
     */
    protected function clearCache($type)
    {
        Cache::forget("contact_fields_{$type}");
        Cache::forget('all_settings');
    }

    /**
     * Migrate legacy data (backward compatibility)
     */
    public function migrateLegacyData()
    {
        $types = ['address', 'phone', 'email'];

        foreach ($types as $type) {
            $prefix = $this->getKeyPrefix($type);
            $legacyKey = $prefix; // e.g., 'office_address'

            $legacySetting = Setting::where('key', $legacyKey)->first();

            if ($legacySetting && !empty($legacySetting->value)) {
                // Check if already migrated
                $newKey = "{$prefix}_1";
                if (!Setting::where('key', $newKey)->exists()) {
                    // Migrate to new format
                    $this->settingService->set($newKey, $legacySetting->value);
                    $this->setPrimary($type, '1');

                    // Optionally delete legacy key
                    // $legacySetting->delete();
                }
            }
        }

        // Clear cache after migration
        $this->settingService->clearCache();
    }
}
