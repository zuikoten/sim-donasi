<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Program;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $query = Donation::with(['user', 'program', 'bankAccount'])->orderBy('created_at', 'desc');

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

        // Pisahkan Kas Tunai dan Bank Accounts
        $kasTunai = BankAccount::where('is_cash', true)
            ->where('is_active', true)
            ->first();

        $bankAccounts = BankAccount::where('is_cash', false)
            ->where('is_active', true)
            ->get();

        return view('donatur.donations.create', compact('programs', 'kasTunai', 'bankAccounts'));
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
            'bank_account_id.required' => 'Rekening bank tujuan wajib dipilih.',
            'bank_account_id.exists' => 'Rekening bank yang dipilih tidak valid.',
            'bukti_transfer.required' => 'Bukti transfer wajib diupload.',
            'bukti_transfer.image' => 'File bukti transfer harus berupa gambar.',
            'bukti_transfer.mimes' => 'Format bukti transfer harus: jpeg, png, jpg, gif.',
            'bukti_transfer.max' => 'Ukuran bukti transfer maksimal 2MB.',
        ];

        $request->validate([
            'program_id' => 'required|exists:programs,id',
            'nominal' => 'required|numeric|min:1000',
            'metode_pembayaran' => 'required|in:Uang Tunai,Transfer Bank,QRIS,E-Wallet',
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'keterangan_tambahan' => 'nullable|string',
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], $messages);

        DB::beginTransaction();
        try {
            // Extra validation: Jika Uang Tunai, harus Kas Tunai
            if ($request->metode_pembayaran === 'Uang Tunai') {
                $kasTunai = BankAccount::where('is_cash', true)->first();

                if (!$kasTunai) {
                    return back()
                        ->with('error', 'Data Kas Tunai tidak ditemukan dalam sistem.')
                        ->withInput();
                }

                if ($request->bank_account_id != $kasTunai->id) {
                    return back()
                        ->with('error', 'Untuk metode Uang Tunai, rekening harus "Kas Tunai".')
                        ->withInput();
                }
            }

            // Validation: Jika bukan Uang Tunai, tidak boleh pilih Kas Tunai
            if ($request->metode_pembayaran !== 'Uang Tunai') {
                $selectedAccount = BankAccount::find($request->bank_account_id);

                if ($selectedAccount && $selectedAccount->is_cash) {
                    return back()
                        ->with('error', 'Kas Tunai hanya untuk metode pembayaran "Uang Tunai".')
                        ->withInput();
                }
            }

            // Validasi tambahan: nominal tidak boleh melebihi target dana
            $program = Program::find($request->program_id);
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
            $donation->bank_account_id = $request->bank_account_id;
            $donation->keterangan_tambahan = $request->keterangan_tambahan;
            $donation->status = 'menunggu'; // Donatur selalu status menunggu

            if ($request->hasFile('bukti_transfer')) {
                $path = $request->file('bukti_transfer')->store('bukti_transfer', 'public');
                $donation->bukti_transfer = $path;
            }

            $donation->save();

            DB::commit();
            return redirect()->route('donations.index')
                ->with('success', 'Donasi berhasil dikirim. Menunggu verifikasi dari admin.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Donation $donation)
    {
        // Donatur hanya bisa lihat donasi miliknya sendiri
        if (Auth::user()->isDonatur() && $donation->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke donasi ini.');
        }

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
        // Donatur tidak bisa delete donasi yang sudah terverifikasi
        if (Auth::user()->isDonatur() && $donation->status === 'terverifikasi') {
            return back()->with('error', 'Donasi yang sudah terverifikasi tidak dapat dihapus.');
        }

        DB::beginTransaction();
        try {
            if ($donation->bukti_transfer && Storage::disk('public')->exists($donation->bukti_transfer)) {
                Storage::disk('public')->delete($donation->bukti_transfer);
            }

            $donation->delete();

            DB::commit();
            return redirect()->route('donations.index')
                ->with('success', 'Donasi berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
