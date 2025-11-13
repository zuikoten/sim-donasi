<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Donation;
use App\Models\Distribution;
use Illuminate\Http\Request;
use App\Services\SettingService;

class PublicController extends Controller
{
    protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function home()
    {
        $programs = Program::where('status', 'aktif')
            ->orderBy('created_at', 'desc')
            ->get();

        $settings = $this->settingService->all();

        return view('public.home', compact('programs', 'settings'));
    }

    public function programDetail($id)
    {
        $program = Program::findOrFail($id);
        $donations = Donation::where('program_id', $id)
            ->where('status', 'terverifikasi')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('public.program', compact('program', 'donations'));
    }

    public function reports()
    {
        $programs = Program::withCount(['donations' => function ($query) {
            $query->where('status', 'terverifikasi');
        }])
            ->withSum(['donations' => function ($query) {
                $query->where('status', 'terverifikasi');
            }], 'nominal')
            ->withSum('distributions', 'nominal_disalurkan')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalDonations = Donation::where('status', 'terverifikasi')->sum('nominal');
        $totalDistributed = Distribution::sum('nominal_disalurkan');

        return view('public.reports', compact('programs', 'totalDonations', 'totalDistributed'));
    }

    public function about()
    {
        return view('public.about');
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
