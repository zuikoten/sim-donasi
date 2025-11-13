<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ImageUploadService;
use App\Services\SettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    protected $settingService;
    protected $imageService;

    public function __construct(SettingService $settingService, ImageUploadService $imageService)
    {
        $this->settingService = $settingService;
        $this->imageService = $imageService;
    }

    /**
     * Display settings page
     */
    public function index()
    {
        $settings = $this->settingService->all();
        // Decode jam operasional dari JSON
        $officeHours = [];
        if (!empty($settings['office_hours'])) {
            $decoded = json_decode($settings['office_hours'], true);
            if (is_array($decoded)) {
                $officeHours = $decoded;
            }
        }
        return view('admin.settings.index', compact('settings', 'officeHours'));
    }

    /**
     * Update general settings
     */
    public function updateGeneral(Request $request)
    {
        $request->validate([
            'site_title' => 'required|string|max:255',
            'site_description' => 'nullable|string|max:200',
            'favicon' => 'nullable|image|mimes:png,ico,jpg|max:512',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
        ]);

        // Update site title
        $this->settingService->set('site_title', $request->site_title);

        // Update site description (Tambahkan ini)
        $this->settingService->set('site_description', $request->site_description);

        // Upload favicon
        if ($request->hasFile('favicon')) {
            $oldFavicon = $this->settingService->get('favicon');
            $faviconPath = $this->imageService->uploadFavicon($request->file('favicon'), $oldFavicon);
            $this->settingService->set('favicon', $faviconPath, 'file');
        }

        // Upload logo
        if ($request->hasFile('logo')) {
            $oldLogo = $this->settingService->get('logo');
            $logoPath = $this->imageService->uploadLogo($request->file('logo'), $oldLogo);
            $this->settingService->set('logo', $logoPath, 'file');
        }

        // Clear cache setelah update
        Cache::forget('all_settings');
        $this->settingService->clearCache();

        return redirect()->route('admin.settings.index')
            ->with('success', 'General settings updated successfully!');
    }

    /**
     * Update contact information
     */
    public function updateContact(Request $request)
    {
        // Bersihkan input telepon
        $cleanedPhone = preg_replace('/\D/', '', $request->office_phone);
        if (str_starts_with($cleanedPhone, '0')) {
            $cleanedPhone = '62' . substr($cleanedPhone, 1);
        }

        // Normalisasi struktur office_hours
        $rawHours = $request->input('office_hours', []);
        $normalizedHours = [];

        foreach ($rawHours as $entry) {
            if (!is_array($entry)) continue;

            $startDay = $entry['start_day'] ?? $entry['day_start'] ?? $entry['dayStart'] ?? ($entry['day'] ?? null);
            $endDay   = $entry['end_day'] ?? $entry['day_end'] ?? $entry['dayEnd'] ?? ($entry['day'] ?? null);
            $startTime = $entry['start_time'] ?? $entry['time_start'] ?? $entry['timeStart'] ?? null;
            $endTime   = $entry['end_time'] ?? $entry['time_end'] ?? $entry['timeEnd'] ?? null;
            $tz        = $entry['timezone'] ?? $entry['zone'] ?? $entry['tz'] ?? null;

            // skip jika data tidak lengkap
            if (!$startDay || !$startTime) continue;

            $normalizedHours[] = [
                'start_day' => $startDay,
                'end_day'   => $endDay ?: $startDay,
                'start_time' => $startTime,
                'end_time'  => $endTime ?: $startTime,
                'timezone'  => $tz ?: 'WIB',
            ];
        }

        // Merge hasil pembersihan & normalisasi ke request
        $request->merge([
            'office_phone' => $cleanedPhone,
            'office_hours' => $normalizedHours,
        ]);

        // Validasi (data sudah bersih dan konsisten)
        $validated = $request->validate([
            'office_address' => 'required|string',
            'office_email'   => 'required|email|max:255',
            'office_phone'   => [
                'required',
                'string',
                'regex:/^(\+?62|0)\d{7,13}$/',
            ],
            'office_hours' => 'nullable|array',
            'office_hours.*.start_day' => 'required|string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Ahad',
            'office_hours.*.end_day'   => 'required|string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Ahad',
            'office_hours.*.start_time' => 'required|string|regex:/^\d{2}:\d{2}$/',
            'office_hours.*.end_time'  => 'required|string|regex:/^\d{2}:\d{2}$/',
            'office_hours.*.timezone'  => 'required|string|in:WIB,WITA,WIT',
            'google_maps_embed' => 'nullable|string',
        ], [
            'office_phone.regex' => 'Nomor telepon harus diawali dengan +62 atau 0 dan berisi 8–13 digit.'
        ]);

        // Simpan ke database
        $this->settingService->set('office_address', $validated['office_address']);
        $this->settingService->set('office_email', $validated['office_email']);
        $this->settingService->set('office_phone', $validated['office_phone']);
        $this->settingService->set('google_maps_embed', $validated['google_maps_embed'] ?? '');

        $this->settingService->set('office_hours', json_encode($validated['office_hours'] ?? []));

        // Clear cache
        $this->settingService->clearCache();

        return redirect()->route('admin.settings.index')
            ->with('success', 'Contact information updated successfully!');
    }
}
