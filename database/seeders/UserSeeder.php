<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'superadmin@donasi.local'], // Cek agar tidak duplikat
            [
                'name' => 'Superadmin',
                'email' => 'superadmin@donasi.local',
                'password' => 'password', // akan otomatis di-hash oleh cast
                'role_id' => 1,
                'status' => 'aktif', 
            ]
        );
    }
}
