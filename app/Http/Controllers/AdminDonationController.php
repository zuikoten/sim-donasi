<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminDonationController extends Controller
{
    public function create()
    {
        $programs = Program::where('status', 'aktif')->get();
        $donaturs = User::whereHas('role', fn($q) => $q->where('name', 'donatur'))->get();

        return view('admin.donations.create', compact('programs', 'donaturs'));
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
            'status' => 'required|in:menunggu,terverifikasi,ditolak',
            'keterangan_tambahan' => 'nullable|string',
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], $messages);

        $donation = new Donation();
        $donation->user_id = $request->user_id;
        $donation->program_id = $request->program_id;
        $donation->nominal = $request->nominal;
        $donation->metode_pembayaran = $request->metode_pembayaran;
        $donation->keterangan_tambahan = $request->keterangan_tambahan;
        $donation->status = $request->status;

        if ($request->hasFile('bukti_transfer')) {
            $path = $request->file('bukti_transfer')->store('bukti_transfer', 'public');
            $donation->bukti_transfer = $path;
        }

        $donation->save();

        return redirect()->route('donations.index')->with('success', 'Donasi berhasil ditambahkan.');
    }
}
