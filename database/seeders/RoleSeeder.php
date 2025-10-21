<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $superadmin = Role::create(['name' => 'superadmin']);
        $admin = Role::create(['name' => 'admin']);
        $donatur = Role::create(['name' => 'donatur']);

        // Create superadmin user
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@donasi.local',
            'password' => Hash::make('password'),
            'role_id' => $superadmin->id,
            'status' => 'aktif',
        ]);
    }
}
