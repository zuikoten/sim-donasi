<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Notification;
use Carbon\Carbon;

class CheckUserBirthdays extends Command
{
    protected $signature = 'app:check-birthdays';
    protected $description = 'Memeriksa dan memberikan notifikasi ulang tahun untuk pengguna.';

    public function handle()
    {
        $this->info('Memulai pengecekan ulang tahun pengguna...');
        
        $today = Carbon::now();
        
        $usersWithBirthday = User::whereHas('profile', function($query) use ($today) {
            $query->whereMonth('tanggal_lahir', $today->month)
                  ->whereDay('tanggal_lahir', $today->day);
        })->with('profile')->get();

        $count = $usersWithBirthday->count();
        $this->info("Ditemukan {$count} pengguna yang ulang tahun hari ini.");

        if ($count === 0) {
            $this->info('Tidak ada pengguna yang ulang tahun hari ini.');
            return Command::SUCCESS;
        }

        foreach ($usersWithBirthday as $user) {
            $this->info("Ulang tahun: {$user->profile->nama_lengkap}");
            
            // Kirim notifikasi ulang tahun ke user
            Notification::create([
                'user_id' => $user->id,
                'judul' => 'Barakallahu Fii Umrik!',
                'isi' => 'Selamat Ulang Tahun, ' . $user->profile->nama_lengkap . '. Semoga selalu diberkahi kesehatan dan kebahagiaan.',
                'status_baca' => false,
            ]);
            
            // Kirim notifikasi ke admin/superadmin
            $admins = User::whereHas('role', function($query) {
                $query->whereIn('name', ['superadmin', 'admin']);
            })->get();
            
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'judul' => 'Ulang Tahun User',
                    'isi' => 'User ' . $user->profile->nama_lengkap . ' (' . $user->profile->tanggal_lahir_formatted . ') sedang merayakan ulang tahun.',
                    'status_baca' => false,
                ]);
            }
        }

        $this->info('Pengecekan selesai. ' . $count . ' pengguna mendapat notifikasi ulang tahun.');
        
        return Command::SUCCESS;
    }
}