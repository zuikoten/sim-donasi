<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Illuminate\Support\Str;

class ImageUploadService
{
    protected $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Upload and resize image
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $path (e.g., 'uploads/teams')
     * @param int|null $width
     * @param int|null $height
     * @param string|null $oldFile (path to old file to delete)
     * @return string Path to saved file
     */
    public function upload($file, $path, $width = null, $height = null, $oldFile = null)
    {
        // Delete old file if exists
        if ($oldFile && Storage::exists($oldFile)) {
            Storage::delete($oldFile);
        }

        // Generate unique filename
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $fullPath = $path . '/' . $filename;

        // Read and process image
        $image = $this->manager->read($file);

        // Resize if dimensions provided
        if ($width || $height) {
            $image->scale($width, $height);
        }

        // Convert to JPG for better compression (except PNG with transparency)
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, ['png', 'gif', 'svg'])) {
            $image->toJpeg(85); // 85% quality
        }

        // Save to storage
        Storage::put($fullPath, (string) $image->encode());

        return $fullPath;
    }

    /**
     * Upload favicon (small icon)
     */
    public function uploadFavicon($file, $oldFile = null)
    {
        return $this->upload($file, 'uploads/settings/favicon', 64, 64, $oldFile);
    }

    /**
     * Upload logo
     */
    public function uploadLogo($file, $oldFile = null)
    {
        return $this->upload($file, 'uploads/settings/logo', 300, null, $oldFile);
    }

    /**
     * Upload team photo
     */
    public function uploadTeamPhoto($file, $oldFile = null)
    {
        return $this->upload($file, 'uploads/teams', 400, 400, $oldFile);
    }

    /**
     * Upload testimonial photo
     */
    public function uploadTestimonialPhoto($file, $oldFile = null)
    {
        return $this->upload($file, 'uploads/testimonials', 150, 150, $oldFile);
    }

    /**
     * Upload bank logo
     */
    public function uploadBankLogo($file, $oldFile = null)
    {
        return $this->upload($file, 'uploads/banks', 120, 120, $oldFile);
    }

    /**
     * Delete file from storage
     */
    public function delete($path)
    {
        if ($path && Storage::exists($path)) {
            return Storage::delete($path);
        }
        return false;
    }
}
