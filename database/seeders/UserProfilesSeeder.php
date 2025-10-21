<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserProfile;

class UserProfilesSeeder extends Seeder
{
    public function run(): void
    {
        User::doesntHave('profile')->chunk(100, function ($users) {
            foreach ($users as $user) {
                UserProfile::create([
                    'user_id' => $user->id,
                ]);
            }
        });
    }
}
