<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Donation;
use App\Models\Distribution;
use Illuminate\Http\Request;
use App\Services\SettingService;
use App\Models\Team;
use App\Models\Testimonial;
use App\Models\BankAccount;

class PublicController extends Controller
{
    protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function home(Request $request)
    {
        $category = $request->get('category', 'all');

        $query = Program::where('status', 'aktif')
            ->withCount(['donations' => function ($query) {
                $query->where('status', 'terverifikasi');
            }])
            ->withSum(['donations' => function ($query) {
                $query->where('status', 'terverifikasi');
            }], 'nominal');

        // Filter by category
        if ($category !== 'all') {
            $query->where('kategori', $category);
        }

        // Urutkan dari yang paling banyak donasinya
        $programs = $query->orderBy('donations_sum_nominal', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Ambil daftar kategori yang ada
        $categories = Program::where('status', 'aktif')
            ->select('kategori')
            ->distinct()
            ->pluck('kategori');

        $settings = $this->settingService->all();

        // Ambil maksimal 5 testimonial aktif
        $testimonials = Testimonial::where('is_active', true)
            ->orderBy('order', 'asc')
            ->limit(5)
            ->get();

        return view('public.home', compact('programs', 'settings', 'categories', 'category', 'testimonials'));
    }

    public function programDetail($id)
    {
        $program = Program::findOrFail($id);
        $donations = Donation::where('program_id', $id)
            ->where('status', 'terverifikasi')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $totalDonasiProgram = Donation::where('program_id', $id)
            ->where('status', 'terverifikasi')
            ->sum('nominal');

        $bankAccounts = BankAccount::getActive();

        return view('public.program', compact('program', 'donations', 'bankAccounts', 'totalDonasiProgram'));
    }

    public function reports(Request $request)
    {
        $sort = $request->get('sort', 'popular'); // default: popular
        $category = $request->get('category', 'all'); // default: all categories

        $query = Program::withCount(['donations' => function ($query) {
            $query->where('status', 'terverifikasi');
        }])
            ->withSum(['donations' => function ($query) {
                $query->where('status', 'terverifikasi');
            }], 'nominal')
            ->withSum('distributions', 'nominal_disalurkan');

        // Filter by category
        if ($category !== 'all') {
            $query->where('kategori', $category);
        }

        // Sorting
        switch ($sort) {
            case 'popular':
                // Terpopuler: berdasarkan total donasi terverifikasi (terbanyak ke sedikit)
                $query->orderBy('donations_sum_nominal', 'desc');
                break;
            case 'least':
                // Paling sedikit pengumpulannya
                $query->orderBy('donations_sum_nominal', 'asc');
                break;
            case 'name_asc':
                // Nama A-Z
                $query->orderBy('nama_program', 'asc');
                break;
            case 'name_desc':
                // Nama Z-A
                $query->orderBy('nama_program', 'desc');
                break;
            default:
                $query->orderBy('donations_sum_nominal', 'desc');
                break;
        }

        // Pagination: 10 items per page
        $programs = $query->paginate(10)->withQueryString();

        $totalDonations = Donation::where('status', 'terverifikasi')->sum('nominal');
        $totalDistributed = Distribution::sum('nominal_disalurkan');

        // Ambil daftar kategori yang ada
        $categories = Program::select('kategori')
            ->distinct()
            ->pluck('kategori');

        return view('public.reports', compact('programs', 'totalDonations', 'totalDistributed', 'categories', 'sort', 'category'));
    }

    public function about()
    {
        // Ambil team yang aktif dan sudah diurutkan
        $teams = Team::getActive();
        return view('public.about', compact('teams'));
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Here you would typically send an email or save to database
        // For now, we'll just redirect with a success message

        return redirect()->route('contact')
            ->with('success', 'Pesan Anda telah terkirim. Kami akan segera menghubungi Anda.');
    }
}
