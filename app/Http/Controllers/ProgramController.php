<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Donation;
use App\Models\Distribution;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:superadmin,admin')->except(['index', 'show']);
    }

    public function index(Request $request)
    {
        // Ambil jumlah data per halaman
        $perPage = $request->input('perPage', 25);

        // Ambil filter sort
        $sort = $request->input('sort', 'dana_desc');

        // Query dasar
        $query = Program::query();

        // Urutkan berdasarkan pilihan user
        switch ($sort) {
            case 'dana_desc':
                $query->orderBy('dana_terkumpul', 'desc');
                break;
            case 'dana_asc':
                $query->orderBy('dana_terkumpul', 'asc');
                break;
            case 'target_desc':
                $query->orderBy('target_dana', 'desc');
                break;
            case 'target_asc':
                $query->orderBy('target_dana', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Jalankan pagination
        $programs = $query->paginate($perPage)->appends($request->query());

        // Kirim ke view
        return view('admin.programs.index', compact('programs'));
    }


    public function create()
    {
        return view('admin.programs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_program' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi gambar
            'kategori' => 'required|in:Zakat,Infaq,Sedekah,Wakaf',
            'target_dana' => 'required|numeric|min:0',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ]);

        $data = $request->all();

        // Proses upload gambar
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('programs', 'public');
            $data['gambar'] = $gambarPath;
        }

        Program::create($data);

        // Create notification for all users
        $users = \App\Models\User::all();
        foreach ($users as $user) {
            \App\Models\Notification::create([
                'user_id' => $user->id,
                'judul' => 'Program Donasi Baru',
                'isi' => 'Program donasi "' . $request->nama_program . '" telah dibuka. Mari berdonasi!',
                'status_baca' => false,
            ]);
        }

        return redirect()->route('programs.index')
            ->with('success', 'Program donasi berhasil dibuat.');
    }

    public function show(Program $program)
    {
        $totalDonasiProgram = Donation::where('program_id', $program->id)
            ->where('status', 'terverifikasi')
            ->sum('nominal');

        $totalDistribusiProgram = Distribution::where('program_id', $program->id)->sum('nominal_disalurkan');

        return view('admin.programs.show', compact('program', 'totalDonasiProgram', 'totalDistribusiProgram'));
    }

    public function edit(Program $program)
    {
        return view('admin.programs.edit', compact('program'));
    }

    public function update(Request $request, Program $program)
    {
        $request->validate([
            'nama_program' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi gambar
            'kategori' => 'required|in:Zakat,Infaq,Sedekah,Wakaf',
            'target_dana' => 'required|numeric|min:0',
            'status' => 'required|in:aktif,selesai,ditutup',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ]);

        $data = $request->all();

        // Proses upload gambar baru jika ada
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($program->gambar) {
                Storage::disk('public')->delete($program->gambar);
            }
            // Upload gambar baru
            $gambarPath = $request->file('gambar')->store('programs', 'public');
            $data['gambar'] = $gambarPath;
        } else {
            // Jika tidak ada gambar baru, hapus 'gambar' dari data agar tidak di-update ke null
            unset($data['gambar']);
        }

        $program->update($data);
        //$program->update($request->all());

        return redirect()->route('programs.index')
            ->with('success', 'Program donasi berhasil diperbarui.');
    }

    public function destroy(Program $program)
    {
        $program->delete();

        return redirect()->route('programs.index')
            ->with('success', 'Program donasi berhasil dihapus.');
    }
}
