<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();

        // Ubah agar mendukung 'role:superadmin|admin' atau 'role:superadmin,admin'
        $expandedRoles = [];
        foreach ($roles as $r) {
            $split = preg_split('/[|,]/', $r); // bisa pakai pemisah | atau ,
            $split = array_map('trim', $split);
            $expandedRoles = array_merge($expandedRoles, $split);
        }

        // Cek apakah role user termasuk dalam daftar yang diizinkan
        if (in_array($user->role->name, $expandedRoles)) {
            return $next($request);
        }

        // Jika user adalah donatur, arahkan ke dashboard donatur
        if ($user->role->name === 'donatur') {
            return redirect()->route('donatur.dashboard');
        }

        // Jika bukan donatur, arahkan ke dashboard umum
        return redirect('/dashboard')->with('error', 'Warning unauthenticated : Anda tidak memiliki akses ke halaman ini.');
    }
}
