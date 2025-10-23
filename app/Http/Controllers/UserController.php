<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:superadmin,admin');
    }

    public function index(Request $request)
    {
        // Ambil jumlah data per halaman dari query string, default 25
        $perPage = $request->input('perPage', 10);

        // Ambil data user lengkap dengan relasi role dan profile
        $users = User::with(['role', 'profile'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage) // gunakan paginate, bukan get()
            ->appends($request->query()); // supaya query perPage tetap ada di link pagination

        // Kirim ke view
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|regex:/^\S*$/u',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:aktif,non-aktif',
        ]);

        // Simpan user utama
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'status' => $request->status,
        ]);

        // Buat profil kosong agar bisa diedit nanti
        UserProfile::create([
            'user_id' => $user->id,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil ditambahkan beserta profilnya.');
    }

    public function show(User $user)
    {
        $user->load('role', 'profile', 'donations');
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $user->load('profile');
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255|regex:/^\S*$/u',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:aktif,non-aktif',

            // validasi tambahan profil
            'full_name' => 'nullable|string|max:255',
            'gender' => 'nullable|in:Laki-laki,Perempuan',
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string|max:500',
            'occupation' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:2048',
        ]);

        // update user utama
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'status' => $request->status,
            'password' => $request->filled('password') ? Hash::make($request->password) : $user->password,
        ]);

        // update atau buat profil
        $profileData = [
            'full_name' => $request->full_name,
            'gender' => $request->gender,
            'phone' => $request->phone,
            'birth_date' => $request->birth_date,
            'address' => $request->address,
            'occupation' => $request->occupation,
        ];

        // upload foto profil jika ada
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $path = $file->store('profile_photos', 'public');
            $profileData['photo'] = $path;
        }

        $user->profile()->updateOrCreate(['user_id' => $user->id], $profileData);

        return redirect()->route('users.index')
            ->with('success', 'Data pengguna dan profil berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        // Cegah user menghapus dirinya sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        try {
            DB::beginTransaction();

            // Cek apakah user punya donasi (foreign key constraint)
            if ($user->donations()->exists()) {
                DB::rollBack(); // batalkan transaksi
                return back()->with('error', 'Pengguna ini memiliki data donasi dan tidak dapat dihapus.');
            }

            // Hapus profil terlebih dahulu (aman karena tidak ada foreign key yang tergantung padanya)
            $user->profile()->delete();

            // Hapus user utama
            $user->delete();

            DB::commit();
            return back()->with('success', 'Pengguna dan profilnya berhasil dihapus.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menghapus pengguna: ' . $e->getMessage());
        }
    }
}
