<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DonaturSearchController extends Controller
{
    public function index(Request $request)
    {
        $term = $request->get('term', '');

        // Ambil semua user dengan role 'donatur'
        $query = User::whereHas('role', function ($q) {
            $q->where('name', 'donatur');
        });

        if ($term != '') {
            // Pencarian nama donatur
            $query->where(function ($q) use ($term) {
                $q->where('name', 'LIKE', "%$term%")
                    ->orWhereHas('profile', function ($sub) use ($term) {
                        $sub->where('nama_lengkap', 'LIKE', "%$term%");
                    });
            });

            $donaturs = $query->limit(10)->get();

            // Tambah 1 untuk setiap donatur yang tampil di hasil pencarian
            foreach ($donaturs as $donatur) {
                $donatur->increment('search_count');
            }
        } else {
            // Kalau tidak mencari apa pun → tampilkan 5 donatur paling sering dicari
            $donaturs = $query->orderByDesc('search_count')->limit(5)->get();
        }

        // Format hasil agar cocok dengan Select2
        $results = $donaturs->map(function ($user) {
            return [
                'id' => $user->id,
                'text' => $user->profile->nama_lengkap ?? $user->name,
            ];
        });

        return response()->json($results);
    }
}
