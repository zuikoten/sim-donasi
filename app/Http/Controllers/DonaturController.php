<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Donation;

class DonaturController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        //$this->middleware('role:donatur');
    }

    /**
     * Display the donatur's dashboard.
     */
    public function dashboard(Request $request)
    {
        $user = Auth::user();

        // Ambil nilai perPage dari query string (default 10)
        $perPage = $request->input('perPage', 10);

        // Ambil data donasi user (paginasi)
        $recentDonations = $user->donations()
            ->with('program')
            ->latest()
            ->paginate($perPage)
            ->appends(['perPage' => $perPage]);

        // Hitung total donasi dan statistik
        $baseQuery = $user->donations();
        $totalDonations = $baseQuery->count();
        $totalAmount = (clone $baseQuery)->where('status', 'terverifikasi')->sum('nominal');
        $pendingDonations = (clone $baseQuery)->where('status', 'menunggu')->count();


        return view('donatur.dashboard', compact(
            'totalDonations',
            'totalAmount',
            'pendingDonations',
            'recentDonations'
        ));
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
