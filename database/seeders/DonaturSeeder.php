<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class DonaturSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cari role donatur
        $donaturRole = Role::where('name', 'donatur')->first();

        if ($donaturRole) {
            // Buat user donatur default
            User::create([
                'name' => 'Donatur Contoh',
                'email' => 'donatur@donasi.local',
                'password' => Hash::make('password'),
                'role_id' => $donaturRole->id,
                'status' => 'aktif',
            ]);
        }
    }
}