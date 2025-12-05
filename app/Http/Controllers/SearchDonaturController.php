<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Donation;

class SearchDonaturController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function search(Request $request)
    {
        $term = $request->get('term', '');

        $donaturs = \App\Models\User::whereHas('role', function ($q) {
            $q->where('name', 'donatur');
        })
            ->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhereHas('profile', function ($q2) use ($term) {
                        $q2->where('nama_lengkap', 'like', "%{$term}%");
                    });
            })
            ->limit(10)
            ->get();

        $results = $donaturs->map(function ($donatur) {
            return [
                'id' => $donatur->id,
                'text' => $donatur->profile->nama_lengkap ?? $donatur->name,
            ];
        });

        return response()->json($results);
    }
}
