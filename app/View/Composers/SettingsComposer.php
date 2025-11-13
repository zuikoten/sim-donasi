<?php

namespace App\View\Composers;

use App\Services\SettingService;
use Illuminate\View\View;

class SettingsComposer
{
    protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    /**
     * Bind data to the view.
     */
    public function compose(View $view)
    {
        // Ambil semua settings (bisa array atau Collection)
        $settingsRaw = $this->settingService->all();

        // Pastikan selalu array asosiatif
        $settings = is_array($settingsRaw) ? $settingsRaw : (method_exists($settingsRaw, 'toArray') ? $settingsRaw->toArray() : (array) $settingsRaw);

        // Decode office_hours jika ada (safely)
        $officeHours = [];
        if (!empty($settings['office_hours'])) {
            $decoded = json_decode($settings['office_hours'], true);
            if (is_array($decoded)) {
                $officeHours = $decoded;
            }
        }

        // Kirimkan variabel ke view
        $view->with([
            'settings' => $settings,
            'officeHours' => $officeHours,
        ]);
    }
}
