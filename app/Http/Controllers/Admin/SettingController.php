<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ImageUploadService;
use App\Services\SettingService;
use App\Services\ContactFieldService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    protected $settingService;
    protected $imageService;
    protected $contactFieldService;

    public function __construct(SettingService $settingService, ImageUploadService $imageService, ContactFieldService $contactFieldService)
    {
        $this->settingService = $settingService;
        $this->imageService = $imageService;
        $this->contactFieldService = $contactFieldService;
    }

    /**
     * Display settings page
     */
    public function index()
    {
        // Migrate legacy data if exists
        $this->contactFieldService->migrateLegacyData();

        // Get all settings
        $settings = $this->settingService->all();

        // Get multi-field data
        $addresses = $this->contactFieldService->getFieldsByType('address');
        $phones = $this->contactFieldService->getFieldsByType('phone');
        $emails = $this->contactFieldService->getFieldsByType('email');

        // Get display modes
        $displayModes = [
            'addresses' => $this->contactFieldService->getDisplayMode('address'),
            'phones' => $this->contactFieldService->getDisplayMode('phone'),
            'emails' => $this->contactFieldService->getDisplayMode('email'),
        ];

        // Get next indexes for new fields
        $nextIndexes = [
            'address' => $this->contactFieldService->getNextIndex('address'),
            'phone' => $this->contactFieldService->getNextIndex('phone'),
            'email' => $this->contactFieldService->getNextIndex('email'),
        ];

        // Decode jam operasional dari JSON
        $officeHours = [];
        if (isset($settings['office_hours'])) {
            $decoded = json_decode($settings['office_hours'], true);
            if (is_array($decoded)) {
                $officeHours = $decoded;
            }
        }
        return view('admin.settings.index', compact(
            'settings',
            'addresses',
            'phones',
            'emails',
            'displayModes',
            'nextIndexes',
            'officeHours'
        ));
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
            ->with('success', 'General settings berhasil diupdate!');
    }

    /**
     * Update contact information with multi-fields support
     */
    public function updateContact(Request $request)
    {
        try {
            // Clean phones: hapus format, jadi angka murni 62xxx
            $cleanedPhones = [];
            if ($request->has('phones')) {
                foreach ($request->phones as $index => $phone) {
                    if (!empty($phone)) {
                        $cleaned = preg_replace('/\D/', '', $phone); // Hapus semua non-digit

                        // Ubah 0 jadi 62
                        if (str_starts_with($cleaned, '0')) {
                            $cleaned = '62' . substr($cleaned, 1);
                        } elseif (!str_starts_with($cleaned, '62')) {
                            $cleaned = '62' . $cleaned;
                        }

                        $cleanedPhones[$index] = $cleaned;
                    }
                }
            }

            // Merge cleaned phones ke request
            $request->merge(['phones' => $cleanedPhones]);

            // VALIDASI (dengan data yang sudah bersih) =====

            $validated = $request->validate([
                // Multi addresses
                'addresses' => 'nullable|array',
                'addresses.*' => 'required_with:addresses|string|max:500',
                'address_primary' => 'nullable|string',

                // Multi phones (validate format yang sudah clean: 62xxxxxxxxx)
                'phones' => 'nullable|array',
                'phones.*' => 'required_with:phones|string|regex:/^62\d{8,13}$/',
                'phone_primary' => 'nullable|string',

                // Multi emails
                'emails' => 'nullable|array',
                'emails.*' => 'required_with:emails|email|max:255',
                'email_primary' => 'nullable|string',

                // Display modes
                'display_addresses' => 'required|in:primary_only,all',
                'display_phones' => 'required|in:primary_only,all',
                'display_emails' => 'required|in:primary_only,all',

                // Office hours (existing)
                'office_hours' => 'nullable|array',
                'office_hours.*.start_day' => 'required|string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Ahad',
                'office_hours.*.end_day' => 'required|string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Ahad',
                'office_hours.*.start_time' => 'required|string|regex:/^\d{2}:\d{2}$/',
                'office_hours.*.end_time' => 'required|string|regex:/^\d{2}:\d{2}$/',
                'office_hours.*.timezone' => 'required|string|in:WIB,WITA,WIT',

                // Google Maps (existing)
                'google_maps_embed' => 'nullable|string',
            ], [
                'addresses.*.required_with' => 'Kolom Alamat tidak boleh kosong.',
                'phones.*.required_with' => 'Kolom Telepon tidak boleh kosong.',
                'phones.*.regex' => 'Format nomor telepon invalid. Harus 10-15 digit.',
                'emails.*.required_with' => 'Kolom Email tidak boleh kosong.',
                'emails.*.email' => 'Format Email invalid.',
            ]);

            // SAVE KE DATABASE =====
            // Save addresses (langsung, tidak perlu cleaning)
            if (isset($validated['addresses']) && !empty($validated['addresses'])) {
                $prefix = 'office_address';

                // Get existing indexes
                $existingAddresses = $this->contactFieldService->getFieldsByType('address');
                $existingIndexes = array_keys($existingAddresses);
                $submittedIndexes = array_keys($validated['addresses']);

                // Delete removed addresses
                $toDelete = array_diff($existingIndexes, $submittedIndexes);
                foreach ($toDelete as $index) {
                    $this->contactFieldService->deleteField('address', $index);
                }

                // Save/update addresses
                foreach ($validated['addresses'] as $index => $value) {
                    if (!empty($value)) {
                        $this->settingService->set("{$prefix}_{$index}", $value);
                    }
                }

                // Set primary
                if (!empty($validated['address_primary'])) {
                    $this->contactFieldService->setPrimary('address', $validated['address_primary']);
                } elseif (!empty($validated['addresses'])) {
                    $firstIndex = array_key_first($validated['addresses']);
                    $this->contactFieldService->setPrimary('address', $firstIndex);
                }
            }

            // Save phones (sudah dalam format clean: 62xxx)
            if (isset($validated['phones']) && !empty($validated['phones'])) {
                $prefix = 'office_phone';

                // Get existing indexes
                $existingPhones = $this->contactFieldService->getFieldsByType('phone');
                $existingIndexes = array_keys($existingPhones);
                $submittedIndexes = array_keys($validated['phones']);

                // Delete removed phones
                $toDelete = array_diff($existingIndexes, $submittedIndexes);
                foreach ($toDelete as $index) {
                    $this->contactFieldService->deleteField('phone', $index);
                }

                // Save/update phones (LANGSUNG save cleaned value)
                foreach ($validated['phones'] as $index => $cleanedValue) {
                    if (!empty($cleanedValue)) {
                        $this->settingService->set("{$prefix}_{$index}", $cleanedValue);
                    }
                }

                // Set primary
                if (!empty($validated['phone_primary'])) {
                    $this->contactFieldService->setPrimary('phone', $validated['phone_primary']);
                } elseif (!empty($validated['phones'])) {
                    $firstIndex = array_key_first($validated['phones']);
                    $this->contactFieldService->setPrimary('phone', $firstIndex);
                }
            }

            // Save emails (langsung, tidak perlu cleaning)
            if (isset($validated['emails']) && !empty($validated['emails'])) {
                $prefix = 'office_email';

                // Get existing indexes
                $existingEmails = $this->contactFieldService->getFieldsByType('email');
                $existingIndexes = array_keys($existingEmails);
                $submittedIndexes = array_keys($validated['emails']);

                // Delete removed emails
                $toDelete = array_diff($existingIndexes, $submittedIndexes);
                foreach ($toDelete as $index) {
                    $this->contactFieldService->deleteField('email', $index);
                }

                // Save/update emails
                foreach ($validated['emails'] as $index => $value) {
                    if (!empty($value)) {
                        $this->settingService->set("{$prefix}_{$index}", $value);
                    }
                }

                // Set primary
                if (!empty($validated['email_primary'])) {
                    $this->contactFieldService->setPrimary('email', $validated['email_primary']);
                } elseif (!empty($validated['emails'])) {
                    $firstIndex = array_key_first($validated['emails']);
                    $this->contactFieldService->setPrimary('email', $firstIndex);
                }
            }

            // Save display modes
            $this->contactFieldService->setDisplayMode('address', $validated['display_addresses']);
            $this->contactFieldService->setDisplayMode('phone', $validated['display_phones']);
            $this->contactFieldService->setDisplayMode('email', $validated['display_emails']);

            // Save office hours (existing)
            $normalizedHours = [];
            if (!empty($validated['office_hours'])) {
                foreach ($validated['office_hours'] as $hour) {
                    $normalizedHours[] = [
                        'start_day' => $hour['start_day'],
                        'end_day' => $hour['end_day'],
                        'start_time' => $hour['start_time'],
                        'end_time' => $hour['end_time'],
                        'timezone' => $hour['timezone'],
                    ];
                }
            }
            $this->settingService->set('office_hours', json_encode($normalizedHours));

            // Save Google Maps (existing)
            $this->settingService->set('google_maps_embed', $validated['google_maps_embed'] ?? '');

            // Clear all cache
            $this->settingService->clearCache();

            return redirect()
                ->route('admin.settings.index')
                ->with('success', 'Informasi Kontak Berhasil diupdate!');
        } catch (\Illuminate\Validation\ValidationException $e) {

            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Mohon Periksa form dari Error.');
        } catch (\Exception $e) {

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal update informasi Kontak: ' . $e->getMessage());
        }
    }
}
