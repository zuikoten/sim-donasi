<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserProfile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function profileRules($isPhotoOnly = false)
    {
        if ($isPhotoOnly) {
            // Hanya validasi foto
            return [
                'foto' => 'required|image|max:2048',
            ];
        }

        // Validasi biodata lengkap
        return [
            'nama_lengkap' => 'required|string|max:150',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'no_telepon' => 'required|string|max:30',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'nullable|string',
            'pekerjaan' => 'nullable|string|max:100',
            'foto' => 'nullable|image|max:2048',
        ];
    }

    private function saveProfile(Request $request, UserProfile $profile, $isPhotoOnly = false)
    {
        // Validasi dengan aturan berbeda jika hanya update foto
        $data = $request->validate($this->profileRules($isPhotoOnly));

        // === Bagian Upload Foto ===
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($profile->foto) {
                Storage::disk('public')->delete($profile->foto);
            }

            // Simpan foto baru
            $profile->foto = $request->file('foto')->store('profile_photos', 'public');
        } elseif ($request->filled('cropped_image')) {
            // === Jika foto dikirim via CropperJS (base64) ===
            $imageData = $request->input('cropped_image');
            $imageName = 'profile_' . time() . '.png';
            $imagePath = 'profile_photos/' . $imageName;

            // Hapus foto lama jika ada
            if ($profile->foto) {
                Storage::disk('public')->delete($profile->foto);
            }

            // Simpan image base64 ke storage/public/profile_photos
            $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData));
            Storage::disk('public')->put($imagePath, $image);

            $profile->foto = $imagePath;
        }

        // === Bagian Update Biodata Umum ===
        if (!$isPhotoOnly) {
            $profile->nama_lengkap = $data['nama_lengkap'];
            $profile->jenis_kelamin = $data['jenis_kelamin'];
            $profile->no_telepon = $data['no_telepon'];
            $profile->tanggal_lahir = $data['tanggal_lahir'];
            $profile->alamat = $data['alamat'] ?? null;
            $profile->pekerjaan = $data['pekerjaan'] ?? null;
        }

        $profile->save();

        return $profile;
    }


    // ==========================
    // Untuk User biasa
    // ==========================
    public function edit()
    {
        $user = Auth::user();
        $profile = $user->profile ?: UserProfile::create(['user_id' => $user->id]);
        return view('profile.details', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $profile = $user->profile ?: UserProfile::create(['user_id' => $user->id]);

        // === 1️⃣ Jika hanya update foto (AJAX) ===
        if (
            $request->hasFile('foto') ||        // Upload file foto biasa
            $request->filled('cropped_image')   // Upload via CropperJS (base64)
        ) {
            $this->saveProfile($request, $profile, true);

            return response()->json([
                'success' => true,
                'foto_url' => asset('storage/' . $profile->foto)
            ]);
        }

        // === 2️⃣ Jika update biodata biasa ===
        $this->saveProfile($request, $profile);

        // Refresh user & profile di session
        $user->load('profile');
        Auth::setUser($user);

        return redirect()->route('profile.details')->with('success', 'Profil tersimpan.');
    }



    // ==========================
    // Untuk Superadmin edit profil user lain
    // ==========================
    public function updateByAdmin(Request $request, User $user)
    {
        try {
            $profile = $user->profile ?: UserProfile::create(['user_id' => $user->id]);
            $this->saveProfile($request, $profile);
            return redirect()->route('users.index')->with('success', 'Profil pengguna berhasil diperbarui.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('users.index')
                ->withErrors($e->errors())
                ->with('error', 'Validasi profil gagal, periksa kembali input.');
        }
    }
}
