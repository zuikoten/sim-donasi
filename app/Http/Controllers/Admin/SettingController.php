<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ImageUploadService;
use App\Services\SettingService;
use Illuminate\Http\Request;

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
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update general settings
     */
    public function updateGeneral(Request $request)
    {
        $request->validate([
            'site_title' => 'required|string|max:255',
            'favicon' => 'nullable|image|mimes:png,ico,jpg|max:512',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
        ]);

        // Update site title
        $this->settingService->set('site_title', $request->site_title);

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

        return redirect()->route('admin.settings.index')
            ->with('success', 'General settings updated successfully!');
    }

    /**
     * Update contact information
     */
    public function updateContact(Request $request)
    {
        $request->validate([
            'office_address' => 'required|string',
            'office_email' => 'required|email|max:255',
            'office_phone' => 'required|string|max:20',
        ]);

        $this->settingService->set('office_address', $request->office_address);
        $this->settingService->set('office_email', $request->office_email);
        $this->settingService->set('office_phone', $request->office_phone);

        return redirect()->route('admin.settings.index')
            ->with('success', 'Contact information updated successfully!');
    }
}
