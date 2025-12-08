<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Program;
use App\Models\User;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AdminDonationController extends Controller
{
    public function create()
    {
        $programs = Program::where('status', 'aktif')->get();
        $donaturs = User::whereHas('role', fn($q) => $q->where('name', 'donatur'))->get();

        // Pisahkan Kas Tunai dan Bank Accounts
        $kasTunai = BankAccount::where('is_cash', true)
            ->where('is_active', true)
            ->first();

        $bankAccounts = BankAccount::where('is_cash', false)
            ->where('is_active', true)
            ->get();

        return view('admin.donations.create', compact('programs', 'donaturs', 'kasTunai', 'bankAccounts'));
    }

    public function store(Request $request)
    {
        $messages = [
            'user_id.required' => 'Donatur wajib dipilih.',
            'program_id.required' => 'Program donasi wajib dipilih.',
            'nominal.required' => 'Nominal donasi wajib diisi.',
            'nominal.numeric' => 'Nominal donasi harus berupa angka.',
            'nominal.min' => 'Nilai donasi minimal Rp 1.000.',
            'metode_pembayaran.required' => 'Metode pembayaran wajib diisi.',
            'bank_account_id.required' => 'Rekening bank tujuan wajib dipilih.',
            'bank_account_id.exists' => 'Rekening bank yang dipilih tidak valid.',
            'bukti_transfer.required' => 'Bukti transfer wajib diupload.',
            'bukti_transfer.image' => 'File bukti transfer harus berupa gambar.',
            'bukti_transfer.mimes' => 'Format bukti transfer harus: jpeg, png, jpg, gif.',
            'bukti_transfer.max' => 'Ukuran bukti transfer maksimal 2MB.',
        ];

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'program_id' => 'required|exists:programs,id',
            'nominal' => 'required|numeric|min:1000',
            'metode_pembayaran' => 'required|in:Uang Tunai,Transfer Bank,QRIS,E-Wallet',
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'status' => 'required|in:menunggu,terverifikasi,ditolak',
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
                        ->with('error', 'Data Kas Tunai tidak ditemukan dalam sistem. Hubungi administrator.')
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

            $donation = new Donation();
            $donation->user_id = $request->user_id;
            $donation->program_id = $request->program_id;
            $donation->nominal = $request->nominal;
            $donation->metode_pembayaran = $request->metode_pembayaran;
            $donation->bank_account_id = $request->bank_account_id;
            $donation->keterangan_tambahan = $request->keterangan_tambahan;
            $donation->status = $request->status;

            if ($request->hasFile('bukti_transfer')) {
                $path = $request->file('bukti_transfer')->store('bukti_transfer', 'public');
                $donation->bukti_transfer = $path;
            }

            $donation->save();

            DB::commit();
            return redirect()->route('admin.donations.index')
                ->with('success', 'Donasi berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $donation = Donation::with(['user', 'program', 'bankAccount'])->findOrFail($id);
        $programs = Program::where('status', 'aktif')->get();
        $donaturs = User::whereHas('role', fn($q) => $q->where('name', 'donatur'))->get();

        // Pisahkan Kas Tunai dan Bank Accounts
        $kasTunai = BankAccount::where('is_cash', true)
            ->where('is_active', true)
            ->first();

        $bankAccounts = BankAccount::where('is_cash', false)
            ->where('is_active', true)
            ->get();

        return view('admin.donations.edit', compact('donation', 'programs', 'donaturs', 'kasTunai', 'bankAccounts'));
    }

    public function update(Request $request, $id)
    {
        $donation = Donation::findOrFail($id);

        $messages = [
            'user_id.required' => 'Donatur wajib dipilih.',
            'program_id.required' => 'Program donasi wajib dipilih.',
            'nominal.required' => 'Nominal donasi wajib diisi.',
            'nominal.numeric' => 'Nominal donasi harus berupa angka.',
            'nominal.min' => 'Nilai donasi minimal Rp 1.000.',
            'metode_pembayaran.required' => 'Metode pembayaran wajib diisi.',
            'bank_account_id.required' => 'Rekening bank tujuan wajib dipilih.',
            'bank_account_id.exists' => 'Rekening bank yang dipilih tidak valid.',
            'bukti_transfer.image' => 'File bukti transfer harus berupa gambar.',
            'bukti_transfer.mimes' => 'Format bukti transfer harus: jpeg, png, jpg, gif.',
            'bukti_transfer.max' => 'Ukuran bukti transfer maksimal 2MB.',
        ];

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'program_id' => 'required|exists:programs,id',
            'nominal' => 'required|numeric|min:1000',
            'metode_pembayaran' => 'required|in:Uang Tunai,Transfer Bank,QRIS,E-Wallet',
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'status' => 'required|in:menunggu,terverifikasi,ditolak',
            'keterangan_tambahan' => 'nullable|string',
            'bukti_transfer' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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

            // Simpan nilai lama untuk logging
            $oldData = [
                'program_id' => $donation->program_id,
                'nominal' => $donation->nominal,
                'status' => $donation->status,
            ];

            // Update data donasi
            $donation->user_id = $request->user_id;
            $donation->program_id = $request->program_id;
            $donation->nominal = $request->nominal;
            $donation->metode_pembayaran = $request->metode_pembayaran;
            $donation->bank_account_id = $request->bank_account_id;
            $donation->keterangan_tambahan = $request->keterangan_tambahan;
            $donation->status = $request->status;

            // Handle upload bukti transfer baru
            if ($request->hasFile('bukti_transfer')) {
                // Hapus file lama jika ada
                if ($donation->bukti_transfer && Storage::disk('public')->exists($donation->bukti_transfer)) {
                    Storage::disk('public')->delete($donation->bukti_transfer);
                }

                $path = $request->file('bukti_transfer')->store('bukti_transfer', 'public');
                $donation->bukti_transfer = $path;
            }

            $donation->save();

            // Log perubahan jika ada perubahan signifikan
            if (
                $oldData['program_id'] != $donation->program_id ||
                $oldData['nominal'] != $donation->nominal ||
                $oldData['status'] != $donation->status
            ) {

                \Log::info('Donation Updated', [
                    'donation_id' => $donation->id,
                    'admin_id' => auth()->id(),
                    'old_data' => $oldData,
                    'new_data' => [
                        'program_id' => $donation->program_id,
                        'nominal' => $donation->nominal,
                        'status' => $donation->status,
                    ]
                ]);
            }

            DB::commit();
            return redirect()->route('donations.show', $donation->id)
                ->with('success', 'Donasi berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $donation = Donation::findOrFail($id);

            // Hapus file bukti transfer
            if ($donation->bukti_transfer && Storage::disk('public')->exists($donation->bukti_transfer)) {
                Storage::disk('public')->delete($donation->bukti_transfer);
            }

            // Log sebelum delete
            \Log::info('Donation Deleted', [
                'donation_id' => $donation->id,
                'admin_id' => auth()->id(),
                'data' => $donation->toArray()
            ]);

            $donation->delete();

            DB::commit();

            // Jika AJAX request, return JSON
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Donasi berhasil dihapus.'
                ]);
            }

            // Jika bukan AJAX (fallback), redirect biasa
            return redirect()->route('donations.index')->with('success', 'Donasi berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();

            // Jika AJAX request, return JSON error
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Get edit form via AJAX for modal
     */
    public function getEditForm($id)
    {
        $donation = Donation::with(['user', 'program', 'bankAccount'])->findOrFail($id);
        $programs = Program::where('status', 'aktif')->get();
        $donaturs = User::whereHas('role', fn($q) => $q->where('name', 'donatur'))->get();

        // Pisahkan Kas Tunai dan Bank Accounts
        $kasTunai = BankAccount::where('is_cash', true)
            ->where('is_active', true)
            ->first();

        $bankAccounts = BankAccount::where('is_cash', false)
            ->where('is_active', true)
            ->get();

        return view('admin.donations.partials.edit-form', compact('donation', 'programs', 'donaturs', 'kasTunai', 'bankAccounts'));
    }
}
