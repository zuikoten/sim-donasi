<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Program;
use App\Models\Beneficiary;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalDonations = Donation::where('status', 'terverifikasi')->sum('nominal');
        $totalPrograms = Program::where('status', 'aktif')->count();
        $totalBeneficiaries = Beneficiary::count();

        // Get monthly donation data for chart
        $monthlyDonations = Donation::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('SUM(nominal) as total')
        )
            ->where('status', 'terverifikasi')
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get()
            ->reverse();

        // --- Donasi Terbaru (Dengan Pagination) ---
        $recentDonationsPerPage = $request->input('recentPerPage', 5); // Default 5, bisa diubah via URL
        $recentDonations = Donation::with(['user', 'program'])
            ->orderBy('created_at', 'desc')
            ->paginate($recentDonationsPerPage);

        // --- Top Programs (Dengan Pagination) ---
        $topProgramsPerPage = $request->input('topProgramsPerPage', 5); // Default 5, bisa diubah via URL
        $topPrograms = Program::withCount(['donations' => function ($query) {
            $query->where('status', 'terverifikasi');
        }])
            ->withSum(['donations' => function ($query) {
                $query->where('status', 'terverifikasi');
            }], 'nominal')
            ->orderBy('donations_sum_nominal', 'desc')
            ->paginate($topProgramsPerPage);

        // Hitung persentase progress untuk setiap program
        $topPrograms->getCollection()->transform(function ($program) {
            $total = $program->donations_sum_nominal ?? 0;
            $target = $program->target_dana ?? 1; // hindari pembagian 0

            $percentage = $target > 0 ? ($total / $target) * 100 : 0;
            // Jika lebih dari 100%, batasi agar progress bar tidak lewat
            $program->progress_percentage = min($percentage, 100);
            return $program;
        });

        return view('admin.dashboard', compact(
            'totalDonations',
            'totalPrograms',
            'totalBeneficiaries',
            'monthlyDonations',
            'recentDonations',
            'topPrograms'
        ));
    }
}
