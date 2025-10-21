<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DonationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        // Ambil nilai perPage dari request, dengan default 25
        $perPage = $request->input('perPage', 25);

        // Query dasar
        $query = Donation::with(['user', 'program'])->orderBy('created_at', 'desc');

        // Terapkan filter berdasarkan role
        if (!$user->isDonatur()) {
            // Admin dan Superadmin melihat semua donasi
        } else {
            // Donatur hanya melihat donasi miliknya
            $query->where('user_id', $user->id);
        }

        // Jalankan query dengan pagination
        $donations = $query->paginate($perPage);

        return view('admin.donations.index', compact('donations'));
    }

    public function create()
    {
        $programs = Program::where('status', 'aktif')->get();
        return view('donatur.donations.create', compact('programs'));
    }

    public function store(Request $request)
    {
        // Definisi Pesan custom
        $messages = [
            'program_id.required' => 'Program donasi wajib dipilih.',
            'program_id.exists' => 'Program donasi yang dipilih tidak valid.',
            'nominal.required' => 'Nominal donasi wajib diisi.',
            'nominal.numeric' => 'Nominal donasi harus berupa angka.',
            'nominal.min' => 'Nilai donasi harus lebih besar daripada atau sama dengan Rp 1.000.',
            'metode_pembayaran.required' => 'Metode pembayaran wajib diisi.',
            'bukti_transfer.required' => 'Bukti transfer wajib diupload.',
            'bukti_transfer.image' => 'File bukti transfer harus berupa gambar.',
            'bukti_transfer.mimes' => 'Format bukti transfer harus: jpeg, png, jpg, gif.',
            'bukti_transfer.max' => 'Ukuran bukti transfer maksimal 2MB.',
        ];

        $request->validate([
            'program_id' => 'required|exists:programs,id',
            'nominal' => 'required|numeric|min:1000',
            'metode_pembayaran' => 'required|in:Uang Tunai,Transfer Bank,QRIS,E-Wallet',
            'keterangan_tambahan' => 'nullable|string',
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], $messages);

        // Validasi tambahan setelah validasi utama
        $program = \App\Models\Program::find($request->program_id);
        if ($program && $request->nominal > $program->target_dana) {
            return back()
                ->withErrors(['nominal' => 'Kendala Sistem: Nominal donasi tidak bisa melebihi target dana program (Rp ' . number_format($program->target_dana, 0, ',', '.') . ').'])
                ->withInput();
        }

        $donation = new Donation();
        $donation->user_id = Auth::id();
        $donation->program_id = $request->program_id;
        $donation->nominal = $request->nominal;
        $donation->metode_pembayaran = $request->metode_pembayaran;
        $donation->keterangan_tambahan = $request->keterangan_tambahan;
        $donation->status = 'menunggu';

        if ($request->hasFile('bukti_transfer')) {
            $path = $request->file('bukti_transfer')->store('bukti_transfer', 'public');
            $donation->bukti_transfer = $path;
        }

        $donation->save();

        return redirect()->route('donations.index')
            ->with('success', 'Donasi berhasil dikirim. Menunggu verifikasi dari admin.');
    }

    public function show(Donation $donation)
    {
        return view('admin.donations.show', compact('donation'));
    }

    public function verify(Request $request, Donation $donation)
    {
        $request->validate([
            'status' => 'required|in:terverifikasi,ditolak',
        ]);

        $donation->status = $request->status;
        $donation->save();

        $statusText = $request->status === 'terverifikasi' ? 'diverifikasi' : 'ditolak';

        return redirect()->route('donations.index')
            ->with('success', 'Donasi berhasil ' . $statusText . '.');
    }

    public function destroy(Donation $donation)
    {
        if ($donation->bukti_transfer) {
            Storage::disk('public')->delete($donation->bukti_transfer);
        }

        $donation->delete();

        return redirect()->route('donations.index')
            ->with('success', 'Donasi berhasil dihapus.');
    }
}
