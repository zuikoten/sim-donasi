<?php

namespace App\View\Composers;

use App\Services\SettingService;
use App\Services\ContactFieldService;
use Illuminate\View\View;

// UNTUK MEMBUAT SEMUA SETTING YANG DIPERLUKAN BISA DIBUKA 
//DI BANYAK HALAMAN TANPA PERLU SATU-SATU DIPANGGIL DI CONTROLLERNYA
class SettingsComposer
{
    protected $settingService;
    protected $contactFieldService;

    public function __construct(SettingService $settingService, ContactFieldService $contactFieldService)
    {
        $this->settingService = $settingService;
        $this->contactFieldService = $contactFieldService;
    }

    /**
     * Bind data to the view.
     */
    public function compose(View $view)
    {
        // 1. Get basic settings (same for all)
        $settings = $this->settingService->all();
        $officeHours = json_decode($settings['office_hours'] ?? '[]', true);

        // 2. Get primary fields (same for all)
        $address = $this->contactFieldService->getPrimaryField('address');
        $phone = $this->contactFieldService->getPrimaryField('phone');
        $email = $this->contactFieldService->getPrimaryField('email');

        // 3. Check context (admin vs public)
        $viewName = $view->getName();
        $isAdminView = str_starts_with($viewName, 'admin.');

        // 4. Get all fields based on context
        if ($isAdminView) {
            // ADMIN: Ignore display mode, always show all
            $addresses = $this->contactFieldService->getFieldsByType('address');
            $phones = $this->contactFieldService->getFieldsByType('phone');
            $emails = $this->contactFieldService->getFieldsByType('email');
        } else {
            // PUBLIC: Respect display mode setting
            $addresses = $this->contactFieldService->getPublicFields('address');
            $phones = $this->contactFieldService->getPublicFields('phone');
            $emails = $this->contactFieldService->getPublicFields('email');
        }

        // 5. Pass to view
        $view->with([
            'settings' => $settings,
            'officeHours' => $officeHours,
            'address' => $address,
            'phone' => $phone,
            'email' => $email,
            'addresses' => $addresses,  // Different based on context
            'phones' => $phones,
            'emails' => $emails,
        ]);
    }
}
