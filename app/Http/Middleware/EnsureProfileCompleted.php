<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileCompleted
{
    public function handle(Request $request, Closure $next): Response
    {
        // Hanya jalankan jika user sudah login
        if ($request->user()) {
            $profile = $request->user()->profile;

            // Jika profil belum lengkap (misalnya nama_lengkap masih kosong)
            if (
                !$profile ||
                blank($profile->nama_lengkap) ||
                blank($profile->jenis_kelamin) ||
                blank($profile->tanggal_lahir) ||
                blank($profile->no_telepon)
            ) {
                // Hindari redirect loop — biarkan lewat kalau sudah di halaman profile
                if (!$request->is('profile*')) {
                    return redirect()->route('profile.details')
                        ->with('warning', 'Silakan lengkapi profil Anda terlebih dahulu.');
                }
            }
        }

        return $next($request);
    }
}
